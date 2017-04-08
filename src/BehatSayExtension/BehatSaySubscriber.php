<?php

namespace FauxAlGore\BehatSayExtension;

use Behat\Behat\EventDispatcher\Event\BeforeStepTested;
use Behat\Behat\EventDispatcher\Event\AfterStepTested;

use Behat\Behat\EventDispatcher\Event\BeforeFeatureTeardown;
use Behat\Behat\EventDispatcher\Event\BeforeFeatureTested;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTeardown;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Behat\Behat\Hook\Call\BeforeStep;
use Behat\Testwork\EventDispatcher\Event\BeforeSuiteTeardown;
use Behat\Testwork\EventDispatcher\Event\BeforeSuiteTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BehatSaySubscriber implements EventSubscriberInterface
{


    public function BeforeStep(BeforeStepTested $scope) {
        exec('say ' . escapeshellarg ($this->getCompleteStepPhrase($scope)) . ' > /dev/null 2>/dev/null &' );
    }

    protected function getCompleteStepPhrase(BeforeStepTested $scope) {
        return $scope->getStep()->getKeywordType() . ' '. $scope->getStep()->getText();
    }


    public function afterStep(AfterStepTested $scope) {
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

    public static function getSubscribedEvents()
    {

        return array(
            BeforeStepTested::BEFORE   => 'BeforeStep',
            AfterStepTested::AFTER  => 'afterStep',

        );
    }

}