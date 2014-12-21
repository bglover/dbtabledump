<?php
namespace Access9;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class DbTableDump
 *
 * @package Access9
 */
class DbTableDump
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
