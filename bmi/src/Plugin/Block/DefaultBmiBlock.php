<?php

/**
 * @file
 * Contains Drupal\bmi\Plugin\Block\DefaultBmiBlock.
 */

namespace Drupal\bmi\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'DefaultBmiBlock' block.
 *
 * @Block(
 *  id = "default_bmi_block",
 *  admin_label = @Translation("Default bmi block"),
 * )
 */
class DefaultBmiBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('name'),
      '#description' => $this->t(''),
      '#default_value' => isset($this->configuration['name']) ? $this->configuration['name'] : '',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['name'] = $form_state->getValue('name');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $form = \Drupal::formBuilder()->getForm('\Drupal\bmi\Form\DefaultBmiViewForm');
    $build['default_bmi_block_name']['#markup'] = render($form) ;

    return $build;
  }

}
