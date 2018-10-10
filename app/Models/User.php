<?php

namespace App\Models;

use App\Traits\InsertIntoMultipleClasses;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models\Uuid
 * @mixin \Eloquent
 *
 * @property int $id
 *
 * @method static $this last()
 */
class User extends Model
{
    use InsertIntoMultipleClasses;

    protected $table = 'users';

    protected $fillable = ['name'];
}
