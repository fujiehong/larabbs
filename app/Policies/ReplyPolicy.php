<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;
        return true;
    }
    //应当是"回复的作者"或者"回复话题的作者"
    public function destroy(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id || $user->id==$reply->topic->user_id;
    }
}
