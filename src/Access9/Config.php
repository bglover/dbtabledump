<?php
namespace Access9;

use InvalidArgumentException;
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
        $path = $this->getPath();

        // Use the appropriate file for testing.
        $this->configFile = $path . DIRECTORY_SEPARATOR
            . (defined('PHPUNIT') ? 'config_test.yml' : 'config.yml');

        if (!file_exists($this->configFile)) {
            $this->isWritable($path);
            copy($this->configFile . '.dist', $this->configFile);
        }
        $this->config = Yaml::parse(file_get_contents($this->configFile));
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'config'
        );
    }

    /**
     * @param string $location
     * @throws FileNotWritableException
     */
    protected function isWritable($location)
    {
        if (!is_writable($location)) {
            throw new FileNotWritableException(
                "'{$location}' is not writable. Check the file/directory permissions and try again."
            );
        }
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
     * @param string $key
     * @param mixed  $value
     * @throws InvalidArgumentException
     */
    public function __set($key, $value)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new InvalidArgumentException('Attempting to set a non-existent configuration key');
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
    public function toArray($user = null, $password = null, $host = null, $dbname = null)
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
     *
     * @throws FileNotWritableException
     */
    public function save()
    {
        $this->isWritable($this->configFile);
        file_put_contents($this->configFile, Yaml::dump($this->config));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return Yaml::dump($this->config);
    }
}
