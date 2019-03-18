<?php

use PHPUnit\DbUnit\Database\DefaultConnection;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use PHPUnit\DbUnit\Operation\Factory;
use PHPUnit\Framework\TestCase;


abstract class AbstractDatabase extends TestCase
{


    /**
     * @var PDO
     */
    static protected $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO('sqlite::memory:');
            }
            $this->conn = new DefaultConnection(self::$pdo, ':memory:');
        }

        static::initDatabase();


        return $this->conn;
    }

    public function getDataSet()
    {
        return new ArrayDataSet(array());

    }

    abstract  function initDatabase();


    protected  function getTearDownOperation()
    {
        return Factory::TRUNCATE();

    }

}