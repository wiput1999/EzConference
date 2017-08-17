<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConferenceAttend extends Model
{
    protected $table = "conference_attend";

    protected $fillable = [
        'user_id', 'conference_id'
    ];
}
