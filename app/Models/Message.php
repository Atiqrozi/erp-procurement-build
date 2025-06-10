<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'message',
        'status',
        'target_division_id',
        'sender_division_id',
        'is_read',
    ];

    public function senderDivision()
    {
        return $this->belongsTo(Division::class, 'sender_division_id');
    }

    public function targetDivision()
    {
        return $this->belongsTo(Division::class, 'target_division_id');
    }

}
