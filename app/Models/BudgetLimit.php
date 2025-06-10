<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetLimit extends Model
{
    protected $table = 'budget_limit';
    protected $primaryKey = 'id';
    public $incrementing = true;


    protected $fillable = [
        'limit',
        'status',
        'active',
    ];



    public $timestamps = false; // karena nama kolomnya bukan `updated_at`
}
