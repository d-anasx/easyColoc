<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Payment;

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
        'role_id',
        'is_banned',
        'reputation',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function colocations()
    {
        return $this->belongsToMany(Colocation::class, 'colocation_user')
            ->withPivot('role', 'joined_at', 'left_at');
    }

    public function expenses(){
        return $this->belongsToMany(Expense::class, 'payments')
            ->withPivot('is_paid')
            ->withTimestamps();
    }

    public function createdExpenses()
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    public function hasActiveColocation(): bool
    {
        return $this->colocations()->wherePivot('left_at', null)->exists();
    }


    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }

    public function isBanned(): bool
    {
        return $this->is_banned;
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
}
