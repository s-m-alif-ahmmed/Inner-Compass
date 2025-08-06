<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::create([
            'title' => 'Inner Compass',
            'system_name' => 'Inner Compass',
            'email' => 'info@innercompassadmin.nl',
            'number' => '5873515720',
            'logo' => '/frontend/logo.png',
            'favicon' => '/frontend/favicon.png',
            'address' => null,
            'copyright_text' => 'Copyright 2025. All Rights Reserved. Powered by Inner Compass.',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}
