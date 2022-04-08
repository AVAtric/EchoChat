<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Chat
 *
 * @property User user1
 * @property User user2
 * @property int id
 * @property int $user1_id
 * @property int $user2_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Message[] $messages
 * @property-read int|null $messages_count
 * @method static Builder|Chat chatExists(User $user1, User $user2)
 * @method static Builder|Chat newModelQuery()
 * @method static Builder|Chat newQuery()
 * @method static Builder|Chat query()
 * @method static Builder|Chat whereCreatedAt($value)
 * @method static Builder|Chat whereId($value)
 * @method static Builder|Chat whereUpdatedAt($value)
 * @method static Builder|Chat whereUser1Id($value)
 * @method static Builder|Chat whereUser2Id($value)
 * @mixin Eloquent
 */
class Chat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user1_id', 'user2_id'
    ];

    protected $with = ['messages'];

    public function user1()
    {
        return $this->hasOne(User::class, 'id', 'user1_id');
    }

    public function user2()
    {
        return $this->hasOne(User::class, 'id', 'user2_id');
    }

    public function isAllowed($user)
    {
        return $user->id == $this->user1->id || $user->id == $this->user2->id;
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Scope a query to only include joined chat of users.
     *
     * @param Builder $query
     * @param User $user1
     * @param User $user2
     * @return Builder
     */
    public function scopeChatExists($query, $user1, $user2){
        return $query->where(function (Builder $q) use ($user2, $user1) {
            $q->where('user1_id', $user1->id)->where('user2_id', $user2->id);
        })->orWhere(function (Builder $q) use ($user2, $user1) {
            $q->where('user2_id', $user1->id)->where('user1_id', $user2->id);
        });
    }
}
