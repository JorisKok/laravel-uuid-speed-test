<?php

namespace App\Traits;

use App\Models\User;

/**
 * Trait InsertOverMultipleTables
 * @package App\Traits
 */
trait InsertIntoMultipleClasses
{
    /**
     * @var int
     */
    private static $count = 0;

    /**
     * @var array
     */
    private static $values = [];

    /**
     * @var array
     */
    private static $otherClasses = [];

    /**
     * @param string $otherClass
     * @param string $relationColumn
     * @param array $attributes
     * @return self
     */
    public static function setInsertIntoOtherClass(string $otherClass, string $relationColumn, array $attributes): self
    {
        self::$otherClasses[$otherClass] = [
            'relation_column' => $relationColumn,
            'attributes' => $attributes,
        ];

        return new static();
    }

    /**
     * @param array $values
     */
    public static function insertIntoMultipleClasses(array $values): void
    {
        self::$count = \count($values);

        self::$values = $values;

        self::insert(self::$values);

        self::insertIntoOtherClasses(self::getLastInsertedId());
    }

    /**
     * @return int
     */
    private static function getLastInsertedId() : int
    {
        $query = \DB::table((new self())->getTable());

        foreach (self::$values[self::$count - 1] as $key => $value) {
            $query->where($key, $value);
        }

        $key = (new self())->getKeyName();

        return $query->orderBy($key, 'desc')->first()->$key;
    }

    /**
     * @param int $lastInsertedId
     */
    private static function insertIntoOtherClasses(int $lastInsertedId) : void
    {
        foreach (self::$otherClasses as $class => $otherClass) {
            $values = [];
            foreach (range($lastInsertedId - self::$count + 1, $lastInsertedId) as $id) {
                $values[] = \array_merge($otherClass['attributes'], [
                    $otherClass['relation_column'] => $id,
                ]);
            }

            (new $class())->insert($values);
        }
    }
}
