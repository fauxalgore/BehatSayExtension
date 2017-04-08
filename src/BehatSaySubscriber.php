<?php

namespace FauxAlGore\BehatSayExtension;

use Behat\Behat\EventDispatcher\Event\BeforeStepTested;
use Behat\Behat\EventDispatcher\Event\AfterStepTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BehatSaySubscriber implements EventSubscriberInterface
{

    public function BeforeStep(BeforeStepTested $scope) {
        exec('say ' . escapeshellarg ($this->getCompleteStepPhrase($scope)) . ' > /dev/null 2>/dev/null &' );
    }

    protected function getCompleteStepPhrase(BeforeStepTested $event) {
        return $event->getStep()->getKeywordType() . ' '. $event->getStep()->getText();
    }

    public function afterStep(AfterStepTested $event) {
        // This while loop exists to block execution of the tests until the
        // say command completes.
        while($this->isSayRunning()) {
            // a quarter of a second.
            usleep(250000);
        }
    }

    protected function isSayRunning() {
        $output = array();
        // As constructed, any say command that is running will cause this
        // function to return true. Such a check may be overly broad. The say
        // command currently running might be coming from a process initiated
        // outside of Behat, in which case block Behat may be inappropriate.
        exec ("ps -axc | grep say",  $output);

        return !empty($output);
    }

    public static function getSubscribedEvents() {
        return array(
            BeforeStepTested::BEFORE   => 'BeforeStep',
            AfterStepTested::AFTER  => 'afterStep',

        );
    }
}
