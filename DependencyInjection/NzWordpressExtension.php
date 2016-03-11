<?php

namespace Nz\WordpressBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NzWordpressExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');

        if (isset($config['table_prefix'])) {
            $this->loadTablePrefix($container, $config['table_prefix']);
            /* $container->setParameter('nz_wordpress.table_prefix', $config['table_prefix']); */
        }

        if (isset($bundles['SonataAdminBundle'])) {
            $loader->load('admin.xml');
        }
        /*
          if (isset($config['entity_manager'])) {
          $this->loadEntityManager($container, $config['entity_manager']);
          }
         */
    }

    /**
     * Loads table prefix from configuration to doctrine table prefix subscriber event.
     *
     * @param ContainerBuilder $container Symfony dependency injection container
     * @param string           $prefix    Wordpress table prefix
     */
    protected function loadTablePrefix(ContainerBuilder $container, $prefix)
    {
        $identifier = 'nz.wordpress.subscriber.table_prefix_subscriber';

        $serviceDefinition = $container->getDefinition($identifier);
        $serviceDefinition->setArguments([$prefix]);

        $container->setDefinition($identifier, $serviceDefinition);
    }

    /**
     * Sets Doctrine entity manager for Wordpress.
     *
     * @param ContainerBuilder       $container
     * @param EntityManagerInterface $em
     */
    protected function loadEntityManager(ContainerBuilder $container, $em)
    {
        $reference = new Reference(sprintf('doctrine.orm.%s_entity_manager', $em));

        foreach (static::$entities as $entityName) {
            $container->findDefinition(sprintf('nz.wordpress.manager.%s', $entityName))->replaceArgument(0, $reference);
        }
    }
}
