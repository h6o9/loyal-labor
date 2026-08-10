<?php

namespace Modules\PaymentWithdraw\database\seeders;

use Illuminate\Database\Seeder;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;

class PaymentWithdrawDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name'        => 'PayPal',
                'min_amount'  => 50.00,
                'max_amount'  => 1000.00,
                'description' => '<p>Please provide the necessary information for payment</p><ol><li>Your payment email</li><li>Your Holder name</li></ol>',
                'status'      => 'active',
            ],
            [
                'name'        => 'Stripe',
                'min_amount'  => 50.00,
                'max_amount'  => 1000.00,
                'description' => '<p>Please provide the necessary information for payment</p><ol><li>Your payment email</li><li>Your Holder name</li></ol>',
                'status'      => 'active',
            ],
            [
                'name'        => 'Razorpay',
                'min_amount'  => 50.00,
                'max_amount'  => 1000.00,
                'description' => '<p>Please provide the necessary information for payment</p><ol><li>Your payment email</li><li>Your Holder name</li></ol>',
                'status'      => 'active',
            ],
            [
                'name'        => 'Bank',
                'min_amount'  => 50.00,
                'max_amount'  => 1000.00,
                'description' => '<p>Please provide the necessary information for payment</p><ol><li>Your Account number</li><li>Your branch name</li><li>Your routing number</li></ol>',
                'status'      => 'active',
            ],
        ];

        foreach ($methods as $method) {
            $newMethod              = new WithdrawMethod();
            $newMethod->name        = $method['name'];
            $newMethod->min_amount  = $method['min_amount'];
            $newMethod->max_amount  = $method['max_amount'];
            $newMethod->description = $method['description'];
            $newMethod->status      = $method['status'];
            $newMethod->save();
        }
    }
}
