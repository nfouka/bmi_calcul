<?php
/**
 * @file
 * Contains \Drupal\hello_world\Controller\HelloController.
 */
namespace Drupal\bmi\Controller;

class DefaultController extends \Drupal\Core\Controller\ControllerBase  {
    
    
  public function bmi(){
          
      $form = \Drupal::formBuilder()->getForm('\Drupal\bmi\Form\DefaultBmiViewForm');
      
    return [
      '#theme' => 'index_page',
      '#all'  => $form  , 
      '#name' => $form  , 
    ];
      
  }  
    
    
  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Hello, World!'),
    );
  }
  
  public function ajax(){
      return new \Symfony\Component\HttpFoundation\Response('Ajax form API PHP5 ,') ; 
  }
  
}
