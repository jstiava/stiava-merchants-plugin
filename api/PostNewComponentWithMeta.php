<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;
use StiavaMerchantsApp\Includes\Schedule;

class PostNewComponentWithMeta extends Endpoint
{
    public function __construct()
    {
        $url = '/components';
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
        $commeta_table = $wpdb->prefix . 'jsmp_commeta';

        // Extract the 'commeta' element from the component data
        $commeta = $data['commeta'];
        unset($data['commeta']);

        $hours = $data['hours'];
        unset($data['hours']);

        $wpdb->insert($components_table, $data);

        // Get the inserted row ID
        $inserted_row_id = $wpdb->insert_id;

        foreach ($commeta as $meta) {
            $meta_data = array(
                'com_id' => $inserted_row_id,
                'meta_key' => $meta['meta_key'],
                'meta_value' => json_encode($meta['meta_value'])
            );
            $wpdb->insert($commeta_table, $meta_data);
        }

        // Use case similar to PostNewHours
        if (isset($hours)) {
            $hours_table = $wpdb->prefix . 'jsmp_hours';

            $new_object = new Schedule($hours);

            $new_hours = array(
                'merchant_id' => $data['merchant_id'],
                'com_id' => $inserted_row_id,
                'name' => "Regular Hours",
                'start' => null,
                'end' => null,
                'days' => json_encode($new_object->days),
                'schemes' => json_encode($new_object->schemes),
                'asString' => json_encode($new_object->hours_abstractor())
            );
            $wpdb->insert($hours_table, $new_hours);
        }

        // Optional: Return the inserted row ID
        return $inserted_row_id;
    }

    public static function append()
    {
        $endpoint = new PostNewComponentWithMeta();
        $endpoint->register();
    }

}