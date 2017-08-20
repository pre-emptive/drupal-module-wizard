# The Wizard API

The following functions are available via the wizard module. In general, functions should be accessed with "module_invoke('wizard', (function), arguments...)" rather than called directly. For example:
```
<?php
module_invoke('wizard', 'config_retrieve');
?>
```
## boolean wizard_config_created()

Returns TRUE if a wizard configuration has been created and is stored within the wizard module. Returns FALSE otherwise.
## boolean wizard_config_create($wizard)

Creates a wizard configuration within the module, using the supplied data structure. Returns TRUE if successfully created, or FALSE if not. The data structure provided overrides default values that are inherent in the module.
## boolean wizard_config_update($newwizard)

Incrementally updates an existing wizard configuration with the data structure supplied. Wizard steps are incrementally updated, as are other configuration options. Returns TRUE on success, FALSE otherwise.
## boolean wizard_destroy()

Destroys the wizard configuration and data within the module, releasing all resources used. If an attempt is made to display a wizard is made after this call, an error is returned. Attempts to retrieve wizard data after this call also fail. Returns TRUE on success, FALSE otherwise.
## mixed wizard_config_retrieve()

Retrieves the current wizard configuration from the module. Returns the data structure on success, or NULL otherwise.
## boolean wizard_config_button_state($button, $state, $wizard)

Changes the state of a wizard button. If a wizard data structure is supplied it is used, otherwise the configuration is loaded from the module. Buttons supported are 'update','back','next','finish' and 'cancel' (regardless of button labels). States can be one of 'enabled', 'disabled', 'visible' and 'invisible'.

This function is a convenience; it is possible to change a button state by manipulating the wizard configuration directly. The $wizard parameter can be set to NULL if not used (in which case the function will load, modify and save the wizard configuration automatically).
## boolean wizard_data_store($data, $overwrite)

Stores arbitrary data into the wizard. This is usually used to store form values between pages so that they can be processed en-masse at the end of the wizard. If $overwrite is true, then any other data is discarded, otherwise it is array merged. Returns TRUE on success, FALSE otherwise.
## mixed wizard_data_retrieve()

Returns any arbitrary data stored in the module. Returns the data on success (usually an array) or NULL on failure.
## mixed wizard_form($form_values)

Returns a Drupal form data structure containing the appropriate form step of the wizard. If the wizard has not been configured, an error is returned. If $form_values is not set, step 1 of the wizard is returned. If values are provided, they are interrogated to determine which step to display.
