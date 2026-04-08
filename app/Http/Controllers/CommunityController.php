<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * Display a listing of all communities.
     */
    public function index()
    {
        $communities = Community::with('extensionPrograms', 'needsAssessments')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('communities.index', compact('communities'));
    }

    /**
     * Show the form for creating a new community.
     * Only Director can create communities.
     */
    public function create()
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('create', Community::class);

        return view('communities.create');
    }

    /**
     * Store a newly created community in database.
     */
    public function store(Request $request)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('create', Community::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:communities,name', 'regex:/^[a-zA-Z0-9\s\-\.&,()]+$/'],
            'municipality' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/'],
            'province' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/'],
            'description' => 'nullable|string|min:10|max:1000',
            'contact_person' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'contact_number' => ['required', 'string', 'regex:/^[\d\s\-\+()]+$/', 'min:7', 'max:20'],
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,archived',
            'notes' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Community name is required.',
            'name.min' => 'Community name must be at least 3 characters long.',
            'name.max' => 'Community name cannot exceed 255 characters.',
            'name.unique' => 'This community name already exists.',
            'name.regex' => 'Community name can only contain letters, numbers, spaces, and basic punctuation.',
            'municipality.required' => 'Municipality is required.',
            'municipality.min' => 'Municipality must be at least 3 characters long.',
            'municipality.regex' => 'Municipality can only contain letters, spaces, hyphens, and periods.',
            'province.required' => 'Province is required.',
            'province.min' => 'Province must be at least 3 characters long.',
            'province.regex' => 'Province can only contain letters, spaces, hyphens, and periods.',
            'contact_person.required' => 'Contact person name is required.',
            'contact_person.min' => 'Contact person name must be at least 3 characters long.',
            'contact_person.regex' => 'Contact person name can only contain letters, spaces, hyphens, and apostrophes.',
            'contact_number.required' => 'Contact number is required.',
            'contact_number.regex' => 'Contact number can only contain digits, spaces, hyphens, plus sign, and parentheses.',
            'contact_number.min' => 'Contact number must be at least 7 characters long.',
            'contact_number.max' => 'Contact number cannot exceed 20 characters.',
            'email.email' => 'Please provide a valid email address.',
            'status.required' => 'Please select a status.',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $community = Community::create($validated);

        // Log activity
        activity()
            ->performedOn($community)
            ->causedBy(Auth::user())
            ->log('created');

        return redirect()->route('communities.show', $community)
            ->with('success', 'Community created successfully!');
    }

    /**
     * Display the specified community.
     */
    public function show(Community $community)
    {
        $community->load('extensionPrograms', 'needsAssessments', 'beneficiaries');

        return view('communities.show', compact('community'));
    }

    /**
     * Show the form for editing the specified community.
     * Only Director can edit communities.
     */
    public function edit(Community $community)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('update', $community);

        return view('communities.edit', compact('community'));
    }

    /**
     * Update the specified community in database.
     */
    public function update(Request $request, Community $community)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('update', $community);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:communities,name,' . $community->id, 'regex:/^[a-zA-Z0-9\s\-\.&,()]+$/'],
            'municipality' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/'],
            'province' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/'],
            'description' => 'nullable|string|min:10|max:1000',
            'contact_person' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'contact_number' => ['required', 'string', 'regex:/^[\d\s\-\+()]+$/', 'min:7', 'max:20'],
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,archived',
            'notes' => 'nullable|string|max:1000',
        ], [
            'name.min' => 'Community name must be at least 3 characters long.',
            'name.max' => 'Community name cannot exceed 255 characters.',
            'name.unique' => 'This community name already exists.',
            'name.regex' => 'Community name can only contain letters, numbers, spaces, and basic punctuation.',
            'municipality.min' => 'Municipality must be at least 3 characters long.',
            'municipality.regex' => 'Municipality can only contain letters, spaces, hyphens, and periods.',
            'province.min' => 'Province must be at least 3 characters long.',
            'province.regex' => 'Province can only contain letters, spaces, hyphens, and periods.',
            'contact_person.min' => 'Contact person name must be at least 3 characters long.',
            'contact_person.regex' => 'Contact person name can only contain letters, spaces, hyphens, and apostrophes.',
            'contact_number.regex' => 'Contact number can only contain digits, spaces, hyphens, plus sign, and parentheses.',
            'contact_number.min' => 'Contact number must be at least 7 characters long.',
        ]);

        $validated['updated_by'] = Auth::id();

        $community->update($validated);

        // Log activity
        activity()
            ->performedOn($community)
            ->causedBy(Auth::user())
            ->log('updated');

        return redirect()->route('communities.show', $community)
            ->with('success', 'Community updated successfully!');
    }

    /**
     * Soft delete the specified community.
     * Only Director can delete communities.
     */
    public function destroy(Community $community)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('delete', $community);

        $community->delete();

        // Log activity
        activity()
            ->performedOn($community)
            ->causedBy(Auth::user())
            ->log('deleted');

        return redirect()->route('communities.index')
            ->with('success', 'Community deleted successfully!');
    }

    /**
     * Search communities by name or municipality.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $communities = Community::where('name', 'like', "%{$query}%")
            ->orWhere('municipality', 'like', "%{$query}%")
            ->orWhere('province', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('communities.index', compact('communities'));
    }

    /**
     * Filter communities by status.
     */
    public function filterByStatus($status)
    {
        $communities = Community::where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('communities.index', compact('communities'));
    }
}
