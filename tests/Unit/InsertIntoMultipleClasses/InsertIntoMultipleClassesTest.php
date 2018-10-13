<?php

namespace Test\Unit\InsertIntoMultipleClasses;

use App\Models\Mailbox;
use App\Models\User;
use App\Models\UserLog;
use Tests\TestCase;
use App\Repositories\UserRepository;

class InsertIntoMultipleClassesTest extends TestCase
{
    /**
     * @dataProvider namesDataProvider
     * @param array $names
     */
    public function test_can_insert_into_two_classes(array $names): void
    {
        $this->assertEmpty(User::all());
        $this->assertEmpty(UserLog::all());

        (new UserRepository())->createManyIndividual($names);

        $this->assertEquals(\count($names), User::all()->count());

        $userLogs = UserLog::all();
        $this->assertEquals(\count($names), $userLogs->count());
        foreach ($userLogs as $userLog) {
            $this->assertNotNull($userLog->user->id);
        }
    }

    /**
     * @param array $names
     *
     * @dataProvider namesDataProvider
     */
    public function test_can_insert_into_three_classes_from_base(array $names): void
    {
        $this->assertEmpty(User::all());
        $this->assertEmpty(UserLog::all());
        $this->assertEmpty(Mailbox::all());

        (new UserRepository())->createManyWithMailboxSimultaneousFromBase($names);

        $this->assertEquals(\count($names), User::all()->count());

        $userLogs = UserLog::all();
        $this->assertEquals(\count($names), $userLogs->count());
        foreach ($userLogs as $userLog) {
            $this->assertNotNull($userLog->user->id);
        }

        $mailbox = Mailbox::all();
        $this->assertEquals(\count($names), $mailbox->count());
        foreach ($mailbox as $mail) {
            $this->assertNull($mail->userLog);
            $this->assertNotNull($mail->user->id);
        }

    }

    /**
     * @param array $names
     * @dataProvider namesDataProvider
     */
    public function test_can_insert_into_three_classes_chaining(array $names): void
    {
        $this->assertEmpty(User::all());
        $this->assertEmpty(UserLog::all());
        $this->assertEmpty(Mailbox::all());

        (new UserRepository())->createManyWithMailboxSimultaneousChaining($names);

        $this->assertEquals(\count($names), User::all()->count());

        $userLogs = UserLog::all();
        $this->assertEquals(\count($names), $userLogs->count());
        foreach ($userLogs as $userLog) {
            $this->assertNotNull($userLog->user->id);
        }

        $mailbox = Mailbox::all();
        $this->assertEquals(\count($names), $mailbox->count());
        foreach ($mailbox as $mail) {
            $this->assertNull($mail->user);
            $this->assertNotNull($mail->userLog->id);
        }
    }

    public function namesDataProvider(): array
    {
        $hundred = [];
        foreach (range(0, 99) as $count) {
            $hundred[] = str_random(5);
        }

        return [
            [['anna', 'dave'], 2],
//            [$hundred, 100], // TODO undo and make the trait not static!! it remembers stuff that shouldnt be remembered ~!
        ];
    }

}
