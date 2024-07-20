<?php
namespace Access9\DbTableDump;

use Access9\DbTableDump\Console\Application;
use Access9\DbTableDump\Console\Command\ConfigGetCommand;
use Access9\DbTableDump\Console\Command\ConfigSetCommand;
use Access9\DbTableDump\Console\Command\ToDelimitedCommand;
use Access9\DbTableDump\Console\Command\ToJsonCommand;
use Access9\DbTableDump\Console\Command\ToXmlCommand;
use Access9\DbTableDump\Console\Command\ToYamlCommand;

/**
 * Class Dump
 *
 * @package Access9\DbTableDump
 */
class Dump
{
    /**
     * Main runner to keep dump simple.
     */
    public static function run(): void
    {
        $app = new Application();
        $app->addCommands([
            new ConfigGetCommand(),
            new ConfigSetCommand(),
            new ToJsonCommand(),
            new ToYamlCommand(),
            new ToDelimitedCommand(),
            new ToXmlCommand()
        ]);

        $app->setConfig(new Config());

        $app->run();
    }
}
