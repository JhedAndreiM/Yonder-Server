<?php

namespace App\Services;

class IprogSmsService
{
    protected string $apiUrl;
    protected string $apiToken;
    protected string $certPath;

    public function __construct()
    {
        $this->apiUrl   = config('services.iprogsms.api_url', 'https://sms.iprogtech.com/api/v1/sms_messages');
        $this->apiToken = config('services.iprogsms.api_token');
        $this->certPath = public_path('certs/cacert.pem'); // <-- you want cert here
    }

    public function send(string $phoneNumber, string $message, array $placeholders = []): array
    {
        if (!empty($placeholders)) {
            $message = vsprintf($message, $placeholders);
        }

        $data = [
            'api_token'    => $this->apiToken,
            'message'      => $message,
            'phone_number' => $phoneNumber,
            'sms_provider' => 2,
        ];

        $ch = curl_init($this->apiUrl);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded',
            ],

            // --- SSL options ---
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => $this->certPath, 
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException("SMS API connection failed: {$error}");
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status'       => $httpCode === 200 ? 'success' : 'error',
            'status_code'  => $httpCode,
            'response'     => json_decode($response, true) ?? $response,
        ];
    }
}
