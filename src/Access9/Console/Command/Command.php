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
class Command extends sfCommand
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
                    'Space delimeted list of tables to dump.'
                ),
                new InputOption(
                    'limit',
                    'l',
                    InputOption::VALUE_OPTIONAL,
                    'Number of rows to limit the output to. This option applies to all tables dumped.'
                ),
                new InputOption(
                    'where',
                    'w',
                    InputOption::VALUE_OPTIONAL,
                    'Add a where clause to the sql. Clause must be in quotes: -w "name = \'larry\'".'
                ),
                new InputOption(
                    'user',
                    'u',
                    InputOption::VALUE_OPTIONAL,
                    'Optional username. Overrides the user setting in config.yml'
                ),
                new InputOption(
                    'password',
                    'p',
                    InputOption::VALUE_OPTIONAL,
                    'Optional password. Overrides the password setting in config.yml'
                ),
                new InputOption(
                    'dbname',
                    'db',
                    InputOption::VALUE_OPTIONAL,
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
        // Begin the base SQL.
        $sql = 'SELECT * FROM %s'
            . $this->setLimit($input->getOption('limit'))
            . $this->setWhere($input->getOption('where'));

        // Array to hold the result set.
        $results = [];

        $db = $this->getDb(
            $input->getOption('user'),
            $input->getOption('password'),
            $input->getOption('dbname')
        );
        foreach ($input->getArgument('tables') as $table) {
            // $table isn't sent to the db yet, so quoting isn't required.
            if (!$this->tableExists($table, $db->getSchemaManager())) {
                throw new \InvalidArgumentException(
                    "The database table '$table' was not found."
                );
            }

            $results[$table] = $db->fetchAll(
                sprintf($sql, $db->quoteIdentifier($table))
            );
        }

        return $results;
    }

    /**
     * Returns whether the given $table exists as a table or view in the database.
     *
     * @param string $table
     * @param \Doctrine\DBAL\Schema\AbstractSchemaManager $sm
     * @return bool
     */
    private function tableExists($table, $sm)
    {
        $hasTable = $sm->tablesExist($table);
        if ($hasTable) {
            return true;
        }

        // Provides similar functionality to Doctine's SchemaManager::tableExists method.
        $viewNames = array_map('strtolower', (array) $table);
        $hasView   = count($viewNames) == count(
            \array_intersect($viewNames, array_map('strtolower', array_keys($sm->listViews())))
        );

        return $hasView;
    }

    /**
     * Return a limit clause to be appended to the SQL.
     *
     * @param string $limit
     * @return string
     */
    private function setLimit($limit)
    {
        $limit = (int) $limit;
        if ($limit) {
            return ' LIMIT ' . $limit;
        }

        return '';
    }

    /**
     * Returns a where clause to be appended to the SQL.
     *
     * @param string $where
     * @return string
     */
    private function setWhere($where)
    {
        if ($where) {
            return ' WHERE ' . $where;
        }

        return '';
    }

    /**
     * Returns an instance of \Doctrin\DBAL\Connection.
     *
     * @param string|null $user
     * @param string|null $password
     * @param string|null $dbname
     * @return \Doctrine\DBAL\Connection
     */
    private function getDb($user = null, $password = null, $dbname = null)
    {
        if ($this->db) {
            return $this->db;
        }

        $this->db = $this->getApplication()->getConnection($user, $password, $dbname);

        return $this->db;
    }
}
