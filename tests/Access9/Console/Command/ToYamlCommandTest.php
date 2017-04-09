<?php
namespace Access9\Tests\Console\Command;

use Access9\Config;
use Access9\Console\Application;
use Access9\Console\Command\ToYamlCommand;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class ToYamlCommandTest
 *
 * @package Access9\Tests\Console\Command
 */
class ToYamlCommandTest extends TestCase
{
    /**
     * Basic test of ToYamlCommand::execute
     */
    public function testExecute()
    {
        $application = $this->getApplication();
        $application->add(new ToYamlCommand());

        $command       = $application->find('to:yaml');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'tables'  => ['phpunit']
        ]);

        $expected = "phpunit:\n  -\n    id: '1'\n    name: sweet\n  -\n    id: '2'\n    name: cheeks";
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
