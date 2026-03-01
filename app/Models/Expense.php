<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;
    protected $casts = [
        'amount'     => 'decimal:2',
        'created_at' => 'datetime',
    ];
    protected $fillable = ['colocation_id', 'created_by', 'paid_by', 'category_id', 'title', 'amount', 'created_at'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function payers()
    {
        return $this->belongsToMany(User::class, 'payments')
            ->withPivot('is_paid', 'amount')
            ->withTimestamps();
    }
}
