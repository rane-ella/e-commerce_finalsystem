<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayMongoService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key', 'sk_test_placeholder'); // Use config or env
    }

    public function createCheckoutSession($amount, $currency = 'PHP', $successUrl, $cancelUrl)
    {
        $amountInCentavos = (int) ($amount * 100);

        $payload = [
            'data' => [
                'attributes' => [
                    'line_items' => [[
                        'currency' => $currency,
                        'amount' => $amountInCentavos,
                        'name' => 'Order Payment',
                        'quantity' => 1
                    ]],
                    'payment_method_types' => ['card', 'gcash', 'paymaya', 'grab_pay'],
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                    'description' => 'Payment for Order',
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true
                ]
            ]
        ];

        \Illuminate\Support\Facades\Log::info('PayMongo Request:', [
            'url' => "{$this->baseUrl}/checkout_sessions",
            'payload' => $payload,
            'key_prefix' => substr($this->secretKey, 0, 8) . '...' // Log partial key for verification
        ]);

        $response = Http::withOptions(['verify' => false])
            ->withBasicAuth($this->secretKey, '')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post("{$this->baseUrl}/checkout_sessions", $payload);

        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error('PayMongo Error:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('PayMongo Error: ' . $response->body());
        }

        \Illuminate\Support\Facades\Log::info('PayMongo Success:', $response->json());

        return $response->json('data');
    }

    public function retrieveCheckoutSession($sessionId)
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->get("{$this->baseUrl}/checkout_sessions/{$sessionId}");

        if ($response->failed()) {
            throw new \Exception('PayMongo Error: ' . $response->body());
        }

        return $response->json('data');
    }
}
