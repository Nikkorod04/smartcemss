<?php

namespace Database\Seeders;

use App\Models\ExtensionProgram;
use App\Models\Community;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExtensionProgramSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create faculty users if they don't exist
        $faculty1Data = $this->createFacultyIfNotExists(
            'Dr. Lorena M. Ripalda',
            'F001',
            'College of Education',
            'Education & Research',
            'Professor'
        );

        $faculty2Data = $this->createFacultyIfNotExists(
            'Dr. Fabiana P. Peñeda',
            'F002',
            'Community Extension Services Office',
            'Extension Services',
            'Professor'
        );

        // Get existing Samar/Leyte communities created by SchoolAndCommunitySeeder
        $taraBasaCommunities = Community::whereIn('municipality', ['Tacloban City', 'Basey', 'Santa Rita'])->get();
        
        if ($taraBasaCommunities->isEmpty()) {
            $this->command->warn('No Samar/Leyte communities found. Please run SchoolAndCommunitySeeder first.');
            return;
        }

        // Create Program 1: Tara, Basa! Tutoring Program
        $program1 = ExtensionProgram::firstOrCreate(
            ['title' => 'Tara, Basa! Tutoring Program'],
            [
                'description' => 'The Tara, Basa! Tutoring Program is a flagship social technology initiative of the Department of Social Welfare and Development (DSWD), in partnership with the Department of Education (DepEd), designed to address learning poverty and reading proficiency gaps among young Filipino learners. This "reformatted educational assistance" model creates a comprehensive learning ecosystem by engaging low-income college students as tutors and Youth Development Workers (YDWs). These students are trained to conduct intensive 20-day reading sessions for struggling or non-reading Grade 1 and 2 learners in public schools, while also facilitating "Nanay-Tatay" teacher sessions to empower parents as their children\'s primary educators at home.',
                'goals' => 'Address learning poverty and reading proficiency gaps; Provide financial assistance to college students; Empower parents as primary educators; Establish a sustainable learning support system.',
                'objectives' => 'Conduct intensive 20-day reading intervention programs for Grade 1 and 2 learners; Train 100 college student tutors as reading coaches; Engage 75 Youth Development Workers in program implementation; Facilitate parent training sessions ("Nanay-Tatay" sessions); Provide cash-for-work assistance to tutors and parent participants; Reach and support over 175 beneficiaries from LNU.',
                'planned_start_date' => Carbon::create(2026, 3, 25),
                'planned_end_date' => Carbon::create(2026, 5, 20),
                'target_beneficiaries' => 85,
                'beneficiary_categories' => json_encode([
                    'College Student Tutors' => 25,
                    'Youth Development Workers' => 10,
                    'Grade 1-2 Learners' => 25,
                    'Parent Participants' => 25,
                ]),
                'allocated_budget' => 2500000,
                'program_lead_id' => $faculty1Data['faculty']->id,
                'partners' => json_encode([
                    'Department of Social Welfare and Development (DSWD)',
                    'Department of Education (DepEd)',
                    'Leyte Normal University',
                    'Public Schools in NCR and Nationwide Regions',
                ]),
                'cover_image' => 'prog/tarabasa.png',
                'gallery_images' => json_encode([
                    'programs/gallery/tarabasa_activity_1.jpg',
                    'programs/gallery/tarabasa_activity_2.jpg',
                    'programs/gallery/tarabasa_activity_3.jpg',
                ]),
                'attachments' => json_encode([
                    'programs/attachments/tarabasa_program_guide.pdf',
                    'programs/attachments/tarabasa_implementation_manual.pdf',
                ]),
                'status' => 'ongoing',
                'notes' => 'Program provides ₱545 daily stipends for tutors and ₱235 per session allowance for parents. Expanded nationwide after 2023 NCR pilot. Covers regions: Ilocos, Bicol, Western Visayas, Northern Mindanao, and CARAGA.',
                'created_by' => User::where('role', 'director')->first()?->id ?? 1,
                'updated_by' => User::where('role', 'director')->first()?->id ?? 1,
            ]
        );

        // Create Program 2: PURPPLE Extension Project
        $program2 = ExtensionProgram::firstOrCreate(
            ['title' => 'PURPPLE Extension Project'],
            [
                'description' => 'The PURPPLE Extension Project at Leyte Normal University (LNU) stands for "Profound Understanding of Research Production and Publication for Learning Educators." Formally launched on April 11, 2025, through a Memorandum of Agreement (MOA) between LNU\'s College of Education and the Community Extension Services Office (CESO), the initiative is specifically designed to bolster the research competencies of educators in the local community. It provides intensive training in research methodologies, scholarly writing, and the technical processes of publication. The project follows a "cascading model" where participating teachers are expected to share their newly acquired skills with their colleagues, thereby fostering a sustainable culture of academic excellence within their respective schools.',
                'goals' => 'Enhance research competencies of educators; Promote scholarly writing and publication; Establish sustainable knowledge-sharing culture; Align academic research with community needs and regional development.',
                'objectives' => 'Conduct intensive training in research methodologies and scholarly writing; Train educators from Palo National High School and Cogon Elementary School; Implement cascading model for knowledge transfer among teachers; Facilitate publication processes and academic dissemination; Support the KAHAYAG extension agenda of LNU; Recognize and empower teacher-participants through community engagement.',
                'planned_start_date' => Carbon::create(2026, 3, 20),
                'planned_end_date' => Carbon::create(2026, 4, 15),
                'target_beneficiaries' => 25,
                'beneficiary_categories' => json_encode([
                    'Direct Teacher Participants' => 10,
                    'Indirect Teacher Beneficiaries' => 15,
                ]),
                'allocated_budget' => 850000,
                'program_lead_id' => $faculty2Data['faculty']->id,
                'partners' => json_encode([
                    'Leyte Normal University - College of Education',
                    'Community Extension Services Office (CESO)',
                    'Palo National High School',
                    'Cogon Elementary School',
                    'Department of Education - Leyte Division',
                ]),
                'cover_image' => 'prog/purpple.png',
                'gallery_images' => json_encode([
                    'programs/gallery/purpple_training_1.jpg',
                    'programs/gallery/purpple_training_2.jpg',
                    'programs/gallery/purpple_training_3.jpg',
                ]),
                'attachments' => json_encode([
                    'programs/attachments/purpple_moa.pdf',
                    'programs/attachments/purpple_training_modules.pdf',
                ]),
                'status' => 'ongoing',
                'notes' => 'Part of LNU\'s KAHAYAG extension agenda. MOA signed April 11, 2025. Features Gift-Giving Day recognition programs (December 19, 2025). Focuses on inclusive learning and regional development. Follows cascading training model for sustainability.',
                'created_by' => User::where('role', 'director')->first()?->id ?? 1,
                'updated_by' => User::where('role', 'director')->first()?->id ?? 1,
            ]
        );

        // Attach all Samar/Leyte communities to both programs
        foreach ($taraBasaCommunities as $community) {
            $program1->communities()->syncWithoutDetaching($community->id);
            $program2->communities()->syncWithoutDetaching($community->id);
        }

        $this->command->info('Extension programs seeded successfully!');
        $this->command->info('✓ Tara, Basa! Tutoring Program created');
        $this->command->info('✓ PURPPLE Extension Project created');
    }

    /**
     * Create faculty if not exists
     */
    private function createFacultyIfNotExists($name, $employeeId, $department, $specialization, $position)
    {
        $user = User::firstOrCreate(
            ['email' => strtolower(str_replace(' ', '.', $name)) . '@lnu.edu.ph'],
            [
                'name' => $name,
                'password' => bcrypt('password123'),
                'role' => 'faculty',
            ]
        );

        $faculty = Faculty::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => $employeeId,
                'department' => $department,
                'specialization' => $specialization,
                'position' => $position,
            ]
        );

        return [
            'user' => $user,
            'faculty' => $faculty,
        ];
    }
}
