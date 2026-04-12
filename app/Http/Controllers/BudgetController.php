<?php

namespace App\Http\Controllers;

use App\Models\ExtensionProgram;
use App\Models\BudgetUtilization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BudgetController extends Controller
{
    /**
     * Display budget transactions for a program
     */
    public function index(ExtensionProgram $program)
    {
        // Authorization check
        if (auth()->user()->role !== 'director' && auth()->user()->role !== 'secretary') {
            abort(403, 'Unauthorized');
        }

        $program->load('budgetUtilizations');
        
        // Calculate budget summary
        $allocatedBudget = (float) $program->allocated_budget;
        $totalSpent = (float) $program->budgetUtilizations()
            ->where('transaction_type', 'expense')
            ->sum('amount');
        
        $totalAdjustments = (float) $program->budgetUtilizations()
            ->where('transaction_type', 'adjustment')
            ->sum('amount');
        
        $netBudget = $allocatedBudget + $totalAdjustments;
        $remainingBudget = $netBudget - $totalSpent;
        $percentageUsed = $netBudget > 0 ? round(($totalSpent / $netBudget) * 100, 2) : 0;

        $budgetData = [
            'allocated' => $allocatedBudget,
            'adjustments' => $totalAdjustments,
            'net' => $netBudget,
            'spent' => $totalSpent,
            'remaining' => $remainingBudget,
            'percentageUsed' => $percentageUsed,
        ];

        return view('budgets.index', compact('program', 'budgetData'));
    }

    /**
     * Show form to create new budget transaction
     */
    public function create(ExtensionProgram $program)
    {
        if (auth()->user()->role !== 'director' && auth()->user()->role !== 'secretary') {
            abort(403, 'Unauthorized');
        }

        $activities = $program->activities()->get();

        return view('budgets.create', compact('program', 'activities'));
    }

    /**
     * Store a new budget transaction
     */
    public function store(Request $request, ExtensionProgram $program)
    {
        if (auth()->user()->role !== 'director' && auth()->user()->role !== 'secretary') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'date_spent' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'transaction_type' => 'required|in:expense,adjustment',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // Handle attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('budget-attachments/' . $program->id, 'public');
        }

        // Create budget transaction
        $budgetUtilization = BudgetUtilization::create([
            'extension_program_id' => $program->id,
            'activity_id' => $validated['activity_id'],
            'date_spent' => $validated['date_spent'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transaction_type' => $validated['transaction_type'],
            'attachment' => $attachmentPath,
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($budgetUtilization)
            ->event('created')
            ->log('Budget ' . $validated['transaction_type'] . ' recorded for program: ' . $program->title . 
                  '. Amount: ₱' . number_format($validated['amount'], 2));

        return redirect()->route('programs.budgets.index', $program)
            ->with('success', 'Budget transaction recorded successfully.');
    }

    /**
     * Show form to edit budget transaction
     */
    public function edit(ExtensionProgram $program, BudgetUtilization $budgetUtilization)
    {
        if (auth()->user()->role !== 'director' && auth()->user()->role !== 'secretary') {
            abort(403, 'Unauthorized');
        }

        // Verify budget belongs to program
        if ($budgetUtilization->extension_program_id !== $program->id) {
            abort(404);
        }

        return view('budgets.edit', compact('program', 'budgetUtilization'));
    }

    /**
     * Update a budget transaction
     */
    public function update(Request $request, ExtensionProgram $program, BudgetUtilization $budgetUtilization)
    {
        if (auth()->user()->role !== 'director' && auth()->user()->role !== 'secretary') {
            abort(403, 'Unauthorized');
        }

        // Verify budget belongs to program
        if ($budgetUtilization->extension_program_id !== $program->id) {
            abort(404);
        }

        $validated = $request->validate([
            'date_spent' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'transaction_type' => 'required|in:expense,adjustment',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // Handle new attachment
        $attachmentPath = $budgetUtilization->attachment;
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($budgetUtilization->attachment) {
                Storage::disk('public')->delete($budgetUtilization->attachment);
            }
            $attachmentPath = $request->file('attachment')->store('budget-attachments/' . $program->id, 'public');
        }

        // Update budget transaction
        $budgetUtilization->update([
            'date_spent' => $validated['date_spent'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'transaction_type' => $validated['transaction_type'],
            'attachment' => $attachmentPath,
        ]);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($budgetUtilization)
            ->event('updated')
            ->log('Budget transaction updated for program: ' . $program->title . 
                  '. Amount: ₱' . number_format($validated['amount'], 2));

        return redirect()->route('programs.budgets.index', $program)
            ->with('success', 'Budget transaction updated successfully.');
    }

    /**
     * Delete a budget transaction
     */
    public function destroy(ExtensionProgram $program, BudgetUtilization $budgetUtilization)
    {
        if (auth()->user()->role !== 'director' && auth()->user()->role !== 'secretary') {
            abort(403, 'Unauthorized');
        }

        // Verify budget belongs to program
        if ($budgetUtilization->extension_program_id !== $program->id) {
            abort(404);
        }

        // Delete attachment if exists
        if ($budgetUtilization->attachment) {
            Storage::disk('public')->delete($budgetUtilization->attachment);
        }

        // Store info for activity log before deleting
        $transactionType = $budgetUtilization->transaction_type;
        $amount = $budgetUtilization->amount;

        $budgetUtilization->delete();

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($budgetUtilization)
            ->event('deleted')
            ->log('Budget ' . $transactionType . ' deleted from program: ' . $program->title . 
                  '. Amount: ₱' . number_format($amount, 2));

        return redirect()->route('programs.budgets.index', $program)
            ->with('success', 'Budget transaction deleted successfully.');
    }

    /**
     * Download budget attachment
     */
    public function downloadAttachment(ExtensionProgram $program, BudgetUtilization $budgetUtilization)
    {
        if (auth()->user()->role !== 'director' && auth()->user()->role !== 'secretary') {
            abort(403, 'Unauthorized');
        }

        if ($budgetUtilization->extension_program_id !== $program->id || !$budgetUtilization->attachment) {
            abort(404);
        }

        return Storage::disk('public')->download($budgetUtilization->attachment);
    }
}
