<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'event_id',
        'user_id',
        'reason',
        'description',
    ];
}
