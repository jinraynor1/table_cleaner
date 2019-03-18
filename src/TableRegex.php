<?php


namespace Jinraynor1\TableCleaner;


class TableRegex
{

    private $regex;
    public $date_format;
    public $date_time;

    public function __construct($regex, \DateTime $date_time, $date_format)
    {
        $this->regex = $regex;
        $this->date_format = $date_format;
        $this->date_time = $date_time;


        if (!$this->isRegularExpression()) {
            throw new \InvalidArgumentException("invalid regex $this->regex");
        }



    }

    /**
     *
     * @return bool
     */
    public function validFormat()
    {
        if (strpos($this->regex, '(') != strrpos($this->regex, '(')) {
            return false;
        }

        $regex_numbers = "(\[0\-9\]\+|[0\-9\]\{[0-9][0-9]?})";

        return (bool)preg_match("/$regex_numbers/", $this->regex);

    }

    function isRegularExpression()
    {
        return @preg_match($this->regex, '') !== false;
    }


    function __toString()
    {
        return $this->regex;
    }

}