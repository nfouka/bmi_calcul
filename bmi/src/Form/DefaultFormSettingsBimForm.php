<?php

/**
 * @file
 * Contains Drupal\bmi\Form\DefaultFormSettingsBimForm.
 */

namespace Drupal\bmi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultFormSettingsBimForm.
 *
 * @package Drupal\bmi\Form
 */
class DefaultFormSettingsBimForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bmi.defaultsettingsbim_config'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_form_settings_bim_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bmi.defaultsettingsbim_config');
    
  $sql = 'SELECT underweight_text, normalweight_text, overweight_text, obesity_text FROM {bmi_settings}';
  $result = db_query($sql);
  $data = array();
  foreach ($result as $weight_text) {
      
    $data['underweight_text'] 
            = isset($weight_text->underweight_text) ? $weight_text->underweight_text : 'Underweight';
    $data['normalweight_text'] 
            = isset($weight_text->normalweight_text) ? $weight_text->normalweight_text : 'Normal/Healthy';
    $data['overweight_text'] 
            = isset($weight_text->overweight_text) ? $weight_text->overweight_text : 'Overweight';
    $data['obesity_text'] 
            = isset($weight_text->obesity_text) ? $weight_text->obesity_text : 'Obesity';
  }
    
    
    
    $form['underweight'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Underweight'),
      '#description' => $this->t('underweight'),
      '#default_value' => $data['underweight_text'] ,
    );
    $form['normalweight'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Normalweight'),
      '#description' => $this->t('normalweight'),
      '#default_value' => $data['normalweight_text'] ,
    );
    $form['overweight'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Overweight'),
      '#description' => $this->t(''),
      '#default_value' => $data['overweight_text']  ,
    );
    $form['obesity'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Obesity'),
      '#description' => $this->t(''),
      '#default_value' => $data['obesity_text'] ,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('bmi.defaultsettingsbim_config')
      ->set('underweight', $form_state->getValue('underweight'))
      ->set('normalweight', $form_state->getValue('normalweight'))
      ->set('overweight', $form_state->getValue('overweight'))
      ->set('obesity', $form_state->getValue('obesity'))
      ->save();
    
    
     $count = db_query('SELECT COUNT(id) FROM {bmi_settings}')->fetchField();
  if ($count > 0) {
   //update
   db_update('bmi_settings')
   ->fields(array(
            'underweight_text' => $form_state->getValue('underweight') ,
            'normalweight_text'=> $form_state->getValue('normalweight'),
            'overweight_text'  => $form_state->getValue('overweight'),
            'obesity_text'     => $form_state->getValue('obesity') ,
   ))
   ->execute();
  } else {
    db_insert('bmi_settings')
    ->fields(array(
             'underweight_text' => $form_state->getValue('underweight') ,
            'normalweight_text'=> $form_state->getValue('normalweight'),
            'overweight_text'  => $form_state->getValue('overweight'),
            'obesity_text'     => $form_state->getValue('obesity') ,
   ))
   ->execute();
  }
    
    drupal_set_message( ' Module has been configured .' ) ; 
    
  }

}
