<?php

namespace Modules\Wallet\app\Http\Controllers;

use App\Facades\MailSender;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GetGlobalInformationTrait;
use Exception;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderDetails;
use Modules\Wallet\app\Models\WalletHistory;

class WalletController extends Controller
{
    use GetGlobalInformationTrait;

    /**
     * @param $id
     * @param $auto
     */
    public static function updateWalletBalance($id, $auto = false)
    {
        $wallet_history = WalletHistory::with('user')->findOrFail($id);

        if ($wallet_history->payment_status == PaymentStatus::REJECTED->value) {
            notifyAdmin(
                'Balance Duplicate', 'You tried to add amount ' . $wallet_history->amount . ' when the payment was rejected', 'danger',
                link: route('admin.show-wallet-history', $wallet_history->id)
            );
        }

        $wallet_history->payment_status = PaymentStatus::COMPLETED->value;

        if ($wallet_history->save() && $wallet_history->payment_status == PaymentStatus::COMPLETED->value) {
            $user = User::find($wallet_history->user_id);
            if (!$user) {
                logger()->error('User not found for wallet history ID: ' . $wallet_history->id);
                return;
            }

            $orderDetailsItem = OrderDetails::find($wallet_history->order_details_id);
            if (!$orderDetailsItem) {
                logger()->error('Order Details not found for wallet history ID: ' . $wallet_history->id);
                return;
            }

            $order = Order::find($wallet_history->order_id);
            if (!$order) {
                logger()->error('Order not found for wallet history ID: ' . $wallet_history->id);
                return;
            }

            // Cast the amount to ensure it's a float
            $wallet_balance        = (string) $user->wallet_balance;
            $wallet_history_amount = (string) $wallet_history->amount;

            // Safely add the amount to the wallet balance using bcadd for precision
            $wallet_balance = bcadd($wallet_balance, $wallet_history_amount, 2);

            // Update the user's wallet balance
            $user->wallet_balance = $wallet_balance;
            $user->save();

            self::sendWalletStatusChangedMail($user, $order, $wallet_history);

            self::sendCommissionReceivedMail($user, $orderDetailsItem, $order, $wallet_history);

            $autoText = $auto ? '(Auto Accepted)' : '';

            notifyAdmin(
                'Balance Added' . $autoText,
                'You added amount ' . $wallet_history->amount . ' to ' . $user->name . ' wallet balance for order #' . optional($wallet_history->order)->order_id
            );
        }
    }

    /**
     * @param $user
     */
    private static function sendWalletStatusChangedMail($vendorUser, $order, $walletHistory)
    {
        try {
            $email = $vendorUser->email;

            [$subject, $message] = MailSender::fetchEmailTemplate('wallet_request_approved', [
                'shop_name' => $vendorUser->seller->shop_name ?? "Shop Name Missing!",
                'amount'    => $walletHistory->amount,
            ]);

            $link = [
                "View Order"       => route('seller.orders.show', ['id' => $order->order_id]),
                "Withdraw Details" => route('seller.my-withdraw.index'),
            ];

            MailSender::sendMail($email, $subject, $message, $link);

        } catch (Exception $e) {
            logError("Unable to send wallet payment approval mail", $e);
        }
    }

    /**
     * @param $user
     * @param $course
     * @param $amount
     */
    private static function sendCommissionReceivedMail($vendorUser, $orderDetailsItem, $order, $walletHistory)
    {
        try {
            $email = $vendorUser->email;

            [$subject, $message] = MailSender::fetchEmailTemplate('commission_received', [
                'shop_name'    => $vendorUser->seller->shop_name ?? "Shop Name Missing!",
                'amount'       => $walletHistory->amount,
                'product_name' => $orderDetailsItem->product_name ?? "Product Name Missing!",
            ]);

            $link = [
                "View Product"     => route('seller.product.show', ['product' => $orderDetailsItem->product_id]),
                "View Order"       => route('seller.orders.show', ['id' => $order->order_id]),
                "Withdraw Details" => route('seller.my-withdraw.index'),
            ];

            MailSender::sendMail($email, $subject, $message, $link);

        } catch (Exception $e) {
            logError("Unable send commission received mail", $e);
        }
    }
}
