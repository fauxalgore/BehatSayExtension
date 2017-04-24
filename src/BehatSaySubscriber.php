<?php

namespace FauxAlGore\BehatSayExtension;

use Behat\Behat\EventDispatcher\Event\BeforeStepTested;
use Behat\Behat\EventDispatcher\Event\AfterStepTested;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\StepNode;

class BehatSaySubscriber implements EventSubscriberInterface
{

    public function __construct($voice, $roles)
    {
        $this->default_voice = $voice;
        $this->current_voice = $voice;
        $this->role_voices = $roles;
    }

    public function isStepRoleSetting(StepNode $step)
    {
        return !empty(strpos($step->getText(), 'logged in as a'));
    }

    protected function getVoiceFlag()
    {
        if ($this->current_voice) {
            return '--voice=' . $this->current_voice;
        }
        return '';
    }

    protected function setVoice(BeforeStepTested $event)
    {
        $step = $event->getStep();
        if ($this->isStepRoleSetting($event->getStep())) {
            foreach ($this->role_voices as $role => $voice) {
                if (strpos($step->getText(), '"' . $role . '"')) {
                    $this->current_voice = $voice;
                }
            }
        }
    }

    public function beforeStep(BeforeStepTested $event)
    {
        $this->setVoice($event);
        $text = escapeshellarg($this->getCompleteStepPhrase($event));
        $voice = $this->getVoiceFlag();
        exec('say ' . $voice . ' ' . $text . ' > /dev/null 2>/dev/null &');
    }

    protected function getCompleteStepPhrase(BeforeStepTested $event)
    {
        return $event->getStep()->getKeywordType() . ' '. $event->getStep()->getText();
    }

    public function afterStep(AfterStepTested $event)
    {
        // This while loop exists to block execution of the tests until the
        // say command completes.
        while ($this->isSayRunning()) {
            // a quarter of a second.
            usleep(250000);
        }
    }

    protected function isSayRunning()
    {
        $output = array();
        // As constructed, any say command that is running will cause this
        // function to return true. Such a check may be overly broad. The say
        // command currently running might be coming from a process initiated
        // outside of Behat, in which case block Behat may be inappropriate.
        exec("ps -axc | grep say", $output);

        return !empty($output);
    }

    public static function getSubscribedEvents()
    {
        return array(
            BeforeStepTested::BEFORE   => 'beforeStep',
            AfterStepTested::AFTER  => 'afterStep',
            BeforeScenarioTested::BEFORE => 'beforeScenario',

        );
    }
}
