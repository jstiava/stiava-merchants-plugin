<?php

namespace StiavaMerchantsApp\Core;

class CoordinatesMetabox
{
    public static function merchant_coordinates_picker()
    {
        add_meta_box('coordinatesmetaboxdiv', __('Merchant Coordinates', 'text-domain'), [self::class, 'merchant_coordinates_metabox'], 'merchants', 'side', 'low');
    }

    public static function merchant_coordinates_metabox($post)
    {
        $coordinates = json_decode(get_post_meta($post->ID, 'coordinates', true));

        if ($coordinates) {
            $content = '<label for="_merchant_lat">Latitude</label>';
            $content .= '<input type="text" name="_merchant_lat" value="'. $coordinates->lat .'"/>';
            $content .= '<label for="_merchant_lng">Latitude</label>';
            $content .= '<input type="text" name="_merchant_lng" value="'. $coordinates->lng .'"/>';
        }
        else {
            $content = '<label for="_merchant_lat">Latitude</label>';
            $content .= '<input type="text" name="_merchant_lat" value=""/>';
            $content .= '<label for="_merchant_lng">Latitude</label>';
            $content .= '<input type="text" name="_merchant_lng" value=""/>';
        }
        echo $content;
    }

    public static function merchant_coordinates_save($post_id)
    {
        if (isset($_POST['_merchant_lat']) && isset($_POST['_merchant_lng'])) {
            $lat = floatval($_POST['_merchant_lat']);
            $lng = floatval($_POST['_merchant_lat']);
            
            if ($lat != 0 || $lng != 0) {
                update_post_meta($post_id, 'coordinates', null);
                return;
            }
        }

        update_post_meta($post_id, 'coordinates', json_encode(array(
            'lat' => $lat, 
            'lng' => $lng
        )));
    }

    public static function run()
    {
        add_action('add_meta_boxes', [self::class, 'merchant_coordinates_picker']);
        add_action('save_post', [self::class, 'merchant_coordinates_save'], 10, 1);
    }
}