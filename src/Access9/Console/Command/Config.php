<?php
namespace Access9\Console\Command;

use Symfony\Component\Console\Command\Command as sfCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ConfigCommand
 *
 * @package Access9\Console\Command
 */
class Config extends sfCommand
{
    /**
     * Common configuration arguments.
     */
    protected function configure()
    {
        $this->setDefinition(
            new InputDefinition([
                new InputOption(
                    'user',
                    'u',
                    InputOption::VALUE_REQUIRED,
                    'Username used to connect to the database.'
                ),
                new InputOption(
                    'password',
                    'p',
                    InputOption::VALUE_REQUIRED,
                    'Password used to connect to the database.'
                ),
                new InputOption(
                    'dbname',
                    'db',
                    InputOption::VALUE_REQUIRED,
                    'Name of the database used for dump operations.'
                ),
                new InputOption(
                    'driver',
                    'd',
                    InputOption::VALUE_REQUIRED,
                    'Driver used to connect to the database. Valid options are "pdo_mysql", "drizzle_pdo_mysql", '
                    . '"mysqli", "pdo_sqlite", "pdo_pgsql", "pdo_oci", "pdo_sqlsrv", "sqlsrv", "oci8" and '
                    . '"sqlanywhere".'
                )
            ])
        );
    }
}
