<?php
namespace Reflex\Forum\Entities\Conversations;

use Reflex\Forum\Entities\BaseModel;

class Conversation extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'conversations';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'topic_id',
        'slug'
    ];

    /**
     * Return the user owner of this conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('forum.user.model'));
    }

    /**
     * Return replies on this conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany('Reflex\Forum\Entities\Replies\Reply')->latest();
    }

    /**
     * Return the category this conversation belongs in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo('Reflex\Forum\Entities\Categories\Category', 'topic_id');
    }

    /**
     * Get owner name attribute
     * @return string
     */
    public function getOwnerNameAttribute()
    {
        return $this->user->{config('forum.user.username')};
    }

    /**
     * Return true if the conversation has a correct answer.
     *
     * @return bool
     */
    public function hasCorrectAnswer()
    {
        foreach ($this->replies as $reply) {
            if ($reply->isCorrect()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return correct answer
     * @return mixed
     */
    public function correctAnswer()
    {
        foreach ($this->replies as $reply) {
            if ($reply->isCorrect()) {
                return $reply;
            }
        }
    }
}
