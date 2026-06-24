<?php

namespace App\Policies;

use App\Models\ForumQuestion;
use App\Models\User;

class ForumQuestionPolicy
{
    public function update(User $user, ForumQuestion $question)
    {
        return $user->id === $question->user_id;
    }

    public function delete(User $user, ForumQuestion $question)
    {
        return $user->id === $question->user_id || $user->isAdmin();
    }
}
