<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;

class GetClosedDays extends Endpoint
{
    public function __construct()
    {
        $url = '/closed_days';
        $method = 'GET';
        $callback = array($this, 'request_callback');
        $params = [
            'merchant' => [
                'required' => false,
                'type' => 'integer',
            ],
            'date' => [
                'required' => false,
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

        $hours_table = $wpdb->prefix . 'jsmp_hours';
        $id = $request['merchant'];
        $date = $request['date'] . '__';

        // Prepare the SQL query
        $sql = $wpdb->prepare("SELECT * FROM $hours_table
            WHERE start LIKE %s
            AND merchant_id = %d", $date, $id);

        // Execute the query
        $results = $wpdb->get_results($sql);

        
        foreach ($results as &$item) {
            $item->days = json_decode($item->days);
            $item->schemes = json_decode($item->schemes);
            $item->asString = json_decode($item->asString);
        }
        
        return $results;
    }

    public static function append()
    {
        $endpoint = new GetClosedDays();
        $endpoint->register();
    }

}