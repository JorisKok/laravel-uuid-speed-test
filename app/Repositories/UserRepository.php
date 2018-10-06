<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserLog;
use Ramsey\Uuid\Uuid;

class UserRepository
{
    public function createManyIndividual(array $names)
    {
        foreach ($names as $name) {
            $user = User::create([
                'name' => $name,
            ]);

            UserLog::create([
                'user_id' => $user->id,
                'title' => 'User created',
            ]);
        }
    }
}
