<?php

namespace Test\Unit\User;

use App\Models\User;
use App\Models\UserLog;
use Tests\TestCase;
use App\Repositories\UserRepository;

class UserTest extends TestCase
{
    /**
     * @param array $names
     * @param int $amount
     * @dataProvider namesDataProvider
     */
    public function test_insert_many_users_individual_with_logs(array $names, $amount)
    {
        \DB::connection()->enableQueryLog();

        $this->assertEmpty(User::all());
        $this->assertEmpty(UserLog::all());

        (new UserRepository())->createManyIndividual($names);

        $this->assertEquals(\count($names), User::all()->count());
        $this->assertEquals(\count($names), UserLog::all()->count());

        $time = \array_reduce(\array_pluck(\DB::getQueryLog(), 'time'), function ($carry, $item) {
            return $carry + ($item * 100);
        });

        print_r(PHP_EOL . "Individual time elapsed for {$amount} names: " . (float) $time / 100 . PHP_EOL);
    }

    /**
     * @param array $names
     * @param int $amount
     * @dataProvider namesDataProvider
     */
    public function test_insert_many_users_simultaneous_with_logs(array $names, $amount)
    {
        \DB::connection()->enableQueryLog();

        $this->assertEmpty(User::all());
        $this->assertEmpty(UserLog::all());

        (new UserRepository())->createManySimultaneous($names);

        $this->assertEquals(\count($names), User::all()->count());
        $this->assertEquals(\count($names), UserLog::all()->count());

        $time = \array_reduce(\array_pluck(\DB::getQueryLog(), 'time'), function ($carry, $item) {
            return $carry + ($item * 100);
        });

        print_r(PHP_EOL . "Simultaneous time elapsed for {$amount} names: " . (float) $time / 100 . PHP_EOL);
    }
    public function namesDataProvider()
    {
        $hundred = [];
        foreach (range(0, 99) as $count) {
            $hundred[] = str_random(5);
        }

        return [
            [['anna', 'dave'], 2],
            [$hundred, 100],
        ];
    }

}
