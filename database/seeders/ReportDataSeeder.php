<?php

namespace Database\Seeders;

use App\Models\ReportData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportDataSeeder extends Seeder
{

    public function run(): void
    {
       ReportData::factory()->count(10)->create();
    }
}
