<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
use App\Models\ExtensionToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Director User
        $director = User::firstOrCreate(
            ['email' => 'director@smartcemes.test'],
            [
                'name' => 'Dr. Juan Dela Cruz',
                'password' => bcrypt('password123'),
                'role' => 'director',
                'email_verified_at' => now(),
            ]
        );

        // Create Secretary User
        $secretary = User::firstOrCreate(
            ['email' => 'secretary@smartcemes.test'],
            [
                'name' => 'Maria Santos',
                'password' => bcrypt('password123'),
                'role' => 'secretary',
                'email_verified_at' => now(),
            ]
        );

        // Create Faculty User
        $facultyUser = User::firstOrCreate(
            ['email' => 'faculty@smartcemes.test'],
            [
                'name' => 'Prof. Roberto Garcia',
                'password' => bcrypt('password123'),
                'role' => 'secretary', // Default role, will log in via token
                'email_verified_at' => now(),
            ]
        );

        // Create Faculty Profile
        $faculty = Faculty::firstOrCreate(
            ['user_id' => $facultyUser->id],
            [
                'employee_id' => 'EMP-2026-001',
                'department' => 'Education',
                'specialization' => 'Community Development',
                'position' => 'Associate Professor',
                'phone' => '09123456789',
                'address' => '123 Main Street, Tacloban City',
                'notes' => 'Test faculty member for token-based login',
            ]
        );

        // Create Access Tokens
        $token1 = ExtensionToken::firstOrCreate(
            ['token' => 'smartcemes_token_test_001'],
            [
                'faculty_id' => $faculty->id,
                'token' => 'smartcemes_token_test_001',
                'expires_at' => now()->addYears(1),
                'generated_by' => $director->id,
            ]
        );

        $token2 = ExtensionToken::firstOrCreate(
            ['token' => 'smartcemes_token_test_002'],
            [
                'faculty_id' => $faculty->id,
                'token' => 'smartcemes_token_test_002',
                'expires_at' => now()->addMonths(6),
                'generated_by' => $director->id,
            ]
        );

        $this->command->info('Test users and tokens created successfully!');
        $this->command->line('');
        $this->command->line('Director Login:');
        $this->command->line('  Email: director@smartcemes.test');
        $this->command->line('  Password: password123');
        $this->command->line('');
        $this->command->line('Secretary Login:');
        $this->command->line('  Email: secretary@smartcemes.test');
        $this->command->line('  Password: password123');
        $this->command->line('');
        $this->command->line('Faculty Token Logins:');
        $this->command->line('  Token 1: smartcemes_token_test_001');
        $this->command->line('  Token 2: smartcemes_token_test_002');
    }
}
