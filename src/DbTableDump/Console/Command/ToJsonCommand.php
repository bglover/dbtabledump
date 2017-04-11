<?php
namespace Access9\DbTableDump\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ToJsonCommand
 *
 * @package Access9\DbTableDump\Console\Command
 */
class ToJsonCommand extends Dump
{
    /**
     * @var array
     */
    private $validBitmask = [
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
    protected function configure()
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
            . PHP_EOL . 'See http://php.net/manual/en/json.constants.php for more information about what these do.'
        );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bitmask = 0;
        $options = $input->getOption('bitmask');
        if (!empty($options)) {
            foreach ($options as $o) {
                if (!defined($o) || !in_array(constant($o), $this->validBitmask)) {
                    throw new \InvalidArgumentException(
                        'The bitmask you gave isn\'t one of the available options.'
                    );
                }
                $bitmask |= constant($o);
            }
        }
        $results = $this->toArray($input);
        $output->writeln(json_encode($results, $bitmask, 9999));
    }
}
