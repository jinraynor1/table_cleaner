<?php


namespace Jinraynor1\TableCleaner\Drivers;


class DefaultDriver
{

    /**
     * @var \PDO
     */
    protected $database;

    public function __construct(\PDO $database)
    {

        $this->database = $database;

    }

    public function drop($table_name)
    {
        $sql = "DROP TABLE " . $this->quoteIdentifier($table_name);
        return $this->database->query($sql);
    }


    public function search()
    {
        throw new \Exception("default driver does not know how to search");
    }

    public function quoteIdentifier($field) {
        return "`".str_replace("`","``",$field)."`";
    }
}