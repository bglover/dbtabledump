<?php declare(strict_types=1);
namespace Access9\DbTableDump\Console\Command;

use Access9\DbTableDump\Console\Application;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as sfCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Access9\DbTableDump\Console\Command
 * @method Application getApplication
 */
class ConfigSetCommand extends sfCommand
{
    private const VALID_DRIVERS = [
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

    private const REQUIRED_OPTIONS = [
        'user',
        'password',
        'host',
        'dbname',
        'driver',
    ];

    /**
     * Common configuration arguments.
     */
    protected function configure(): void
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
                        . PHP_EOL . implode(', ', self::VALID_DRIVERS)
                    )
                ])
            );

        parent::configure();
    }

    /**
     * {@inheritdoc}
     * @throws \Access9\DbTableDump\FileNotWritableException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = $input->getOptions();
        $this->validateOptions($options);
        $this->updateConfig($options);

        return 0;
    }

    /**
     * @throws \Access9\DbTableDump\FileNotWritableException
     * @throws InvalidArgumentException
     */
    private function updateConfig(array $options): void
    {
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
            if (!in_array($driver, self::VALID_DRIVERS, true)) {
                throw new InvalidArgumentException(
                    '"' . $driver . '" is not a valid driver. Valid drivers are: '
                    . PHP_EOL . ' - ' . implode(PHP_EOL . ' - ', self::VALID_DRIVERS)
                );
            }
            $config->driver = $driver;
        }

        $config->save();
    }

    /**
     * Validate that at least one option is given.
     *
     * @throws InvalidArgumentException
     */
    private function validateOptions(array $options): void
    {
        foreach (self::REQUIRED_OPTIONS as $key) {
            if (!array_key_exists($key, $options) || empty($options[$key])) {
                throw new InvalidArgumentException("The '$key' option is required.");
            }
        }
    }
}
