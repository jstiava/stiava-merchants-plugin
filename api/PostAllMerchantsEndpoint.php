<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;

class PostAllMerchantsEndpoint extends Endpoint
{
    public function __construct()
    {
        $url = '/merchants';
        $method = 'POST';
        $callback = array($this, 'request_callback');
        $params = [];

        parent::__construct($url, $method, $callback, $params);
    }

    public function request_callback($request)
    {
        return $this->implement($request);
    }

    private function implement($request)
    {

        global $wpdb;

        $args = [
            'post_type' => 'merchants',
            'numberposts' => -1,
        ];
        $posts = get_posts($args);
    
        foreach ($posts as $post) {
            $id = $post->ID;
            
            update_post_meta($id, 'isMarket', true);
    
            // Reset the post data to ensure other functions/processes work correctly
            wp_reset_postdata();
        }
    
        return ["success"];

    }

    public static function append()
    {
        $endpoint = new PostAllMerchantsEndpoint();
        $endpoint->register();
    }

}