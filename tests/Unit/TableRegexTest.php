<?php

use Jinraynor1\TableCleaner\TableRegex;
use PHPUnit\Framework\TestCase;

class TableRegexTest extends TestCase
{

    public function testThatInvalidRegexThrowsException()
    {

        $this->expectException("InvalidArgumentException");
        new TableRegex("/invalid-(regex/", new DateTime(), "Ymd");
    }

    public function testThatRegexDoesNotThrowException()
    {


        try {
            $regex = new TableRegex("/table regex([0-9]{8})/", new DateTime(), "Ymd");

            $this->assertTrue($regex->validFormat());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }


    }
}