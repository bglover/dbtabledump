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
class Application extends sfApplication
{
    public const APP_NAME    = 'Dump';
    public const APP_VERSION = '0.14.2';

    private ?Connection $db = null;
    private Config      $config;

    public function __construct($name = self::APP_NAME, $version = self::APP_VERSION)
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);
        $this->overrideDefaultDefinition();
    }

    /**
     * Returns the instance of \Doctrine\DBAL\Connection.
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function getConnection(
        ?string $user = null,
        ?string $password = null,
        ?string $host = null,
        ?string $dbname = null
    ): Connection {
        // Return the connection if it's already been created AND no custom params are provided.
        if ((!$user && !$password && !$host && !$dbname) && null !== $this->db) {
            return $this->db;
        }

        $this->db = DriverManager::getConnection(
            $this->config->toArray($user, $password, $host, $dbname)
        );

        return $this->db;
    }

    /**
     * Returns the instance of \Access9\DbTableDump\Config.
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): void
    {
        $this->config = $config;
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
