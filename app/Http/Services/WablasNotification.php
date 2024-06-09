<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class WablasNotification
{
    private $token;
    private $phone;
    private $message;

    public function __construct()
    {
        $this->token = config('services.wablas.token');
    }

    public function setPhone($phone)
    {
        $this->phone = $this->formatPhone($phone);
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    private function formatPhone($phone)
    {
        return preg_replace('/^0/', '62', $phone);
    }

    public function sendMessage()
    {
        $response = Http::withHeaders(['Authorization' => $this->token])
            ->asForm()
            ->post('https://jogja.wablas.com/api/send-message', [
                'phone' => $this->phone,
                'message' => $this->message,
            ]);

        $responseData = json_decode($response->getBody()->getContents(), true);

        return [
            'status' => $response->getStatusCode(),
            'response' => $responseData,
        ];
    }
}
