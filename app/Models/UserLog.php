<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserLog
 * @package App\Models\Uuid
 * @mixin \Eloquent
 */
class UserLog extends Model
{
    protected $table = 'user_logs';

    protected $fillable =  ['user_id', 'title'];
}
