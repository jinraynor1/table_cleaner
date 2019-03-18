<?php


namespace Jinraynor1\TableCleaner\Drivers;


interface DriverInterface
{
    public function search();

    public function drop($table_name);
}