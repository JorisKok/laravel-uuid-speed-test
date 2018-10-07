<?php

namespace App\Repositories\Uuid;

use App\Models\Uuid\User;
use App\Models\Uuid\UserLog;
use Ramsey\Uuid\Uuid;

class UserRepository
{
    /**
     * @param array $names
     * @throws \Exception
     */
    public function createManyIndividual(array $names) : void
    {
        foreach ($names as $name) {
            $userUuid = Uuid::uuid4()->toString();
            User::create([
                'uuid' => $userUuid,
                'name' => $name,
            ]);

            UserLog::create([
                'uuid' => Uuid::uuid4(),
                'user_uuid' => $userUuid,
                'title' => 'User created',
            ]);
        }
    }

    /**
     * @param array $names
     * @throws \Exception
     */
    public function createManySimultaneous(array $names) : void
    {
        $allUsers = [];
        $allUserLogs = [];
        foreach ($names as $name) {
            $userUuid = Uuid::uuid4();
            $allUsers[] = [
                'uuid' => $userUuid,
                'name' => $name,
            ];

            $allUserLogs[] = [
                'uuid' => Uuid::uuid4(),
                'user_uuid' => $userUuid,
                'title' => 'User created',
            ];
        }

        User::insert($allUsers);
        UserLog::insert($allUserLogs);
    }
}
