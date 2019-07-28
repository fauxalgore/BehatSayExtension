# BehatSay

[![CircleCI](https://circleci.com/gh/fauxalgore/BehatSayExtension.svg?style=svg)](https://circleci.com/gh/fauxalgore/BehatSayExtension)

This Behat Extension uses the `say` command to speak aloud your Behat steps as they run.

## Configuring

This extension takes configuration for a default voice. Voices can also vary based on the (Drupal) user role. Enable the extension by adding the following to your `behat.yml` file.

```
    FauxAlGore\BehatSayExtension:
      default_voice: Fiona
      roles:
        administrator: Pipe Organ
        content_administrator: Alex
```

Adapt the configuration to use whichever `say` voices you prefer. To see a list of all available voices run `say --voice=\?`

## Benefits

By speaking your Behat commands aloud, this extension highlights poorly constructed Behat steps. Behavior Driven Development encourages using domain-specific language. If your steps are written around CSS selectors, they will sound absurd.

Additionally, varying voices by user role will encourage deeper consideration of the role used for a given scenario.
