<?php

namespace Database\Seeders;

use App\Models\Community;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolAndCommunitySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contactPersons = ['Nikko Villas', 'Bianca Oledan', 'Kent Naputo', 'Carlo Sumile'];
        $contactNumber = '09123456789';
        $personIndex = 0;

        // Tacloban City, Leyte Barangays
        $taclobanBarangays = [
            'Anibong',
            'Bagacay',
            'Basper',
            'Caibaan',
            'Calanipawan',
            'Camansihay',
            'Diit',
            'Marasbaras',
            'Palanog',
            'Sagkahan',
            'Salvacion',
            'San Jose',
            'San Roque',
            'Tagpuro',
            'Utap',
        ];

        // Basey, Samar Barangays
        $baseyBarangays = [
            'Amandayehan',
            'Anglit',
            'Basiao',
            'Buenavista',
            'Cogon',
            'Dolongan',
            'Guirang',
            'Salvacion',
        ];

        // Santa Rita, Samar Barangays
        $santaritaBarangays = [
            'Anibongon',
            'Aslum',
            'Bagolibas',
            'Binanalan',
            'Cabacungan',
            'Magsaysay',
            'Sta. Elena',
        ];

        // Create Tacloban City Barangays
        foreach ($taclobanBarangays as $barangay) {
            Community::firstOrCreate(
                ['name' => $barangay],
                [
                    'municipality' => 'Tacloban City',
                    'province' => 'Leyte',
                    'address' => "Barangay {$barangay}",
                    'description' => "Community in Barangay {$barangay}, Tacloban City, Leyte",
                    'contact_person' => $contactPersons[$personIndex % 4],
                    'contact_number' => $contactNumber,
                    'status' => 'active',
                ]
            );
            $personIndex++;
        }

        // Create Basey, Samar Barangays
        foreach ($baseyBarangays as $barangay) {
            Community::firstOrCreate(
                ['name' => $barangay],
                [
                    'municipality' => 'Basey',
                    'province' => 'Samar',
                    'address' => "Barangay {$barangay}",
                    'description' => "Community in Barangay {$barangay}, Basey, Samar",
                    'contact_person' => $contactPersons[$personIndex % 4],
                    'contact_number' => $contactNumber,
                    'status' => 'active',
                ]
            );
            $personIndex++;
        }

        // Create Santa Rita, Samar Barangays
        foreach ($santaritaBarangays as $barangay) {
            Community::firstOrCreate(
                ['name' => $barangay],
                [
                    'municipality' => 'Santa Rita',
                    'province' => 'Samar',
                    'address' => "Barangay {$barangay}",
                    'description' => "Community in Barangay {$barangay}, Santa Rita, Samar",
                    'contact_person' => $contactPersons[$personIndex % 4],
                    'contact_number' => $contactNumber,
                    'status' => 'active',
                ]
            );
            $personIndex++;
        }

        // Create Tacloban City Schools
        $taclobanSchools = [
            ['name' => 'Anibong Elementary School', 'barangay' => 'Anibong'],
            ['name' => 'Bagacay Elementary School', 'barangay' => 'Bagacay'],
            ['name' => 'Caibaan Elementary School', 'barangay' => 'Caibaan'],
            ['name' => 'Camansihay Elementary School', 'barangay' => 'Camansihay'],
            ['name' => 'Marasbaras Central School', 'barangay' => 'Marasbaras'],
            ['name' => 'Palanog Elementary School', 'barangay' => 'Palanog'],
            ['name' => 'Sagkahan Central School', 'barangay' => 'Sagkahan'],
            ['name' => 'Tagpuro Elementary School', 'barangay' => 'Tagpuro'],
        ];

        foreach ($taclobanSchools as $school) {
            Community::firstOrCreate(
                ['name' => $school['name']],
                [
                    'municipality' => 'Tacloban City',
                    'province' => 'Leyte',
                    'address' => "Barangay {$school['barangay']}, Tacloban City, Leyte",
                    'description' => "School located in {$school['barangay']}, Tacloban City, Leyte",
                    'contact_person' => $contactPersons[$personIndex % 4],
                    'contact_number' => $contactNumber,
                    'status' => 'active',
                ]
            );
            $personIndex++;
        }

        // Create Basey, Samar Schools
        $baseySchools = [
            ['name' => 'Basey I Central Elementary School', 'barangay' => 'Poblacion area'],
            ['name' => 'Basiao Elementary School', 'barangay' => 'Basiao'],
            ['name' => 'Buenavista Elementary School', 'barangay' => 'Buenavista'],
            ['name' => 'Anglit Elementary School', 'barangay' => 'Anglit'],
        ];

        foreach ($baseySchools as $school) {
            Community::firstOrCreate(
                ['name' => $school['name']],
                [
                    'municipality' => 'Basey',
                    'province' => 'Samar',
                    'address' => "Barangay {$school['barangay']}, Basey, Samar",
                    'description' => "School located in {$school['barangay']}, Basey, Samar",
                    'contact_person' => $contactPersons[$personIndex % 4],
                    'contact_number' => $contactNumber,
                    'status' => 'active',
                ]
            );
            $personIndex++;
        }

        // Create Santa Rita, Samar Schools
        $santaritaSchools = [
            ['name' => 'Anibongon Elementary School', 'barangay' => 'Anibongon'],
            ['name' => 'Aslum Elementary School', 'barangay' => 'Aslum'],
            ['name' => 'Magsaysay Elementary School', 'barangay' => 'Magsaysay'],
        ];

        foreach ($santaritaSchools as $school) {
            Community::firstOrCreate(
                ['name' => $school['name']],
                [
                    'municipality' => 'Santa Rita',
                    'province' => 'Samar',
                    'address' => "Barangay {$school['barangay']}, Santa Rita, Samar",
                    'description' => "School located in {$school['barangay']}, Santa Rita, Samar",
                    'contact_person' => $contactPersons[$personIndex % 4],
                    'contact_number' => $contactNumber,
                    'status' => 'active',
                ]
            );
            $personIndex++;
        }
    }
}
