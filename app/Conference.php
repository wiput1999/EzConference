<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $table = "conference";

    protected $fillable = [
        'name', 'description', 'capacity', 'open', 'owner', 'thumbnail', 'remember_token'
    ];

    protected $guarded = [
        'id', 'created_at'
    ];

}
