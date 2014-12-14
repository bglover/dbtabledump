<?php
namespace Access9\Tests\Console\Command;

use Access9\Console\Command\Command;

/**
 * Class CommandTest
 *
 * @package Access9\Tests\Console\Command
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Command
     */
    private $command;

    public function testArgumentDefinition()
    {
        $def       = $this->command->getDefinition();
        $arguments = $def->getArguments();
        $this->assertArrayHasKey('tables', $arguments);
        $this->assertCount(1, $arguments);

        $arg = $arguments['tables'];
        $this->assertSame('Space delimeted list of tables to dump.', $arg->getDescription());
        $this->assertTrue($arg->isArray());
        $this->assertTrue($arg->isRequired());
    }

    public function testCommandHasLimitOption()
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('limit'));

        $limit = $def->getOption('limit');
        $this->assertFalse($limit->isValueRequired());
        $this->assertSame('l', $limit->getShortcut());
        $this->assertSame(
            'Number of rows to limit the output to. This option applies to all tables dumped.',
            $limit->getDescription()
        );
        $this->assertFalse($limit->isArray());
        $this->assertTrue($limit->isValueOptional());
    }

    public function testCommandHasUserOption()
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('user'));

        $user = $def->getOption('user');
        $this->assertFalse($user->isValueRequired());
        $this->assertSame('u', $user->getShortcut());
        $this->assertSame(
            'Optional username. Overrides the user setting in config.yml',
            $user->getDescription()
        );
        $this->assertFalse($user->isArray());
        $this->assertTrue($user->isValueOptional());
    }

    public function testCommandHasPasswordOption()
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('password'));

        $password = $def->getOption('password');
        $this->assertFalse($password->isValueRequired());
        $this->assertSame('p', $password->getShortcut());
        $this->assertSame(
            'Optional password. Overrides the password setting in config.yml',
            $password->getDescription()
        );
        $this->assertFalse($password->isArray());
        $this->assertTrue($password->isValueOptional());
    }

    public function testCommandHasDbnameOption()
    {
        $def = $this->command->getDefinition();
        $this->assertTrue($def->hasOption('dbname'));

        $dbname = $def->getOption('dbname');
        $this->assertFalse($dbname->isValueRequired());
        $this->assertSame('db', $dbname->getShortcut());
        $this->assertSame(
            'Optional database name. Overrides the dbname setting in config.yml',
            $dbname->getDescription()
        );
        $this->assertFalse($dbname->isArray());
        $this->assertTrue($dbname->isValueOptional());
    }

    public function testToArray()
    {
        $this->markTestIncomplete('todo');
    }

    public function testSetLimit()
    {
        $method = new \ReflectionMethod($this->command, 'setLimit');
        $method->setAccessible(true);

        $this->assertEmpty($method->invokeArgs($this->command, ['']));
        $this->assertSame(' LIMIT 1', $method->invokeArgs($this->command, ['1']));
    }

    public function testGetDb()
    {
        $this->markTestIncomplete('todo');
    }

    protected function setUp()
    {
        $this->command = new Command('dump');
        $ref           = new \ReflectionClass($this->command);
        $method        = $ref->getMethod('configure');
        $method->setAccessible(true);
        $method->invoke($this->command);
    }

    protected function tearDown()
    {
        $this->command = null;
    }
}
