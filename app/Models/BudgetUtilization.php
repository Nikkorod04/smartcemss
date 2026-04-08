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
    ];

    protected $casts = [
        'date_spent' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function extensionProgram(): BelongsTo
    {
        return $this->belongsTo(ExtensionProgram::class);
    }
}
