<?php

use App\Models\Business_unit;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class BusinessUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 500; $i++) {
            Business_unit::create([
                'vc_short_name' => $faker->name,
                'vc_legal_name' => $faker->name,
                'vc_description' => $faker->text,
                'vc_comments' => $faker->text,
                'i_ref_location_id' => $faker->numberBetween(1, 10),
                'i_status' => 1,
                'i_ref_company_id' => 1,
            ]);
        }
    }
}
