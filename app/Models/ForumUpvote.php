<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumUpvote extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'reply_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(ForumQuestion::class);
    }

    public function reply()
    {
        return $this->belongsTo(ForumReply::class);
    }
}
