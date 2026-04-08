<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'extension_program_id',
        'title',
        'description',
        'actual_start_date',
        'actual_end_date',
        'venue',
        'status',
        'notes',
    ];

    protected $casts = [
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
    ];

    public function extensionProgram(): BelongsTo
    {
        return $this->belongsTo(ExtensionProgram::class);
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'activity_faculty');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
