<?php
namespace Access9\Writer;

/**
 * Class XmlWriter
 *
 * @package Access9\Writer
 */
class XmlWriter implements WriterInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Format the data.
     *
     * @return string
     */
    public function format()
    {
        $xml               = new \DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        foreach ($this->data as $tableName => $rows) {
            $table = $xml->createElement($tableName);
            foreach ($rows as $columns) {
                $row = $xml->createElement('row');
                foreach ($columns as $name => $value) {
                    if (false !== strpos($name, ' ')) {
                        $name = str_replace(' ', '_', $name);
                    }

                    $column = $xml->createElement($name, $value);
                    $row->appendChild($column);
                    $column = null;
                }
                $table->appendChild($row);
                $row = null;
            }
            $xml->appendChild($table);
            $table = null;
        }

        return $xml->saveXML();
    }
}
