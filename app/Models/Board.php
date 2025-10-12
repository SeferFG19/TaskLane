<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    protected $fillable = ['name', 'project_id', 'created_by'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tlists(): HasMany
    {
        return $this->hasMany(Tlist::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
}
