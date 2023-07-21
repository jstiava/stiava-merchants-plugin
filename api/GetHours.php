<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;

class GetHours extends Endpoint
{
    public function __construct()
    {
        $url = '/hours';
        $method = 'GET';
        $callback = array($this, 'request_callback');
        $params = [
            'merchant' => [
                'required' => true,
                'type' => 'integer',
            ],
            'date' => [
                'required' => false,
                'type' => 'integer'
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
        $date = $request['date'];

        if (isset($date) && $date !== null) {
            // Prepare the SQL query
            $sql = "SELECT * FROM $hours_table
            WHERE (merchant_id = $id OR merchant_id IS NULL)
            AND (`start` < $date)
            ORDER BY `end` ASC";
        } else {
            // Prepare the SQL query
            $sql = "SELECT * FROM $hours_table
            WHERE (merchant_id = $id OR merchant_id IS NULL)
            ORDER BY `end` ASC";
        }



        // Prepare and execute the query
        $statement = $wpdb->prepare($sql);
        $wpdb->query($statement);
        $results = $wpdb->get_results($statement);

        foreach ($results as &$item) {
            $item->days = json_decode($item->days);
            $item->schemes = json_decode($item->schemes);
        }

        return $results;

    }

    public static function append()
    {
        $endpoint = new GetHours();
        $endpoint->register();
    }

}