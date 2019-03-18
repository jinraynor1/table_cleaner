<?php


namespace Jinraynor1\TableCleaner;

use Jinraynor1\TableCleaner\Drivers\DriverInterface;

class TableCleaner
{
    /**
     * @var TableRegex
     */
    private $regex;

    private $driver;

    /**
     * @var \DateTime
     */
    protected $datetime;


    /**
     * TableCondition constructor.
     * @param $regex
     * @param $time_ago
     */
    public function __construct(DriverInterface $driver, TableRegex $regex )
    {
        $this->driver = $driver;
        $this->regex = $regex;

    }

    public function drop()
    {
        $dropped = 0;
        foreach ($this->filter() as $drop_table) {
            if($this->driver->drop($drop_table)){
                $dropped++;
            }
        }
        return $dropped;
    }


    public function filter()
    {
        $table_list = $this->driver->search();
        $table_iterator = new \ArrayIterator($table_list);
        return  new RegexTableIterator($table_iterator, $this->regex);

    }


}