<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
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

        // Create Secretary users
        User::factory()->create([
            'name' => 'Secretary One',
            'email' => 'secretary1@lnu.com',
            'password' => 'password',
            'role' => 'secretary',
        ]);

        User::factory()->create([
            'name' => 'Secretary Two',
            'email' => 'secretary2@lnu.com',
            'password' => 'password',
            'role' => 'secretary',
        ]);

        // Create Faculty users and their Faculty records
        $facultyNames = [
            ['name' => 'Dr. Maria Santos', 'email' => 'faculty1@lnu.com'],
            ['name' => 'Prof. Juan Dela Cruz', 'email' => 'faculty2@lnu.com'],
            ['name' => 'Assoc. Prof. Anna Garcia', 'email' => 'faculty3@lnu.com'],
        ];

        foreach ($facultyNames as $facultyData) {
            $user = User::factory()->create([
                'name' => $facultyData['name'],
                'email' => $facultyData['email'],
                'password' => 'password',
                'role' => 'faculty',
            ]);

            // Create corresponding Faculty record
            Faculty::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'department' => 'Department of Extension',
                'specialization' => 'Community Development',
                'position' => 'Faculty Member',
                'phone' => '09123456789',
                'address' => 'Tacloban City, Leyte',
            ]);
        }

        // Call school and community seeder
        $this->call(SchoolAndCommunitySeeder::class);

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
