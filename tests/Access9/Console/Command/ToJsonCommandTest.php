<?php
namespace Access9\Tests\Console\Command;

use Access9\Config;
use Access9\Console\Application;
use Access9\Console\Command\ToJsonCommand;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ToJsonCommandTest
 *
 * @package Access9\Tests\Console\Command
 */
class ToJsonCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic test of ToJsonCommad::execute
     */
    public function testExecute()
    {
        $application = $this->getApplication();
        $application->add(new ToJsonCommand());

        $command       = $application->find('to:json');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'tables'  => ['phpunit']
        ]);

        $expected = '{"phpunit":[{"id":"1","name":"sweet"},{"id":"2","name":"cheeks"}]}';
        $display  = $commandTester->getDisplay(true);
        $this->assertSame($expected, trim($display));
    }

    /**
     * Returns an instance of Application.
     *
     * @return Application
     */
    private function getApplication()
    {
        $application = new Application();
        $application->setConfig(new Config());
        $application->setAutoExit(false);
        $this->createTestDb($application);
        return $application;
    }

    /**
     * Create the test database.
     *
     * @param Application $application
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createTestDb(Application $application)
    {
        $db = $application->getConnection();
        $db->getSchemaManager()->createTable(new Table(
            'phpunit',
            [
                new Column('id', Type::getType(Type::INTEGER)),
                new Column('name', Type::getType(Type::TEXT))
            ]
        ));
        $db->insert('phpunit', ['id' => 1, 'name' => 'sweet']);
        $db->insert('phpunit', ['id' => 2, 'name' => 'cheeks']);
    }
}
