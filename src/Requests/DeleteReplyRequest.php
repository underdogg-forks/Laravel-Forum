<?php

namespace Reflex\Forum\Requests;

use Illuminate\Support\Facades\Config;
use Reflex\Forum\Entities\Replies\ReplyRepo;

class DeleteReplyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Guard $auth
     * @param ReplyRepo $replyRepo
     * @return bool
     */
    public function authorize(ReplyRepo $replyRepo)
    {
        $reply_id = $this->route('reply_id');

        $reply = $replyRepo->findOrFail($reply_id);

        return  $reply->user_id == $this->auth->getActiveUser()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $databasePrefix = (Config::get('forum.database.prefix') ? Config::get('forum.database.prefix') . '_' : '');

        return [
            'reply_id' => 'required|exists:'.$databasePrefix.'replies,id'
        ];
    }
}
