<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Beneficiary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'age',
        'gender',
        'email',
        'phone',
        'address',
        'barangay',
        'municipality',
        'province',
        'community_id',
        'program_ids',
        'beneficiary_category',
        'monthly_income',
        'occupation',
        'educational_attainment',
        'marital_status',
        'number_of_dependents',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'program_ids' => 'json',
        'date_of_birth' => 'date',
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function extensionPrograms(): BelongsToMany
    {
        return $this->belongsToMany(ExtensionProgram::class, 'beneficiary_extension_program');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
