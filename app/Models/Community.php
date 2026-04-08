<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Community extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'municipality',
        'province',
        'description',
        'contact_person',
        'contact_number',
        'email',
        'address',
        'status',
        'notes',
    ];

    public function needsAssessments(): HasMany
    {
        return $this->hasMany(NeedsAssessment::class);
    }

    public function extensionPrograms(): BelongsToMany
    {
        return $this->belongsToMany(ExtensionProgram::class, 'community_extension_program');
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }
}
