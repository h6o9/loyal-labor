<?php

namespace Modules\BasicPayment\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Modules\Order\app\Models\Order;

class SslCommerzPaymentController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $order_data = [
            'total_amount'     => 15,
            'currency'         => 'BDT',
            'tran_id'          => uniqid(),
            // CUSTOMER INFORMATION
            'cus_name'         => 'Towfik Hasan',
            'cus_email'        => 'tufikhasan05@gmail.com',
            'cus_add1'         => 'Bogura',
            'cus_add2'         => 'Bogura',
            'cus_city'         => '',
            'cus_state'        => '',
            'cus_postcode'     => '',
            'cus_country'      => 'Bangladesh',
            'cus_phone'        => '8801XXXXXXXXX',
            'cus_fax'          => '',
            // SHIPMENT INFORMATION
            'ship_name'        => 'Store Test',
            'ship_add1'        => 'Dhaka',
            'ship_add2'        => 'Dhaka',
            'ship_city'        => 'Dhaka',
            'ship_state'       => 'Dhaka',
            'ship_postcode'    => '1000',
            'ship_phone'       => '',
            'ship_country'     => 'Bangladesh',
            'shipping_method'  => 'NO',
            'product_name'     => 'Computer',
            'product_category' => 'Goods',
            'product_profile'  => 'physical-goods',
            // OPTIONAL PARAMETERS
            'value_a'          => 'ref001',
            'value_b'          => 'ref002',
            'value_c'          => 'ref003',
            'value_d'          => 'ref004',
        ];

        $sslc = new SslCommerzNotification;
        // initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($order_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = [];
        }

    }

    /**
     * @param Request $request
     */
    public function success(Request $request)
    {
        $tran_id  = $request->input('tran_id');
        $amount   = $request->input('amount');
        $currency = $request->input('currency');
        $status   = $request->input('status');

        $sslc = new SslCommerzNotification;

        $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

        if ($validation && strtolower($status) == 'valid') {
            $paymentDetails = [
                'transaction_id' => $tran_id,
                'amount'         => $amount,
                'currency'       => $currency,
                'payment_status' => $status, //VALID OR FAILED
                'created'        => $request->input('tran_date'),
            ];
            Session::put('paid_amount', $amount);
            Session::put('after_success_gateway', 'SslCommerz');
            Session::put('after_success_transaction', $tran_id);
            Session::put('payment_details', $paymentDetails);

            return to_route('payment-addon-success');
        } else {
            return to_route('payment-addon-faild');
        }

    }

    public function fail_and_cancel()
    {
        return to_route('payment-addon-faild');
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        if ($request->input('tran_id')) {
            $tran_id = $request->input('tran_id');

            try {
                $order_details = Order::whereHas('paymentDetails', function ($query) use ($tran_id) {
                    $query->where('transaction_id', $tran_id);
                })->first();

                if (!$order_details) {
                    Log::error('Order not found for transaction ID: ' . $tran_id);

                    return response()->json(['status' => 'Order not found'], 404);
                }

                $sslc       = new SslCommerzNotification;
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->payment_method);

                if ($validation == true) {
                    Order::whereHas('paymentDetails', function ($query) use ($tran_id) {
                        $query->where('transaction_id', $tran_id);
                    })->update(['status' => 'success']);

                    // Additional processing like sending confirmation email
                    Log::info('Transaction successfully completed for transaction ID: ' . $tran_id);
                } else {
                    Log::warning('Validation failed for transaction ID: ' . $tran_id);
                }
            } catch (\Exception $e) {
                Log::error('IPN Error: ' . $e->getMessage());
            }
        } else {
            Log::warning('Invalid IPN data received');
        }
    }
}
