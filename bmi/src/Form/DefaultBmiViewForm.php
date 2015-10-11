<?php

/**
 * @file
 * Contains Drupal\bmi\Form\DefaultBmiViewForm.
 */

namespace Drupal\bmi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;


/**
 * Class DefaultBmiViewForm.
 *
 * @package Drupal\bmi\Form
 */
class DefaultBmiViewForm implements \Drupal\Core\Form\FormInterface {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bmi.defaultbmiview_config'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_bmi_view_form';
  }

  
  
   public function buildForm(array $form, FormStateInterface $form_state) {
        

   $form['body_weight'] = array(
    '#type' => 'textfield',
    '#title' => t('Weight'),
    '#size' => 10,
    '#required' => TRUE,

    );
  $weight_units = array("kgs" => "kgs", "lbs" => "lbs");
  $height_units = array("cms" => "cms", "mts" => "mts");
  $form['weight_units'] = array(
    '#type' => 'select',
    '#title' => 'Units',
    '#required' => TRUE,
    '#options' => $weight_units,

    );
  $form['body_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Height'),
    '#size' => 10,
    '#required' => TRUE,

    );
  $form['height_units'] = array(
    '#type' => 'select',
    '#title' => t('Units'),
    '#required' => TRUE,
    '#options' => $height_units,

    );
  $form['bmi_result'] = array(
    '#type' => 'markup',
    '#prefix' => '<div id="bmi_calculate_wrapper">',
    '#suffix' => '</div>',
    '#markup' => '',
  );

    $form['random_user'] = array(
      '#type' => 'button',
      '#value' => 'BMI Value',
      '#ajax' => array(
        'callback' => '::calculateCallback',
        'event' => 'click',
        'effect' => 'fade',
        'progress' => array(
          'type' => 'throbber',
          'message' => 'Getting Random Username',
        ),
        
      ),
    );
    return $form;
        
        
    }

    public function addMoreCallback(array &$form, FormStateInterface $form_state) {
       
        //$form['body_weight']['title'] = 'result' ; 
        //$form_state['rebuild'] = TRUE ; 
        return $form['body_weight'] ; 
        /*
         * $form['body_weight'] = array(
    '#type' => 'textfield',
    '#title' => t('Weight'),
    '#size' => 10,
    '#required' => TRUE,
    '#prefix' => '<div style="float:left;">',
    '#suffix' => '</div>',
    );
         */
  }

public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message('Nothing Submitted. Just an Example.');
  }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        
    }

  public function calculateCallback(array &$form, FormStateInterface $form_state) {
    // Get all User Entities.

      
  $body_weight = $form_state->getValue('body_weight');
  $body_height = $form_state->getValue('body_height');
  $weight_unit = $form_state->getValue('weight_units');
  $height_unit = $form_state->getValue('height_units');   
  
  
  if ((is_numeric($body_weight)) && (is_numeric($body_height))) {
    $body_weight = self::convert_weight_kgs($body_weight, $weight_unit);
    $body_height = self::convert_height_mts($body_height, $height_unit);
    $bmi = 1.3*$body_weight/pow($body_height,2.5);
    $bmi = round($bmi, 2);
    $bmi_std = $body_weight/($body_height*$body_height);
    $bmi_std = round($bmi_std, 2);
    $bmi_text = self::get_bmi_text($bmi);
    $output = t("Your BMI value according to the Quetelet formula is");
    $output .= " <b>". $bmi_std ."</b><br>";
    $output .= t("Your adjusted BMI value according to Nick Trefethen of 
	  <a href='http://www.ox.ac.uk/media/science_blog/130116.html' target='_blank'>Oxford University's Mathematical Institute</a> is");
    $output .= " <b>". $bmi ."</b><br>". $bmi_text;
  }
  else {
    $output = "Please enter numeric values for weight and height fields";
  }

   $ajax_response = new AjaxResponse(); 
   $ajax_response->addCommand(new HtmlCommand('#bmi_calculate_wrapper', $output));
   $ajax_response->addCommand(new InvokeCommand('#bmi_calculate_wrapper', 'css',array('color','green')));
   return $ajax_response;
      
  }
  
  
  public static function convert_weight_kgs($body_weight = NULL, $weight_unit = NULL) {
  if ($weight_unit == 'lbs') {
  // 1pound = 0.4359237
    return $body_weight * 0.4536;
  }
  // return the weight as is bcoz it is in kg only
    return $body_weight;
}

public static function convert_height_mts($body_height = NULL, $height_unit = NULL) {
  switch ($height_unit) {
    case 'mts':
      return $body_height;
      break;
    case 'cms':
    // 1 cms = 0.01 m.		
      return $body_height * 0.01;
  }
}

public static function get_bmi_text($bmi = NULL) {
  if ($bmi <= 18.5)
    $column = 'underweight_text';
  elseif ($bmi > 18.5 && $bmi <= 24.9)
    $column = 'normalweight_text';
  elseif ($bmi >24.9 && $bmi <= 29.9)
    $column = 'overweight_text';
  elseif ($bmi > 29.9)
    $column = 'obesity_text';
  $sql = 'SELECT '. $column .' FROM {bmi_settings}';
  $result = db_query($sql);
  foreach ($result as $weight_text) {
    return $weight_text->$column;
  }
    return NULL;
}
  
  
}

