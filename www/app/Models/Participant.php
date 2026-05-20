<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Voyage;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    // Cette ligne est OBLIGATOIRE pour valider le Bloc D du sujet !
    protected $fillable = ['voyage_id', 'user_id', 'autorisation_parent'];

    public function voyage(): BelongsTo
    {
        return $this->belongsTo(Voyage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
