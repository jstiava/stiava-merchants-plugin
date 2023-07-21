<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;
use StiavaMerchantsApp\Includes\Schedule;

/**
 * Edits to implementation are copied within
 * - PostNewComponentWithMeta
 */
class UpdateHours extends Endpoint
{
    public function __construct()
    {
        $url = '/hours';
        $method = 'PUT';
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

        // Update a closure
        if (!isset($data['string'])) {
            $updated_hours = array(
                'name' => $data['name'],
                'start' => $data['start'],
                'end' => $data['end'],
                'days' => null,
                'schemes' => null,
                'asString' => "Closed",
                'type' => 'closure',
                'priority' => $data['end'] - $data['start']
            );

            $where = array(
                'ID' => $data['ID']
            );
    
            $wpdb->update($hours_table, $updated_hours, $where);
            return $data['ID'];
        }

        // Update a normal hours statement
        $updated_object = new Schedule($data['string']);

        if ($data['name'] == "") {
            $data['name'] = "Regular Hours";
        }

        $type = $data['end'] == null ? 'standard' : ($updated_object->days == null ? 'closure' : 'special');

        $updated_hours = array(
            'name' => $data['name'],
            'start' => $data['start'],
            'end' => $data['end'],
            'days' => json_encode($updated_object->days),
            'schemes' => json_encode($updated_object->schemes),
            'asString' => $updated_object->stringify(),
            'type' => $type,
            'priority' => $type === 'standard' ? 1000 : $data['end'] - $data['start']
        );

        $where = array(
            'ID' => $data['ID']
        );

        $wpdb->update($hours_table, $updated_hours, $where);

        return $data['ID'];
    }

    public static function append()
    {
        $endpoint = new UpdateHours();
        $endpoint->register();
    }

}