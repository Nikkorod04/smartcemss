<?php

namespace App\Livewire;

use App\Models\BudgetUtilization;
use App\Models\ExtensionProgram;
use Livewire\Component;
use Livewire\Attributes\On;

class AddExpenseModal extends Component
{
    public $programId;
    public $program;
    
    public $date_spent = '';
    public $amount = '';
    public $description = '';
    public $transaction_type = 'expense';
    public $attachment = '';
    
    // Budget Source Fields
    public $budget_source = '';
    public $source_description = '';
    public $approval_status = 'pending';
    
    // People Involved
    public $people_involved = [];
    public $personName = '';
    public $personPosition = '';
    public $personOffice = '';
    
    // Offices Involved
    public $offices_involved = [];
    public $officeName = '';
    public $officeContact = '';
    
    // Alert State
    public $showAlert = false;
    public $alertType = 'success';
    public $alertMessage = '';
    public $alertDetails = '';

    public function mount($programId)
    {
        $this->programId = $programId;
        $this->program = ExtensionProgram::find($programId);
        $this->date_spent = now()->format('Y-m-d');
    }

    public function addPerson()
    {
        if ($this->personName && $this->personPosition) {
            $this->people_involved[] = [
                'name' => $this->personName,
                'position' => $this->personPosition,
                'office' => $this->personOffice,
            ];
            
            $this->personName = '';
            $this->personPosition = '';
            $this->personOffice = '';
        }
    }

    public function removePerson($index)
    {
        unset($this->people_involved[$index]);
        $this->people_involved = array_values($this->people_involved);
    }

    public function addOffice()
    {
        if ($this->officeName) {
            $this->offices_involved[] = [
                'name' => $this->officeName,
                'contact' => $this->officeContact,
            ];
            
            $this->officeName = '';
            $this->officeContact = '';
        }
    }

    public function removeOffice($index)
    {
        unset($this->offices_involved[$index]);
        $this->offices_involved = array_values($this->offices_involved);
    }

    public function saveExpense()
    {
        $this->validate([
            'date_spent' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'budget_source' => 'required|string|max:255',
        ]);

        try {
            BudgetUtilization::create([
                'extension_program_id' => $this->programId,
                'date_spent' => $this->date_spent,
                'amount' => $this->amount,
                'description' => $this->description,
                'transaction_type' => $this->transaction_type,
                'budget_source' => $this->budget_source,
                'source_description' => $this->source_description,
                'people_involved' => count($this->people_involved) > 0 ? $this->people_involved : null,
                'offices_involved' => count($this->offices_involved) > 0 ? $this->offices_involved : null,
                'approval_status' => $this->approval_status,
            ]);

            // Show success alert
            $this->alertType = 'success';
            $this->alertMessage = 'Expense recorded successfully!';
            $this->alertDetails = '₱' . number_format($this->amount, 2) . ' • ' . $this->date_spent;
            $this->showAlert = true;
            
            // Fire event for parent component to refresh
            $this->dispatch('expense-added');
            
        } catch (\Exception $e) {
            $this->alertType = 'error';
            $this->alertMessage = 'Failed to record expense: ' . $e->getMessage();
            $this->alertDetails = '';
            $this->showAlert = true;
        }
    }

    public function closeAlert()
    {
        $this->showAlert = false;
        $this->resetForm();
        $this->dispatch('close-modal');
    }

    public function resetForm()
    {
        $this->reset(['date_spent', 'amount', 'description', 'transaction_type', 'budget_source', 
                     'source_description', 'approval_status', 'people_involved', 'offices_involved']);
        $this->date_spent = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.add-expense-modal');
    }
}
