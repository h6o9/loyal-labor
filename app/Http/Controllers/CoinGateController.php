<?php

namespace App\Http\Controllers;

use CoinGate\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoinGateController extends Controller
{
    public function createPayment()
    {
        try {
            $order_id = uniqid();

            // Create a client instance
            $client = $this->createCoinGateClient();
            $token = hash('sha512', 'coingate'.rand());

            $params = [
                'order_id' => $order_id,
                'price_amount' => 10.00,
                'price_currency' => 'USD',
                'receive_currency' => 'EUR',
                'callback_url' => url('coin-gate/callback').'?token='.$token,
                'cancel_url' => route('payment-addon-faild'),
                'success_url' => url('coin-gate/success'),
                'title' => 'Apple iPhone 10',
                'description' => "Payment for order #$order_id",
            ];

            $order = $client->order->create($params);

            if (isset($order->payment_url)) {
                // Store the token and Order ID for verification later
                session(['coin_gate_token' => $token, 'coin_gate_order_id' => $order->id]);

                // Redirect to the CoinGate payment URL
                return redirect($order->payment_url);
            } else {
                // Handle error creating the order
                return to_route('payment-addon-faild');
            }
        } catch (\Exception $e) {
            info($e->getMessage());

            return to_route('payment-addon-faild');
        }
    }

    public function handleCallback(Request $request)
    {
        $token = $request->query('token');
        $sessionToken = session('coin_gate_token');
        $order_id = session('coin_gate_order_id');

        // Verify the token to ensure the callback is legitimate
        if ($token !== $sessionToken) {
            Log::error('Invalid token in CoinGate callback', ['received_token' => $token, 'expected_token' => $sessionToken]);

            return response()->json(['error' => 'Invalid token'], 400);
        }

        $client = $this->createCoinGateClient();
        $order_details = $client->order->get($order_id);

        // Clear the session data
        session()->forget(['coin_gate_token', 'coin_gate_order_id']);

        return response()->json(['status' => 'success', 'data' => $order_details]);
    }

    public function success(Request $request)
    {
        $order_id = session('coin_gate_order_id');

        $order_details = [];
        if ($order_id) {
            $client = $this->createCoinGateClient();
            $order_details = $client->order->get($order_id);
            session()->forget(['coin_gate_token', 'coin_gate_order_id']);
        }

        return response()->json(['status' => 'success', 'data' => $order_details]);
    }

    // Method to create CoinGate client instance
    private function createCoinGateClient()
    {
        $config = cache()->get('payment_setting');

        return new Client($config?->crypto_api_key, $config?->crypto_sandbox ?? false); // 'true' for sandbox mode, 'false' for live mode
    }
}
