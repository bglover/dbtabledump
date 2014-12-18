<?php
namespace Access9\Console\Command;

use Symfony\Component\Console\Command\Command as sfCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigCommand
 *
 * @package Access9\Console\Command
 */
class ConfigSetCommand extends sfCommand
{
    /**
     * @var array
     */
    private $validDrivers = [
        'drizzle_pdo_mysql',
        'mysqli',
        'oci8',
        'pdo_mysql',
        'pdo_oci',
        'pdo_pgsql',
        'pdo_sqlite',
        'pdo_sqlsrv',
        'sqlanywhere',
        'sqlsrv'
    ];

    /**
     * Common configuration arguments.
     */
    protected function configure()
    {
        $this->setName('config:set')
            ->setDescription('Set a configuration value.')
            ->setDefinition(
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
                        'host',
                        'o',
                        InputOption::VALUE_REQUIRED,
                        'Host the database is on. Either an IP address or a hostname are valid.'
                    ),
                    new InputOption(
                        'dbname',
                        'n',
                        InputOption::VALUE_REQUIRED,
                        'Name of the database used for dump operations.'
                    ),
                    new InputOption(
                        'driver',
                        'd',
                        InputOption::VALUE_REQUIRED,
                        'Driver used to connect to the database. Valid options are '
                        . PHP_EOL . 'pdo_mysql, drizzle_pdo_mysql, mysqli, pdo_sqlite, pdo_pgsql, '
                        . PHP_EOL . 'pdo_oci, pdo_sqlsrv, sqlsrv, oci8 and sqlanywhere.'
                    )
                ])
            );
        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Access9\Config $config */
        $config = $this->getApplication()->getConfig();
        if (null !== ($user = $input->getOption('user'))) {
            $config->user = $user;
        }

        if (null !== ($password = $input->getOption('password'))) {
            $config->password = $password;
        }

        if (null !== ($host = $input->getOption('host'))) {
            $config->host = $host;
        }

        if (null !== ($dbname = $input->getOption('dbname'))) {
            $config->dbname = $dbname;
        }

        if (null !== ($driver = $input->getOption('driver'))) {
            if (!in_array($driver, $this->validDrivers)) {
                throw new \InvalidArgumentException(
                    '"' . $driver . '" is not a valid driver. Valid drivers are: '
                    . PHP_EOL . ' - ' . implode(PHP_EOL . ' - ', $this->validDrivers)
                );
            }
            $config->driver = $driver;
        }

        $config->save();
    }
}
