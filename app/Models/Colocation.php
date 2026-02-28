<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colocation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'status'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'colocation_user')
            ->withPivot('role', 'joined_at', 'left_at')
            ->wherePivotNull('left_at');
    }


    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }


    public function owner()
    {
        return $this->members()->wherePivot('role', 'owner')->first();
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    
}
