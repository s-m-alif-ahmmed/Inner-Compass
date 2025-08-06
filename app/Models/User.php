<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'terms',
        'provider_id',
        'email_verified_at',
        'remember_token',
        'reset_password_token',
        'reset_password_token_exp',
        'accept_push_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'terms',
        'role',
        'reset_password_token',
        'reset_password_token_exp'
    ];

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
            'accept_push_notifications' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (Auth::user()->role === 'Admin') {
            return true;
        }

        return false;
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function firebaseToken()
    {
        return $this->hasOne(FirebaseToken::class);
    }

}
