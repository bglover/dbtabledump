<?php declare(strict_types=1);
namespace Access9\DbTableDump;

use Access9\DbTableDump\Console\Application;
use Access9\DbTableDump\Console\Command\ConfigGetCommand;
use Access9\DbTableDump\Console\Command\ConfigSetCommand;
use Access9\DbTableDump\Console\Command\ToDelimitedCommand;
use Access9\DbTableDump\Console\Command\ToJsonCommand;
use Access9\DbTableDump\Console\Command\ToXmlCommand;
use Access9\DbTableDump\Console\Command\ToYamlCommand;

/**
 * @package Access9\DbTableDump
 */
final class Dump
{
    /**
     * Main runner to keep dump simple.
     *
     * @throws \Access9\DbTableDump\FileNotWritableException
     * @throws \Exception
     */
    public static function run(string $configPath): void
    {
        $app = new Application(new Config($configPath));
        $app->addCommands([
            new ConfigGetCommand(),
            new ConfigSetCommand(),
            new ToJsonCommand(),
            new ToYamlCommand(),
            new ToDelimitedCommand(),
            new ToXmlCommand()
        ]);

        $app->run();
    }
}
