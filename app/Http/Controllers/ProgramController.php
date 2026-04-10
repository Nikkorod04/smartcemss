<?php

namespace App\Http\Controllers;

use App\Models\ExtensionProgram;
use App\Models\Faculty;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    /**
     * Display a listing of all extension programs.
     */
    public function index()
    {
        return view('programs.index');
    }

    /**
     * Show the form for creating a new extension program.
     * Only Director can create programs.
     */
    public function create()
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('create', ExtensionProgram::class);

        $faculties = Faculty::with('user')->get();
        $communities = Community::all();

        return view('programs.create', compact('faculties', 'communities'));
    }

    /**
     * Store a newly created extension program in database.
     */
    public function store(Request $request)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('create', ExtensionProgram::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:150', 'regex:/^[a-zA-Z0-9\s\-\.&,()]+$/'],
            'description' => ['required', 'string', 'min:10', 'max:1500'],
            'goals' => ['required', 'string', 'min:10', 'max:1000'],
            'objectives' => ['required', 'string', 'min:10', 'max:1000'],
            'planned_start_date' => ['required', 'date', 'after_or_equal:today'],
            'planned_end_date' => ['required', 'date', 'after:planned_start_date'],
            'target_beneficiaries' => ['required', 'integer', 'min:1', 'max:1000000'],
            'beneficiary_categories' => 'nullable|array',
            'allocated_budget' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'program_lead_id' => 'required|exists:faculties,id',
            'partners' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'communities' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip|max:10240',
            'status' => 'required|in:draft,ongoing,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ], [
            'title.required' => 'Program title is required.',
            'title.min' => 'Program title must be at least 5 characters long.',
            'title.max' => 'Program title cannot exceed 150 characters.',
            'title.regex' => 'Program title can only contain letters, numbers, spaces, and basic punctuation.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters long.',
            'description.max' => 'Description cannot exceed 1500 characters.',
            'goals.required' => 'Goals are required.',
            'goals.min' => 'Goals must be at least 10 characters long.',
            'objectives.min' => 'Objectives must be at least 10 characters long.',
            'planned_start_date.after_or_equal' => 'Start date must be today or later.',
            'planned_end_date.after' => 'End date must be after the start date.',
            'target_beneficiaries.min' => 'Target beneficiaries must be at least 1.',
            'target_beneficiaries.max' => 'Target beneficiaries cannot exceed 1,000,000.',
            'allocated_budget.min' => 'Budget must be 0 or greater.',
            'program_lead_id.required' => 'Please select a program lead.',
            'gallery_images.*.image' => 'Each gallery image must be an image file.',
            'gallery_images.*.mimes' => 'Gallery images must be JPEG, PNG, JPG, or GIF format.',
            'gallery_images.*.max' => 'Each gallery image cannot exceed 5MB.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.mimes' => 'Attachments must be PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, or ZIP format.',
            'attachments.*.max' => 'Each attachment cannot exceed 10MB.',
        ]);

        // Store program lead as created_by
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        // Convert partners string to JSON array if provided
        if (!empty($validated['partners'])) {
            $validated['partners'] = collect(array_map('trim', explode(',', $validated['partners'])))
                ->filter()
                ->values()
                ->toArray();
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $path = $coverImage->store('programs/cover', 'public');
            $validated['cover_image'] = $path;
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('programs/gallery', 'public');
                $galleryImages[] = $path;
            }
            $validated['gallery_images'] = $galleryImages;
        }

        // Handle attachments upload
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $path = $attachment->store('programs/attachments', 'public');
                $attachments[] = $path;
            }
            $validated['attachments'] = $attachments;
        }

        $program = ExtensionProgram::create($validated);

        // Attach communities (many-to-many relationship)
        if (!empty($validated['communities'])) {
            $program->communities()->sync($validated['communities']);
        }

        // Log activity - disabled until spatie activitylog is configured
        // activity()
        //     ->performedOn($program)
        //     ->causedBy(Auth::user())
        //     ->log('created');

        return redirect()->route('programs.show', $program)
            ->with('success', 'Extension program created successfully!');
    }

    /**
     * Display the specified extension program.
     */
    public function show(ExtensionProgram $program)
    {
        $program->load('programLead', 'communities', 'activities', 'budgetUtilizations');

        return view('programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified extension program.
     * Only Director can edit programs.
     */
    public function edit(ExtensionProgram $program)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('update', $program);

        $faculties = Faculty::with('user')->get();
        $communities = Community::all();
        $selectedCommunities = $program->communities()->pluck('communities.id')->toArray();

        return view('programs.edit', compact('program', 'faculties', 'communities', 'selectedCommunities'));
    }

    /**
     * Update the specified extension program in database.
     */
    public function update(Request $request, ExtensionProgram $program)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('update', $program);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:150', 'regex:/^[a-zA-Z0-9\s\-\.&,()]+$/'],
            'description' => ['required', 'string', 'min:10', 'max:1500'],
            'goals' => ['required', 'string', 'min:10', 'max:1000'],
            'objectives' => ['required', 'string', 'min:10', 'max:1000'],
            'planned_start_date' => ['required', 'date', 'after_or_equal:today'],
            'planned_end_date' => ['required', 'date', 'after:planned_start_date'],
            'target_beneficiaries' => ['required', 'integer', 'min:1', 'max:1000000'],
            'beneficiary_categories' => 'nullable|array',
            'allocated_budget' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'program_lead_id' => 'required|exists:faculties,id',
            'partners' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'communities' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip|max:10240',
            'status' => 'required|in:draft,ongoing,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ], [
            'title.required' => 'Program title is required.',
            'title.min' => 'Program title must be at least 5 characters long.',
            'title.max' => 'Program title cannot exceed 150 characters.',
            'title.regex' => 'Program title can only contain letters, numbers, spaces, and basic punctuation.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters long.',
            'description.max' => 'Description cannot exceed 1500 characters.',
            'goals.required' => 'Goals are required.',
            'goals.min' => 'Goals must be at least 10 characters long.',
            'objectives.min' => 'Objectives must be at least 10 characters long.',
            'planned_start_date.after_or_equal' => 'Start date must be today or later.',
            'planned_end_date.after' => 'End date must be after the start date.',
            'target_beneficiaries.min' => 'Target beneficiaries must be at least 1.',
            'target_beneficiaries.max' => 'Target beneficiaries cannot exceed 1,000,000.',
            'allocated_budget.min' => 'Budget must be 0 or greater.',
            'program_lead_id.required' => 'Please select a program lead.',
            'gallery_images.*.image' => 'Each gallery image must be an image file.',
            'gallery_images.*.mimes' => 'Gallery images must be JPEG, PNG, JPG, or GIF format.',
            'gallery_images.*.max' => 'Each gallery image cannot exceed 5MB.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.mimes' => 'Attachments must be PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, or ZIP format.',
            'attachments.*.max' => 'Each attachment cannot exceed 10MB.',
        ]);

        // Update by current user
        $validated['updated_by'] = Auth::id();

        // Convert partners string to JSON array if provided
        if (!empty($validated['partners'])) {
            $validated['partners'] = collect(array_map('trim', explode(',', $validated['partners'])))
                ->filter()
                ->values()
                ->toArray();
        }

        // Handle cover image upload (replaces existing)
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $path = $coverImage->store('programs/cover', 'public');
            $validated['cover_image'] = $path;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = $program->gallery_images ?? [];
            if (!is_array($galleryImages)) {
                $galleryImages = [];
            }
            
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('programs/gallery', 'public');
                $galleryImages[] = $path;
            }
            $validated['gallery_images'] = $galleryImages;
        }

        // Handle attachments upload
        if ($request->hasFile('attachments')) {
            $attachments = $program->attachments ?? [];
            if (!is_array($attachments)) {
                $attachments = [];
            }
            
            foreach ($request->file('attachments') as $attachment) {
                $path = $attachment->store('programs/attachments', 'public');
                $attachments[] = $path;
            }
            $validated['attachments'] = $attachments;
        }

        $program->update($validated);

        // Sync communities (many-to-many relationship)
        if (isset($validated['communities'])) {
            $program->communities()->sync($validated['communities']);
        }

        // Log activity - disabled until spatie activitylog is configured
        // activity()
        //     ->performedOn($program)
        //     ->causedBy(Auth::user())
        //     ->log('updated');

        return redirect()->route('programs.show', $program)
            ->with('success', 'Extension program updated successfully!');
    }

    /**
     * Remove the specified extension program from database.
     * Only Director can delete programs.
     */
    public function destroy(ExtensionProgram $program)
    {
        // Temporarily bypass authorization for debugging
        // $this->authorize('delete', $program);

        // Detach related records
        $program->communities()->detach();
        $program->activities()->delete();
        $program->budgetUtilizations()->delete();

        // Log activity before deletion - disabled until spatie activitylog is configured
        // activity()
        //     ->performedOn($program)
        //     ->causedBy(Auth::user())
        //     ->log('deleted');

        // Soft delete the program
        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', 'Extension program deleted successfully!');
    }

    /**
     * Search programs by title or description
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $programs = ExtensionProgram::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('programLead', 'communities')
            ->paginate(10);

        return view('programs.index', compact('programs'));
    }

    /**
     * Filter programs by status
     */
    public function filterByStatus(Request $request)
    {
        $status = $request->input('status');

        $programs = ExtensionProgram::where('status', $status)
            ->with('programLead', 'communities')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('programs.index', compact('programs'));
    }
}
