<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\ExtensionToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FacultyController extends Controller
{
    /**
     * Display a listing of faculty members.
     */
    public function index()
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $faculties = Faculty::with('user', 'tokens', 'extensionPrograms')
            ->paginate(15);

        return view('faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new faculty member.
     */
    public function create()
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        return view('faculties.create');
    }

    /**
     * Store a newly created faculty member in database.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:faculties,employee_id',
            'department' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'position' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Create User account for faculty
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt(Str::random(16)), // Random password, faculty uses token to login
            'role' => 'faculty',
            'email_verified_at' => now(),
        ]);

        // Create Faculty profile
        $faculty = Faculty::create([
            'user_id' => $user->id,
            'employee_id' => $validated['employee_id'],
            'department' => $validated['department'],
            'specialization' => $validated['specialization'] ?? null,
            'position' => $validated['position'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($faculty)
            ->event('created')
            ->log('Faculty member created: ' . $faculty->user->name);

        return redirect()->route('faculties.show', $faculty)
            ->with('success', 'Faculty member created successfully. Generate an access token to allow them to login.');
    }

    /**
     * Display the specified faculty member.
     */
    public function show(Faculty $faculty)
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $faculty->load('user', 'tokens', 'extensionPrograms', 'activities', 'availabilities');
        $activeTokens = $faculty->tokens()->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        })->get();

        return view('faculties.show', compact('faculty', 'activeTokens'));
    }

    /**
     * Show the form for editing the specified faculty member.
     */
    public function edit(Faculty $faculty)
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $faculty->load('user');
        return view('faculties.edit', compact('faculty'));
    }

    /**
     * Update the specified faculty member in database.
     */
    public function update(Request $request, Faculty $faculty)
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $faculty->user_id,
            'department' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'position' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Update user info
        $faculty->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update faculty profile
        $faculty->update([
            'department' => $validated['department'],
            'specialization' => $validated['specialization'] ?? null,
            'position' => $validated['position'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($faculty)
            ->event('updated')
            ->log('Faculty member updated: ' . $faculty->user->name);

        return redirect()->route('faculties.show', $faculty)
            ->with('success', 'Faculty member updated successfully.');
    }

    /**
     * Delete the specified faculty member from database.
     */
    public function destroy(Faculty $faculty)
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $userName = $faculty->user->name;

        // Soft delete faculty and associated user
        $faculty->delete();
        $faculty->user->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($faculty)
            ->event('deleted')
            ->log('Faculty member deleted: ' . $userName);

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty member deleted successfully.');
    }

    /**
     * Generate a new access token for faculty member (Director chooses expiration)
     */
    public function generateToken(Request $request, Faculty $faculty)
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $validated = $request->validate([
            'expires_at' => 'nullable|date|after_or_equal:today',
            'expires_in_days' => 'nullable|integer|min:1|max:1095', // Up to 3 years
        ]);

        $expiresAt = null;

        // Either explicit date or calculated from days
        if ($validated['expires_at'] ?? null) {
            $expiresAt = \Carbon\Carbon::parse($validated['expires_at'])->endOfDay();
        } elseif ($validated['expires_in_days'] ?? null) {
            $expiresAt = now()->addDays($validated['expires_in_days'])->endOfDay();
        }

        // Generate unique token
        $token = Str::random(64);

        // Create token record
        $extensionToken = ExtensionToken::create([
            'faculty_id' => $faculty->id,
            'token' => $token,
            'expires_at' => $expiresAt,
            'generated_by' => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($extensionToken)
            ->event('created')
            ->log('Access token generated for faculty: ' . $faculty->user->name . 
                  '. Expires: ' . ($expiresAt ? $expiresAt->format('M d, Y') : 'Never'));

        return redirect()->route('faculties.show', $faculty)
            ->with('success', 'Access token generated successfully.')
            ->with('token', $token)
            ->with('tokenDisplay', true);
    }

    /**
     * Revoke an access token
     */
    public function revokeToken(ExtensionToken $token)
    {
        if (auth()->user()->role !== 'director') {
            abort(403, 'Unauthorized. Only Directors can manage faculty.');
        }

        $faculty = $token->faculty;
        $token->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($token)
            ->event('deleted')
            ->log('Access token revoked for faculty: ' . $faculty->user->name);

        return redirect()->route('faculties.show', $faculty)
            ->with('success', 'Access token revoked successfully.');
    }
}
