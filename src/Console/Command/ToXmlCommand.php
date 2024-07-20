<?php declare(strict_types=1);
namespace Access9\DbTableDump\Console\Command;

use Access9\DbTableDump\Writer\XmlWriter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Access9\DbTableDump\Console\Command
 */
class ToXmlCommand extends Dump
{
    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->setName('to:xml')
            ->setDescription('Dump one or more database tables to xml.')
            ->setHelp(
                'Column names that contain spaces will have the spaces converted to underscores.'
            );
        parent::configure();
    }

    /**
     * {@inheritdoc}
     * @throws \Doctrine\DBAL\Exception
     * @throws \DOMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $results = $this->toArray($input);
        $writer  = new XmlWriter($results);
        $output->writeln($writer->format());

        return 0;
    }
}
