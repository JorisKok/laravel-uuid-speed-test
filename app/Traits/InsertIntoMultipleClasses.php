<?php

namespace App\Traits;

/**
 * Trait InsertOverMultipleTables
 * @package App\Traits
 */
trait InsertIntoMultipleClasses
{
    /**
     * @var int
     */
    private $lastInsertedId;

    /**
     * @var int
     */
    private $lastInsertedIdFromChain;

    /**
     * @var array
     */
    private $values = [];

    /**
     * @var array
     */
    private $chainedValues = [];

    /**
     * @var int
     */
    private $count = 0;

    /**
     * @param array $values
     * @return $this
     */
    public static function insertMany(array $values) : self
    {
        $model = new self();

        $model->count = \count($values);

        $model->values = $values;

        $model->insert($values);

        $model->lastInsertedId = $model->getLastInsertedId();

        return $model;
    }

    public function insertChain(string $class, array $attributes, $foreignKey = null, $chain = null) : self
    {
        $lastInsertedId = $chain ? $this->lastInsertedIdFromChain : $this->lastInsertedId;

        $this->chainedValues = $this->getValues($lastInsertedId, $foreignKey, $attributes);

        (new $class())->insert($this->chainedValues);

        $this->lastInsertedIdFromChain = $this->getLastInsertedId($class);

        return $this;
    }


    private function getValues(int $lastInsertedId, string $foreignKey, array $attributes) : array
    {
        $result = [];
        foreach (range($lastInsertedId - $this->count + 1, $lastInsertedId) as $id) {
            $result[] = \array_merge($attributes, [
                $foreignKey => $id,
            ]);
        }

        return $result;
    }

    private function getLastInsertedId(string $class = null)
    {
        $table = $class ? (new $class())->getTable() : (new self())->getTable();

        $values = $class ? $this->chainedValues : $this->values;

        $query = \DB::table($table);

        foreach ($values[$this->count - 1] as $key => $value) {
            $query->where($key, $value);
        }

        $key = $class ? (new $class())->getKeyName() : (new self())->getKeyName();

        return $query->orderBy($key, 'desc')->first()->$key;
    }
}
