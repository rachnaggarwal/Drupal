<?php

namespace Drupal\mydata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DisplayTableController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'mydata_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    /**return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/

    //create table header
    $header_table = array(
      'id'=>    t('SrNo'),
      'Name' => t('Employee_Name'),
      'Email' => t('Employee_Email'),
      'Gender' => t('Employee_Gender'),
      'Job_Title' => t('Job Title'),
      'Mobile' => t('Employee_Mobile'),
      //'website' => t('Web site'),
      'opt' => t('operations'),
      'opt1' => t('operations'),
    );

//select records from table
    $query = \Drupal::database()->select('mydata', 'm');
    $query->fields('m', ['id','Name','Email','Gender','Job_Title','Department','Mobile','Address','Zipcode']);
    $results = $query->execute()->fetchAll();
    $rows=array();
    foreach($results as $data){
      $delete = Url::fromUserInput('/mydata/form/delete/'.$data->id);
      $edit   = Url::fromUserInput('/mydata/form/mydata?num='.$data->id);

      //print the data from table
      $rows[] = array(
        'id' =>$data->id,
        'Name' => $data->Name,
        'Email' => $data->Email,
        'Gender' => $data->Gender,
        'Job_Title' => $data->Job_Title,
        'Mobile' => $data->Mobile,
        Link::fromTextAndUrl(t('Delete'), $delete),
        Link::fromTextAndUrl(t('Edit'), $edit),
      );
    }
    //display data in site
    $form['table'] = [
        '#type' => 'table',
        '#header' => $header_table,
        '#rows' => $rows,
        '#empty' => t('No users found'),
      ];
    return $form;
  }
}
