<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
class TapPaymentService extends PaymentService
{
    protected function initializeSettings()
    {
        $this->secretKey = config('services.tap.secret_key');
        $this->baseUrl = config('services.tap.base_url');
    }

    public function createCharge($order, $token)
    {
        $payload = $this->prepareChargeData($order, $token);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type'  => 'application/json',
            'accept'        => 'application/json',
        ])->post($this->baseUrl . '/charges', $payload);

        return $response->json();
    }

    /**
     * Prepare the charge data for the given order.
     *
     * @param mixed $order
     * @return array
     */
    public function prepareChargeData($order, $token)
    {
        $country_code = $this->getCountryCode($order->buyer->country);
        return [
            "amount"            => $order->price,
            "currency"          => "USD",
            "customer_initiated" => true,
            "threeDSecure"      => true,
            "save_card"         => false,
            "description"       => "Test Description",
            'order'             => $order->id,
            "metadata" => [
                "udf1" => "Metadata 1"
            ],
            "reference" => [
                "transaction"   => "txn_" . $order->id,
                "order"         => "ord_" . $order->id,
                'idempotent'    => "ORDER_" . $order->id,
            ],
            "customer" => [
                "first_name"    => $order->buyer->first_name,
                "middle_name"   => "",
                "last_name"     => $order->buyer->last_name,
                "email"         => $order->buyer->email,
                "phone" => [
                    "country_code" => $country_code,
                    "number" => $order->buyer->phone
                ]
            ],
            "source" => [
                "id" => $token,
            ],
            "post" => [
                "url" => route('webhooks.tap')
            ],
            "redirect" => [
                "url" => route('orders.show', $order->id)
            ]
        ];
    }

    public function retrieveCharge($chargeId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'accept'        => 'application/json',
        ])->get($this->baseUrl . '/charges', $chargeId);

        return $response->json();
    }

    public function callback(Request $request)
    {
        // $charge     = 
    }

    public function pay($order)
    {
        // Implement the logic to pay the order from the Tap payment service
    }

    public function cancel($order)
    {
        // Implement the logic to cancel the charge from the Tap payment service
    }

    public function refund($order)
    {
        // Implement the logic to refund the order from the Tap payment service
    }

    public function status($order)
    {
        // Implement the logic to get the status of the order from the Tap payment service
    }

    public function getCharge($chargeId)
    {
        // Implement the logic to get the charge from the Tap payment service
    }

    public function getChargeStatus($chargeId)
    {
        // Implement the logic to get the status of the charge from the Tap payment service
    }
}
