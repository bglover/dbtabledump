<?php
namespace Access9\DbTableDump\Tests\Console\Command;

use Access9\DbTableDump\Config;
use Access9\DbTableDump\Console\Application;
use Access9\DbTableDump\Console\Command\ConfigGetCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \Access9\DbTableDump\Console\Command\ConfigGetCommand
 */
class ConfigGetCommandTest extends TestCase
{
    const COMMAND = 'config:get';

    /**
     * @var CommandTester
     */
    private $cmdTester;

    protected function setUp(): void
    {
        $application = new Application();
        $application->setConfig(new Config());
        $application->setAutoExit(false);
        $application->add(new ConfigGetCommand());

        $this->cmdTester = new CommandTester(
            $application->find('config:get')
        );
    }

    protected function tearDown(): void
    {
        $this->cmdTester = null;
    }

    /**
     * @covers ::execute
     */
    public function testExecute(): void
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
     * @covers ::execute
     */
    public function testExecuteWithParams(): void
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
