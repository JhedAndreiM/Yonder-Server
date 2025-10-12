<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Events\NewNotification;
use App\Models\User;

class ReportController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'report_id' => 'required',
        'reason' => 'required|string',
        'message' => 'nullable|string|max:1000',
    ]);

    // Map reason values to human-readable labels
    $reasonLabels = [
        'inappropriate_content' => 'Inappropriate content',
        'misleading_info' => 'Misleading information',
        'counterfeit' => 'Counterfeit product',
        'spam' => 'Spam or duplicate listing',
        'prohibited_item' => 'Prohibited item',
    ];

    // Simple message handling: use mapped label for predefined reasons, or custom message for "other"
    if ($validated['reason'] === 'other') {
        $message = $validated['message'] ?? 'Other reason specified';
    } else {
        $message = $reasonLabels[$validated['reason']] ?? $validated['reason'];
    }

    DB::table('reports')->insert([
        'user_id' => $validated['user_id'],
        'report_id' => $validated['report_id'],
        'message' => $message,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Fetch product info for notification message
    $adminUserId = User::where('role', 'admin')->first();
    $product = DB::table('product')->where('product_id', $validated['report_id'])->first();
    $productName = $product ? $product->name : 'Unknown Product';
    $productId = $validated['report_id'];
    $notificationMessage = "A product has been reported. Product: {$productName}";

    $notification = Notification::create([
        'user_id' => $adminUserId->id,
        'title' => 'Product Report',
        'message' => $notificationMessage,
        'is_read' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    event(new NewNotification($notification));
    return back()->with('success', 'Report submitted successfully.');
}
}
