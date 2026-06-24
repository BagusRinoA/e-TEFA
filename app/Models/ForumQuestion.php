<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author',
        'title',
        'category',
        'content',
        'tags',
        'upvotes',
        'image',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function upvotedBy()
    {
        return $this->hasMany(ForumUpvote::class, 'question_id');
    }

    public function isUpvotedBy($userId)
    {
        return $this->upvotedBy()->where('user_id', $userId)->exists();
    }
}
