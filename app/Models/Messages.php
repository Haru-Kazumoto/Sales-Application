<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Messages extends Model
{

    protected $table = 'messages';

    protected $fillable = [
        "template_id",
        "recipient",
        "content",
        "sent_at",
        "sent_by"
    ];

}