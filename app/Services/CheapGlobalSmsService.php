<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CheapGlobalSmsService
{
    protected $token;
    protected $sender;
    protected $apiUrl;

    public function __construct()
    {
        $this->token = env('CHEAPGLOBALSMS_TOKEN');
        $this->sender = env('CHEAPGLOBALSMS_SENDER');
        $this->apiUrl = env('CHEAPGLOBALSMS_API_URL');
    }

    public function sendSms($recipient, $message)
    {
        $response = Http::withoutVerifying()->asForm()->post($this->apiUrl, [
            'token'   => $this->token,
            'to'      => $recipient,
            'from'    => $this->sender,
            'message' => $message,
        ]);

        return $response->json();
    }
}