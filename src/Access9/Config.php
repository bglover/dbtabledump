<?php
namespace Access9;

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
     * @var string
     */
    private $configFile = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config';

        // Use the appropriate file for testing.
        $this->configFile = $path . DIRECTORY_SEPARATOR . (defined('PHPUNIT') ? 'config_test.yml' : 'config.yml');
        if (!file_exists($this->configFile)) {
            if (!is_writable($path)) {
                throw new FileNotWritableException(
                    "The directory '{$path}' is not writable. Check the directory permissions and try again."
                );
            }
            copy($this->configFile . '.dist', $this->configFile);
        }
        $this->config     = Yaml::parse($this->configFile);
    }

    /**
     * Public getter.
     *
     * @param string $key
     * @return string|null
     */
    public function __get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }

    /**
     * Public setter.
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new \OutOfRangeException('Attempting to set a non-existent configuration key');
        }

        $this->config[$key] = $value;
    }

    /**
     * Public isset.
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->config[$key]);
    }

    /**
     * Return the array configuration.
     *
     * @param string|null $user
     * @param string|null $password
     * @param string|null $host
     * @param string|null $dbname
     * @return array
     */
    public function getConfig($user = null, $password = null, $host = null, $dbname = null)
    {
        if ($user) {
            $this->config['user'] = $user;
        }

        if ($password) {
            $this->config['password'] = $password;
        }

        if ($host) {
            $this->config['host'] = $host;
        }

        if ($dbname) {
            $this->config['dbname'] = $dbname;
        }

        return $this->config;
    }

    /**
     * Save the configuration.
     */
    public function save()
    {
        if (!is_writable($this->configFile)) {
            throw new FileNotWritableException(
                "The config file at {$this->configFile} is not writable. Check the file permissions and try again."
            );
        }
        file_put_contents($this->configFile, Yaml::dump($this->config));
    }

    public function __toString()
    {
        return Yaml::dump($this->config);
    }
}
