<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'phone', 'address', 'profile_photo'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

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
     * Relasi One to One dengan Member
     */
    public function member()
    {
        return $this->hasOne(Member::class, 'user_id', 'id');
    }

public function getProfilePhotoUrlAttribute(): string
{
    if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
        return Storage::url($this->profile_photo);
    }

    $initials = collect(explode(' ', trim($this->name)))
        ->filter()->take(2)
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->implode('');

    return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=4e73df&color=fff&size=128';
}
}

