# DbTableDump - The Database Table Dumper

DbTableDump is a PHP CLI tool to dump a database table's content to various formats.


## Installation

To install this tool, clone this repo
``` bash
git clone https://github.com/bglover/dbtabledump.git
```

Then make sure you get the required vendor dependencies using composer
``` bash
composer update
```


## Supported Formats

YAML is the only supported format right now. Support for additional formats
(JSON, XML) is in the works


## Usage

``` bash
php dump.php help dump:yaml
Usage:
 dump:yaml [-l|--limit[="..."]] [-u|--user[="..."]] [-p|--password[="..."]] [-db|--dbname[="..."]] tables1 ... [tablesN]

Arguments:
 tables                Space delimeted list of tables to dump.

Options:
 --limit (-l)          Number of rows to limit the output to. This option applies to all tables dumped.
 --user (-u)           Optional username. Overrides the user setting in config.yml
 --password (-p)       Optional password. Overrides the password setting in config.yml
 --dbname (-db)        Optional database name. Overrides the dbname setting in config.yml
 --help (-h)           Display this help message.
 --quiet (-q)          Do not output any message.
 --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug.
 --version (-V)        Display this application version.
 --ansi                Force ANSI output.
 --no-ansi             Disable ANSI output.
 --no-interaction (-n) Do not ask any interactive question.
```

The `--quiet`, `--verbose` and `--no-interaction` options have no effect.


### Example - Dumping a single table to yaml

``` bash
php dump.php dump:yaml mytable
mytable:
  -
    id: '1'
    column: column for row 1
  -
    id: '2'
    column: column for row 2
```


### Example - Dumping a multiple tables to yaml


``` bash
php dump.php dump:yaml mytable othertable
mytable:
  -
    mytable_id: '1'
    column: column for row 1
  -
    mytable_id: '2'
    column: column for row 2
othertable
  -
    othertable_id: '1'
    data: ABC123
    mytable_id: '1'
  -
    othertable_id: '2'
    data: DEF456
    mytable_id: '2'
```


### Example - Dumping using the --dbname argument

``` bash
php dump.php dump:yaml store language --dbname sakila
store:
  -
    store_id: '1'
    manager_staff_id: '1'
    address_id: '1'
    last_update: '2006-02-15 04:57:12'
  -
    store_id: '2'
    manager_staff_id: '2'
    address_id: '2'
    last_update: '2006-02-15 04:57:12'
language:
  -
    language_id: '1'
    name: English
    last_update: '2006-02-15 05:02:19'
  -
    language_id: '2'
    name: Italian
    last_update: '2006-02-15 05:02:19'
  -
    language_id: '3'
    name: Japanese
    last_update: '2006-02-15 05:02:19'
  -
    language_id: '4'
    name: Mandarin
    last_update: '2006-02-15 05:02:19'
  -
    language_id: '5'
    name: French
    last_update: '2006-02-15 05:02:19'
  -
    language_id: '6'
    name: German
    last_update: '2006-02-15 05:02:19'
```


## Configuration

Copy config/config.yml.dist to config/config.yml and edit appropriately.


### Supported Database Drivers

This project uses [Doctrine's DBAL](http://www.doctrine-project.org/projects/dbal.html).

The following drivers are support by the Doctine's DBAL:
  - pdo_mysql
  - drizzle_pdo_mysql
  - mysqli
  - pdo_sqlite
  - pdo_pgsql
  - pdo_oci
  - pdo_sqlsrv
  - sqlsrv
  - oci8
  - sqlanywhere

There are known issues with the pdo_oci and pdo_sqlsrv drivers. See Doctine's
[Driver](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#driver)
page for more information if you need to use one of those.

## Prerequisites

DbTableDump requires PHP 5.4 or higher.
