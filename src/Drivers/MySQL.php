<?php


namespace Jinraynor1\TableCleaner\Drivers;


class MySQL extends DefaultDriver implements DriverInterface
{

    public function search()
    {

        $sql = "SELECT table_name,create_time FROM information_schema.tables WHERE table_schema = ?";

        $stmt = $this->database->prepare($sql);

        $stmt->execute(array($this->getDatabase()));
        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

    }

    private function getDatabase()
    {
        return $this->database->query('select database()')->fetchColumn();

    }



}