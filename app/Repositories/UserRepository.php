<?php

namespace App\Repositories;

use App\Models\Mailbox;
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


        User::insertMany($allUsers)->insertChain(UserLog::class, ['title' => 'User_created'], 'user_id'); // TODO remove user_id
    }

    public function createManyWithMailboxSimultaneousFromBase(array $names) : void
    {
        $allUsers = [];
        foreach ($names as $name) {
            $allUsers[] = ['name' => $name];
        }

        User::insertMany($allUsers)
            ->insertChain(UserLog::class, ['title' => 'User_created'], 'user_id') // TODO remove user_id
            ->insertChain(Mailbox::class, ['title' => 'Welcome to our site'], 'user_id');
    }

    public function createManyWithMailboxSimultaneousChaining(array $names) : void
    {
        $allUsers = [];
        foreach ($names as $name) {
            $allUsers[] = ['name' => $name];
        }

        User::insertMany($allUsers)
            ->insertChain(UserLog::class, ['title' => 'User created'], 'user_id')
            ->insertChain(Mailbox::class, ['title' => 'Welcome to our site'], 'user_log_id', true);
    }
}

