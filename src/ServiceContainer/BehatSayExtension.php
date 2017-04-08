<?php

namespace FauxAlGore\BehatSayExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class BehatSayExtension implements ExtensionInterface
{
    /**
   * Returns the extension config key.
   *
   * @return string
   */
    public function getConfigKey()
    {
        return 'behatsay';
    }

    /**
   * Initializes other extensions.
   *
   * This method is called immediately after all extensions are activated but
   * before any extension `configure()` method is called. This allows extensions
   * to hook into the configuration of other extensions providing such an
   * extension point.
   *
   * @param ExtensionManager $extensionManager
   */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
   * Setups configuration for the extension.
   *
   * @param ArrayNodeDefinition $builder
   */
    public function configure(ArrayNodeDefinition $builder)
    {

        $builder
            ->addDefaultsIfNotSet()
            ->children()
            // The voice setting is configurable but that configuration is not yet
            // used by BehatSaySubscriber.
            ->scalarNode('voice')->defaultNull()->end()
            ->end()
            ->end();
    }

    /**
   * Loads extension services into temporary container.
   *
   * @param ContainerBuilder $container
   * @param array            $config
   */
    public function load(ContainerBuilder $container, array $config)
    {

        $definition = (new Definition('FauxAlGore\BehatSayExtension\BehatSaySubscriber'))
        ->addTag('event_dispatcher.subscriber');
        $container->setDefinition('command_runner.listener', $definition);
        $container->setParameter('behatsay.voice', $config['voice']);
    }
    public function process(ContainerBuilder $container)
    {
    }
}
