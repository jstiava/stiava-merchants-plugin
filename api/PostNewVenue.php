<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;

/**
 * Edits to implementation are copied within
 * - PostNewComponentWithMeta
 */
class PostNewVenue extends Endpoint
{
    public function __construct()
    {
        $url = '/venues';
        $method = 'POST';
        $callback = array($this, 'request_callback');
        $params = [];

        parent::__construct($url, $method, $callback, $params);
    }

    public function request_callback($request)
    {
        $params = $request->get_params();

        return $this->implement($params);
    }


    private function implement($data)
    {
        global $wpdb;

        $components_table = $wpdb->prefix . 'jsmp_components';

        $new_venue = array(
            'merchant_id' => $data['merchant_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'com_type' => 'Venue'
        );
        $wpdb->insert($components_table, $new_venue);

        return $wpdb->insert_id;
    }

    public static function append()
    {
        $endpoint = new PostNewVenue();
        $endpoint->register();
    }

}