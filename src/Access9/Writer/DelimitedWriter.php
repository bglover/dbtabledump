<?php
namespace Access9\Writer;

/**
 * Class DelimitedWriter
 *
 * @package Access9\Writer
 */
class DelimitedWriter implements WriterInterface
{
    const QUOTE = '"';

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var bool
     */
    private $quote;

    /**
     * @param array  $data
     * @param string $delimiter
     * @param bool   $quote
     */
    public function __construct(array $data, $delimiter, $quote = false)
    {
        $this->data      = $data;
        $this->delimiter = $this->convertLiteralTab($delimiter);
        $this->quote     = $quote;
    }

    /**
     * @return array
     */
    public function format()
    {
        $data = [];
        foreach ($this->data as $table => $rows) {
            /** @noinspection UnSafeIsSetOverArrayInspection */
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
     *
     * @param array $headers
     * @return string
     */
    protected function getHeader(array $headers)
    {
        if ($this->quote) {
            $headers = $this->quoteFields($headers);
        }

        return implode($this->delimiter, $headers);
    }

    /**
     * Surrounds the values in $fields with double quotes.
     *
     * @param array $fields
     * @return array
     */
    protected function quoteFields(array $fields)
    {
        foreach ($fields as &$field) {
            $field = self::QUOTE . $field . self::QUOTE;
        }

        return $fields;
    }

    /**
     * Convert literal tab "\t" to it real value.
     *
     * @param string $delimiter
     * @return string
     */
    protected function convertLiteralTab($delimiter)
    {
        if ($delimiter === '\t') {
            $delimiter = "\t";
        }

        return $delimiter;
    }
}
