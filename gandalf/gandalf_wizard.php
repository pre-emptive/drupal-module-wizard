<?php

// $Id: gandalf_wizard.php,v 1.2 2007/06/11 16:34:16 ralph Exp $

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
            '#options' => array('earth' => t('Earth Years'), 'mercurian' => t('Mercurian Years')),
            '#description' => t('Please tell us how you measure your age'),
            '#default_value' => 'earth',
          ),
        ),
      ),
    ),
    '#finish_page' => 'node',
    '#progress_bar' => 'graphical',
    '#update_button' => array(
      '#title' => t('Update'),
      '#weight' => 20,
      '#disabled' => FALSE,
      '#visible' => TRUE,
      '#automatic' => TRUE,
    ),

  );

  return $wizard;
}

function _gandalf_step2(&$form_state) {
  $userdata = $form_state['storage'];

  $form = array();

  if($userdata['planet'] == 'mars') {
    // If the user is from Mars, offer them some local choices
    $options = array(
      'lager' => t('Martian Gulping Lager'),
      'water' => t('Martian Syntho-Water'),
      'redtop' => t('RedTop(TM) Finest Brew'),
      'wine' => t('Plutonian Summer Wine'),
    );
    $form['drinks'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Drinks'),
      '#description' => t('Please tick your favourite drinks'),
      '#options' => $options,
    );
  } else {
    // User is from Venus...
    $options = array(
      'weavel' => t('Venution Space-Weavel skin handbags'),
      'cotton' => t('Imported Earth-cotton t-shirts'),
      'diamond' => t('Venution Green Diamonds'),
      'shoes' => t('ESA Special Issue Gravity Shoes'),
    );

    $form['shopping'] = array(
      '#title' => t('Shopping'),
      '#type' => 'checkboxes',
      '#options' => $options,
      '#description' => t('Please tick your favourite shopping items'),
    );
  }

  return $form;
}


// The following line is for Vim users - please don't delete it.
// vim: set filetype=php expandtab tabstop=2 shiftwidth=2:
