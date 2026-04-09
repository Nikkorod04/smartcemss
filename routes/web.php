<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\NeedsAssessmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\FacultyAvailabilityController;
use App\Http\Controllers\CalendarController;
use App\Livewire\FacultyDashboard;
use App\Livewire\FacultyPrograms;
use App\Livewire\FacultyCalendar;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Program routes
    Route::resource('programs', ProgramController::class);
    Route::get('/programs-search', [ProgramController::class, 'search'])->name('programs.search');
    Route::get('/programs-filter/{status}', [ProgramController::class, 'filterByStatus'])->name('programs.filter');

    // Community routes
    Route::resource('communities', CommunityController::class);
    Route::get('/communities-search', [CommunityController::class, 'search'])->name('communities.search');
    Route::get('/communities-filter/{status}', [CommunityController::class, 'filterByStatus'])->name('communities.filter');

    // Beneficiary routes
    Route::resource('beneficiaries', BeneficiaryController::class);
    Route::get('/beneficiaries-search', [BeneficiaryController::class, 'search'])->name('beneficiaries.search');
    Route::get('/beneficiaries-filter/{status}', [BeneficiaryController::class, 'filterByStatus'])->name('beneficiaries.filter');

    // Activity routes
    Route::resource('activities', ActivityController::class);
    Route::get('/activities-search', [ActivityController::class, 'search'])->name('activities.search');
    Route::get('/activities-filter/{status}', [ActivityController::class, 'filterByStatus'])->name('activities.filter');
    Route::post('/activities/{activity}/record-attendance', [ActivityController::class, 'recordAttendance'])->name('activities.recordAttendance');
    Route::get('/activities/{activity}/progress', [ActivityController::class, 'getProgress'])->name('activities.progress');

    // Assessment routes
    Route::resource('assessments', NeedsAssessmentController::class);
    Route::get('/assessments-search', [NeedsAssessmentController::class, 'search'])->name('assessments.search');
    Route::get('/assessments-filter/{quarter}', [NeedsAssessmentController::class, 'filterByQuarter'])->name('assessments.filter');

    // Faculty routes (Director only)
    Route::resource('faculties', FacultyController::class);
    Route::post('/faculties/{faculty}/generate-token', [FacultyController::class, 'generateToken'])->name('faculties.generateToken');
    Route::delete('/tokens/{token}/revoke', [FacultyController::class, 'revokeToken'])->name('tokens.revoke');

    // Faculty availability approval (Director only)
    Route::middleware('auth')->group(function () {
        Route::post('/availability/{availability}/approve', [FacultyAvailabilityController::class, 'approve'])
            ->name('availability.approve')
            ->middleware('director');
        Route::post('/availability/{availability}/reject', [FacultyAvailabilityController::class, 'reject'])
            ->name('availability.reject')
            ->middleware('director');
        Route::get('/availability/pending', function () {
            return view('availability.pending');
        })->name('availability.pending')->middleware('director');
    });

    // Faculty portal (Faculty only)
    Route::middleware('faculty')->group(function () {
        Route::get('/faculty/dashboard', FacultyDashboard::class)->name('faculty.dashboard');
        Route::get('/faculty/programs', FacultyPrograms::class)->name('faculty.programs');
        Route::get('/faculty/programs/{program}', \App\Livewire\FacultyProgramDetail::class)->name('faculty.programs.show');
        Route::get('/faculty/calendar', FacultyCalendar::class)->name('faculty.calendar');
        Route::get('/faculty/availability', [FacultyAvailabilityController::class, 'index'])->name('faculty.availability.index');
        Route::post('/faculty/availability', [FacultyAvailabilityController::class, 'store'])->name('faculty.availability.store');
        Route::delete('/faculty/availability/{availability}', [FacultyAvailabilityController::class, 'destroy'])->name('faculty.availability.destroy');
    });

    // Calendar API endpoints (Faculty, Director, Secretary)
    Route::get('/api/calendar/events', [CalendarController::class, 'getEvents'])->name('api.calendar.events');
    Route::post('/api/calendar/events/update', [CalendarController::class, 'updateEvent'])->name('api.calendar.events.update');
    Route::post('/api/calendar/activities/create', [CalendarController::class, 'createActivity'])->name('api.calendar.activities.create');

    // Admin calendar page (Director/Secretary)
    Route::view('/calendar', 'calendar.index')->name('calendar.index');
});

require __DIR__.'/auth.php';
