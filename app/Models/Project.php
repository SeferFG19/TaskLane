<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'created_by'];

    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');    
    }

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public function projectUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }
}

