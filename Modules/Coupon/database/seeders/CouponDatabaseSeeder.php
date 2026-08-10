<?php

namespace Modules\Coupon\database\seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\Coupon\app\Models\Coupon;

class CouponDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            $startDate   = $faker->dateTimeBetween('-6 months', 'now');
            $expiredDate = $faker->optional()->dateTimeBetween($startDate, '+1 year');

            $coupon = Coupon::create([
                'author_id'                => 0,
                'coupon_code'              => strtoupper($faker->regexify('[A-Z0-9]{' . rand(5, 10) . '}')),
                'apply_for'                => $faker->randomElement(['product', 'category', 'all']),
                'minimum_spend'            => $faker->optional()->randomFloat(2, 0, 1000),
                'usage_limit_per_coupon'   => $faker->optional()->numberBetween(1, 100),
                'usage_limit_per_customer' => $faker->optional()->numberBetween(1, 10),
                'can_use_with_campaign'    => $faker->boolean(),
                'free_shipping'            => $faker->boolean(),
                'is_percent'               => $isPercent = $faker->boolean(),
                'used'                     => $faker->numberBetween(0, 100),
                'status'                   => $faker->boolean(89),
                'show_homepage'            => 1,

                'start_date'               => $startDate->format('Y-m-d H:i:s'),
                'expired_date'             => $expiredDate?->format('Y-m-d H:i:s'),
                'is_never_expired'         => $expiredDate ? 0 : 1,

                'discount'                 => $isPercent
                ? $faker->numberBetween(1, 50)
                : $faker->randomFloat(2, 1, 200),
            ]);

            foreach (allLanguages() as $language) {
                $coupon->translations()->create([
                    'lang_code' => $language->code,
                    'name'      => $faker->sentence(),
                ]);
            }
        }
    }
}
