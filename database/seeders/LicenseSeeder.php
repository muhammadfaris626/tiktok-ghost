<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        License::create([
            'user_id' => 1,
            'license_key' => 'ABC123-DEF456-GHI789',
            'expired_at' => now()->addMonth(),
            'is_active' => true,
        ]);
    }
}
