<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const PROFILE_PENDING  = 0;
    public const PROFILE_APPROVED = 1;
    public const PROFILE_REJECTED = 2;

    protected $fillable = [
        'name',
        'email',
        'password',
        'agency_name',
        'phone',
        'address',
        'birth_date',
        'id_number',
        'bank_name',
        'bank_account',
        'kota',
        'profile_status',
        'kode_agent',
        'profile_approved_by',
        'profile_approved_at',
        'profile_approval_note',
    ];

    protected $casts = [
        'birth_date'          => 'date',
        'profile_approved_at' => 'datetime',
        'profile_status'      => 'integer',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'profile_approved_by');
    }

    public function getProfileStatusLabelAttribute(): string
    {
        return [0 => 'Pending', 1 => 'Approved', 2 => 'Rejected'][$this->profile_status] ?? 'Unknown';
    }

    public function getProfileStatusColorAttribute(): string
    {
        return [0 => 'bg-amber-100 text-amber-700', 1 => 'bg-green-100 text-green-700', 2 => 'bg-rose-100 text-rose-700'][$this->profile_status] ?? 'bg-gray-100 text-gray-700';
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isRole(string $roleName): bool
    {
        return $this->role?->name === $roleName;
    }
}
