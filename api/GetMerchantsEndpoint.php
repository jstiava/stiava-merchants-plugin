<?php

namespace StiavaMerchantsApp\API;

use Exception;
use StiavaMerchantsApp\Includes\Endpoint;

class GetMerchantsEndpoint extends Endpoint
{

    private $metakeys = array(
        "active",
        "description",
        "icon_id",
        "icon_type",
        "isMarket"
    );

    private $encoded_metakeys = array(
        "color_scheme",
        "coordinates"
    );

    public function __construct()
    {
        $url = '/merchants';
        $method = 'GET';
        $callback = array($this, 'request_callback');
        $params = [
            'day' => [
                'required' => true,
                'type' => 'integer',
            ],
            'date' => [
                'required' => true,
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
        $date = $request['date'];
        $hours_table = $wpdb->prefix . 'jsmp_hours';
        $components_table = $wpdb->prefix . 'jsmp_components';

        $query = "
            SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title, {$wpdb->posts}.post_name, {$wpdb->postmeta}.*
            FROM {$wpdb->posts}
            INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
            WHERE {$wpdb->posts}.post_type = 'merchants' AND {$wpdb->posts}.post_status = 'publish'";

        $results = $wpdb->get_results($query);

        $merchants = array();
        $master_hours = array();

        if ($results) {
            foreach ($results as $result) {
                $postID = $result->ID;

                // Group rows with the same post_id into the same JSON object
                if (!isset($merchants[$postID])) {
                    $merchants[$postID] = (object) array();
                    $merchants[$postID]->title = html_entity_decode($result->post_title);
                    $merchants[$postID]->slug = html_entity_decode($result->post_name);
                    $merchants[$postID]->permalink = get_permalink($postID);
                    $merchants[$postID]->venues = [];
                    $merchants[$postID]->hours = null;

                    $terms = wp_get_post_terms($postID, 'merchant_categories', array('fields' => 'ids'));

                    $merchants[$postID]->categories = $terms;
                }

                $meta_key = $result->meta_key;
                if ($meta_key === 'icon_id') {
                    $merchants[$postID]->icon = wp_get_attachment_url($result->meta_value);
                } else if (in_array($meta_key, $this->encoded_metakeys)) {
                    $merchants[$postID]->{$meta_key} = json_decode($result->meta_value);
                } else if (in_array($meta_key, $this->metakeys)) {
                    $merchants[$postID]->{$meta_key} = $result->meta_value;
                }
            }
        }

        // Add venues
        $venue_query = "SELECT * FROM $components_table
                WHERE com_type = 'Venue'";

        // Prepare and execute the query
        $statement = $wpdb->prepare($venue_query);
        $wpdb->query($statement);
        $venues = $wpdb->get_results($statement);

        foreach ($venues as &$item) {
            if (isset($merchants[$item->merchant_id])) {
                $item->hours = [];
                $merchants[$item->merchant_id]->venues[$item->ID] = $item;
            }
        }

        // Get active hours
        $hours_query = "SELECT * FROM $hours_table
        WHERE (`start` < $date AND (`end` IS NULL OR $date < `end`))
        ORDER BY `end` ASC";

        $statement = $wpdb->prepare($hours_query);
        $wpdb->query($statement);
        $hours = $wpdb->get_results($statement);

        foreach ($hours as &$item) {
            $item->days = json_decode($item->days);
            $item->schemes = json_decode($item->schemes);
            $venue_id = $item->ID;

            if ($item->merchant_id == null) {
                $master_hours[] = $item;
            } else if ($item->com_id == null) {
                if (isset($merchants[$item->merchant_id])) {
                    $merchants[$item->merchant_id]->hours[$venue_id] = $item;
                }
            } else {
                if (isset($merchants[$item->merchant_id]) && isset($merchants[$item->merchant_id]->venues[$item->com_id])) {
                    $merchants[$item->merchant_id]->venues[$item->com_id]->hours[$venue_id] = $item;
                }
            }
        }

        return array(
            "merchants" => $merchants,
            "chronos" => $master_hours
        );

    }

    public static function append()
    {
        $endpoint = new GetMerchantsEndpoint();
        $endpoint->register();
    }

}