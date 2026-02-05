<?php declare(strict_types=1);
namespace Access9\DbTableDump\Writer;

/**
 * @package Access9\DbTableDump\Writer
 */
final class DelimitedWriter implements WriterInterface
{
    private const string QUOTE = '"';

    private readonly string $delimiter;

    public function __construct(
        private readonly array $data,
        string $delimiter,
        private readonly bool $quote = false
    ) {
        $this->delimiter = $this->convertLiteralTab($delimiter);
    }

    #[\Override]
    public function format(): array
    {
        $data = [];
        foreach ($this->data as $table => $rows) {
            if (!isset($data[$table])) {
                $data[$table] = '';
            }

            // Set the table headers by returning the keys of the first index of the array.
            $data[$table] .= $this->getHeader(array_keys(reset($rows))) . PHP_EOL;

            // Add the rows.
            foreach ($rows as $row) {
                // Only the values matter.
                $row = array_values($row);
                if ($this->quote) {
                    $row = $this->quoteFields($row);
                }
                $data[$table] .= implode($this->delimiter, $row) . PHP_EOL;
            }

            // Remove the last EOL.
            $data[$table] = trim($data[$table]);
        }

        return $data;
    }

    /**
     * Return the formatted headers.
     */
    protected function getHeader(array $headers): string
    {
        if ($this->quote) {
            $headers = $this->quoteFields($headers);
        }

        return implode($this->delimiter, $headers);
    }

    /**
     * Surrounds the values in $fields with double quotes.
     */
    protected function quoteFields(array $fields): array
    {
        foreach ($fields as &$field) {
            $field = self::QUOTE . $field . self::QUOTE;
        }

        return $fields;
    }

    /**
     * Convert literal tab "\t" to it real value.
     */
    protected function convertLiteralTab(string $delimiter): string
    {
        if ($delimiter === '\t') {
            $delimiter = "\t";
        }

        return $delimiter;
    }
}
