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

        // Get programs by title
        $taraBasaProgram = $programs->where('title', 'Tara, Basa! Tutoring Program')->first();
        $purppleProgram = $programs->where('title', 'PURPPLE Extension Project')->first();

        // Tara, Basa Activities (April 16-20 pending, May 18 & 20 pending)
        $taraBasaActivities = [
            [
                'extension_program_id' => $taraBasaProgram->id,
                'title' => 'Capbuilding Day 1: Program Overview',
                'description' => 'First day of capacity-building workshops focusing on program overview, objectives, and expected outcomes for all participants (college tutors, YDWs, and facilitators).',
                'actual_start_date' => Carbon::create(2026, 4, 16),
                'actual_end_date' => Carbon::create(2026, 4, 16),
                'venue' => 'LNU College of Education Auditorium',
                'status' => 'pending',
                'notes' => 'Scheduled capacity-building session. All 85 beneficiaries expected to attend.',
                'allocated_budget' => 75000,
            ],
            [
                'extension_program_id' => $taraBasaProgram->id,
                'title' => 'Capbuilding Day 2: Pedagogical Foundations and Learner Diversity',
                'description' => 'Second day focusing on pedagogical foundations, understanding diverse learners, and differentiated instruction strategies.',
                'actual_start_date' => Carbon::create(2026, 4, 17),
                'actual_end_date' => Carbon::create(2026, 4, 17),
                'venue' => 'LNU College of Education Auditorium',
                'status' => 'pending',
                'notes' => 'Scheduled capacity-building session. All 85 beneficiaries expected to attend.',
                'allocated_budget' => 75000,
            ],
            [
                'extension_program_id' => $taraBasaProgram->id,
                'title' => 'Capbuilding Day 3: Assessment Tools and Enhanced Guidebooks',
                'description' => 'Third day covering assessment tools, evaluation methods, and enhanced guidebook usage for effective tutoring delivery.',
                'actual_start_date' => Carbon::create(2026, 4, 18),
                'actual_end_date' => Carbon::create(2026, 4, 18),
                'venue' => 'LNU College of Education Auditorium',
                'status' => 'pending',
                'notes' => 'Scheduled capacity-building session. All 85 beneficiaries expected to attend.',
                'allocated_budget' => 75000,
            ],
            [
                'extension_program_id' => $taraBasaProgram->id,
                'title' => 'Capbuilding Day 4: Hands-on Application and Return Demonstrations',
                'description' => 'Fourth day featuring hands-on applications of learned concepts and return demonstrations where participants practice teaching techniques.',
                'actual_start_date' => Carbon::create(2026, 4, 19),
                'actual_end_date' => Carbon::create(2026, 4, 19),
                'venue' => 'LNU College of Education Auditorium',
                'status' => 'pending',
                'notes' => 'Scheduled capacity-building session. All 85 beneficiaries expected to attend.',
                'allocated_budget' => 75000,
            ],
            [
                'extension_program_id' => $taraBasaProgram->id,
                'title' => 'Capbuilding Day 5: Deployment Readiness and Final Evaluation',
                'description' => 'Fifth day focusing on deployment readiness, logistical preparations, final evaluation of learning, and program rollout guidelines.',
                'actual_start_date' => Carbon::create(2026, 4, 20),
                'actual_end_date' => Carbon::create(2026, 4, 20),
                'venue' => 'LNU College of Education Auditorium',
                'status' => 'pending',
                'notes' => 'Scheduled capacity-building session. All 85 beneficiaries expected to attend.',
                'allocated_budget' => 75000,
            ],
            [
                'extension_program_id' => $taraBasaProgram->id,
                'title' => 'Culminating Activities',
                'description' => 'Grand culminating activity celebrating the achievements of the Tara, Basa! program with certificate awarding, recognition of outstanding participants, and informal fellowship.',
                'actual_start_date' => Carbon::create(2026, 5, 18),
                'actual_end_date' => Carbon::create(2026, 5, 18),
                'venue' => 'LNU Main Gymnasium',
                'status' => 'pending',
                'notes' => 'Scheduled culminating event. Expected attendance: all 85 beneficiaries and program partners.',
                'allocated_budget' => 100000,
            ],
            [
                'extension_program_id' => $taraBasaProgram->id,
                'title' => 'Cash-for-Work (CFW) Payout',
                'description' => 'Distribution of cash allowances to college tutors, Youth Development Workers, and parent participants as compensation for program participation.',
                'actual_start_date' => Carbon::create(2026, 5, 20),
                'actual_end_date' => Carbon::create(2026, 5, 20),
                'venue' => 'LNU Finance Office',
                'status' => 'pending',
                'notes' => 'Scheduled payout. Expected beneficiaries: All 85 participants receiving cash allowances.',
                'allocated_budget' => 500000,
            ],
        ];

        // PURPPLE Activities (March 23-25, April 6 - all completed)
        $purppleActivities = [
            [
                'extension_program_id' => $purppleProgram->id,
                'title' => 'Capacity-Building & Mentoring',
                'description' => 'Initial capacity-building workshop and one-on-one mentoring sessions for teacher participants focusing on research competencies and scholarly writing foundations.',
                'actual_start_date' => Carbon::create(2026, 3, 23),
                'actual_end_date' => Carbon::create(2026, 3, 23),
                'venue' => 'Palo National High School Conference Room',
                'status' => 'completed',
                'notes' => 'Successfully completed with 24 of 25 teacher participants attending. High engagement observed.',
                'allocated_budget' => 60000,
            ],
            [
                'extension_program_id' => $purppleProgram->id,
                'title' => 'Seminar-Workshops',
                'description' => 'Series of seminar-workshops covering research methodologies, data collection techniques, and scholarly article writing for publication.',
                'actual_start_date' => Carbon::create(2026, 3, 24),
                'actual_end_date' => Carbon::create(2026, 3, 24),
                'venue' => 'Palo National High School Convention Center',
                'status' => 'completed',
                'notes' => 'Attended by 23 of 25 teacher participants. Resource materials distributed. Positive feedback received.',
                'allocated_budget' => 80000,
            ],
            [
                'extension_program_id' => $purppleProgram->id,
                'title' => 'Research Production & Publication',
                'description' => 'Guided research production sessions where teachers conduct collaborative research projects and prepare manuscripts for submission to academic journals.',
                'actual_start_date' => Carbon::create(2026, 3, 25),
                'actual_end_date' => Carbon::create(2026, 3, 25),
                'venue' => 'Cogon Elementary School Multi-purpose Hall',
                'status' => 'completed',
                'notes' => 'Research teams formed and project proposals finalized. 22 participants actively involved in research planning.',
                'allocated_budget' => 70000,
            ],
            [
                'extension_program_id' => $purppleProgram->id,
                'title' => 'Community Engagement',
                'description' => 'Community engagement activity where teacher-participants share their acquired research and publication skills with colleagues in their respective schools, fostering cascading knowledge transfer.',
                'actual_start_date' => Carbon::create(2026, 4, 6),
                'actual_end_date' => Carbon::create(2026, 4, 6),
                'venue' => 'Palo National High School & Cogon Elementary School',
                'status' => 'completed',
                'notes' => 'Community dissemination completed. Cascading training model initiated with peer educators from both partner schools.',
                'allocated_budget' => 50000,
            ],
        ];

        // Merge all activities
        $allActivities = array_merge($taraBasaActivities, $purppleActivities);

        // Create activities with faculty assignments and attendance
        foreach ($allActivities as $data) {
            $activity = Activity::create($data);

            // Attach 1-2 random faculties
            $facultyCount = min(2, $faculties->count());
            $selectedFaculties = $faculties->random($facultyCount)->pluck('id')->toArray();
            $activity->faculties()->attach($selectedFaculties);

            // Create realistic attendance records for completed activities only
            if ($data['status'] === 'completed') {
                $this->createAttendanceRecords($activity, $beneficiaries, $data['extension_program_id']);
            }
        }

        $this->command->info('ActivitySeeder completed successfully!');
        $this->command->info('✓ Tara, Basa! Program: 7 activities (5 pending capbuilding, 1 pending culminating, 1 pending CFW payout)');
        $this->command->info('✓ PURPPLE Extension Project: 4 activities (all completed)');
    }

    /**
     * Create realistic attendance records for an activity (completed activities only)
     */
    private function createAttendanceRecords($activity, $allBeneficiaries, $programId)
    {
        // Get beneficiaries belonging to this program
        $programBeneficiaries = $allBeneficiaries->filter(function ($beneficiary) use ($programId) {
            return $beneficiary->extensionPrograms->contains('id', $programId);
        });

        if ($programBeneficiaries->isEmpty()) {
            return;
        }

        // Select 70-90% of available beneficiaries realistically (some always miss)
        $attendanceRate = rand(70, 90);
        $attendeeCount = max(1, intdiv($programBeneficiaries->count() * $attendanceRate, 100));
        $selectedBeneficiaries = $programBeneficiaries->random($attendeeCount);

        // Create attendance record for the single activity day
        foreach ($selectedBeneficiaries as $beneficiary) {
            // 85% present, 10% absent, 5% excused
            $rand = rand(1, 100);
            if ($rand <= 85) {
                $status = 'present';
            } elseif ($rand <= 95) {
                $status = 'absent';
            } else {
                $status = 'excused';
            }

            Attendance::create([
                'activity_id' => $activity->id,
                'beneficiary_id' => $beneficiary->id,
                'attendance_date' => $activity->actual_start_date,
                'status' => $status,
                'remarks' => $status === 'absent' ? 'Did not attend' : null,
            ]);
        }
    }
}
