<?php

// http://mysql.rjweb.org/doc.php/deletebig

namespace Persistence;


use Jinraynor1\TableCleaner\TableDelete;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;

class MyLogger extends AbstractLogger{
    public function log($level, $message, array $context = array())
    {
        echo "$level $message\n";
    }

};
class TableDeleteTest extends TestCase
{
    static $db;
    public static function setUpBeforeClass()    {

        self::$db = new \PDO('sqlite::memory:');

    }

    public function setUp()
    {

        $querys = array();
        $querys[] = "DROP TABLE IF EXISTS `dropme`";
        $querys[] = "CREATE TABLE `dropme` (`id` INT,`fecha` text)";

        $querys[] = "INSERT INTO `dropme` VALUES (1,'2018-01-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme` VALUES (2,'2018-02-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme` VALUES (3,'2018-03-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme` VALUES (4,'2019-01-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme` VALUES (5,'2019-02-01 00:00:00')";

        $querys[] = "DROP TABLE IF EXISTS `dropme2`";
        $querys[] = "CREATE TABLE `dropme2` (`id` INT,origen text,`fecha` text)";

        $querys[] = "INSERT INTO `dropme2` VALUES (1,'master','2018-01-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme2` VALUES (2,'master','2018-02-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme2` VALUES (1,'replica','2018-03-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme2` VALUES (2,'replica','2019-01-01 00:00:00')";
        $querys[] = "INSERT INTO `dropme2` VALUES (3,'master','2019-02-01 00:00:00')";


        foreach ($querys as $query) {
            self::$db->query($query);
        }
    }

    public function testNonUniqueId()
    {
        $tableDelete = new TableDelete(self::$db, "dropme2", "id", "fecha", new \DateTime("2019-02-01"), 1);
        $tableDelete->delete();

        $data = self::$db->query("SELECT * FROM dropme2")->fetchAll();


        $this->assertCount(1, $data);

        $this->assertEquals("3", $data[0]['id']);
        $this->assertEquals("2019-02-01 00:00:00", $data[0]['fecha']);
        
    }

    public function testDeleteByOne()
    {
        $tableDelete = new TableDelete(self::$db, "dropme", "id", "fecha", new \DateTime("2018-04-01"), 1);
        $tableDelete->setLogger(new MyLogger());

        $tableDelete->delete();

        $data = self::$db->query("SELECT * FROM dropme")->fetchAll();


        $this->assertCount(2, $data);
        $this->assertEquals("4", $data[0]['id']);
        $this->assertEquals("2019-01-01 00:00:00", $data[0]['fecha']);
        $this->assertEquals("5", $data[1]['id']);
        $this->assertEquals("2019-02-01 00:00:00", $data[1]['fecha']);
    }

    public function testDeleteByTwo()
    {

        $tableDelete = new TableDelete(self::$db, "dropme", "id", "fecha", new \DateTime("2019-02-01"), 2);
        $tableDelete->delete();

        $data = self::$db->query("SELECT * FROM dropme")->fetchAll();


        $this->assertCount(1, $data);

        $this->assertEquals("5", $data[0]['id']);
        $this->assertEquals("2019-02-01 00:00:00", $data[0]['fecha']);
    }

    public function testDeleteByOneThousand()
    {

        $tableDelete = new TableDelete(self::$db, "dropme", "id", "fecha", new \DateTime("2019-02-01"), 1000);
        $tableDelete->delete();

        $data = self::$db->query("SELECT * FROM dropme")->fetchAll();


        $this->assertCount(1, $data);

        $this->assertEquals("5", $data[0]['id']);
        $this->assertEquals("2019-02-01 00:00:00", $data[0]['fecha']);
    }
}