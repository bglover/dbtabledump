<?php declare(strict_types=1);
namespace Access9\DbTableDump\Tests\Console;

use Access9\DbTableDump\Config;
use Access9\DbTableDump\Console\Application;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Access9\DbTableDump\Console\Application
 */
class ApplicationTest extends TestCase
{
    private ?Application $application;

    /**
     * @throws \Access9\DbTableDump\FileNotWritableException
     */
    protected function setUp(): void
    {
        $this->application = new Application(new Config(dirname(__DIR__, 2) . '/config'));
    }

    protected function tearDown(): void
    {
        $this->application = null;
    }

    /**
     * @covers ::getConnection
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetConnection(): void
    {
        $connection = $this->application->getConnection();
        $this->assertInstanceOf(Connection::class, $connection);

        // Ensure the same instance is returned.
        $this->assertSame($this->application->getConnection(), $connection);
    }

    /**
     * @covers ::getConfig
     */
    public function testGetConfig(): void
    {
        $config = $this->application->getConfig();
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @covers ::getDefinition
     */
    public function testOverrideDefaultDefinition(): void
    {
        $meth = new \ReflectionMethod($this->application, 'overrideDefaultDefinition');
        $meth->setAccessible(true);

        $defaultApp        = new \Symfony\Component\Console\Application();
        $defaultDefinition = $defaultApp->getDefinition();

        $definition = $this->application->getDefinition();
        $this->assertNotSame($definition, $defaultDefinition);
        foreach ($definition->getOptions() as $option) {
            $this->assertNotContains($option->getName(), ['quiet', 'no-interaction', 'verbose']);
        }
    }
}
