<?php declare(strict_types=1);
namespace Access9\DbTableDump\Console;

use Access9\DbTableDump\Config;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Application as sfApplication;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * @package Access9\DbTableDump\Console
 */
final class Application extends sfApplication
{
    public const string APP_NAME    = 'Dump';
    public const string APP_VERSION = '0.16.0';

    private ?Connection $db = null;

    public function __construct(private readonly Config $config)
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);
        $this->overrideDefaultDefinition();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getConnection(
        ?string $user = null,
        ?string $password = null,
        ?string $host = null,
        ?string $dbname = null
    ): Connection {
        // Return the connection if it's already been created AND no custom params are provided.
        if ($user === null && $password === null && $host === null && $dbname === null && null !== $this->db) {
            return $this->db;
        }

        if (!isset($this->config->driver)) {
            throw new \RuntimeException('Database driver is not configured.');
        }

        $this->db = DriverManager::getConnection(
            $this->config->toArray($user, $password, $host, $dbname)
        );

        return $this->db;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Remove a few of the default options that will never be implemented.
     */
    private function overrideDefaultDefinition(): void
    {
        $defaultDefinition = new InputDefinition();
        $removeMe          = ['quiet', 'verbose', 'no-interaction'];
        foreach ($this->getDefaultInputDefinition()->getOptions() as $inputOption) {
            if (!in_array($inputOption->getName(), $removeMe)) {
                $defaultDefinition->addOption($inputOption);
            }
        }
        $defaultDefinition->addArguments($this->getDefaultInputDefinition()->getArguments());
        $this->setDefinition($defaultDefinition);
    }
}
