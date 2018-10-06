<?php

namespace App\Models\Uuid;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserLog
 * @package App\Models\Uuid
 * @mixin \Eloquent
 */
class UserLog extends Model
{
    protected $table = 'uuid_user_logs';

    protected $fillable =  ['uuid', 'user_uuid', 'title'];
}
