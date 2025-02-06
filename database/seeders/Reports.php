<?php

namespace Database\Seeders;

use App\Models\Reports as ModelsReports;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class Reports extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = ModelsReports::Create([
            'user_id' => 1,
            'company_name' => 'Company 1',
            'location' => 'Location 1',
            'description_of_problem' => 'Description of problem 1',
            'date_to_be_resolved' => '2025-02-06',
            'date_reported' => now(),
            'status' => 'open',
        ]);

        $reports->images()->createMany([
            ['image_path' => 'path/to/image1.jpg', 'is_before' => true],
            ['image_path' => 'path/to/image2.jpg', 'is_before' => false],
        ]);
    }
}
