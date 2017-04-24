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
              ->arrayNode('roles')
              ->requiresAtLeastOneElement()
              ->useAttributeAsKey('key')
              ->prototype('variable')
            ->end()
          ->end()
          // The voice setting is configurable but that configuration is not yet
          // used by BehatSaySubscriber.
          ->scalarNode('default_voice')->defaultNull()->end()
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
        $container->setParameter('behatsay.default_voice', $config['default_voice']);
        $container->setParameter('behatsay.roles', $config['roles']);
        $class = 'FauxAlGore\BehatSayExtension\BehatSaySubscriber';
        $definition = (new Definition($class, array ('%behatsay.default_voice%', '%behatsay.roles%')))
        ->addTag('event_dispatcher.subscriber');
        $container->setDefinition('command_runner.listener', $definition);
    }
    public function process(ContainerBuilder $container)
    {
    }
}
