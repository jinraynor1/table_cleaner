# Table Cleaner

Cleans table by dropping them by a regex and time

## Getting Started

Brief example of how to use
```php
<?php
$date = new DateTime("2019-02-01");
$regex = new \Jinraynor1\TableCleaner\TableRegex("/^dropme([0-9]{8})$/", $date, "Ymd");
$driver = new \Jinraynor1\TableCleaner\Drivers\Sqlite(new PDO('mysql:host=localhost;dbname=testdb','root',''));

$table_cleaner = new \Jinraynor1\TableCleaner\TableCleaner($driver, $regex);
$table_cleaner->drop();
```        
Please see tests directory for more example on how to use this library

### Prerequisites

You will need at least php 5.3 and PDO libraries for your database driver


### Installing

You can install it with composer by typing

```
composer require jinraynor1/table_cleaner
```

For testing you will need these dependencies

```
composer require-dev phpunit/phpunit "~6.5"
composer require --dev phpunit/dbunit
```



## Running the tests

Just type something like this

```
/usr/local/bin/phpunit --configuration phpunit.xml tests 
```