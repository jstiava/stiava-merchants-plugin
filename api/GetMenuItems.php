<?php

namespace StiavaMerchantsApp\API;

use Exception;
use StiavaMerchantsApp\Includes\Endpoint;

class GetMenuItems extends Endpoint
{
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

    public function run($menu_slug) {
        return $this->implement($menu_slug);
    }

    public function request_callback($request)
    {
        $id = $request['menu'];
        return $this->implement($id);
    }

    private function implement($id)
    {

        $loc = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object($loc[$id]);

        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menu_item_ids = array();
        $last = null;

        foreach ($menu_items as $item) {
            if ($item->menu_item_parent === "0") {
                $menu_item_ids['_' . $item->ID] = array(
                    "title" => html_entity_decode($item->title),
                    "url" => $item->url,
                    "classes" => $item->classes
                );
                $last = &$menu_item_ids['_' . $item->ID];
            }
            else if (isset($menu_item_ids['_' . $item->menu_item_parent])) {
                $menu_item_ids['_' . $item->menu_item_parent]['children']['_' . $item->ID] = array(
                    "id" => $item->ID,
                    "title" => html_entity_decode($item->title),
                    "url" => $item->url,
                    "classes" => $item->classes
                );
                $last = &$menu_item_ids['_' . $item->menu_item_parent]['children']['_' . $item->ID];
            }
            else {
                $last['children'][$item->ID] = array(
                    "id" => $item->ID,
                    "title" => html_entity_decode($item->title),
                    "url" => $item->url,
                    "content" => html_entity_decode($item->post_content),
                    "classes" => $item->classes
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