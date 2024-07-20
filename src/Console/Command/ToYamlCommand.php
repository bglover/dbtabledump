<?php declare(strict_types=1);
namespace Access9\DbTableDump\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Access9\DbTableDump\Console\Command
 */
class ToYamlCommand extends Dump
{
    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->setName('to:yaml')
            ->setDescription('Dump one or more database tables to yaml format.');
        parent::configure();
    }

    /**
     * {@inheritdoc}
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $results = $this->toArray($input);
        $output->writeln(Yaml::dump($results, 4, 2));

        return 0;
    }
}
