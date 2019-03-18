<?php


namespace Jinraynor1\TableCleaner;


class RegexTableIterator extends \RegexIterator
{
    private $regex;

    public function __construct(\ArrayIterator $iterator, TableRegex $regex)
    {
        $this->regex = $regex;

        parent::__construct($iterator, $regex->__toString(), \RegexIterator::MATCH);

    }

    public function accept()
    {
        if (!parent::accept()) {
            return false;
        }

        $current = $this->getInnerIterator()->current();
        preg_match($this->regex->__toString(), $current, $matches);

        $table_datetime = \DateTime::createFromFormat($this->regex->date_format, $matches[1]);

        if (!$table_datetime) {
            return false;
        }

        if ($table_datetime > $this->regex->date_time) {
            return false;
        }

        return true;
    }
}