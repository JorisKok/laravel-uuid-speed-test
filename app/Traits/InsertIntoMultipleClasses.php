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
     * @var array
     */
    private static $chainedClasses = [];

    /**
     * @param string $otherClass
     * @param string $relationColumn
     * @param array $attributes
     * @param bool $chain
     * @return self
     */
    public static function setInsertIntoOtherClass(string $otherClass, string $relationColumn, array $attributes, bool $chain = false): self
    {
        if ($chain) {
            end(self::$otherClasses); // Change internal pointer
            self::$chainedClasses[$otherClass] = [key(self::$otherClasses) => end(self::$otherClasses)];
            reset(self::$otherClasses); // Reset pointer
        }

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
     * @param string $class
     * @return int
     */
    private static function getLastInsertedId(string $class = null) : int
    {
        $table = $class ? (new $class())->getTable() : (new self())->getTable();

        $query = \DB::table($table);

        foreach (self::$values[self::$count - 1] as $key => $value) {
            $query->where($key, $value);
        }

        $key = $class ? (new $class())->getKeyName() : (new self())->getKeyName();

        return $query->orderBy($key, 'desc')->first()->$key;
    }

    /**
     * @param int $lastInsertedId
     */
    private static function insertIntoOtherClasses(int $lastInsertedId) : void
    {
        foreach (self::$otherClasses as $class => $otherClass) {
            self::$values = ! empty (self::$chainedClasses[$class]) ? self::getValuesIfChained($class, $otherClass) : self::getValues($lastInsertedId, $otherClass);

            (new $class())->insert(self::$values);
        }
    }

    /**
     * @param int $lastInsertedId
     * @param array $otherClass
     * @return array
     */
    private static function getValues(int $lastInsertedId, array $otherClass) : array
    {
        $result = [];
        foreach (range($lastInsertedId - self::$count + 1, $lastInsertedId) as $id) {
            $result[] = \array_merge($otherClass['attributes'], [
                $otherClass['relation_column'] => $id,
            ]);
        }

        return $result;
    }

    /**
     * @param string $class
     * @param array $otherClass
     * @return array
     */
    private static function getValuesIfChained(string $class, array $otherClass) : array
    {
        return self::getValues(self::getLastInsertedId(key(self::$chainedClasses[$class])), $otherClass);
    }
}
