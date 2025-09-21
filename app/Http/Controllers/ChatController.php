<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Helpers\WordFilter;
use App\Models\User;
use App\Models\ChMessage as Message;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Send a message via the chat widget
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        // Default variables
        $error = (object)[
            'status' => 0,
            'message' => null
        ];
        $attachment = null;
        $attachment_title = null;

        // Handle file attachments if present
        if ($request->hasFile('file')) {
            // Allowed extensions
            $allowed_images = Chatify::getAllowedImages();
            $allowed_files  = Chatify::getAllowedFiles();
            $allowed        = array_merge($allowed_images, $allowed_files);

            $file = $request->file('file');
            // Check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed)) {
                    // Get attachment name
                    $attachment_title = $file->getClientOriginalName();
                    // Upload attachment and store the new name
                    $attachment = Str::uuid() . "." . $file->extension();
                    $file->storeAs(config('chatify.attachments.folder'), $attachment, config('chatify.storage_disk_name'));
                } else {
                    $error->status = 1;
                    $error->message = "File extension not allowed!";
                }
            } else {
                $error->status = 1;
                $error->message = "File size you are trying to upload is too large!";
            }
        }

        if (!$error->status) {
            // Get message from request
            $messageText = $request->input('message', '');
            
            // Filter bad words
            $cleanedMessage = WordFilter::filter($messageText);
            
            // Create the message using Chatify
            $message = Chatify::newMessage([
                'from_id' => Auth::user()->id,
                'to_id' => $request->input('id'),
                'body' => htmlentities(trim($cleanedMessage), ENT_QUOTES, 'UTF-8'),
                'attachment' => ($attachment) ? json_encode((object)[
                    'new_name' => $attachment,
                    'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                ]) : null,
            ]);

            $messageData = Chatify::parseMessage($message);

            // Send real-time notification to the recipient
            if (Auth::user()->id != $request->input('id')) {
                Chatify::push("private-chatify." . $request->input('id'), 'Messaging', [
                    'from_id' => Auth::user()->id,
                    'to_id' => $request->input('id'),
                    'message' => Chatify::messageCard($messageData, true)
                ]);
            }

            // Send response
            return Response::json([
                'status' => '200',
                'error' => $error,
                'message' => Chatify::messageCard(@$messageData),
                'tempID' => $request->input('temporaryMsgId'),
            ]);
        }

        // Return error response
        return Response::json([
            'status' => '400',
            'error' => $error,
        ], 400);
    }

    /**
     * Get user's chat contacts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContacts()
    {
        // Get all users that received/sent message from/to [Auth user]
        $users = Message::join('users', function ($join) {
            $join->on('ch_messages.from_id', '=', 'users.id')
                ->orOn('ch_messages.to_id', '=', 'users.id');
        })
        ->where(function ($q) {
            $q->where('ch_messages.from_id', Auth::user()->id)
            ->orWhere('ch_messages.to_id', Auth::user()->id);
        })
        ->where('users.id', '!=', Auth::user()->id)
        ->select(
            'users.id',
            'users.name',
            'users.email',
            'users.avatar',
            DB::raw('MAX(ch_messages.created_at) as last_message_time'),
            DB::raw('(SELECT body FROM ch_messages WHERE (from_id = users.id AND to_id = ' . Auth::user()->id . ') OR (from_id = ' . Auth::user()->id . ' AND to_id = users.id) ORDER BY created_at DESC LIMIT 1) as last_message_preview')
        )
        ->orderBy('last_message_time', 'desc')
        ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
        ->limit(20)
        ->get();

        return response()->json([
            'contacts' => $users,
            'total' => $users->count(),
        ], 200);
    }

    /**
     * Get conversation messages with a specific user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversation($id)
    {
        // Verify the user exists and is not the current user
        $user = User::find($id);
        if (!$user || $user->id === Auth::user()->id) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Get messages between current user and the specified user
        $messages = Message::where(function ($query) use ($id) {
            $query->where('from_id', Auth::user()->id)
                  ->where('to_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('from_id', $id)
                  ->where('to_id', Auth::user()->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Format messages for the frontend
        $formattedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'body' => $message->body,
                'from_id' => $message->from_id,
                'to_id' => $message->to_id,
                'created_at' => $message->created_at,
                'is_own' => $message->from_id === Auth::user()->id,
                'attachment' => $message->attachment ? json_decode($message->attachment, true) : null,
            ];
        });

        return response()->json([
            'messages' => $formattedMessages,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
            ],
        ], 200);
    }
}
