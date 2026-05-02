<?php

namespace Database\Seeders;

use App\Models\AboutCompany;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutCompany::firstOrCreate([
            'id' => 1
        ], [
            'banner_image' => null,
            'title' => 'About Our Company',
            'subtitle' => null,
            'our_story' => null,
            'story_image' => null,
            'mission' => null,
            'vision' => null,
            'core_values' => null,
            'company_stats' => null,
            'why_choose' => null,
        ]);
    }
}
