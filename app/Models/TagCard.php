<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardTag extends Model
{
    protected $fillable = ['card_id', 'tag_id'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
