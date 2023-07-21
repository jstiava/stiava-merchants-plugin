<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;
use StiavaMerchantsApp\Includes\Schedule;

/**
 * Edits to implementation are copied within
 * - PostNewComponentWithMeta
 */
class DeleteHours extends Endpoint
{
    public function __construct()
    {
        $url = '/hours';
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

        $id = $data['id'];

        $result = $wpdb->delete($hours_table, array('ID' => $id)); // Delete the row with matching ID

        if ($result === false) {
            return false; // Handle deletion error
        }

        return true; // Deletion successful
    }

    public static function append()
    {
        $endpoint = new DeleteHours();
        $endpoint->register();
    }

}