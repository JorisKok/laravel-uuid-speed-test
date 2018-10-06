<?php

namespace App\Models\Uuid;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models\Uuid
 * @mixin \Eloquent
 */
class User extends Model
{
    protected $table = 'uuid_users';

    protected $fillable = ['uuid', 'name'];
}
