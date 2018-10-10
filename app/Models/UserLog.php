<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class UserLog
 * @package App\Models\Uuid
 * @mixin \Eloquent
 *
 * @property User $user
 *
 */
class UserLog extends Model
{
    protected $table = 'user_logs';

    protected $fillable =  ['user_id', 'title'];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
