<?php
namespace Access9\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigCommand
 *
 * @package Access9\Console\Command
 */
class ConfigSetCommand extends Config
{
    /**
     * Common configuration arguments.
     */
    protected function configure()
    {
        $this->setName('config:set')
            ->setDescription('Set a configuration value.');
        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
