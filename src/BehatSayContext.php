<?php

namespace FauxAlGore\BehatSay;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Behat\Hook\Scope\AfterStepScope;

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
  public function BeforeStep(BeforeStepScope $scope) {
    exec('say ' . escapeshellarg ($this->getCompleteStepPhrase($scope)) . ' > /dev/null 2>/dev/null &' );
  }

  protected function getCompleteStepPhrase(BeforeStepScope $scope) {
    return $scope->getStep()->getKeywordType() . ' '. $scope->getStep()->getText();
  }

  /**
   * @AfterStep
   */
  public function afterStep(AfterStepScope $scope) {
     while($this->isSayRunning()) {
       // a quarter of a second.
       usleep(250000);
     }
  }

  protected function isSayRunning() {
    $output = array();
    exec ("ps -axc | grep say",  $output);

    return !empty($output);
  }
}
