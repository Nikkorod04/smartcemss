<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ExtensionProgram;
use App\Models\Faculty;
use App\Models\Attendance;
use App\Models\Beneficiary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get data
        $programs = ExtensionProgram::all();
        $faculties = Faculty::all();
        $beneficiaries = Beneficiary::all();

        if ($programs->isEmpty() || $beneficiaries->isEmpty()) {
            $this->command->warn('Programs or Beneficiaries not found. Please run their seeders first.');
            return;
        }

        // Sample activities data
        $activitiesData = [
            [
                'extension_program_id' => $programs->first()->id,
                'title' => 'Week 1: Tutoring Session - Reading Foundation',
                'description' => 'First week of intensive reading sessions focusing on phonetics and letter recognition. Students learn basic phonetic patterns and practice word formation.',
                'actual_start_date' => Carbon::create(2025, 4, 15),
                'actual_end_date' => Carbon::create(2025, 4, 19),
                'venue' => 'Barangay San Juan Community Center',
                'status' => 'completed',
                'notes' => 'Successfully completed with 85% attendance rate. Students showed significant improvement in letter recognition.',
                'allocated_budget' => 50000,
            ],
            [
                'extension_program_id' => $programs->first()->id,
                'title' => 'Week 2: Tutoring Session - Sight Words',
                'description' => 'Introduction to common sight words and simple sentence formation. Students practice reading short sentences with familiar words.',
                'actual_start_date' => Carbon::create(2025, 4, 22),
                'actual_end_date' => Carbon::create(2025, 4, 26),
                'venue' => 'Barangay Diliman Elementary School',
                'status' => 'completed',
                'notes' => 'Good progress observed. All students able to read at least 10 sight words by end of week.',
                'allocated_budget' => 50000,
            ],
            [
                'extension_program_id' => $programs->first()->id,
                'title' => 'Week 3: Tutoring Session - Comprehension Building',
                'description' => 'Focus on reading comprehension through guided reading sessions. Students answer questions about short stories to develop understanding.',
                'actual_start_date' => Carbon::create(2025, 5, 1),
                'actual_end_date' => Carbon::create(2025, 5, 5),
                'venue' => 'Barangay Tondo Community Center',
                'status' => 'ongoing',
                'notes' => 'Currently in progress. Observing good engagement from students.',
                'allocated_budget' => 50000,
            ],
            [
                'extension_program_id' => $programs->first()->id,
                'title' => 'Parent Engagement Session - Reading at Home',
                'description' => 'Nanay-Tatay session to teach parents strategies for supporting reading development at home. Emphasis on creating reading-friendly environments.',
                'actual_start_date' => Carbon::create(2025, 5, 10),
                'actual_end_date' => Carbon::create(2025, 5, 10),
                'venue' => 'Barangay San Juan Community Hall',
                'status' => 'pending',
                'notes' => 'Scheduled for next month. Preparing parent orientation materials.',
                'allocated_budget' => 25000,
            ],
            [
                'extension_program_id' => $programs->last()->id,
                'title' => 'Project Kickoff Meeting - PURPPLE Initiative',
                'description' => 'Official launch and planning meeting for PURPPLE extension project. Teams assigned and responsibilities clarified.',
                'actual_start_date' => Carbon::create(2025, 3, 1),
                'actual_end_date' => Carbon::create(2025, 3, 1),
                'venue' => 'LNU Main Conference Room',
                'status' => 'completed',
                'notes' => 'All stakeholders present. Project timeline finalized.',
                'allocated_budget' => 15000,
            ],
            [
                'extension_program_id' => $programs->last()->id,
                'title' => 'Community Consultation - Needs Assessment',
                'description' => 'Community consultation meeting to conduct needs assessment and identify priority areas for the PURPPLE project.',
                'actual_start_date' => Carbon::create(2025, 3, 15),
                'actual_end_date' => Carbon::create(2025, 3, 15),
                'venue' => 'Palo Municipal Hall',
                'status' => 'completed',
                'notes' => 'Attended by 50+ community members. Key issues identified and documented.',
                'allocated_budget' => 20000,
            ],
        ];

        // Create activities
        foreach ($activitiesData as $data) {
            $activity = Activity::create($data);

            // Attach 1-2 random faculties (limited by available faculties)
            $facultyCount = min(2, $faculties->count());
            $selectedFaculties = $faculties->random($facultyCount)->pluck('id')->toArray();
            $activity->faculties()->attach($selectedFaculties);

            // Create attendance records for completed/ongoing activities
            if (in_array($data['status'], ['completed', 'ongoing'])) {
                $this->createAttendanceRecords($activity, $beneficiaries, $data['actual_start_date'], $data['actual_end_date']);
            }
        }

        $this->command->info('ActivitySeeder completed successfully!');
    }

    /**
     * Create sample attendance records for an activity
     */
    private function createAttendanceRecords($activity, $beneficiaries, $startDate, $endDate)
    {
        // Select 8-12 beneficiaries to mark attendance for
        $selectedBeneficiaries = $beneficiaries->random(min(10, $beneficiaries->count()));
        
        // For each beneficiary, create attendance records for each day
        foreach ($selectedBeneficiaries as $beneficiary) {
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                // 90% chance of present, 7% absent, 3% excused
                $rand = rand(1, 100);
                if ($rand <= 90) {
                    $status = 'present';
                } elseif ($rand <= 97) {
                    $status = 'absent';
                } else {
                    $status = 'excused';
                }

                Attendance::create([
                    'activity_id' => $activity->id,
                    'beneficiary_id' => $beneficiary->id,
                    'attendance_date' => $currentDate,
                    'status' => $status,
                    'remarks' => $status === 'absent' ? 'Did not attend' : null,
                ]);

                $currentDate->addDay();
            }
        }
    }
}
