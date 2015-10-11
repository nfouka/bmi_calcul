<?php

/**
 * @file
 * Contains Drupal\bmi\Form\SettingsForm.
 */

namespace Drupal\bmi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\bmi\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bmi.settings_config'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bmi.settings_config');
    $form['nom'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('nom'),
      '#description' => $this->t('nom de personne'),
      '#default_value' => $config->get('nom'),
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

    $this->config('bmi.settings_config')
      ->set('nom', $form_state->getValue('nom'))
      ->save();
  }

}
