<?php declare(strict_types=1);
namespace Access9\DbTableDump\Console\Command;

use Access9\DbTableDump\Console\Application;
use Symfony\Component\Console\Command\Command as sfCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Access9\DbTableDump\Console\Command
 * @method Application getApplication
 */
class ConfigGetCommand extends sfCommand
{
    /**
     * Common configuration arguments.
     */
    protected function configure(): void
    {
        $this->setName('config:get')
            ->setHelp('If no options are given, the entire config is printed.')
            ->setDescription('Get a configuration value. If no options are given, the entire config is printed.')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('user', 'u'),
                    new InputOption('password', 'p'),
                    // -o doesn't make sense.
                    new InputOption('host', 'o'),
                    new InputOption('dbname', 'n'),
                    new InputOption('driver', 'd')
                ])
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $out = [];

        $config = $this->getApplication()->getConfig();
        if ($input->getOption('user')) {
            $out[] = 'user: ' . $config->user;
        }

        if ($input->getOption('password')) {
            $out[] = 'password: ' . $config->password;
        }

        if ($input->getOption('host')) {
            $out[] = 'host: ' . $config->host;
        }

        if ($input->getOption('dbname')) {
            $out[] = 'dbname: ' . $config->dbname;
        }

        if ($input->getOption('driver')) {
            $out[] = 'driver: ' . $config->driver;
        }

        if (!empty($out)) {
            $output->writeln($out);
        } else {
            $output->writeln($config->__toString());
        }

        return 0;
    }
}
