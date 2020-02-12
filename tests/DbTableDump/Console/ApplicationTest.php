<?php
namespace Access9\DbTableDump\Tests\Console;

use Access9\DbTableDump\Config;
use Access9\DbTableDump\Console\Application;
use PHPUnit\Framework\TestCase;

/**
 * Class ApplicationTest
 *
 * @package Access9\DbTableDump\Tests\Console
 */
class ApplicationTest extends TestCase
{
    /**
     * @var Application
     */
    private $application;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->application->setConfig(new Config());
    }

    protected function tearDown(): void
    {
        $this->application = null;
    }

    public function testGetConnection()
    {
        $connection = $this->application->getConnection();
        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $connection);

        // Ensure the same instance is returned.
        $this->assertSame($this->application->getConnection(), $connection);
    }

    public function testGetConfig()
    {
        $config = $this->application->getConfig();
        $this->assertInstanceOf('\Access9\DbTableDump\Config', $config);
    }

    public function testOverrideDefaultDefinition()
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
