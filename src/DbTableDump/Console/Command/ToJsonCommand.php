<?php declare(strict_types=1);
namespace Access9\DbTableDump\Console\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Access9\DbTableDump\Console\Command
 */
class ToJsonCommand extends Dump
{
    private const VALID_BITMASKS = [
        JSON_HEX_QUOT,
        JSON_HEX_TAG,
        JSON_HEX_AMP,
        JSON_HEX_APOS,
        JSON_NUMERIC_CHECK,
        JSON_PRETTY_PRINT,
        JSON_UNESCAPED_SLASHES,
        JSON_FORCE_OBJECT,
        JSON_UNESCAPED_UNICODE
    ];

    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this->setName('to:json')
            ->setDescription('Dump one or more database tables to json format.');
        parent::configure();
        $this->getDefinition()->addOption(
            new InputOption(
                'bitmask',
                'b',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Bitmask to use. May be one or more of JSON_* constants' . PHP_EOL
                . 'Usage example: `dump to:json -b JSON_PRETTY_PRINT -b JSON_UNESCAPED_SLASHES table`'
            )
        );
        $this->setHelp(
            'Available JSON constants are: '
            . 'JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, '
            . 'JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT and JSON_UNESCAPED_UNICODE.'
            . PHP_EOL . 'See https://php.net/manual/en/json.constants.php for more information about what these do.'
        );
    }

    /**
     * {@inheritdoc}
     * @throws \Doctrine\DBAL\Exception
     * @throws \JsonException
     * @throws InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bitmask = 0;
        $options = $input->getOption('bitmask');
        if (!empty($options)) {
            foreach ($options as $o) {
                if (!defined($o) || !in_array(constant($o), self::VALID_BITMASKS, true)) {
                    throw new InvalidArgumentException(
                        'The bitmask you gave isn\'t one of the available options.'
                    );
                }
                $bitmask |= constant($o);
            }
        }
        $results = $this->toArray($input);
        $output->writeln(json_encode($results, JSON_THROW_ON_ERROR | $bitmask, 9999));

        return 0;
    }
}
