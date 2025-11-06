<?php

namespace Modules\Elearning\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Elearning\Models\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount 10%',
                'description' => 'Get 10% off your first course',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'minimum_purchase' => 100000.00,
                'maximum_discount' => 200000.00,
                'usage_limit_per_coupon' => 100,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
                'is_one_time' => true,
            ],
            [
                'code' => 'SUMMER25',
                'name' => 'Summer Special 25%',
                'description' => 'Summer promotion - 25% off all courses',
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'minimum_purchase' => 200000.00,
                'maximum_discount' => 500000.00,
                'usage_limit_per_coupon' => 50,
                'usage_limit_per_user' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
                'is_one_time' => false,
            ],
            [
                'code' => 'FIXED100K',
                'name' => 'Fixed 100K Discount',
                'description' => 'Get 100,000 VND off your course',
                'discount_type' => 'fixed',
                'discount_value' => 100000.00,
                'minimum_purchase' => 300000.00,
                'maximum_discount' => null,
                'usage_limit_per_coupon' => 30,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
                'is_one_time' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
