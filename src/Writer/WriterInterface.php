<?php declare(strict_types=1);
namespace Access9\DbTableDump\Writer;

/**
 * @package Access9\DbTableDump\Writer
 */
interface WriterInterface
{
    /**
     * Returns the formatted data.
     */
    public function format(): mixed;
}
