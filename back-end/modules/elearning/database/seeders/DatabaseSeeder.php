<?php

namespace Modules\Elearning\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PaymentMethodSeeder::class,
            CouponSeeder::class,
            // Add other seeders here
        ]);
    }
}
