<?php

namespace App\Models;

use App\Traits\InsertIntoMultipleClasses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class User
 * @package App\Models\Uuid
 * @mixin \Eloquent
 *
 * @property User $user
 * @property UserLog $userLog
 *
 * @method static $this last()
 */
class Mailbox extends Model
{
    protected $table = 'mailbox';

    protected $fillable = ['title'];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function userLog() : BelongsTo
    {
        return $this->belongsTo(UserLog::class);
    }
}
