<?php
namespace Access9\Console\Command;

use Symfony\Component\Console\Command\Command as sfCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Command
 *
 * @package Access9\Console
 */
class Dump extends sfCommand
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * Common configuration arguments.
     */
    protected function configure()
    {
        $this->setDefinition(
            new InputDefinition([
                new InputArgument(
                    'tables',
                    InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                    'Space delimited list of tables to dump.'
                ),
                new InputOption(
                    'limit',
                    'l',
                    InputOption::VALUE_REQUIRED,
                    'Number of rows to limit the output to. This option applies to all tables dumped.'
                ),
                new InputOption(
                    'where',
                    'w',
                    InputOption::VALUE_REQUIRED,
                    'Add a where clause to the sql. Clause must be in quotes: -w "name = \'larry\'".'
                ),
                new InputOption(
                    'user',
                    'u',
                    InputOption::VALUE_REQUIRED,
                    'Optional username. Overrides the user setting in config.yml'
                ),
                new InputOption(
                    'password',
                    'p',
                    InputOption::VALUE_REQUIRED,
                    'Optional password. Overrides the password setting in config.yml'
                ),
                new InputOption(
                    'host',
                    'o',
                    InputOption::VALUE_REQUIRED,
                    'Optional host. Overrides the host setting in config.yml'
                ),
                new InputOption(
                    'dbname',
                    'n',
                    InputOption::VALUE_REQUIRED,
                    'Optional database name. Overrides the dbname setting in config.yml'
                )
            ])
        );
    }

    /**
     * Transform the table output to an array.
     *
     * @param InputInterface $input
     * @return array
     */
    protected function toArray(InputInterface $input)
    {
        // Array to hold the result set.
        $results = [];

        $db = $this->getDb(
            $input->getOption('user'),
            $input->getOption('password'),
            $input->getOption('host'),
            $input->getOption('dbname')
        );

        // Get the query builder and begin the select.
        $qb = $db->createQueryBuilder()->select('*');

        if ($input->getOption('where')) {
            $qb->where($input->getOption('where'));
        }

        if ($input->getOption('limit')) {
            $qb->setMaxResults($input->getOption('limit'));
        }

        foreach ($input->getArgument('tables') as $table) {
            $tableBuilder = clone $qb;
            $results[$table] = $tableBuilder
                ->from($table)
                ->execute()
                ->fetchAll();
        }

        return $results;
    }

    /**
     * Returns an instance of \Doctrine\DBAL\Connection.
     *
     * @param string|null $user
     * @param string|null $password
     * @param string|null $host
     * @param string|null $dbname
     * @return \Doctrine\DBAL\Connection
     */
    private function getDb($user = null, $password = null, $host = null, $dbname = null)
    {
        if ($this->db) {
            return $this->db;
        }

        $this->db = $this->getApplication()->getConnection($user, $password, $host, $dbname);

        return $this->db;
    }
}
