<?php
namespace Access9\Console;

use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Application as sfApplication;

/**
 * Class Application
 *
 * @package Access9\Console
 */
class Application extends sfApplication
{
    const APP_NAME   = 'DbTableDump';
    const APP_VERION = '0.5.1';

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * @inheritdoc
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct(self::APP_NAME, self::APP_VERION);
    }

    /**
     * @param string|null $username
     * @param string|null $password
     * @param string|null $dbname
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getConnection($username = null, $password = null, $dbname = null)
    {
        // Return the connection if it's already been created.
        if ($this->db) {
            return $this->db;
        }

        $this->db = DriverManager::getConnection(
            (new Config())->getConfig($username, $password, $dbname)
        );

        return $this->db;
    }
}
