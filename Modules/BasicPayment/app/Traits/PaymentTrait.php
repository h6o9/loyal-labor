<?php

namespace Modules\BasicPayment\app\Traits;

use App\Facades\MailSender;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\BasicPayment\app\Services\PaymentMethodService;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\Order\app\Models\TransactionHistory;

trait PaymentTrait
{
    /**
     * @var string
     */
    private $appName = 'TopCommerce';

    protected static array $messages = [
        'success'             => 'Payment completed successfully',
        'failed'              => 'Payment failed, please try again',
        'pending'             => 'Payment pending, wait for payment confirmation',
        'paymentTypeNotFound' => 'Payment Type Not Found!',
        'error'               => 'An error occurred. Please try again.',
    ];

    /**
     * @param $order
     */
    protected function checkUnpaidStatus($order)
    {
        $errorMessage = 'Payment already ' . $order->paymentDetails->payment_status->getLabel();

        if ($order->paymentDetails->payment_status->value != OrderStatus::PENDING->value) {
            session()->flash('info', __($errorMessage));
            throw new HttpResponseException(
                redirect()
                    ->back()
                    ->with('info', __($errorMessage)),
            );
        }
    }

    /**
     * @return mixed
     */
    protected function saveOrderSuccess($order, $details, $amount, $currency, $trxId, $orderType = 'order')
    {
        if ($orderType == 'order') {
            $oldPaymentStatus = $order->paymentDetails->payment_status->value;

            $order->paymentDetails->payment_details  = json_encode($details);
            $order->paymentDetails->paid_amount      = $amount;
            $order->paymentDetails->payable_currency = $currency;
            $order->paymentDetails->transaction_id   = $trxId;
            $order->paymentDetails->payment_status   = PaymentStatus::COMPLETED->value;

            $return = $order->push();

            $order->paymentDetails->orders->each(function ($otherOrder) use ($oldPaymentStatus, $trxId) {
                $this->statusChangeAndUtilityUpdate($otherOrder, $oldPaymentStatus, $trxId);
            });
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * @param $order
     */
    private function statusChangeAndUtilityUpdate($order, $oldPaymentStatus, $trxId)
    {
        $oldStatus = $order->order_status->value;

        $order->order_status = OrderStatus::APPROVED->value;

        $order->save();

        $this->saveWalletOnSuccess($order);

        $order->addOrderHistory('payment_status', $oldPaymentStatus);

        $order->addOrderHistory('order_status', $oldStatus);

        $order = $order->fresh();

        $this->storeTransactionHistory($order, $trxId);

        $this->sendConfirmMail($order);

        $this->sendOrderStatusChangeMail($order);

        notifyAdmin("Order #{$order->order_id} Payment Confirmed.", "Order #{$order->order_id} has been successfully paid.", 'order', route('admin.order', ['id' => $order->order_id]));
    }

    /**
     * @param $order
     * @param $trxId
     */
    private function storeTransactionHistory($order, $trxId)
    {
        $newTH                  = new TransactionHistory();
        $newTH->order_id        = $order->id;
        $newTH->user_id         = $order->user_id;
        $newTH->vendor_id       = $order->vendor_id;
        $newTH->payment_method  = $order->paymentDetails->payment_method;
        $newTH->transaction_id  = $trxId;
        $newTH->payment_details = $order->paymentDetails->payment_details;
        $newTH->amount          = $order->paymentDetails->paid_amount ?? 0;
        $newTH->currency        = $order->paymentDetails->payable_currency;
        $newTH->status          = $order->paymentDetails->payment_status->value;
        $newTH->save();

        return $newTH;
    }

    public function afterSuccessOperations(): void
    {
        PaymentMethodService::removeSessions();
    }

    /**
     * @param $value
     */
    private function checkArrayIsset($value)
    {
        return isset($value) ? $value : null;
    }

    /**
     * @param $order
     */
    private function sendConfirmMail($order)
    {
        try {
            [$subject, $message] = MailSender::fetchEmailTemplate('order_payment_confirmed', [
                'user_name'        => $order?->shippingAddress?->name ?? $order?->user?->name ?? 'Name Missing!',
                'order_id'         => $order->order_id,
                'amount'           => $order->paymentDetails->payable_amount,
                'amount_currency'  => $order->paymentDetails->payable_currency,
                'payment_method'   => $order->paymentDetails->payment_method,
                'payment_status'   => $order->paymentDetails->payment_status->getLabel(),
                'shipping_address' => $order?->shippingAddress?->full_address ?? '',
                'billing_address'  => $order?->billingAddress?->full_address ?? '',
            ]);

            $link = [
                __('INVOICE') . ' #' . ($order->order_id) => route('website.invoice', ['uuid' => $order->uuid]),
            ];

            MailSender::sendMail($order->billingAddress->email, $subject, $message, $link);
        } catch (Exception $e) {
            logError("Unable to send order placed email to {$order->billingAddress->email} for #{$order->order_id}", $e);
        }
    }

    /**
     * @param $order
     */
    private function sendOrderStatusChangeMail($order, $changedByAdmin = false)
    {
        try {
            $emailData = [
                'user_name'        => $order?->shippingAddress?->name ?? $order?->user?->name ?? 'Name Missing!',
                'order_id'         => $order->order_id,
                'order_status'     => $order->order_status->getLabel(),
                'amount'           => $order->paymentDetails->payable_amount,
                'amount_currency'  => $order->paymentDetails->payable_currency,
                'payment_method'   => $order->paymentDetails->payment_method,
                'payment_status'   => $order->paymentDetails->payment_status->getLabel(),
                'shipping_address' => $order?->shippingAddress?->full_address ?? '',
            ];

            [$subject, $message] = MailSender::fetchEmailTemplate('order_status_update', $emailData);

            $link = [
                __('INVOICE') . ' #' . ($order->order_id) => route('website.invoice', ['uuid' => $order->uuid]),
            ];

            MailSender::sendMail($order->billingAddress->email, $subject, $message, $link);

            if ($changedByAdmin) {
                $sellerEmailData              = $emailData;
                $sellerEmailData['user_name'] = $order?->seller?->shop_name ?? 'Name Missing!';

                [$subject, $message] = MailSender::fetchEmailTemplate('seller_order_status_update', $sellerEmailData);

                $link = [
                    __('Order') . ' #' . ($order->order_id) => route('seller.orders.show', ['id' => $order->order_id]),
                ];

                MailSender::sendMail($order->seller->email, $subject, $message, $link);
            }
        } catch (Exception $e) {
            logError("Unable to send order placed email to {$order->billingAddress->email} for #{$order->order_id}", $e);
        }
    }

    /**
     * @param $order
     * @param $changedByAdmin
     */
    private function sendPaymentStatusChangeMail($order, $changedByAdmin = false)
    {
        try {
            $emailData = [
                'user_name'        => $order?->shippingAddress?->name ?? $order?->user?->name ?? 'Name Missing!',
                'order_id'         => $order->order_id,
                'order_status'     => $order->order_status->getLabel(),
                'amount'           => $order->paymentDetails->payable_amount,
                'amount_currency'  => $order->paymentDetails->payable_currency,
                'payment_method'   => $order->paymentDetails->payment_method,
                'payment_status'   => $order->paymentDetails->payment_status->getLabel(),
                'shipping_address' => $order?->shippingAddress?->full_address ?? '',
            ];

            [$subject, $message] = MailSender::fetchEmailTemplate('order_payment_status_update', $emailData);

            $link = [
                __('INVOICE') . ' #' . ($order->order_id) => route('website.invoice', ['uuid' => $order->uuid]),
            ];

            MailSender::sendMail($order->billingAddress->email, $subject, $message, $link);

            if ($changedByAdmin) {
                $sellerEmailData              = $emailData;
                $sellerEmailData['user_name'] = $order?->seller?->shop_name ?? 'Name Missing!';

                [$subject, $message] = MailSender::fetchEmailTemplate('seller_order_status_update', $sellerEmailData);

                $link = [
                    __('Order') . ' #' . ($order->order_id) => route('seller.orders.show', ['id' => $order->order_id]),
                ];

                MailSender::sendMail($order->seller->email, $subject, $message, $link);
            }
        } catch (Exception $e) {
            logError("Unable to send order placed email to {$order->billingAddress->email} for #{$order->order_id}", $e);
        }
    }

    /**
     * @param $order
     */
    private function saveWalletOnSuccess($order)
    {
        if ($order->paymentDetails->payment_status == PaymentStatus::COMPLETED) {
            $order = $order->load('items');

            if ($order->items->isEmpty()) {
                return;
            }

            foreach ($order->items as $newOrder) {
                if ($newOrder?->vendor) {
                    saveWalletHistory($newOrder, $order);
                }
            }
        }
    }
}
