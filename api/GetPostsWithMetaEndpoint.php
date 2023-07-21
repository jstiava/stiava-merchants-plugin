<?php

namespace StiavaMerchantsApp\API;

use StiavaMerchantsApp\Includes\Endpoint;

class GetPostsWithMetaEndpoint extends Endpoint
{
    public function __construct()
    {
        $url = '/posts_with_meta';
        $method = 'GET';
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

        $query = "
            SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title, {$wpdb->postmeta}.*
            FROM {$wpdb->posts}
            INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id";

        $results = $wpdb->get_results($query);

        $data = array();

        if ($results) {
            foreach ($results as $result) {
                $postID = $result->ID;
                $postTitle = $result->post_title;

                // Group rows with the same post_id into the same JSON object
                if (!isset($data[$postID])) {
                    $data[$postID] = array(
                        'ID' => $postID,
                        'post_title' => $postTitle,
                        'meta_data' => (object) array()
                    );
                }

                $data[$postID]['meta_data']->{$result->meta_key} = $result->meta_value;
            }
        }

        return $data;

    }

    public static function append()
    {
        $endpoint = new GetPostsWithMetaEndpoint();
        $endpoint->register();
    }

}