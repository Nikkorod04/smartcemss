<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Faculty extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department',
        'specialization',
        'position',
        'avatar',
        'phone',
        'address',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function extensionPrograms(): HasMany
    {
        return $this->hasMany(ExtensionProgram::class, 'program_lead_id');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_faculty');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(FacultyAvailability::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(ExtensionToken::class);
    }
}
