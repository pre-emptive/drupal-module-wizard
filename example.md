Update June 6th 2007: There are a couple of bugs ([one](http://drupal.org/node/144482} and [two](http://drupal.org/node/149744)) that make multi-step forms in Drupal 5.1 highly problematic. The latter bug really makes multistep forms pretty useless as it affects all validation of such forms. The good news is the soon-to-be-released Drupal 6 has a new multistep approach, so hopefully things will be different by then.

Drupal 5's new Form API (called Form API 2.0) has a number of features for making 'multi-step' forms. These are forms that change depending on user interaction. There are a number of examples around on the Internet, but most of them are quite basic. As an introduction though, see Dynamic and Multi-Page forms with Forms API at Drupal.org (pretty much duplicated by it's author from here - but check the comments for other useful hints). Also see a similar article at Lullabot (and their handy Forms API Cheat Sheet PDF).

In this example, we build a basic 'wizard'. The wizard collects information on the first page, before proceeding to the next page and so on. Each page could ask questions based on the previous page's answers, although this example doesn't actually do that.

The wizard has all the features it ought to have. It has "Previous" and "Next" buttons, as well as a "Cancel" button. Obviously, moving forwards through the wizard works as expected. Moving back a page shows the previously entered information. Moving forwards again retains whatever was in the form before the user pressed 'back'. This example also doesn't have any mandatory fields, but if they exist then this wizard will allow users to go 'back' even if they haven't filled in the mandatory fields on the current page (which normally would cause problems with Drupal's built in field checking). Pretty neat!

The code:
```php
<?php
// These three defines set the words written in the wizard buttons.
// We use defines because these values get passed to and fro, and
// we have to compare against them when processing the form.
define('WIZARD_NEXT', 'Next');
define('WIZARD_PREVIOUS', 'Back');
define('WIZARD_CANCEL', 'Cancel');

// Set how many steps in this wizard.
define('WIZARD_FINAL_STEP', 3);

// When finishing the wizard (or cancelling it), this defines where
// the browser ends up going.
define('WIZARD_FINISH_REDIRECT', 'node');

function _my_module_wizard($form_values=NULL) {
  // Get the "step" in the wizard we're on. If it's not yet set, then
  // we must be on step one. We usually increment the step, but if
  // the user last pressed the 'back' button, then we decrement it instead.
  if (!isset($form_values)) {
    $step = 1;
  } else {
    if(isset($form_values['op']) && $form_values['op'] == t(WIZARD_PREVIOUS)) {
      // Back button pressed...
      $step = $form_values['step'] - 1;
    } else {
      $step = $form_values['step'] + 1;
    }
  }

  // Put a hidden value in the form that says which step we're on.
  // This gets posted back to us, and used (above) next time around.
  $form['step'] = array(
    '#type' => 'hidden',
    '#value' => $step,
  );

  // This is purely cosmetic. We just say which page we're on. This
  // probably wants removing (or at least pretty-ing up!)
  $form['display'] = array(
    '#type' => 'markup',
    '#value' => "Page $step",
  );

  // These are *very* important. 'multistep' tells Drupal to store
  // extra information between form steps. 'tree' tells drupal to
  // preserve the multi-dimensional form information we're using.
  // 'tree' isn't completely necessary in this example, but for
  // big/complex forms, it's pretty obligatory!
  $form['#multistep'] = TRUE;
  $form['#tree'] = TRUE;

  // This is where we actually produce the form on each step of the
  // wizard. Naturally, it's possible to have as many steps as needed
  // here. It's not obligatory, but useful to separate each page in
  // a separate key in for $form.
  switch($step) {
    case 1:
      $form['pageone']['itemone'] = array(
        '#type' => 'textfield',
        '#default_value' => 'page 1 item',
      );
      break;
    case 2:
      $form['pagetwo']['itemtwo'] = array(
        '#type' => 'textfield',
        '#default_value' => 'page 2 item',
      );
      break;
    case 3:
      $form['pagethree']['itemthree'] = array(
        '#type' => 'textfield',
        '#default_value' => 'page 3 item',
      );
      break;
  }

  // This is important. If we're on the final step,
  // we tell drupal to use the normal redirect functionality.
  // That means the browser goes to whatever page after the
  // final submit. All previous steps don't redirect, so just
  // post back to this form.
  if($step == WIZARD_FINAL_STEP) {
    $form['#redirect'] = NULL;
  } else {
    $form['#redirect'] = FALSE;
  }

  // Now insert any previous form values... We need to remember
  // what has gone on previously. It's possible this could go into
  // $_SESSION instead, which is probably a good idea if there's
  // a lot of data captured in the wizard. This code only re-inserts
  // multi-dimensional form values (which are used in the switch/case
  // above). This leaves all non multi-dimensional out of the
  // post/repost cycle (saving a bit of mess in the form)
  if(!is_null($form_values)) {
    foreach (array_keys($form_values) as $pagenum) {
      if(is_array($form_values[$pagenum])) {
        foreach ($form_values[$pagenum] as $key => $value) {
          // Don't overwrite any form elements that are set above.
          // This could happen if the user presses "back"; it basically
          // makes elements disappear, which we don't want!
          if(!isset($form[$pagenum][$key])) {
            $form[$pagenum][$key] = array(
              '#type' => 'hidden',
              '#value' => $value,
            );
          }
        }
      }
    }
  }

  // Insert a button. We use a 'submit', which causes the form
  // to be sent to Drupal, which puts it through validation AND
  // submission routines. Use a 'button' to avoid the submit
  // phase (for all but the last page of your form!). The
  // wizard uses the submit phase to provide the 'cancel' facility,
  // so be careful.
  if($step > 1) {
    $form['previous'] = array(
      '#type' => 'submit',
      '#value' => t(WIZARD_PREVIOUS),
    );
  }
  $form['next'] = array(
    '#type' => 'submit',
    '#value' => t(WIZARD_NEXT),
  );
  $form['cancel'] = array(
    '#type' => 'submit',
    '#value' => t(WIZARD_CANCEL),
  );

  return $form;
}

function _my_module_wizard_validate($form_id, $form_values) {
  // If the user presses 'cancel' or 'back', we should do no further
  // validation. Also, if they press 'cancel' we should actually
  // goto the finish page, because the 'submit' stage won't be called
  // if the user hasn't filled in one of the mandatory fields. In fact,
  // in that case, Drupal's built in form validation will have set
  // errors that we don't need to show the user.
  if($form_values['op'] == t(WIZARD_CANCEL)) {
    // Clear errors from Drupal's built in validation...
    drupal_get_messages('error');
    // Tell the user we've cancelled
    drupal_set_message('Wizard cancelled.');
    // Now go to the 'finish page'
    drupal_goto(WIZARD_FINISH_REDIRECT);
    return;
  } else if($form_values['op'] == t(WIZARD_PREVIOUS)) {
    // Clear messages, and do no further validation
    drupal_get_messages('error');
    return;
  }

  // Do whatever validation here. It's probably a good idea to do a
  // switch/case on the wizard step. It may be a good idea to validate
  // everything on each call, as that will catch anyone hacking the
  // form with directly injected form posts, although at slightly
  // more processing.

}

function _my_module_wizard_submit($form_id, $form_values) {
  // If the user presses 'back' or 'cancel' don't do any submission work...
  if($form_values['op'] == t(WIZARD_PREVIOUS) || $form_values['op'] == t(WIZARD_CANCEL)) {
    // Don't do any submission work here, it's not relevant
    return FALSE;
  }

  // Process the form values. In this example, we only do something
  // when we reach the end of the wizard. Our example just displays
  // the form values on whatever page we redirect to.
  if(isset($form_values['step']) && $form_values['step'] == WIZARD_FINAL_STEP) {
    if(!is_null($form_values)) {
      foreach (array_keys($form_values) as $pagenum) {
        if(is_array($form_values[$pagenum])) {
          foreach ($form_values[$pagenum] as $key => $value) {
            drupal_set_message("Got page $pagenum key $key = $value");
          }
        }
      }
    }
    // Now send the browser to the 'finish page'.
    return WIZARD_FINISH_REDIRECT;
  }

  // If we haven't processed the form and completed fully, we have
  // to return FALSE so that Drupal redisplays our form.
  return FALSE;
}
?>
```

The example uses multi-dimensional form elements. For complex forms, it's almost impossible not to do so. The advantage of doing this is that the validate and submit routines only need to process the multi-dimensional bits of $form_values. Everything else is only used to control the wizard.

As with all examples, this one does very little as-is. However, it provides a good base upon which to build complex multi-step forms in 'wizard style'. Happy hacking!

Update 20th Dec. 2007: Thanks to Kendall Anderson, a couple of typos and differences between the page and the attachment have now been tidied up.
