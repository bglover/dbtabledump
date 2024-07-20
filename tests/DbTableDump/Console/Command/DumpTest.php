<?php declare(strict_types=1);
namespace Access9\DbTableDump\Tests\Console\Command;

use Access9\DbTableDump\Console\Command\Dump;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Access9\DbTableDump\Console\Command\Dump
 */
class DumpTest extends TestCase
{
    private ?Dump $command;

    protected function setUp(): void
    {
        $this->command = new Dump('dump');
        $ref           = new \ReflectionClass($this->command);
        $method        = $ref->getMethod('configure');
        $method->setAccessible(true);
        $method->invoke($this->command);
    }

    protected function tearDown(): void
    {
        $this->command = null;
    }

    /**
     * @covers ::getDefinition
     */
    public function testArgumentDefinition(): void
    {
        $def       = $this->command->getDefinition();
        $arguments = $def->getArguments();
        $this->assertArrayHasKey('tables', $arguments);
        $this->assertCount(1, $arguments);

        $arg = $arguments['tables'];
        $this->assertSame('Space delimited list of tables to dump.', $arg->getDescription());
        $this->assertTrue($arg->isArray());
        $this->assertTrue($arg->isRequired());
    }

    /**
     * @covers ::getDefinition
     */
    public function testCommandHasLimitOption(): void
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('limit'));

        $limit = $def->getOption('limit');
        $this->assertTrue($limit->isValueRequired());
        $this->assertSame('l', $limit->getShortcut());
        $this->assertSame(
            'Number of rows to limit the output to. This option applies to all tables dumped.',
            $limit->getDescription()
        );
        $this->assertFalse($limit->isArray());
        $this->assertFalse($limit->isValueOptional());
    }

    /**
     * @covers ::getDefinition
     */
    public function testCommandHasUserOption(): void
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('user'));

        $user = $def->getOption('user');
        $this->assertTrue($user->isValueRequired());
        $this->assertSame('u', $user->getShortcut());
        $this->assertSame(
            'Optional username. Overrides the user setting in config.yml',
            $user->getDescription()
        );
        $this->assertFalse($user->isArray());
        $this->assertFalse($user->isValueOptional());
    }

    /**
     * @covers ::getDefinition
     */
    public function testCommandHasPasswordOption(): void
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('password'));

        $password = $def->getOption('password');
        $this->assertTrue($password->isValueRequired());
        $this->assertSame('p', $password->getShortcut());
        $this->assertSame(
            'Optional password. Overrides the password setting in config.yml',
            $password->getDescription()
        );
        $this->assertFalse($password->isArray());
        $this->assertFalse($password->isValueOptional());
    }

    /**
     * @covers ::getDefinition
     */
    public function testCommandHasDbnameOption(): void
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('dbname'));

        $dbname = $def->getOption('dbname');
        $this->assertTrue($dbname->isValueRequired());
        $this->assertSame('n', $dbname->getShortcut());
        $this->assertSame(
            'Optional database name. Overrides the dbname setting in config.yml',
            $dbname->getDescription()
        );
        $this->assertFalse($dbname->isArray());
        $this->assertFalse($dbname->isValueOptional());
    }

    /**
     * @covers ::getDefinition
     */
    public function testCommandHaHostOption(): void
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('host'));

        $dbname = $def->getOption('host');
        $this->assertTrue($dbname->isValueRequired());
        $this->assertSame('o', $dbname->getShortcut());
        $this->assertSame(
            'Optional host. Overrides the host setting in config.yml',
            $dbname->getDescription()
        );
        $this->assertFalse($dbname->isArray());
        $this->assertFalse($dbname->isValueOptional());
    }

    /**
     * @covers ::toArray
     */
    public function testToArray(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @covers ::getDb
     */
    public function testGetDb(): void
    {
        $this->markTestIncomplete();
    }
}
