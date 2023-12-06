<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;
use StiavaMerchantsApp\Includes\Schedule;

/**
 * Edits to implementation are copied within
 * - PostNewComponentWithMeta
 */
class DeleteVenueAndHours extends Endpoint
{
  public function __construct()
  {
    $url = '/venue';
    $method = 'DELETE';
    $callback = array($this, 'request_callback');
    $params = [];

    parent::__construct($url, $method, $callback, $params);
  }

  public function request_callback($request)
  {

    return $this->implement($request);
  }


  private function implement($data)
  {
    global $wpdb;

    $hours_table = $wpdb->prefix . 'jsmp_hours';
    $components_table = $wpdb->prefix . 'jsmp_components';
    $commeta_table = $wpdb->prefix . 'jsmp_commeta';

    $id = $data['id'];

    if ($wpdb->delete($components_table, array('ID' => $id)) === false) {
      return false; // Handle deletion error
    }

    if ($wpdb->delete($hours_table, array('com_id' => $id)) === false) {
      return false; // Handle deletion error
    }
    
    if ($wpdb->delete($commeta_table, array('com_id' => $id)) === false) {
      return false; // Handle deletion error
    }
    
    return true; // Deletion successful
  }

  public static function append()
  {
    $endpoint = new DeleteVenueAndHours();
    $endpoint->register();
  }
}
