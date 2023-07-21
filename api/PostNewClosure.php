<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;
use StiavaMerchantsApp\Includes\Schedule;

/**
 * Edits to implementation are copied within
 * - PostNewComponentWithMeta
 */
class PostNewClosure extends Endpoint
{
    public function __construct()
    {
        $url = '/closure';
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

        
        $hours_table = $wpdb->prefix . 'jsmp_hours';
        
        if ($data['name'] == "") {
            $data['name'] = "Regular Hours";
        }
        
        if (!isset($data['com_id'])) {
            $data['com_id'] = null;
        }
        
        $new_hours = array(
            'merchant_id' => $data['merchant_id'],
            'com_id' => $data['com_id'],
            'name' => $data['name'],
            'start' => $data['start'],
            'end' => $data['end'],
            'days' => null,
            'schemes' => null,
            'asString' => "Closed",
            'type' => 'closure',
            'priority' => $data['end'] - $data['start']
        );
        $wpdb->insert($hours_table, $new_hours);
        

        return $wpdb;
    }

    public static function append()
    {
        $endpoint = new PostNewClosure();
        $endpoint->register();
    }

}