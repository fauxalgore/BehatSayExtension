<?php


namespace FauxAlGore\BehatSayExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\MinkExtension\ServiceContainer\Driver\BrowserStackFactory;
use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Behat\MinkExtension\ServiceContainer\Driver\GoutteFactory;
use Behat\MinkExtension\ServiceContainer\Driver\SahiFactory;
use Behat\MinkExtension\ServiceContainer\Driver\SauceLabsFactory;
use Behat\MinkExtension\ServiceContainer\Driver\Selenium2Factory;
use Behat\MinkExtension\ServiceContainer\Driver\SeleniumFactory;
use Behat\MinkExtension\ServiceContainer\Driver\ZombieFactory;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\Exception\ProcessingException;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;


use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Behat\Hook\Scope\AfterStepScope;

class BehatSayExtension implements ExtensionInterface
{
  /**
   * Returns the extension config key.
   *
   * @return string
   */
  public function getConfigKey() {
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
  public function initialize(ExtensionManager $extensionManager) {}

  /**
   * Setups configuration for the extension.
   *
   * @param ArrayNodeDefinition $builder
   */
  public function configure(ArrayNodeDefinition $builder) {




    $builder
      ->addDefaultsIfNotSet()
      ->children()

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
  public function load(ContainerBuilder $container, array $config) {




    $definition = (new Definition('FauxAlGore\BehatSayExtension\BehatSaySubscriber'))
      ->addTag('event_dispatcher.subscriber')

    ;
    $container->setDefinition('command_runner.listener', $definition);















    $container->setParameter('behatsay.voice', $config['voice']);

  }
  public function process(ContainerBuilder $container) {}






}
