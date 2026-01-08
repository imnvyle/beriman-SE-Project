<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['poster', 'title', 'event_date', 'venue', 'description', 'user_id'];
}
