<?php declare(strict_types=1);
namespace Access9\DbTableDump\Console\Command;

use Access9\DbTableDump\Writer\DelimitedWriter;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Access9\DbTableDump\Console\Command
 */
final class ToDelimitedCommand extends Dump
{
    #[\Override]
    protected function configure(): void
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
     * @throws \Doctrine\DBAL\Exception
     * @throws InvalidArgumentException
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $delimiter = $input->getOption('delimiter');

        if (empty($delimiter)) {
            throw new InvalidArgumentException('--delimiter is required');
        }

        $results = $this->toArray($input);
        $writer  = new DelimitedWriter($results, $delimiter, $input->getOption('quote'));
        $output->writeln($writer->format());

        return 0;
    }
}
