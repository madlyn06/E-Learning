<?php

namespace Modules\Elearning\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Elearning\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            [
                'name' => 'VietQR',
                'code' => 'vietqr',
                'description' => 'Pay with any bank via VietQR code',
                'config' => [
                    'account_number' => '1234567890',
                    'account_name' => 'Elearning System',
                    'bank_id' => '970436', // VCB bank code
                ],
                'logo' => '/assets/images/payment/vietqr.png',
                'display_order' => 1,
            ],
            [
                'name' => 'MoMo',
                'code' => 'momo',
                'description' => 'Pay with MoMo e-wallet',
                'config' => [
                    'partner_code' => 'MOMOXYZ',
                    'partner_name' => 'Elearning System',
                    'access_key' => 'test_access_key',
                    'secret_key' => 'test_secret_key',
                ],
                'logo' => '/assets/images/payment/momo.png',
                'display_order' => 2,
            ],
            // Add more payment methods as needed
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
