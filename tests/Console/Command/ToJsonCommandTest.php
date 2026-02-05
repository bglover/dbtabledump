<?php declare(strict_types=1);
namespace Access9\DbTableDump\Tests\Console\Command;

use Access9\DbTableDump\Config;
use Access9\DbTableDump\Console\Application;
use Access9\DbTableDump\Console\Command\ToJsonCommand;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \Access9\DbTableDump\Console\Command\ToJsonCommand
 */
class ToJsonCommandTest extends TestCase
{
    /**
     * Basic test of ToJsonCommand::execute
     * @covers ::execute
     */
    public function testExecute(): void
    {
        $application = $this->getApplication();
        $application->add(new ToJsonCommand());

        $command       = $application->find('to:json');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'tables'  => ['phpunit']
        ]);

        $expected = '{"phpunit":[{"id":1,"name":"sweet"},{"id":2,"name":"cheeks"}]}';
        $display  = $commandTester->getDisplay(true);
        $this->assertSame($expected, trim($display));
    }

    /**
     * Returns an instance of Application.
     *
     * @throws \Access9\DbTableDump\FileNotWritableException
     * @throws \Doctrine\DBAL\Exception
     */
    private function getApplication(): Application
    {
        $application = new Application(new Config(dirname(__DIR__, 3) . '/config'));
        $application->setAutoExit(false);
        $this->createTestDb($application);

        return $application;
    }

    /**
     * Create the test database.
     *
     * @throws \Doctrine\DBAL\Exception
     */
    private function createTestDb(Application $application): void
    {
        $db = $application->getConnection();
        $db->createSchemaManager()->createTable(new Table(
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
