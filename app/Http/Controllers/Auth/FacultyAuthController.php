<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ExtensionToken;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FacultyAuthController extends Controller
{
    /**
     * Handle faculty login via token
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
        ], [
            'token.required' => 'Access token is required.',
            'token.string' => 'Invalid token format.',
        ]);

        // Find the token in the database
        $extensionToken = ExtensionToken::where('token', $request->token)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$extensionToken) {
            throw ValidationException::withMessages([
                'token' => ['The provided token is invalid or has expired.'],
            ]);
        }

        // Get the faculty and their user
        $faculty = Faculty::findOrFail($extensionToken->faculty_id);
        $user = $faculty->user;

        // Login the user
        Auth::login($user, $request->filled('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('faculty.dashboard'));
    }

    /**
     * Destroy faculty session
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
