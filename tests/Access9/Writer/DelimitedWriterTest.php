<?php
namespace Access9\Tests\Writer;

use Access9\Writer\DelimitedWriter;

/**
 * Class DelimitedWriterTest
 *
 * @package Access9\Tests\Writer
 */
class DelimitedWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $writer = new DelimitedWriter($this->getArray(), '|');
        $expected = [
            'store' => 'store_id|manager_staff_id|address_id|last_update'
                    .  PHP_EOL
                    .  '1|1|1|2006-02-15 04:57:12'
                    .  PHP_EOL
                    .  '2|2|2|2006-02-15 04:57:12'
        ];
        $this->assertSame($expected, $writer->format());
    }

    public function testFormatQuoted()
    {
        $writer = new DelimitedWriter($this->getArray(), '|', true);
        $expected = [
            'store' => '"store_id"|"manager_staff_id"|"address_id"|"last_update"'
                    .  PHP_EOL
                    .  '"1"|"1"|"1"|"2006-02-15 04:57:12"'
                    .  PHP_EOL
                    .  '"2"|"2"|"2"|"2006-02-15 04:57:12"'
        ];
        $this->assertSame($expected, $writer->format());
    }

    public function testFormatWithLiteralTab()
    {
        $writer = new DelimitedWriter($this->getArray(), '\t');
        $expected = [
            'store' => 'store_id	manager_staff_id	address_id	last_update'
                    .  PHP_EOL
                    .  '1	1	1	2006-02-15 04:57:12'
                    .  PHP_EOL
                    .  '2	2	2	2006-02-15 04:57:12'
        ];
        $this->assertSame($expected, $writer->format());
    }

    public function testFormatWithLiteralTabQuoted()
    {
        $writer = new DelimitedWriter($this->getArray(), '\t', true);
        $expected = [
            'store' => '"store_id"	"manager_staff_id"	"address_id"	"last_update"'
                    .  PHP_EOL
                    .  '"1"	"1"	"1"	"2006-02-15 04:57:12"'
                    .  PHP_EOL
                    .  '"2"	"2"	"2"	"2006-02-15 04:57:12"'
        ];
        $this->assertSame($expected, $writer->format());
    }

    /**
     * @return array
     */
    private function getArray()
    {
        return [
            'store' => [
                0 => [
                    'store_id'         => '1',
                    'manager_staff_id' => '1',
                    'address_id'       => '1',
                    'last_update'      => '2006-02-15 04:57:12'
                ],
                1 => [
                    'store_id'         => '2',
                    'manager_staff_id' => '2',
                    'address_id'       => '2',
                    'last_update'      => '2006-02-15 04:57:12'
                ]
            ]
        ];
    }
}
