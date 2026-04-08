<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a Director user
        User::factory()->create([
            'name' => 'Director Admin',
            'email' => 'admin@lnu.com',
            'password' => 'password',
            'role' => 'director',
        ]);

        // Create a Secretary user
        User::factory()->create([
            'name' => 'Secretary User',
            'email' => 'secretary@lnu.com',
            'password' => 'password',
            'role' => 'secretary',
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Call extension program seeder
        $this->call(ExtensionProgramSeeder::class);

        // Call beneficiary seeder
        $this->call(BeneficiarySeeder::class);

        // Call activity seeder
        $this->call(ActivitySeeder::class);

        // Call needs assessment seeder
        $this->call(NeedsAssessmentSeeder::class);
    }
}
