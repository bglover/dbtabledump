<?php
namespace Access9\DbTableDump\Console\Command;

use Access9\DbTableDump\Writer\DelimitedWriter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ToDelimitedCommand
 *
 * @package Access9\DbTableDump\Console\Command
 */
class ToDelimitedCommand extends Dump
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('to:delimited')
            ->setDescription('Dump one or more database tables to a delimited format.')
            ->setHelp(
                'The --delimiter option is required. '
                . 'You can use tabs as your delimiter by passing "\t" as the delimiter.'
            );
        parent::configure();
        $this->getDefinition()
            ->addOptions([
                new InputOption(
                    'delimiter',
                    'd',
                    InputOption::VALUE_REQUIRED,
                    'Required delimiter to use. Enclose delimiter in quotes. --delimiter "|"'
                ),
                new InputOption(
                    'quote',
                    'q',
                    InputOption::VALUE_NONE,
                    'Quote each delimited column: \'"column_one","column_two"\', etc'
                )
            ]);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $delimiter = $input->getOption('delimiter');

        if (empty($delimiter)) {
            throw new \InvalidArgumentException('--delimiter is required');
        }

        $results = $this->toArray($input);
        $writer  = new DelimitedWriter($results, $delimiter, $input->getOption('quote'));
        $output->writeln($writer->format());
    }

    /**
     * @param array  $results
     * @param string $delimiter
     * @param bool   $quote
     * @return DelimitedWriter
     */
    protected function formatResults(array $results, $delimiter, $quote = false)
    {
        return new DelimitedWriter($results, $delimiter, $quote);
    }
}
