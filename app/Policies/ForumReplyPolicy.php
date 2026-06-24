<?php

namespace App\Policies;

use App\Models\ForumReply;
use App\Models\User;

class ForumReplyPolicy
{
    public function delete(User $user, ForumReply $reply)
    {
        return $user->id === $reply->user_id || $user->isAdmin();
    }
}
