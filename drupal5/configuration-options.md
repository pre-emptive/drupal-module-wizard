# Wizard Configuration Options

The wizard.module has to be configured before it can be used. This is done by supplying an array data structure to the wizard module via the wizard_config_store() API call. The structure of the array is as follows:
```
<?php
  array(
    '#steps' => array(),

    '#validate' => NULL,
    '#submit' => NULL,
    '#finishpage' => 'node',

    '#update_button' => array(
      '#title' => t('Update'),
      '#weight' => 20,
      '#disabled' => FALSE,
      '#visible' => FALSE,
      '#automatic' => TRUE,
    ),
    '#back_button' => array(
      '#title' => t('Back'),
      '#weight' => 21,
      '#disabled' => FALSE,
      '#visible' => TRUE,
      '#automatic' => TRUE,
    ),
    '#next_button' => array(
      '#title' => t('Next'),
      '#weight' => 22,
      '#disabled' => FALSE,
      '#visible' => TRUE,
      '#automatic' => TRUE,
    ),
    '#finish_button' => array(
      '#title' => t('Finish'),
      '#weight' => 23,
      '#disabled' => TRUE,
      '#visible' => TRUE,
      '#automatic' => TRUE,
    ),
    '#cancel_button' => array(
      '#title' => t('Cancel'),
      '#weight' => 24,
      '#disabled' => FALSE,
      '#visible' => TRUE,
      '#automatic' => TRUE,
    ),

    '#data_storage' => 'session',

    '#progress_bar' => 'none',

    '#wizard_cancelled_message' => t('Wizard has been cancelled.'),

    '#destroy_on_cancel' => TRUE,
    '#destroy_on_finish' => TRUE,
  );
?>
```
The structure shown above is actually the defaults used by the wizard module. The elements are as follows:

Element | Description
------- | -----------
#steps | An array of form steps (see below)
#validate | Optionally specify the function to be used for validation. Defaults to form_id_validate.
#submit | Optional. Same as #validate, but for submission handling.
#finishpage | The Drupal path to a page to redirect the browser when the wizard completes, or the user presses cancel
#update_button | A button configuration (see below). Unlike the others, the update button defaults to "invisible".
#back_button | A button configuration (see below)
#next_button | A button configuration (see below)
#finish_button | A button configuration (see below)
#cancel_button | A button configuration (see below)
#data_storage | Defines the method of storage for intermediate data. Defaults to "session" (which allows the use of wizard_data_store() and wizard_data_retrieve() ). Can be set to "repost" which posts all previous form values as hidden fields in each page of the wizard.
#progress_bar | Defaults to "none", but can be "graphical" for a graphical "progress bar" or "textual" for written details of the users progress through the wizard
#wizard_cancelled_message | A message to emit as a notice message if the user presses "cancel". Can be set to an empty string to stop printing a message.
#destroy_on_cancel | Set to TRUE by default, causing the module to call wizard_destroy() when the user presses the cancel button. Set to FALSE to leave wizard config and data in module storage after cancelling the wizard.
#destroy_on_finish | Same as #destroy_on_cancel, except takes effect when the user presses the finish button

## Steps Configuration

The steps configuration is a numbered array of wizard steps. Each step defines the title of the step and the Drupal form for it. The form can either be "hard coded" or created with a callback function. For example:
```
<?php
  array(
    '#title' => t('Where Are You From?'),
    '#form' => array(
      'planet' => array(
        '#type' => 'select',
        '#title' => t('Where are you from?'),
        '#options' => array('mars' => t('Mars'), 'venus' => t('Venus')),
        '#description' => t('Please select which planet you come from'),
      ),
      'likeit' => array(
        '#type' => 'checkbox',
        '#title' => t('I like it there'),
        '#description' => t('Check this if you like where you come from'),
      ),
    ),
  );
?>
```
The above example is a hardcoded form step. The step configuration elements are as follows:

Element | Description
------- | -----------
#title | The title for the wizard step
#form | A hardcoded form array
#callback | The name of a function to call to get the form data
#callback arguments | An array of arguments to the form callback
#skip | If set to TRUE, causes this step to be ignored by the wizard module. Useful for development and testing.
#button_button | Set the state of 'update', 'back', 'next', 'finish' or 'cancel' buttons. The value of this element can be an array of any of 'visible', 'invisible', 'disabled' and 'enabled'. These states take effect when the step is displayed, and persist for any other pages displayed after that (even those "back" from the current one).

Note: Either a #form or a #callback must be specified for a step to be usable.
## Button Configuration

Each of the wizard control buttons can be configured. There is an "update" button, which is not used to control the wizard. It has the effect of submitting the form information and returning to the same form step. Each button configuration has the following structure:
```
<?php
  array(
    '#title' => t('Back'),
    '#weight' => 21,
    '#disabled' => FALSE,
    '#visible' => TRUE,
    '#automatic' => TRUE,
  ),
?>
```
Only "back", "next" and "finish" have "#automatic" capability. This automatically adjusts #disabled according to the position in the wizard. For example, "Back" is ordinarily disabled on the first step of the wizard.

"#disabled" has the effect of "greying out" the button. "#visible" can be used to remove the button entirely from the form.
