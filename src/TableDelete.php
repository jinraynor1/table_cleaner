<?php


namespace Jinraynor1\TableCleaner;


use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TableDelete
{
    /**
     * @var \PDO
     */
    private $db;
    private $table_name;
    private $column_id;
    /**
     * @var \DateTime
     */
    private $dateTime;
    /**
     * @var int
     */
    private $offset;
    private $column_time;
    /**
     * @var int
     */
    private $sleep_microseconds;

    private $logger;

    public function __construct(\PDO $db,
                                $table_name,
                                $column_id,
                                $column_time,
                                \DateTime $dateTime,
                                $offset = 1000,
                                $sleep_microseconds = 1000000)
    {

        $this->db = $db;
        $this->table_name = $table_name;
        $this->column_id = $column_id;
        $this->dateTime = $dateTime;
        $this->offset = $offset;
        $this->column_time = $column_time;
        $this->sleep_microseconds = $sleep_microseconds;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function delete()
    {
        $id_begin = $this->getMinValue();
        $this->logger->debug("starting with value $id_begin");
        while (true) {
            $id_next = $this->getNextValue($id_begin);

            if ($id_begin == $id_next) {
                $id_next = $this->getNextValue($id_begin,false);
            }

            if (!$id_next) break;

            $this->logger->debug("deleting $this->offset records from column id $id_begin to $id_next date less than {$this->dateTime->format('Y-m-d H:i:s')}");
            $this->realDelete($id_begin, $id_next);

            $id_begin = $id_next;
            usleep($this->sleep_microseconds);
        }

        $this->logger->debug("deleting $this->offset records from column id $id_begin date less than {$this->dateTime->format('Y-m-d H:i:s')}");
        $this->realDelete($id_begin, false);

    }

    private function getMinValue()
    {
        return $this->db->query("SELECT MIN($this->column_id) FROM $this->table_name")->fetchColumn();
    }

    private function getNextValue($v, $equal = true)
    {

        $cond = $equal ? ">=" : ">";

        $sql = "SELECT $this->column_id FROM $this->table_name 
            WHERE $this->column_id $cond $v 
            AND $this->column_time < '{$this->dateTime->format('Y-m-d H:i:s')}'
             ORDER BY $this->column_id LIMIT $this->offset,1 \n";

        return $this->db->query($sql)->fetchColumn();
    }

    private function realDelete($id_begin, $id_end)
    {
        $sql = "DELETE FROM $this->table_name
         WHERE $this->column_id >= $id_begin";

        if ($id_end)
            $sql .= " AND $this->column_id <  $id_end ";

        $sql .= " AND $this->column_time < '{$this->dateTime->format('Y-m-d H:i:s')}' ";
$this->logger->debug($sql);
        $this->db->query($sql);
    }

}