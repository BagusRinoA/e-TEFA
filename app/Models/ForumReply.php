<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'author',
        'content',
        'upvotes',
    ];

    public function question()
    {
        return $this->belongsTo(ForumQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function upvotedBy()
    {
        return $this->hasMany(ForumUpvote::class, 'reply_id');
    }

    public function isUpvotedBy($userId)
    {
        return $this->upvotedBy()->where('user_id', $userId)->exists();
    }
}
