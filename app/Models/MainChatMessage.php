<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\MainChatMessage
 *
 * @method static create(array $array)
 * @property int $id
 * @property int $user_id
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|MainChatMessage newModelQuery()
 * @method static Builder|MainChatMessage newQuery()
 * @method static Builder|MainChatMessage query()
 * @method static Builder|MainChatMessage whereBody($value)
 * @method static Builder|MainChatMessage whereCreatedAt($value)
 * @method static Builder|MainChatMessage whereId($value)
 * @method static Builder|MainChatMessage whereUpdatedAt($value)
 * @method static Builder|MainChatMessage whereUserId($value)
 * @mixin Eloquent
 */
class MainChatMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'body'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
