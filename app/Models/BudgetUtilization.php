<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetUtilization extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'extension_program_id',
        'date_spent',
        'amount',
        'description',
        'attachment',
        'transaction_type',
        'budget_source',
        'source_description',
        'people_involved',
        'offices_involved',
        'approval_status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date_spent' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',
        'people_involved' => 'json',
        'offices_involved' => 'json',
    ];

    public function extensionProgram(): BelongsTo
    {
        return $this->belongsTo(ExtensionProgram::class);
    }
}
