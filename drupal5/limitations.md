#Limitation

Drupal 5.1 has a couple of bugs that cause problems for multi-step forms in general, the wizard is unfortunately no exception. These bugs (at no fault of wizard.module) may cause significant problems when attempting to use multi-step forms/wizards with Drupal 5.1.
##Checkboxes

See [bug report](http://drupal.org/node/144482).

Checkboxes are not normally set correctly when moving between pages in a multi-step form. For example, say a user checks a checkbox in page 1, then moves to page 2 and back to page 1, the checkbox will not be selected. This means the user has to remember to check it again before proceeding. The same is true for multiple checkbox groups (Drupal "checkboxes" form elements).

wizard.module can workaround this bug (if configured to do so in it's admin screen). With single checkboxes, the workaround works very satisfactorily (although in a Drupal sense, it's a bit of a code hack). The problems with multiple checkboxes are so severe that wizard.module automatically converts multiple checkboxes into single checkboxes (which it puts into a fieldset - see theming documentation for more information). Whilst wizard.module handles the setting of those adjusted checkboxes correctly, validate and submit handlers must be adjusted to handle the incomming information correctly. Each checkbox is returned as a single element, rather than being returned as an array.
##Submit after Validation Failure

See [bug report](http://drupal.org/node/149744).

There is a major bug in Drupal 5.1 which causes problems when a multi-step form is submitted after validation has failed. Essentially, the submit phase of the form is incorrectly called, meaning that it is impossible to correctly validate the form input. If the user fills the form in correctly and so passes validation the first time they submit, then everything works as it should.

This problem makes Drupal 5.1 wizards very difficult to implement properly (not the fault of wizard.module!). However, wizards are still usable, but may have to be redesigned to workaround this problem.

Essentially, validation is usually only critical when accepting textual information (in "textfield" or "textbox" form elements). Other cases are when specific combinations of radio or checkboxes are selected. If the complex validation information is captured on page 1, the wizard can still be usable. That is, any validation failures will cause the first page of the form to be loaded again, allowing the user another attempt.

Where specific combinations of radio or checkboxes require careful validation, it may be possible to alter the ordering of the form to force correct input. That is, by splitting a page into two, and having the second built dynamically.
##Mandatory Fields

Mandatory fields are perfectly fine in wizards. However, they cause problems when pressing the "back" button. This is because Drupal validates the form and complains about the mandatory field not being set. There is currently no way for module validation routines to tell Drupal to ignore errors it has found.

There are ways to workaround this problem. Firstly, putting all mandatory fields on the first page immediately eliminates the problem. Alternatively, instead of using "#required => TRUE" in form elements, enforce the requirement with a validation of the field. This means the little "*" indicating a mandatory field will not be shown, but the form can be used without non-intuitive validation problems.
##File Uploads

At present, each step of a wizard can only have a maximum of a single file upload on it. This upload will be called "wizard", regardless of the name of the form element used. Thus, to handle uploads, use "file_check_upload('wizard')".

The limitation of a single file per page of the wizard will be removed in future versions of the wizard module.
