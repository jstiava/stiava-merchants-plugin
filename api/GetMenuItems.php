<?php

namespace StiavaMerchantsApp\API;

use Exception;
use StiavaMerchantsApp\Includes\Endpoint;

class GetMenuItems extends Endpoint
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
        $url = '/menu';
        $method = 'GET';
        $callback = array($this, 'request_callback');
        $params = [
            'menu' => [
                'required' => true,
                'type' => 'string',
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

        $loc = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object($loc[$request['menu']]);

        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menu_item_ids = array();
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent === "0") {
                $menu_item_ids[$item->ID] = array(
                    "title" => html_entity_decode($item->title),
                    "url" => $item->url
                );
            }
            else {
                $menu_item_ids[$item->menu_item_parent]['children'][] = array(
                    "id" => $item->ID,
                    "title" => html_entity_decode($item->title),
                    "url" => $item->url,
                    "content" => html_entity_decode($item->post_content)
                );
            }
        }

        return $menu_item_ids;

    }

    public static function append()
    {
        $endpoint = new GetMenuItems();
        $endpoint->register();
    }

}