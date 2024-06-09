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
        $phone = preg_replace('/\D/', '', $phone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        } else {
            $phone = null;
        }

        return $phone;
    }

    public function sendMessage()
    {
        if ($this->phone === null) {
            return [
                'status' => 400,
                'response' => 'Invalid phone number' . $this->phone,
            ];
        }

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
