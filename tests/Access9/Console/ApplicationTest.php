<?php
namespace Access9\Tests\Console;

use Access9\Config;
use Access9\Console\Application;

/**
 * Class ApplicationTest
 *
 * @package Access9\Tests\Console
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $application;

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
        $this->assertInstanceOf('\Access9\Config', $config);
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

    protected function setUp()
    {
        $this->application = new Application();
        $this->application->setConfig(new Config());
    }

    protected function tearDown()
    {
        $this->application = null;
    }
}
