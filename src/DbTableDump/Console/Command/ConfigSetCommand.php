<?php
namespace Access9\DbTableDump\Console\Command;

use Symfony\Component\Console\Command\Command as sfCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigCommand
 *
 * @package Access9\DbTableDump\Console\Command
 */
class ConfigSetCommand extends sfCommand
{
    /**
     * @var array
     */
    private static $validDrivers = [
        'pdo_mysql',
        'drizzle_pdo_mysql',
        'mysqli',
        'pdo_sqlite',
        'pdo_pgsql',
        'pdo_oci',
        'pdo_sqlsrv',
        'sqlsrv',
        'oci8',
        'sqlanywhere',
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
                        . PHP_EOL . implode(', ', self::$validDrivers)
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
        $options = $input->getOptions();
        $this->validateOptions($options);
        $this->updateConfig($options);
    }

    /**
     * @param array $options
     * @throws \Access9\DbTableDump\FileNotWritableException
     * @throws \InvalidArgumentException
     */
    private function updateConfig(array $options)
    {

        /** @var \Access9\DbTableDump\Config $config */
        $config = $this->getApplication()->getConfig();

        if ($options['user']) {
            $config->user = $options['user'];
        }

        if (null !== ($password = $options['password'])) {
            $config->password = $password;
        }

        if (null !== ($host = $options['host'])) {
            $config->host = $host;
        }

        if (null !== ($dbname = $options['dbname'])) {
            $config->dbname = $dbname;
        }

        if (null !== ($driver = $options['driver'])) {
            if (!in_array($driver, self::$validDrivers)) {
                throw new \InvalidArgumentException(
                    '"' . $driver . '" is not a valid driver. Valid drivers are: '
                    . PHP_EOL . ' - ' . implode(PHP_EOL . ' - ', self::$validDrivers)
                );
            }
            $config->driver = $driver;
        }

        $config->save();
    }

    /**
     * Validate that at least one option is given.
     *
     * @param array $options
     * @throws \InvalidArgumentException
     * @return bool
     */
    private function validateOptions(array $options)
    {
        foreach ($options as $o) {
            if (!empty($o)) {
                return;
            }
        }

        throw new \InvalidArgumentException('An option is required.');
    }
}
