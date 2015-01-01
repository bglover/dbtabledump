<?php
namespace Access9\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ToYamlCommand
 *
 * @package Access9\Console\Command
 */
class ToYamlCommand extends Dump
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('to:yaml')
            ->setDescription('Dump one or more database tables to yaml format.');
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
