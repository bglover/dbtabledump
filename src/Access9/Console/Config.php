<?php
namespace Access9\Console;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 *
 * @package Access9\Console
 */
class Config
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $locator      = new FileLocator([__DIR__ . '/../../../config']);

        // Use the appropriate file for testing or normal runs.
        $filename     = defined('PHPUNIT') ? 'config_test.yml' : 'config.yml';
        $configFile   = $locator->locate($filename, null, true);
        $this->config = Yaml::parse($configFile);
    }


    /**
     * Return the array configuration.
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $dbname
     * @return array
     */
    public function getConfig($username = null, $password = null, $dbname = null)
    {
        if ($username) {
            $this->config['user'] = $username;
        }

        if ($password) {
            $this->config['password'] = $password;
        }

        if ($dbname) {
            $this->config['dbname'] = $dbname;
        }
        return $this->config;
    }
}
