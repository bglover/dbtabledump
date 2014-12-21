# DbTableDump - The Database Table Dumper

DbTableDump is a PHP CLI tool to dump a database table's content to various formats.


## Installation

### Via Composer

The easiest way to install this tool is via composer:
``` bash
composer require access9/dbtabledump
```

### Cloning

Of course you can always clone the repo
``` bash
git clone https://github.com/bglover/dbtabledump.git
```

Then make sure you get the required vendor dependencies using composer
``` bash
composer update
```


## Supported Formats

YAML and JSON are the only supported formats right now. Support for additional formats is planned.


# Usage

``` bash
php dump
DbTableDump version 0.7.0

Usage:
 [options] command [arguments]

Options:
 --help (-h)    Display this help message.
 --version (-V) Display this application version.
 --ansi         Force ANSI output.
 --no-ansi      Disable ANSI output.

Available commands:
 help         Displays help for a command
 list         Lists commands
config
 config:get   Get a configuration value. If no options are given, the entire config is printed.
 config:set   Set a configuration value.
to
 to:json      Dump one or more database tables to json format.
 to:yaml      Dump one or more database tables to yaml format.
```


## Table Dumping to YAML
``` bash
php dump help to:yaml
Usage:
 to:yaml [-l|--limit="..."] [-w|--where="..."] [-u|--user="..."] [-p|--password="..."] [-o|--host="..."] [-n|--dbname="..."] tables1 ... [tablesN]

Arguments:
 tables          Space delimited list of tables to dump.

Options:
 --limit (-l)    Number of rows to limit the output to. This option applies to all tables dumped.
 --where (-w)    Add a where clause to the sql. Clause must be in quotes: -w "name = 'larry'".
 --user (-u)     Optional username. Overrides the user setting in config.yml
 --password (-p) Optional password. Overrides the password setting in config.yml
 --host (-o)     Optional host. Overrides the host setting in config.yml
 --dbname (-n)   Optional database name. Overrides the dbname setting in config.yml
 --help (-h)     Display this help message.
 --version (-V)  Display this application version.
 --ansi          Force ANSI output.
 --no-ansi       Disable ANSI output.
```


## Table Dumping to JSON
``` bash
Usage:
 to:json [-l|--limit="..."] [-w|--where="..."] [-u|--user="..."] [-p|--password="..."] [-o|--host="..."] [-n|--dbname="..."] [-b|--bitmask[="..."]] tables1 ... [tablesN]

Arguments:
 tables          Space delimited list of tables to dump.

Options:
 --limit (-l)    Number of rows to limit the output to. This option applies to all tables dumped.
 --where (-w)    Add a where clause to the sql. Clause must be in quotes: -w "name = 'larry'".
 --user (-u)     Optional username. Overrides the user setting in config.yml
 --password (-p) Optional password. Overrides the password setting in config.yml
 --host (-o)     Optional host. Overrides the host setting in config.yml
 --dbname (-n)   Optional database name. Overrides the dbname setting in config.yml
 --bitmask (-b)  Bitmask to use. May be one or more of JSON_* constants
                 Usage example: `dump to:json -b JSON_PRETTY_PRINT -b JSON_UNESCAPED_SLASHES table` (multiple values allowed)
 --help (-h)     Display this help message.
 --version (-V)  Display this application version.
 --ansi          Force ANSI output.
 --no-ansi       Disable ANSI output.

Help:
 Available JSON constants are: JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT and JSON_UNESCAPED_UNICODE.
 See http://php.net/manual/en/json.constants.php for more information about what these do.
 ```

## Configuration

### Initializing your configuration

``` bash
php dump help config:set
Usage:
 config:set [-u|--user="..."] [-p|--password="..."] [-o|--host="..."] [-n|--dbname="..."] [-d|--driver="..."]

Options:
 --user (-u)     Username used to connect to the database.
 --password (-p) Password used to connect to the database.
 --host (-o)     Host the database is on. Either an IP address or a hostname are valid.
 --dbname (-n)   Name of the database used for dump operations.
 --driver (-d)   Driver used to connect to the database. Valid options are
                 pdo_mysql, drizzle_pdo_mysql, mysqli, pdo_sqlite, pdo_pgsql,
                 pdo_oci, pdo_sqlsrv, sqlsrv, oci8 and sqlanywhere.
 --help (-h)     Display this help message.
 --version (-V)  Display this application version.
 --ansi          Force ANSI output.
 --no-ansi       Disable ANSI output.
```


### Retrieving your configuration options.

``` bash
php dump help config:get
Usage:
 config:get [-u|--user] [-p|--password] [-o|--host] [-n|--dbname] [-d|--driver]

Options:
 --user (-u)
 --password (-p)
 --host (-o)
 --dbname (-n)
 --driver (-d)
 --help (-h)     Display this help message.
 --version (-V)  Display this application version.
 --ansi          Force ANSI output.
 --no-ansi       Disable ANSI output.

Help:
 If no options are given, the entire config is printed.
```

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
