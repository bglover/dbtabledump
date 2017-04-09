<?php
namespace Access9\Tests\Console\Command;

use Access9\Config;
use Access9\Console\Application;
use Access9\Console\Command\ConfigGetCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ConfigGetCommandTest
 *
 * @package Access9\Tests\Console\Command
 */
class ConfigGetCommandTest extends TestCase
{
    const COMMAND = 'config:get';

    /**
     * @var CommandTester
     */
    private $cmdTester;

    protected function setUp()
    {
        $application = new Application();
        $application->setConfig(new Config());
        $application->setAutoExit(false);
        $application->add(new ConfigGetCommand());

        $this->cmdTester = new CommandTester(
            $application->find('config:get')
        );
    }

    protected function tearDown()
    {
        $this->cmdTester = null;
    }

    /**
     * Basic test of ConfigGetCommand::execute
     */
    public function testExecute()
    {
        $this->cmdTester->execute(['command' => self::COMMAND]);

        $expected = "user: null\n"
            . "password: null\n"
            . "memory: true\n"
            . "dbname: phpunit\n"
            . "driver: pdo_sqlite\n"
            . 'host: null';
        $display  = $this->cmdTester->getDisplay(true);
        $this->assertSame($expected, trim($display));
    }

    /**
     * Basic test of ConfigGetCommand::execute with parameters
     */
    public function testExecuteWithParams()
    {
        $expected = [
            '--user'     => 'user:',
            '--password' => 'password:',
            '--dbname'   => 'dbname: phpunit',
            '--driver'   => 'driver: pdo_sqlite',
            '--host'     => 'host:'
        ];
        foreach ($expected as $cmd => $exp) {
            $this->cmdTester->execute([
                'command' => self::COMMAND,
                $cmd      => null
            ]);
            $display = $this->cmdTester->getDisplay(true);
            $this->assertSame($exp, trim($display), "I expected: '${exp}'");
        }
    }
}
