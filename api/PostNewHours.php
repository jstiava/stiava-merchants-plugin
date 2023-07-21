<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;
use StiavaMerchantsApp\Includes\Schedule;

/**
 * Edits to implementation are copied within
 * - PostNewComponentWithMeta
 */
class PostNewHours extends Endpoint
{
    public function __construct()
    {
        $url = '/hours';
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
        
        $new_object = new Schedule($data['string']);

        if ($data['name'] == "") {
            $data['name'] = "Regular Hours";
        }

        $type = $data['end'] == null ? 'standard' : ($new_object->days == null ? 'closure' : 'special');

        $new_hours = array(
            'merchant_id' => $data['merchant_id'],
            'com_id' => $data['com_id'],
            'name' => $data['name'],
            'start' => $data['start'],
            'end' => $data['end'],
            'days' => json_encode($new_object->days),
            'schemes' => json_encode($new_object->schemes),
            'asString' => $new_object->stringify(),
            'type' => $type,
            'priority' => $type === 'standard' ? 1000 : $data['end'] - $data['start']
        );
        $wpdb->insert($hours_table, $new_hours);

        return $wpdb->insert_id;
    }

    public static function append()
    {
        $endpoint = new PostNewHours();
        $endpoint->register();
    }

}