<?php
namespace Access9\DbTableDump\Writer;

/**
 * Interface WriterInterface
 *
 * @package Access9\DbTableDump\Writer
 */
interface WriterInterface
{
    /**
     * Returns the formatted data.
     *
     * @return mixed
     */
    public function format();
}
