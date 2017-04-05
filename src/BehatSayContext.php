<?php

namespace FauxAlGore\BehatSay;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;

/**
 * Define application features from the specific context.
 */
class BehatSayContext implements Context {
  /**
   * Initializes context.
   * Every scenario gets its own context object.
   *
   * @param array $parameters
   *   Context parameters (set them in behat.yml)
   */
  public function __construct(array $parameters = []) {
    // Initialize your context here
  }

    /**
     * @BeforeStep
     */
    public function BeforeStep(BeforeStepScope $scope)
    {
       exec('say hello');
    }
}
