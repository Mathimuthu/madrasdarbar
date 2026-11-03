<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['location_name' => 'Phuket',   'country' => 'Thailand', 'currency' => 'THB'],
            ['location_name' => 'Bangkok',  'country' => 'Thailand', 'currency' => 'THB'],
            ['location_name' => 'Pattaya',  'country' => 'Thailand', 'currency' => 'THB'],
            ['location_name' => 'Colombo',  'country' => 'Sri Lanka', 'currency' => 'LKR'],
        ];

        foreach ($locations as $loc) {
            DB::table('location')->updateOrInsert(
                [
                    'location_name' => $loc['location_name'],
                    'country' => $loc['country']
                ],
                ['currency' => $loc['currency']]
            );
        }
    }
}
