<?php
namespace Access9\DbTableDump\Tests\Console\Command;

use Access9\DbTableDump\Config;
use Access9\DbTableDump\Console\Application;
use Access9\DbTableDump\Console\Command\ToYamlCommand;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \Access9\DbTableDump\Console\Command\ToYamlCommand
 */
class ToYamlCommandTest extends TestCase
{
    /**
     * @covers ::execute
     */
    public function testExecute(): void
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
    private function getApplication(): Application
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
     */
    private function createTestDb(Application $application): void
    {
        $db = $application->getConnection();
        $db->getSchemaManager()->createTable(new Table(
            'phpunit',
            [
                new Column('id', Type::getType(Types::INTEGER)),
                new Column('name', Type::getType(Types::TEXT))
            ]
        ));
        $db->insert('phpunit', ['id' => 1, 'name' => 'sweet']);
        $db->insert('phpunit', ['id' => 2, 'name' => 'cheeks']);
    }
}
