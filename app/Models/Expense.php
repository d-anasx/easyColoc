<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{   
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['colocation_id', 'created_by', 'paid_by', 'category_id', 'title', 'amount', 'created_at'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function payers(){
        return $this->belongsToMany(User::class, 'payments')
            ->withPivot('is_paid')
            ->withTimestamps();
    }
}
