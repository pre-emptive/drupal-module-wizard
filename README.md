# drupal-module-wizard
Drupal 6 Wizard API module

a preliminary Drupal 6 compatible Wizard API module and it's counterpart example. Note that Drupal 6 is not yet released, and is indeed still under active development. As such, this module may not work in the future if things change. Similarly, the module is subject to change, and so modules that depend on it may need significant work to keep up.

USE THIS MODULE AT YOUR OWN RISK

That said, this module is a working implementation of wizard.module on Drupal 6. It's very similar to it's Drupal 5 counterpart, except:

- All intermediate data storage is done in $form_state['storage'] rather than wizard_data_store() and wizard_data_retrieve() (which have been removed)
- Form callbacks now get $form_state appended to any other arguments

Internally, Drupal's Forms API uses Drupal's cache_set() and cache_get() to store intermediate data (a change to previous versions that used $_SESSION). This change will likely also occur on wizard.module, but with luck, this will not affect the API. Other internal changes to wizard.module are likely, again this ought not affect the API.

There's not much documentation for this version of wizard.module. Thankfully, much is the same as Drupal 5, so that's a good place to start. Naturally we'll be updating the documentation for this version in due course.
