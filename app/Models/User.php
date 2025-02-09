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
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $casts = [
        'role' => 'string',
    ];

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

    // /**
    //  * Get the role of the user.
    //  *
    //  * @return string
    //  */
    // public function getRoleAttribute(): string
    // {
    //     return $this->role;
    // }

    // /**
    //  * Set the role of the user.
    //  *
    //  * @param string $role
    //  * @return void
    //  */
    // public function setRoleAttribute(string $role): void
    // {
    //     $this->role = $role;
    // }

    /**
     * Get the reports of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reports::class);
    }
}
