<?php
namespace Access9\DbTableDump;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class Dump
 *
 * @package Access9\DbTableDump
 */
class Dump
{
    /**
     * Main runner to keep dump simple.
     */
    public static function run()
    {
        $path = realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'config'
        );

        $container = new ContainerBuilder();
        $container->setParameter('config.path', $path);
        $loader = new YamlFileLoader($container, new FileLocator($path));
        $loader->load('services.yml');
        $container->get('application')->run();
    }
}
