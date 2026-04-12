<?php

namespace Database\Seeders;

use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\ExtensionProgram;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BeneficiarySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing communities from Samar/Leyte regions only
        $communities = Community::where('municipality', 'LIKE', '%Tacloban%')
            ->orWhere('municipality', 'LIKE', '%Basey%')
            ->orWhere('municipality', 'LIKE', '%Santa Rita%')
            ->get();
        
        $taraBasa = ExtensionProgram::where('title', 'Tara, Basa! Tutoring Program')->first();
        $purpple = ExtensionProgram::where('title', 'PURPPLE Extension Project')->first();
        $director = User::where('role', 'director')->first();

        if ($communities->isEmpty() || !$taraBasa || !$purpple) {
            $this->command->warn('Communities or Programs not found. Please run ExtensionProgramSeeder first.');
            return;
        }

        $creatorId = $director?->id ?? 1;

        // All 125 beneficiary names from Samar/Leyte
        $allNames = [
            'John Rey dela Cruz', 'Maria Fe Flores', 'Angelito Solayao', 'Althea Casaljay', 'Nathaniel Labong',
            'Samantha Picardal', 'Jacob Montes', 'Princess Bajado', 'Joshua Rivera', 'Sofia Macawile',
            'Gabriel Moscosa', 'Andrea Boco', 'Ethan Pajanustan', 'Chloe Elacion', 'Kyle Salazar',
            'Nathalie Canillas', 'Angelo Alvarina', 'Jasmine Demillo', 'Ezekiel Toling', 'Angela Tenedero',
            'James Cabe', 'Sophia Obiado', 'Nathan Durin', 'Liezel Empon', 'Mark Anthony Solayao',
            'Rose Ann Casaljay', 'Daniel Flores', 'Kristine dela Cruz', 'Rafael Labong', 'Marjorie Picardal',
            'Lester Montes', 'Cherry Bajado', 'Rommel Rivera', 'Hazel Macawile', 'Bryan Moscosa',
            'Elaine Boco', 'Vincent Pajanustan', 'Rica Elacion', 'Paolo Salazar', 'Lovely Canillas',
            'Christian Alvarina', 'Joanna Demillo', 'Allan Toling', 'Michelle Tenedero', 'Ronald Cabe',
            'Grace Obiado', 'Edwin Durin', 'April Empon', 'Jerome Solayao', 'Fatima Casaljay',
            'Kenneth Flores', 'Irish dela Cruz', 'Darwin Labong', 'Sheryl Picardal', 'Marlon Montes',
            'Jenny Bajado', 'Arnel Rivera', 'Rhea Macawile', 'Jayson Moscosa', 'Bernadette Boco',
            'Niño Pajanustan', 'Melody Elacion', 'Francis Salazar', 'Catherine Canillas', 'Gerald Alvarina',
            'Teresa Demillo', 'Harold Toling', 'Vanessa Tenedero', 'Dexter Cabe', 'Kristel Obiado',
            'Roland Durin', 'Abigail Empon', 'Vincent Solayao', 'Dianne Casaljay', 'Carlo Flores',
            'Rowena dela Cruz', 'Erickson Labong', 'Maricel Picardal', 'Dennis Montes', 'Precious Bajado',
            'Randy Rivera', 'Jocelyn Macawile', 'Jeffrey Moscosa', 'Annalyn Boco', 'Glenn Pajanustan',
            'Eunice Elacion', 'Patrick Salazar', 'Rosalie Canillas', 'Bernard Alvarina', 'Lyn Demillo',
            'Timothy Toling', 'Carmela Tenedero', 'Jonathan Cabe', 'Gladys Obiado', 'Marvin Durin',
            'Sharlene Empon', 'Adrian Solayao', 'Beatrice Casaljay', 'Frederick Flores', 'Evangeline dela Cruz',
            'Rodrigo Labong', 'Imelda Picardal', 'Oscar Montes', 'Vilma Bajado', 'Ernesto Rivera',
            'Corazon Macawile', 'Renato Moscosa', 'Lilibeth Boco', 'Wilfredo Pajanustan', 'Josephine Elacion',
            'Arturo Salazar', 'Remedios Canillas', 'Edgardo Alvarina', 'Lourdes Demillo', 'Mariano Toling',
            'Reymark Abad', 'Christine Joy Gomera', 'Jeraldine Lacaba', 'Marvin Jay Tan', 'Analyn Sabulao',
            'Romar Galos', 'Glenda Pacatang', 'Arnold Balberona', 'Shaira Mae Dacillo', 'Edgardo Villamor',
        ];

        // Define beneficiary categories with name allocation and ONE program per person
        $taraBasaCategories = [
            // Names 1-25: College Student Tutors (Tara Basa)
            [
                'names' => array_slice($allNames, 0, 25),
                'program_id' => $taraBasa->id,
                'category' => 'College Student Tutors',
                'age_range' => [21, 24],
                'occupation' => 'Student',
                'education' => 'College',
                'monthly_income_range' => [15000, 17000],
            ],
            // Names 26-35: Youth Development Workers (Tara Basa)
            [
                'names' => array_slice($allNames, 25, 10),
                'program_id' => $taraBasa->id,
                'category' => 'Youth Development Workers',
                'age_range' => [27, 30],
                'occupation' => 'Coordinator',
                'education' => 'College',
                'monthly_income_range' => [17000, 19000],
            ],
            // Names 36-60: Grade 1-2 Learners (Tara Basa)
            [
                'names' => array_slice($allNames, 35, 25),
                'program_id' => $taraBasa->id,
                'category' => 'Grade 1-2 Learners',
                'age_range' => [6, 8],
                'occupation' => 'Student',
                'education' => 'Elementary',
                'monthly_income_range' => [0, 5000],
            ],
            // Names 61-85: Parent Participants (Tara Basa)
            [
                'names' => array_slice($allNames, 60, 25),
                'program_id' => $taraBasa->id,
                'category' => 'Parent Participants',
                'age_range' => [30, 45],
                'occupation' => 'Various',
                'education' => 'High School',
                'monthly_income_range' => [10000, 20000],
            ],
        ];

        $purppleCategories = [
            // Names 86-95: Direct Teacher Participants (PURPPLE)
            [
                'names' => array_slice($allNames, 85, 10),
                'program_id' => $purpple->id,
                'category' => 'Direct Teacher Participants',
                'age_range' => [28, 45],
                'occupation' => 'Teacher',
                'education' => 'College',
                'monthly_income_range' => [25000, 35000],
            ],
            // Names 96-110: Indirect Teacher Beneficiaries (PURPPLE)
            [
                'names' => array_slice($allNames, 95, 15),
                'program_id' => $purpple->id,
                'category' => 'Indirect Teacher Beneficiaries',
                'age_range' => [30, 50],
                'occupation' => 'Teacher',
                'education' => 'College',
                'monthly_income_range' => [23000, 32000],
            ],
        ];

        // Merge all categories
        $allCategories = array_merge($taraBasaCategories, $purppleCategories);

        // Create beneficiaries for each category
        foreach ($allCategories as $categoryData) {
            foreach ($categoryData['names'] as $fullName) {
                // Parse name into first and last name
                $nameParts = explode(' ', trim($fullName));
                $lastName = array_pop($nameParts);
                $firstName = implode(' ', $nameParts);

                // Get random community from Samar/Leyte
                $community = $communities->random();

                // Determine age based on category
                $ageRange = $categoryData['age_range'];
                $age = rand($ageRange[0], $ageRange[1]);
                $dateOfBirth = Carbon::now()->subYears($age)->subDays(rand(1, 365));

                // Determine monthly income
                $incomeRange = $categoryData['monthly_income_range'];
                $monthlyIncome = rand($incomeRange[0], $incomeRange[1]);

                // Create beneficiary with ONE program only
                Beneficiary::updateOrCreate(
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    ],
                    [
                        'date_of_birth' => $dateOfBirth,
                        'age' => $age,
                        'gender' => $this->guessGender($firstName),
                        'email' => strtolower(str_replace(' ', '.', $firstName . '.' . $lastName)) . '@lnu.edu.ph',
                        'phone' => '09' . rand(100000000, 999999999),
                        'address' => $community->address ?? $community->municipality,
                        'barangay' => $community->name,
                        'municipality' => $community->municipality,
                        'province' => $community->province,
                        'community_id' => $community->id,
                        'program_ids' => json_encode([$categoryData['program_id']]), // ONE program only
                        'beneficiary_category' => $categoryData['category'],
                        'monthly_income' => $monthlyIncome,
                        'occupation' => $categoryData['occupation'],
                        'educational_attainment' => $categoryData['education'],
                        'marital_status' => $age < 25 ? 'Single' : ($age < 35 ? 'Married' : 'Married'),
                        'number_of_dependents' => $age < 25 ? 0 : rand(1, 4),
                        'status' => 'active',
                        'notes' => 'Beneficiary for ' . $categoryData['category'] . ' in ' . ($categoryData['program_id'] === $taraBasa->id ? 'Tara, Basa!' : 'PURPPLE') . ' program.',
                        'created_by' => $creatorId,
                        'updated_by' => $creatorId,
                    ]
                );
            }
        }

        // Buffer names (111-125) - kept for future enrollment or as inactive
        $bufferNames = array_slice($allNames, 110, 15);
        foreach ($bufferNames as $fullName) {
            $nameParts = explode(' ', trim($fullName));
            $lastName = array_pop($nameParts);
            $firstName = implode(' ', $nameParts);
            $community = $communities->random();

            Beneficiary::updateOrCreate(
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ],
                [
                    'date_of_birth' => Carbon::now()->subYears(rand(20, 50))->subDays(rand(1, 365)),
                    'age' => rand(20, 50),
                    'gender' => $this->guessGender($firstName),
                    'email' => strtolower(str_replace(' ', '.', $firstName . '.' . $lastName)) . '@lnu.edu.ph',
                    'phone' => '09' . rand(100000000, 999999999),
                    'address' => $community->address ?? $community->municipality,
                    'barangay' => $community->name,
                    'municipality' => $community->municipality,
                    'province' => $community->province,
                    'community_id' => $community->id,
                    'program_ids' => json_encode([]),
                    'beneficiary_category' => 'Community Beneficiary',
                    'monthly_income' => rand(10000, 25000),
                    'occupation' => 'Undetermined',
                    'educational_attainment' => 'High School',
                    'marital_status' => 'Single',
                    'number_of_dependents' => 0,
                    'status' => 'inactive',
                    'notes' => 'Buffer beneficiary - Not yet assigned to any program.',
                    'created_by' => $creatorId,
                    'updated_by' => $creatorId,
                ]
            );
        }

        $this->command->info('BeneficiarySeeder completed successfully!');
        $this->command->info('✓ Created 125 beneficiaries (110 active, 15 buffer)');
        $this->command->info('  - Tara, Basa!: 85 beneficiaries');
        $this->command->info('  - PURPPLE: 25 beneficiaries');
        $this->command->info('✓ All beneficiaries linked to ONE program only');
        $this->command->info('✓ All from Samar/Leyte communities');
    }

    /**
     * Guess gender based on first name
     */
    private function guessGender($firstName)
    {
        $maleEndings = ['o', 'e', 'n', 't', 'r', 'd'];
        $femaleEndings = ['a', 'ie', 'a', 'lyn', 'dy', 'ia', 'ine'];

        $lastChar = strtolower(substr($firstName, -1));
        $lastTwoChars = strtolower(substr($firstName, -2));

        // Check if name is typically female
        if (in_array($lastTwoChars, $femaleEndings) || in_array($lastChar, $femaleEndings)) {
            return 'female';
        }

        // Common female names
        $femaleNames = ['Maria', 'Maria Fe', 'Althea', 'Samantha', 'Princess', 'Sofia', 'Andrea', 'Chloe', 'Nathalie', 'Jasmine'];
        if (in_array($firstName, $femaleNames)) {
            return 'female';
        }

        return 'male';
    }
}
