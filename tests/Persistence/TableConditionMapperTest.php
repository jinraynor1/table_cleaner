<?php
require_once 'AbstractDatabase.php';

use Jinraynor1\TableCleaner\Drivers\Sqlite;
use Jinraynor1\TableCleaner\TableRegex;
use Jinraynor1\TableCleaner\TableCleaner;

class TableConditionMapperTest extends AbstractDatabase
{

    public  function initDatabase()
    {

        $querys = array();
        $querys[] = "CREATE TABLE IF NOT EXISTS `very_critical_table` (`iduser` INT UNSIGNED)";
        $querys[] = "CREATE TABLE IF NOT EXISTS `dropme20180101` (`iduser` INT UNSIGNED)";
        $querys[] = "CREATE TABLE IF NOT EXISTS `dropme20190101` (`iduser` INT UNSIGNED)";
        $querys[] = "CREATE TABLE IF NOT EXISTS `dropme20190201` (`iduser` INT UNSIGNED)";

        foreach ($querys as $query) {
            static::$pdo->query($query);
        }

    }

    public function testFiltersLeavesRecentTablesIntact()
    {
        $date = new DateTime("2019-01-01");
        $regex = new TableRegex("/^dropme([0-9]{8})$/", $date, "Ymd");
        $driver = new Sqlite($this->getConnection()->getConnection());

        $table_cleaner = new TableCleaner($driver, $regex);

        $filtered_results = $table_cleaner->filter();
        $this->assertCount(1, $filtered_results);

    }

    public function testFiltersDeletesExpectedTables()
    {
        $date = new DateTime("2019-02-01");
        $regex = new TableRegex("/^dropme([0-9]{8})$/", $date, "Ymd");
        $driver = new Sqlite($this->getConnection()->getConnection());

        $table_cleaner = new TableCleaner($driver, $regex);

        $dropped = $table_cleaner->drop();
        $this->assertEquals(2, $dropped);

        $this->assertEquals(array(
            'very_critical_table',
            'dropme20190201',
        ), $driver->search());


    }

}