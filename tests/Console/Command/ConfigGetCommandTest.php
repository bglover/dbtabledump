<?php declare(strict_types=1);
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
    private const COMMAND = 'config:get';

    private ?CommandTester $cmdTester;

    /**
     * @throws \Access9\DbTableDump\FileNotWritableException
     */
    protected function setUp(): void
    {
        $application = new Application();
        $application->setConfig(new Config(dirname(__DIR__, 3) . '/config'));
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

        $expected = <<<EXPECTED
            user: null
            password: null
            memory: true
            dbname: phpunit
            driver: pdo_sqlite
            host: null
            EXPECTED;
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
            $this->assertSame($exp, trim($display), "I expected: '{$exp}'");
        }
    }
}
