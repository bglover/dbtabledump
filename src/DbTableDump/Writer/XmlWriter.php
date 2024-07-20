<?php declare(strict_types=1);
namespace Access9\DbTableDump\Writer;

/**
 * @package Access9\DbTableDump\Writer
 */
class XmlWriter implements WriterInterface
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Format the data.
     *
     * @throws \DOMException
     */
    public function format(): string
    {
        $xml               = new \DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        foreach ($this->data as $tableName => $rows) {
            $table = $xml->createElement($tableName);
            foreach ($rows as $columns) {
                $row = $xml->createElement('row');
                foreach ($columns as $name => $value) {
                    if (str_contains($name, ' ')) {
                        $name = str_replace(' ', '_', $name);
                    }

                    $column = $xml->createElement($name, (string) $value);
                    $row->appendChild($column);
                    $column = null;
                }
                $table->appendChild($row);
                $row = null;
            }
            $xml->appendChild($table);
            $table = null;
        }

        return $xml->saveXML(null, \LIBXML_NOEMPTYTAG);
    }
}
