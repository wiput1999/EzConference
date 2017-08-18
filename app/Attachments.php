<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    protected $table = "conference_attachment";

    protected $fillable = [
      'conference_id', 'owner', 'filename', 'location', 'updated_at', 'created_at'
    ];

    protected $guarded = [
        'id'
    ];
}
