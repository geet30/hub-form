<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Policies'
            ],
            [
                'name' => 'Procedures'
            ],
            [
                'name' => 'Instructions'
            ],
            [
                'name' => 'Management Plans'
            ],
            [
                'name' => 'Drawings'
            ]
        ]);
    }
}
