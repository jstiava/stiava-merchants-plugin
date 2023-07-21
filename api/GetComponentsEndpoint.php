<?php

namespace StiavaMerchantsApp\API;
use StiavaMerchantsApp\Includes\Endpoint;

class GetComponentsEndpoint extends Endpoint
{
    public function __construct()
    {
        $url = '/components';
        $method = 'GET';
        $callback = array($this, 'request_callback');
        $params = [
            'merchant' => [
                'required' => false,
                'type' => 'integer',
            ],
            'metadata' => [
                'required' => false,
                'default' => false,
                'type' => 'boolean',
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
        $params = $request->get_query_params();

        $args = [
            'post_type' => 'merchants',
            'numberposts' => -1,
        ];
        $args = get_posts($args);

        $merchants = get_posts($args);
        $data = [];

        foreach($merchants as $i => $merchant)
        {
            $id = $merchant->ID;
            $metadata = get_post_meta($id);

            $data[$i] = $metadata;
        }

        return $data;

    }

    public static function append()
    {
        $endpoint = new GetMerchantsEndpoint();
        $endpoint->register();
    }

}