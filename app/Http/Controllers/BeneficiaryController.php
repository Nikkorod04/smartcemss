<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\ExtensionProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{
    /**
     * Display a listing of all beneficiaries.
     */
    public function index()
    {
        $beneficiaries = Beneficiary::with('community')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('beneficiaries.index', compact('beneficiaries'));
    }

    /**
     * Show the form for creating a new beneficiary.
     */
    public function create()
    {
        $communities = Community::all();
        $programs = ExtensionProgram::all();

        return view('beneficiaries.create', compact('communities', 'programs'));
    }

    /**
     * Store a newly created beneficiary in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'min:2', 'max:50'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'gender' => ['nullable', 'in:male,female,other'],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'community_id' => ['nullable', 'exists:communities,id'],
            'program_ids' => ['nullable', 'array'],
            'program_ids.*' => ['exists:extension_programs,id'],
            'beneficiary_category' => ['nullable', 'string', 'max:100'],
            'monthly_income' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'educational_attainment' => ['nullable', 'string', 'max:100'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'number_of_dependents' => ['nullable', 'integer', 'min:0', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.email' => 'Please provide a valid email address.',
            'age.integer' => 'Age must be a valid number.',
            'monthly_income.numeric' => 'Monthly income must be a valid number.',
            'community_id.exists' => 'Please select a valid community.',
            'program_ids.*.exists' => 'One or more selected programs do not exist.',
        ]);

        // Convert program_ids array to JSON if provided
        if (!empty($validated['program_ids'])) {
            $validated['program_ids'] = json_encode($validated['program_ids']);
        }

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $beneficiary = Beneficiary::create($validated);

        return redirect()->route('beneficiaries.show', $beneficiary)
            ->with('success', 'Beneficiary created successfully!');
    }

    /**
     * Display the specified beneficiary.
     */
    public function show(Beneficiary $beneficiary)
    {
        $beneficiary->load('community', 'extensionPrograms');

        return view('beneficiaries.show', compact('beneficiary'));
    }

    /**
     * Show the form for editing the specified beneficiary.
     */
    public function edit(Beneficiary $beneficiary)
    {
        $communities = Community::all();
        $programs = ExtensionProgram::all();
        $selectedPrograms = is_array($beneficiary->program_ids) 
            ? $beneficiary->program_ids 
            : ($beneficiary->program_ids ? json_decode($beneficiary->program_ids, true) : []);

        return view('beneficiaries.edit', compact('beneficiary', 'communities', 'programs', 'selectedPrograms'));
    }

    /**
     * Update the specified beneficiary in database.
     */
    public function update(Request $request, Beneficiary $beneficiary)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'min:2', 'max:50'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'gender' => ['nullable', 'in:male,female,other'],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'community_id' => ['nullable', 'exists:communities,id'],
            'program_ids' => ['nullable', 'array'],
            'program_ids.*' => ['exists:extension_programs,id'],
            'beneficiary_category' => ['nullable', 'string', 'max:100'],
            'monthly_income' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'educational_attainment' => ['nullable', 'string', 'max:100'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'number_of_dependents' => ['nullable', 'integer', 'min:0', 'max:20'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Convert program_ids array to JSON if provided
        if (!empty($validated['program_ids'])) {
            $validated['program_ids'] = json_encode($validated['program_ids']);
        } else {
            $validated['program_ids'] = null;
        }

        $validated['updated_by'] = Auth::id();
        $beneficiary->update($validated);

        return redirect()->route('beneficiaries.show', $beneficiary)
            ->with('success', 'Beneficiary updated successfully!');
    }

    /**
     * Remove the specified beneficiary from database.
     */
    public function destroy(Beneficiary $beneficiary)
    {
        $beneficiary->delete();

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary deleted successfully!');
    }

    /**
     * Search beneficiaries by name or email.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $beneficiaries = Beneficiary::with('community')
            ->where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('beneficiaries.index', compact('beneficiaries'));
    }

    /**
     * Filter beneficiaries by status.
     */
    public function filterByStatus($status)
    {
        $beneficiaries = Beneficiary::with('community')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('beneficiaries.index', compact('beneficiaries'));
    }
}
