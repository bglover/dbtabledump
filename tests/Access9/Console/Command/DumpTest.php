<?php
namespace Access9\Tests\Console\Command;

use Access9\Console\Command\Dump;

/**
 * Class CommandTest
 *
 * @package Access9\Tests\Console\Command
 */
class DumpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Dump
     */
    private $command;

    protected function setUp()
    {
        $this->command = new Dump('dump');
        $ref           = new \ReflectionClass($this->command);
        $method        = $ref->getMethod('configure');
        $method->setAccessible(true);
        $method->invoke($this->command);
    }

    protected function tearDown()
    {
        $this->command = null;
    }

    public function testArgumentDefinition()
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

    public function testCommandHasLimitOption()
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

    public function testCommandHasUserOption()
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

    public function testCommandHasPasswordOption()
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

    public function testCommandHasDbnameOption()
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

    public function testCommandHaHostOption()
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

    public function testToArray()
    {
        $this->markTestIncomplete('todo');
    }

    public function testGetDb()
    {
        $this->markTestIncomplete('todo');
    }
}
