<?php
namespace Access9\Console\Command;

use Access9\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class DumpTableCommand
 *
 * @package Access9\Console\Command
 */
class DumpYamlCommand extends ConsoleCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('dump:yaml')
            ->setDescription('Dumps a database table to yaml format.');
        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $results = $this->toArray($input);
        $output->writeln(Yaml::dump($results, 4, 2));
    }
}
