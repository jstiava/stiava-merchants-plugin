<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;

/**
 * Edits to implementation are copied within
 * - PostNewComponentWithMeta
 */
class UpdateColor extends Endpoint
{
    public function __construct()
    {
        $url = '/color';
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

        $post_id = $data['ID'];
        $primary_color = $data['primary'];
        $secondary_color = $data['secondary'];

        update_post_meta($post_id, '_merchant_primary_color', $primary_color);
        update_post_meta($post_id, '_merchant_secondary_color', $secondary_color);

        return $data['ID'];
    }

    public static function append()
    {
        $endpoint = new UpdateColor();
        $endpoint->register();
    }

}