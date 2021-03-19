<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Services\Connection;

/**
 * [class Model]
 */
class Model
{
    protected $table;
    protected $class;

    public function __construct()
    {
    }

    /**
     * @return an instance of PDO
     */
    public function getDb()
    {
        return (new Connection)->getDb();
    }

    /**
     * Get an item from db
     * @param string $class
     * @param integer $id
     * @param boolean $instance
     * @return mixed
     */
    public function find(int $id, string $class = "")
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() === 1) {
            return $class !== "" ? new $class($stmt->fetch()) : $stmt->fetch();
        }
        return null;
    }

    /**
     * findAll
     * @param string $class
     * @param boolean $order_by
     * @return mixed
     */
    public function findAll(string $class = "", $order_by = false)
    {
        $order = "";
        if (is_bool($order_by) && $order_by === true) {
            $order = "ORDER BY created_at DESC";
        } elseif (is_bool($order_by) && $order_by === false) {
            $order = "";
        } elseif (is_string($order_by)) {
            $explode = explode('.', $order_by);
            $order = count($explode) === 1 ? "ORDER BY {$explode[0]}" : "ORDER BY {$explode[0]} {$explode[1]}";
        }
        $sql = "SELECT * FROM {$this->table} {$order}";
        $stmt = $this->getDb()->query($sql);
        $data = $stmt->fetchAll();
        if ($class !== "") {
            return $this->getInstances($data, $class);
        }
        return $data;
    }

    /**
     * insert
     * @param array $data
     * @param boolean $last_insert
     * @return mixed
     */
    public function insert(array $data, bool $last_insert = false)
    {
        $sql = "INSERT INTO {$this->table} SET {$this->getSetTables($data)}";
        $stmt = $this->getDb()->prepare($sql);
        if ($stmt->execute(Helpers::transformKeys($data))) {
            return $last_insert === true ? $this->find($this->getDb()->lastInsertId(), $this->class) : true;
        }
        return false;
    }

    /**
     * update
     * @param array $set
     * @param array $where
     * @param boolean $object
     * @return mixed
     */
    public function update(array $set = [], array $where = [], bool $object = false)
    {
        $sql = "UPDATE {$this->table} SET {$this->getSetTables($set)} ";
        if (count($where) > 0) {
            $sql .= "WHERE {$this->getWhereTables($where)}";
        }

        $stmt = $this->getDb()->prepare($sql);
        $data = array_merge($set, $where);
        if ($stmt->execute(Helpers::transformKeys($data))) {
            return $object === true ? $this->find((int) $where['id'], $this->class) : true;
        }
        return false;
    }

    /**
     * delete
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        $stmt = $this->getDb()->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([":id" => $id]) ? true : false;
    }

    /**
     * getSetTables
     * @param array $data
     * @return string
     */
    public function getSetTables(array $data): string
    {
        $set = [];
        foreach ($data as $k => $v) {
            $set[] = "{$k} = :{$k}";
        }
        return Helpers::putBetween(' , ', $set);
    }

    /**
     * getWhereTables
     * @param array $data
     * @return string
     */
    public function getWhereTables(array $data): string
    {
        $where = [];
        foreach ($data as $k => $v) {
            $where[] = "{$k} = :{$k}";
        }
        return Helpers::putBetween(' AND ', $where);
    }

    /**
     * getInstances
     * @param array $data
     * @param string $class
     * @return array
     */
    public function getInstances(array $data, string $class = ""): array
    {
        if ($class === "") {
            $class = $this->class;
        }

        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = new $class($value);
        }
        return $result;
    }

    /**
     * pending
     * @return array
     */
    public function pending(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'pending' ORDER BY created_at DESC";
        $data = $this->getDb()->query($sql)->fetchAll();
        return $this->getInstances($data);
    }

    /**
     * pending
     * @return array
     */
    public function completed(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'completed'";
        $data = $this->getDb()->query($sql)->fetchAll();
        return $this->getInstances($data);
    }

    /**
     * @param array $criteria
     * @param bool $instance
     *
     * @return mixed
     */
    public function findBy(array $criteria = [], bool $instance = false)
    {
        $class = $this->class;
        $sql = "SELECT * FROM {$this->table} WHERE {$this->getWhereTables($criteria)}";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute(Helpers::transformKeys($criteria));

        if ($stmt->rowCount() === 1) {
            return $instance === true ? new $class($stmt->fetch()) : $stmt->fetch();
        } elseif ($stmt->rowCount() > 1) {
            return $instance === true ? $this->getInstances($stmt->fetchAll()) : $stmt->fetchAll();
        }

        return null;
    }

}
