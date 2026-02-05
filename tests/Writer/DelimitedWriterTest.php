<?php declare(strict_types=1);
namespace Access9\DbTableDump\Tests\Writer;

use Access9\DbTableDump\Writer\DelimitedWriter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(DelimitedWriter::class)]
#[CoversMethod(DelimitedWriter::class, 'format')]
class DelimitedWriterTest extends TestCase
{
    public function testFormat(): void
    {
        $writer   = new DelimitedWriter($this->getArray(), '|');
        $expected = [
            'store' => 'store_id|manager_staff_id|address_id|last_update'
                    .  PHP_EOL
                    .  '1|1|1|2006-02-15 04:57:12'
                    .  PHP_EOL
                    .  '2|2|2|2006-02-15 04:57:12'
        ];
        $this->assertSame($expected, $writer->format());
    }

    public function testFormatQuoted(): void
    {
        $writer   = new DelimitedWriter($this->getArray(), '|', true);
        $expected = [
            'store' => '"store_id"|"manager_staff_id"|"address_id"|"last_update"'
                    .  PHP_EOL
                    .  '"1"|"1"|"1"|"2006-02-15 04:57:12"'
                    .  PHP_EOL
                    .  '"2"|"2"|"2"|"2006-02-15 04:57:12"'
        ];
        $this->assertSame($expected, $writer->format());
    }

    public function testFormatWithLiteralTab(): void
    {
        $writer   = new DelimitedWriter($this->getArray(), '\t');
        $expected = [
            'store' => 'store_id	manager_staff_id	address_id	last_update'
                    .  PHP_EOL
                    .  '1	1	1	2006-02-15 04:57:12'
                    .  PHP_EOL
                    .  '2	2	2	2006-02-15 04:57:12'
        ];
        $this->assertSame($expected, $writer->format());
    }

    public function testFormatWithLiteralTabQuoted(): void
    {
        $writer   = new DelimitedWriter($this->getArray(), '\t', true);
        $expected = [
            'store' => '"store_id"	"manager_staff_id"	"address_id"	"last_update"'
                    .  PHP_EOL
                    .  '"1"	"1"	"1"	"2006-02-15 04:57:12"'
                    .  PHP_EOL
                    .  '"2"	"2"	"2"	"2006-02-15 04:57:12"'
        ];
        $this->assertSame($expected, $writer->format());
    }

    private function getArray(): array
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
