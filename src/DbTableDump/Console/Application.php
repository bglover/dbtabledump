<?php
namespace Access9\DbTableDump\Console;

use Access9\DbTableDump\Config;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Application as sfApplication;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Class Application
 *
 * @package Access9\DbTableDump\Console
 */
class Application extends sfApplication
{
    const APP_NAME    = 'Dump';
    const APP_VERSION = '0.14.2';

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * @var Config
     */
    private $config;

    /**
     * @inheritdoc
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);
        $this->overrideDefaultDefinition();
    }

    /**
     * Returns the instance of \Doctrine\DBAL\Connection.
     *
     * @param string|null $user
     * @param string|null $password
     * @param string|null $host
     * @param string|null $dbname
     * @throws \Doctrine\DBAL\DBALException
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection($user = null, $password = null, $host = null, $dbname = null)
    {
        // Return the connection if it's already been created AND no custom params are provided.
        if ((!$user && !$password && !$host && !$dbname) && $this->db) {
            return $this->db;
        }

        $this->db = DriverManager::getConnection(
            $this->config->toArray($user, $password, $host, $dbname)
        );

        return $this->db;
    }

    /**
     * Returns the instance of \Access9\DbTableDump\Config.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Remove a few of the default options that will never be implemented.
     */
    private function overrideDefaultDefinition()
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
