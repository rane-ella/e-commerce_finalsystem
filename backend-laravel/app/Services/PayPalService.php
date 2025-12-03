<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id', 'client_id_placeholder');
        $this->clientSecret = config('services.paypal.client_secret', 'client_secret_placeholder');
        $this->baseUrl = config('services.paypal.mode') === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
    }

    protected function getAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

        if ($response->failed()) {
            throw new \Exception('PayPal Auth Error: ' . $response->body());
        }

        return $response->json('access_token');
    }

    public function createOrder($amount, $currency = 'PHP', $returnUrl, $cancelUrl)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2, '.', '')
                    ]
                ]],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl
                ]
            ]);

        if ($response->failed()) {
            throw new \Exception('PayPal Create Order Error: ' . $response->body());
        }

        return $response->json();
    }

    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture", [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

        if ($response->failed()) {
            throw new \Exception('PayPal Capture Error: ' . $response->body());
        }

        return $response->json();
    }
}
