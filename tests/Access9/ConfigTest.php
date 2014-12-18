<?php
namespace Access9\Tests;

use Access9\Config;
use org\bovigo\vfs\vfsStream;

/**
 * Class ConfigTest
 *
 * @package Access9\Tests\Console
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    private $config;

    public function testConstruct()
    {
        $this->assertInstanceOf('\Access9\Config', $this->config);
    }

    /**
     * Ensure the $config property is set properly.
     */
    public function testConfigPropertyIsSetAfterConstruction()
    {
        $prop = (new \ReflectionClass($this->config))->getProperty('config');
        $prop->setAccessible(true);

        $expected = [
            'user'     => null,
            'password' => null,
            'memory'   => true,
            'dbname'   => 'phpunit',
            'driver'   => 'pdo_sqlite',
            'host'     => null
        ];
        $this->assertSame($expected, $prop->getValue($this->config));
    }

    /**
     * Ensure the $configFile property is set properly.
     */
    public function testConfigFilePropertyIsSetAfterConstruction()
    {
        $prop = (new \ReflectionClass($this->config))->getProperty('configFile');
        $prop->setAccessible(true);

        $actual   = realpath($prop->getValue($this->config));
        $expected = realpath(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config'
            . DIRECTORY_SEPARATOR . 'config_test.yml'
        );
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Config::getConfig
     */
    public function testGetConfig()
    {
        $expected = [
            'user'     => null,
            'password' => null,
            'memory'   => true,
            'dbname'   => 'phpunit',
            'driver'   => 'pdo_sqlite',
            'host'     => null
        ];
        $this->assertSame($expected, $this->config->getConfig());
    }

    /**
     * Tests Config::getConfig
     */
    public function testGetConfigWithParams()
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
            $this->config->getConfig('user-param', 'password-param', 'host-param', 'dbname-param')
        );
    }

    public function test__set()
    {
        $expected           = 'Boogers';
        $this->config->user = $expected;

        $prop = (new \ReflectionClass($this->config))->getProperty('config');
        $prop->setAccessible(true);
        $this->assertSame($expected, $prop->getValue($this->config)['user']);
    }

    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Attempting to set a non-existent configuration key
     */
    public function test__setThrowsException()
    {
        $this->config->randomunknownprop = 'this is totally going to throw an exception';
    }

    public function test__get()
    {
        $this->assertNull($this->config->user);
        $this->assertNull($this->config->password);
        $this->assertTrue($this->config->memory);
        $this->assertSame($this->config->dbname, 'phpunit');
        $this->assertSame($this->config->driver, 'pdo_sqlite');
    }

    public function test__isset()
    {
        // These two are false because the config for phpunit doesn't define these.
        $this->assertFalse(isset($this->config->user));
        $this->assertFalse(isset($this->config->password));

        $this->assertTrue(isset($this->config->memory));
        $this->assertTrue(isset($this->config->dbname));
        $this->assertTrue(isset($this->config->driver));
    }

    public function testSave()
    {
        $ref  = new \ReflectionClass($this->config);
        $prop = $ref->getProperty('configFile');
        $prop->setAccessible(true);

        vfsStream::setup('config');
        $dir = vfsStream::copyFromFileSystem(dirname($prop->getValue($this->config)));

        $file = $dir->getChild('config.yml')->url();
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

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->config = new Config();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->config = null;
    }
}
