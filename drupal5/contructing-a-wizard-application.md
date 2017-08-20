#Constructing a Wizard Application

Construction of a wizard application is incredibly easy with wizard.module. This document describes how it's done (with reference to the demonstration wizard, Gandalf).

Before we begin coding, we need to decide on the design of the wizard itself. There are some limitations to be aware of (see the documents about limitations). However, for the purposes of this document, we will construct a simple wizard that has three screens. The second screen will differ depending on the input in the first screen. The third screen will be the same, no matter what information has been presented before it.

wizard.module has to know about the structure of the wizard before it can begin. It's possible to completely change the wizard configuration at any time during the flow trough the screens, but it's advisable to start the process with the a "best guess" of the wizard configuration. Many aspects of the wizard are configurable, but for this example, we'll keep it simple. We'll also put the configuration into it's own function. This isn't actually necessary, but it keeps the code a little tidier.

The configuration looks like this:
```
<?php
function gandalf_wizard_config() {
  $wizard = array(
    '#steps' => array(
      1 => array(
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
      ),
      2 => array(
        '#title' => t('What Do You Like?'),
        '#callback' => '_gandalf_step2',
      ),
      3 => array(
        '#title' => t('How Old Are You?'),
        '#form' => array(
          'age' => array(
            '#type' => 'textfield',
            '#title' => t('What age are you?'),
            '#description' => t('Please tell us how old you are'),
            '#required' => TRUE,
          ),
          'units' => array(
            '#type' => 'radios',
            '#title' => t('What time measurement?'),
            '#options' => array('earth' => t('Earth Years'), 'mercury' => t('Mercurian Years')),
            '#description' => t('Please tell us how you measure your age'),
            '#default_value' => 'earth',
          ),
        ),
      ),
    ),
    '#finish_page' => 'node',
    '#progress_bar' => 'graphical',
  );

  return $wizard;
}
?>
```
The configuration defines three wizard steps. The first and third steps are really just ordinary Drupal form structures. The second step uses a callback to a function that provides the form. This means that when the wizard shows the second step, the function will be called, which means it can decide which variation of the form to show (obviously, if it was a coded form like steps 1 and 3, then it wouldn't be able to adapt to the input from step 1). The callback function must return a Drupal form data structure.

There are two wizard configuration options provided to the wizard. The '#finish_page' setting means that when the wizard completes (because the user presses the "finish" button), the Wizard module will redirect the browser to the URL specified (in this case that would be "http://yoursite/node").

The '#progress_bar' option can be set to "none", "text" or "graphical". If used, the progress through the wizard is displayed on each page of the wizard. This output can be themed if required. There are a handful of other options for the wizard, see the relevant documents for details.

So now we've created our wizard configuration, we can actually make the web forms and see it in action. All we need to do is to configure the wizard and ask the module to produce the right page of it. For the purposes of this example, we'll assume you've set up a Drupal Menu item that calls the form function with drupal_get_form().
```
<?php
function gandalf_wizard($form_values=NULL) {

  if($form_values == NULL) {
    $wizard = gandalf_wizard_config();
    module_invoke('wizard','config_create',$wizard);
  }

  return module_invoke('wizard', 'form', $form_values);
}
?>
```
Here, the gandalf_wizard() function checks to see if it's been called with $form_values. Drupal automatically calls form functions with form values if they've been posted. If no values have been posted, the form is being called for the first time. As a result, we fetch the wizard configuration and tell the wizard module about it. After that, we only need to call the wizard module to get it to display the form.

This is actually enough to get the wizard working. With this, we can move forward and backwards in the wizard, and see it retain form values. Of course, without field validation or a submit handler, there's no way to check the information in the form, and then no way to do anything with it. This is where the validate and submit handlers come in. By default, wizard.module looks to run two functions that are the same name as the form, but with "_validate" or "_submit" suffixed to it. In our wizard, all the questions we ask are multiple choice, except when we ask for an age. This is the only field we need to validate, but of course most forms will require much more validation than this. Here's the function (with a skeleton for more complex forms):
```
<?php
function gandalf_wizard_validate($form_id, $form_values, $step) {
  switch($step) {
    case 1:
      break;
    case 2:
      break;
    case 3:
      // Check the age is reasonable. People get pretty old, so we just
      // make sure it's a positive integer.
      if($form_values['age'] != '' && (!is_numeric($form_values['age']) || $form_values['age'] <= 0)) {
        form_set_error('age', t('Your age must be numeric and greater than 0.'));
      }
      break;
  }
}
?>
```
The Wizard module calls the validate handler in almost the same way as core Drupal would, except it only ever uses three arguments. The step is also added, making processing of each stage of the wizard simpler. As with core Drupal, any validation errors are indicated by setting an error with form_set_error().

Both submit and validate handlers are caled with an extra form value set. This is called "wizard_button_pressed" and contains one of "back", "next", "finish", "cancel" or "unknown", depending on which button was pressed by the user. "unknown" is used is any non-wizard button was pressed. The names used in wizard_button_pressed are always the same, regardless of the labels used on buttons.

Similarly, here's a skeleton submit function:
```
<?php
function gandalf_wizard_submit($form_id, $form_values, $step) {

  ...do step based, or other submit work...
 
  if($form_values['wizard_button_pressed'] == 'finish') {

    ...do work when the form has been complete finished...

    // Now redirect the browser to node/1234
    return 'node/1234';
  }

  ...any other work...
}
?>
```
Submission of forms is almost the same as validation. There is one key difference, and that is that the submit handler must handle the "finish" button event. If the finish button is pressed (and validation succeeds), the submit handler must return a "redirect" rather than the usual TRUE/FALSE or NULL. If the submit handler does not do this, the wizard module will redirect to the configured "finish page", and will emit a warning. Of course, most forms will save values from the form into the database, or do other similar functions when the user presses the finish button.
