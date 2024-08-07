<?php declare(strict_types=1);
namespace Access9\DbTableDump\Tests\Writer;

use Access9\DbTableDump\Writer\XmlWriter;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Access9\DbTableDump\Writer\XmlWriter
 */
class XmlWriterTest extends TestCase
{
    /**
     * @covers ::format
     */
    public function testFormat(): void
    {
        $writer   = new XmlWriter($this->getArray());
        $expected = '<?xml version="1.0" encoding="utf-8"?>
<store>
  <row>
    <store_id>1</store_id>
    <manager_staff_id>1</manager_staff_id>
    <address_id>1</address_id>
    <last_update>2006-02-15 04:57:12</last_update>
  </row>
  <row>
    <store_id>2</store_id>
    <manager_staff_id>2</manager_staff_id>
    <address_id>2</address_id>
    <last_update>2006-02-15 04:57:12</last_update>
  </row>
</store>
';
        $this->assertSame($expected, $writer->format());
    }

    /**
     * @covers ::format
     */
    public function testFormatMultipleTables(): void
    {
        $writer   = new XmlWriter($this->getArray(true));
        $expected = '<?xml version="1.0" encoding="utf-8"?>
<store>
  <row>
    <store_id>1</store_id>
    <manager_staff_id>1</manager_staff_id>
    <address_id>1</address_id>
    <last_update>2006-02-15 04:57:12</last_update>
  </row>
  <row>
    <store_id>2</store_id>
    <manager_staff_id>2</manager_staff_id>
    <address_id>2</address_id>
    <last_update>2006-02-15 04:57:12</last_update>
  </row>
</store>
<staff_list>
  <row>
    <ID>1</ID>
    <name>Mike Hillyer</name>
    <address>23 Workhaven Lane</address>
    <zip_code></zip_code>
    <phone>14033335568</phone>
    <city>Lethbridge</city>
    <country>Canada</country>
    <SID>1</SID>
  </row>
  <row>
    <ID>2</ID>
    <name>Jon Stephens</name>
    <address>1411 Lillydale Drive</address>
    <zip_code></zip_code>
    <phone>6172235589</phone>
    <city>Woodridge</city>
    <country>Australia</country>
    <SID>2</SID>
  </row>
</staff_list>
';
        $this->assertSame($expected, $writer->format());
    }

    private function getArray(bool $multi = false): array
    {
        $return = [
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

        if ($multi) {
            $return['staff_list'] = [
                0 => [
                    'ID'       => '1',
                    'name'     => 'Mike Hillyer',
                    'address'  => '23 Workhaven Lane',
                    'zip code' => '',
                    'phone'    => '14033335568',
                    'city'     => 'Lethbridge',
                    'country'  => 'Canada',
                    'SID'      => '1'
                ],
                1 => [
                    'ID'       => '2',
                    'name'     => 'Jon Stephens',
                    'address'  => '1411 Lillydale Drive',
                    'zip code' => '',
                    'phone'    => '6172235589',
                    'city'     => 'Woodridge',
                    'country'  => 'Australia',
                    'SID'      => '2'
                ]
            ];
        }

        return $return;
    }
}
