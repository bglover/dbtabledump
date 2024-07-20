<?php declare(strict_types=1);
namespace Access9\DbTableDump\Tests;

use Access9\DbTableDump\Config;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @package Access9\DbTableDump\Tests\Console
 * @coversDefaultClass \Access9\DbTableDump\Config
 */
class ConfigTest extends TestCase
{
    private ?Config $config;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->config = new Config(dirname(__DIR__, 1) . '/config');
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        $this->config = null;
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(Config::class, $this->config);
    }

    /**
     * @covers ::toArray
     */
    public function testGetConfig(): void
    {
        $expected = [
            'user'     => null,
            'password' => null,
            'memory'   => true,
            'dbname'   => 'phpunit',
            'driver'   => 'pdo_sqlite',
            'host'     => null
        ];
        $this->assertSame($expected, $this->config->toArray());
    }

    /**
     * @covers ::toArray
     */
    public function testGetConfigWithParams(): void
    {
        $expected = [
            'user'     => 'user-param',
            'password' => 'password-param',
            'memory'   => true,
            'dbname'   => 'dbname-param',
            'driver'   => 'pdo_sqlite',
            'host'     => 'host-param'
        ];
        $this->assertSame(
            $expected,
            $this->config->toArray('user-param', 'password-param', 'host-param', 'dbname-param')
        );
    }

    /**
     * @covers ::__set
     */
    public function test__set(): void
    {
        $expected           = 'Boogers';
        $this->config->user = $expected;

        $prop = (new \ReflectionClass($this->config))->getProperty('config');
        $prop->setAccessible(true);
        $this->assertSame($expected, $prop->getValue($this->config)['user']);
    }

    public function test__setThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Attempting to set a non-existent configuration key');
        $this->config->randomunknownprop = 'this is totally going to throw an exception';
    }

    /**
     * @covers ::__get
     */
    public function test__get(): void
    {
        $this->assertNull($this->config->user);
        $this->assertNull($this->config->password);
        $this->assertTrue($this->config->memory);
        $this->assertSame($this->config->dbname, 'phpunit');
        $this->assertSame($this->config->driver, 'pdo_sqlite');
    }

    /**
     * @covers ::__isset
     */
    public function test__isset(): void
    {
        // These two are false because the config for phpunit doesn't define these.
        $this->assertFalse(isset($this->config->user));
        $this->assertFalse(isset($this->config->password));

        $this->assertTrue(isset($this->config->memory));
        $this->assertTrue(isset($this->config->dbname));
        $this->assertTrue(isset($this->config->driver));
    }

    /**
     * @covers ::save
     */
    public function testSave(): void
    {
        $ref  = new \ReflectionClass($this->config);
        $prop = $ref->getProperty('configFile');
        $prop->setAccessible(true);

        vfsStream::setup('config');
        $dir = vfsStream::copyFromFileSystem(dirname($prop->getValue($this->config)));


        $file = $dir->getChild('config_test.yml')->url();
        $prop->setValue($this->config, $file);

        $this->config->user     = 'DRUGS';
        $this->config->password = 'ARE BAD';
        $meth                   = $ref->getMethod('save');

        // {o.O} //
        $meth->setAccessible(true);
        $meth->invoke($this->config);

        $eol      = "\n";
        $expected = 'user: DRUGS' . $eol
            . "password: 'ARE BAD'" . $eol
            . 'memory: true' . $eol
            . 'dbname: phpunit' . $eol
            . 'driver: pdo_sqlite' . $eol
            . 'host: null' . $eol;

        $this->assertStringEqualsFile($file, $expected);
    }
}
