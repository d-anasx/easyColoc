<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['colocation_id', 'name'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
