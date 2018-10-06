<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models\Uuid
 * @mixin \Eloquent
 */
class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['name'];
}
