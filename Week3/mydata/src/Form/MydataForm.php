<?php
namespace Drupal\mydata\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MydataForm.
 *
 * @package Drupal\mydata\Form
 */
class MydataForm extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mydata_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
    $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('mydata', 'm')
          ->condition('id', $_GET['num'])
          ->fields('m');
        $record = $query->execute()->fetchAssoc();
    }
    $form['Employee_Name'] = array(
      '#type' => 'textfield',
      '#title' => t('Employee Name:'),
      '#required' => TRUE,
      //'#default_values' => array(array('id')),
      '#default_value' => (isset($record['Name']) && $_GET['num']) ? $record['Name']:'',
      );
    $form['Employee_Email'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['Email']) && $_GET['num']) ? $record['Email']:'',
      );
    $form['Employee_Gender'] = array (
      '#type' => 'select',
      '#title' => ('Gender'),
      '#options' => array(
      'Female' => t('Female'),
      'Male' => t('Male'),
      '#default_value' => (isset($record['Gender']) && $_GET['num']) ? $record['Gender']:'',
        ),
      );
    $form['Employee_Job_Title'] = array(
      '#type' => 'textfield',
      '#title' => t('Employee Job Title:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['Job_Title']) && $_GET['num']) ? $record['Job_Title']:'',
    );
    $form['Employee_Department'] = array(
      '#type' => 'textfield',
      '#title' => t('Employee Department:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['Department']) && $_GET['num']) ? $record['Department']:'',
    );
    $form['Employee_Mobile'] = array(
      '#type' => 'textfield',
      '#title' => t('Employee Mobile Number:'),
      '#default_value' => (isset($record['Mobile']) && $_GET['num']) ? $record['Mobile']:'',
      );
    $form['Employee_Address'] = array(
      '#type' => 'textfield',
      '#title' => t('Employee Address:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['Address']) && $_GET['num']) ? $record['Address']:'',
    );
    $form['Employee_Zipcode'] = array(
      '#type' => 'textfield',
      '#title' => t('Employee Zipcode:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['Zipcode']) && $_GET['num']) ? $record['Zipcode']:'',
    );
  $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'save',
      //'#value' => t('Submit'),
    ];
    return $form;
  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
        $name = $form_state->getValue('Employee_Name');
          if(preg_match('/[^A-Za-z]/', $name)) {
             $form_state->setErrorByName('Employee_Name', $this->t('your name must in characters without space'));
          }
          if (strlen($form_state->getValue('Employee_Mobile')) < 10 ) {
            $form_state->setErrorByName('Employee_Mobile', $this->t('your mobile number must in 10 digits'));
          }
    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $name=$field['Employee_Name'];
    $email=$field['Employee_Email'];
    $gender=$field['Employee_Gender'];
    $job=$field['Employee_Job_Title'];
    $department=$field['Employee_Department'];
    $number=$field['Employee_Mobile'];
    $address=$field['Employee_Address'];
    $zipcode=$field['Employee_Zipcode'];
   
    if (isset($_GET['num'])) {
          $field  = array(
            'Name'   => $name,
            'Email'  => $email,
            'Gender'  => $gender,
            'Job_Title'  => $job,
            'Department'  => $department,
            'Mobile'  => $number,
            'Address' => $address,
            'Zipcode'  => $zipcode,
          );
          $query = \Drupal::database();
          $query->update('mydata')
                ->fields($field)
                ->condition('id', $_GET['num'])
                ->execute();
          $form_state->setRedirect('mydata.display_table_controller_display');
      }
       else
       {
          $field  = array(
            'Name'   => $name,
            'Email'  => $email,
            'Gender'  => $gender,
            'Job_Title'  => $job,
            'Department'  => $department,
            'Mobile'  => $number,
            'Address' => $address,
            'Zipcode'  => $zipcode,
          );
          $query = \Drupal::database();
          $query ->insert('mydata')
                 ->fields($field)
                 ->execute();
          $response = new RedirectResponse("mydata/hello/table");
          $response->send();
          exit();
       }
     }
}
