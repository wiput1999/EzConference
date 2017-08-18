<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'conference_question';

    protected $fillable = [
        'conference_id', 'owner', 'question', 'description', 'updated_at'
    ];

    protected $guarded = [
        'created_at', 'id'
    ];
}
