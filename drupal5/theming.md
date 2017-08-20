#Theming

Form elements in wizards can be themed like any other elements in Drupal. However, some special theming is also possible.

Firstly, the wizard has an optional progress indicator. This is either graphical or textual, but either can be themed.
##string theme_wizard_progress_info($title, $step, $total)

This theme function returns a textual representation of the progress of the wizard. Ordinarily this will appear something like:
```
[title]
Step [step] of [total].
```
However, this can of course be themed any way required by overriding this function in a theme.

##string theme_wizard_progress_bar($percent, $title)

This theme function ordinarily uses theme_progress_bar() to return a graphical representation of the progress of the wizard.
Checkboxes

Drupal 5.1 has some limitations with regard to multiple checkboxes (the Drupal "checkboxes" form element). This limitation can be worked around by wizard.module, which it does by making checkbox groups into individual checkbox elements. It wraps these into a fieldset, to visually group them on the screen. If this is not the required visual effect, it can be changed by a theme function.

Whenever wizard.module creates a fieldset with individual checkboxes in it, it adds a proprietary tag to the fieldset definition, which can be detected by a theme function. For example, if inserted in a theme called "mytheme", the following code will prefix all checkbox fieldset titles with "XXX".
```
<?php
function mytheme_fieldset($form) {
        if(isset($form['#wizard_fapi_workaround'])) {
                $form['#title'] = "XXX" . $form['#title'];
        }
        return theme_fieldset($form);
}
?>
```
It is of course possible to completely change the form definition for the fieldset into something completely different if required. In this case though, we only modify a standard fieldset.
