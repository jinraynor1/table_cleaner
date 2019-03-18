<?php


namespace Jinraynor1\TableCleaner\Drivers;


class Sqlite extends DefaultDriver implements DriverInterface
{

    public function search()
    {

        $sql = "SELECT name, sql FROM sqlite_master WHERE type='table' ";

        $stmt = $this->database->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

    }
}