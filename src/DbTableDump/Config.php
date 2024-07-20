<?php declare(strict_types=1);
namespace Access9\DbTableDump;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Access9\DbTableDump\Console
 * @property null|string $user
 * @property null|string $password
 * @property null|string $host
 * @property null|string $dbname
 * @property null|bool   $memory    Used for testing.
 * @property null|string $driver
 */
class Config
{
    private array $config;
    private string $configFile;

    /**
     * @throws \Access9\DbTableDump\FileNotWritableException
     */
    public function __construct(string $configPath)
    {
        // Use the appropriate file for testing.
        $this->configFile = $configPath . DIRECTORY_SEPARATOR
            . (defined('PHPUNIT') ? 'config_test.yml' : 'config.yml');

        if (!file_exists($this->configFile)) {
            $this->isWritable($configPath);
            copy($this->configFile . '.dist', $this->configFile);
        }
        $this->config = Yaml::parseFile($this->configFile);
    }

    /**
     * @throws FileNotWritableException
     */
    protected function isWritable(string $location): void
    {
        if (!is_writable($location)) {
            throw new FileNotWritableException(
                "'{$location}' is not writable. Check the file/directory permissions and try again."
            );
        }
    }

    public function __get(string $key): mixed
    {
        return $this->config[$key] ?? null;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __set(string $key, mixed $value): void
    {
        if (!array_key_exists($key, $this->config)) {
            throw new InvalidArgumentException('Attempting to set a non-existent configuration key');
        }

        $this->config[$key] = $value;
    }

    public function __isset(string $key): bool
    {
        return isset($this->config[$key]);
    }

    /**
     * Return the array configuration.
     */
    public function toArray(
        ?string $user = null,
        ?string $password = null,
        ?string $host = null,
        ?string $dbname = null
    ): array {
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
    public function save(): void
    {
        $this->isWritable($this->configFile);
        file_put_contents($this->configFile, Yaml::dump($this->config));
    }

    public function __toString(): string
    {
        return Yaml::dump($this->config);
    }
}
