#Drupal 5 Wizard API Module

Attached is a Wizard module for Drupal 5.1. It provides developers with an easy API to make wizards in their applications with a minimum of hassle.

Features include:

* Provides configurable "Back", "Next", "Finish" and "Cancel" buttons on all form pages
* Button labels are configurable, they can be enabled/disabled and made invisible
* Moving forwards and backwards in the wizard is completely automatic
* Developers only need to configure the wizard and provide validate/submit handlers
* Validate and Submit phases are greatly simplified - developers only need handle their own form elements as all wizard functionality is already handled by the module
* A generic data storage facility is provided for developers to store form data between pages

Note: Drupal 5.1 has some serious [limitations](https://www.pre-emptive.net/doco/limitations) related to validation of multistep forms ([bug report](http://drupal.org/node/149744)). This limitation makes Wizards of limited use (especially with text fields). Also, checkboxes are problematic in Drupal 5.1 ([bug report](http://drupal.org/node/144482)), but workarounds are provided by the wizard module.

An example module (gandalf) is provided to demonstrate the wizard functionality (as wizard.module does nothing on it's own!).
