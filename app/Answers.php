<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    protected $table = "conference_answer";

    protected $fillable = [
      'question_id', 'owner', 'answer', 'updated_at'
    ];

    protected $guarded = [
        'id', 'created_at'
    ];
}
