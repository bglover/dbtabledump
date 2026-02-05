<?php declare(strict_types=1);
namespace Access9\DbTableDump\Tests\Console\Command;

use Access9\DbTableDump\Console\Command\ConfigSetCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigSetCommand::class)]
#[CoversMethod(ConfigSetCommand::class, 'execute')]
class ConfigSetCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $this->markTestIncomplete();
    }
}
