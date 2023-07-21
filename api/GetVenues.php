<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;

class GetVenues extends Endpoint
{
    public function __construct()
    {
        $url = '/venues';
        $method = 'GET';
        $callback = array($this, 'request_callback');
        $params = [
            'merchant' => [
                'required' => true,
                'type' => 'integer',
            ]
        ];

        parent::__construct($url, $method, $callback, $params);
    }

    public function request_callback($request)
    {
        return $this->implement($request);
    }

    private function implement($request)
    {
        global $wpdb;

        $components_table = $wpdb->prefix . 'jsmp_components';
        $id = $request['merchant'];

        $sql = "SELECT * FROM $components_table
                WHERE merchant_id = $id
                AND com_type = 'Venue'";

        // Prepare and execute the query
        $statement = $wpdb->prepare($sql);
        $wpdb->query($statement);
        $results = $wpdb->get_results($statement);

        return $results;

    }

    public static function append()
    {
        $endpoint = new GetVenues();
        $endpoint->register();
    }

}