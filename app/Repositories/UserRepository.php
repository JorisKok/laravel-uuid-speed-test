<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserLog;
use Ramsey\Uuid\Uuid;

class UserRepository
{
    /**
     * @param array $names
     */
    public function createManyIndividual(array $names) : void
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

    /**
     * @param array $names
     */
    public function createManySimultaneous(array $names) : void
    {
        $allUsers = [];
        foreach ($names as $name) {
            $allUsers[] = ['name' => $name];
        }


        User::setInsertIntoOtherClass(UserLog::class, 'user_id', ['title' => 'User created'])::insertIntoMultipleClasses($allUsers);
    }
}

