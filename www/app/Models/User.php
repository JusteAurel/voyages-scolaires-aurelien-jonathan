<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// !!! LES TROIS IMPORTS INDISPENSABLES À RAJOUTER ICI !!!
use App\Models\Voyage;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Un enseignant peut créer plusieurs voyages.
     */
    public function voyages(): HasMany
    {
        return $this->hasMany(Voyage::class, 'user_id');
    }

    /**
     * Un élève peut avoir plusieurs fiches d'inscriptions.
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
