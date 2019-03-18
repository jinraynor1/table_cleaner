<?php

use Jinraynor1\TableCleaner\TableRegex;
use Jinraynor1\TableCleaner\TableCleaner;
use PHPUnit\Framework\TestCase;

class TableConditionTest extends TestCase
{

    public function testFilterRemoves()
    {

        $driver_stub = $this->createMock('Jinraynor1\\TableCleaner\\Drivers\\MySQL');

        $driver_stub->method('search')
            ->willReturn(array(
                'critical_table',
                'borrame20180101',// this must be deleted
                'borrame20190102',
                'borrame20190103',

            ));

        $date = new DateTime("2019-01-01");
        $regex = new TableRegex("/^borrame([0-9]{8})$/", $date, "Ymd");


        $table_cleaner = new TableCleaner($driver_stub, $regex);

        $filtered_results = $table_cleaner->filter();
        $this->assertCount(1, $filtered_results);

    }
}