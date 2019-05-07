<?php

use Illuminate\Database\Seeder;

class VendorCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories =[
            ['name'=>'Fashion store'],
            ['name'=>'Food delivery'],
            ['name'=>'General shop'],
            ['name'=>'Groceries'],
            ['name'=>'Liquor store'],
            ['name'=>'Pharmacies'],
            ['name'=>'Supermarkets'],
        ];

        foreach ($categories as $category){
            \App\Models\VendorCategory::create($category);
        }
    }
}
