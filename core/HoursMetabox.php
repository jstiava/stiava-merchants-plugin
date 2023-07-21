<?php

namespace StiavaMerchantsApp\Core;

class HoursMetabox
{
    public static function merchant_description_picker()
    {
        add_meta_box(
            'hoursmetabox', 
            __('Description', 'text-domain'),
             [self::class, 'merchant_hours_metabox'],
              'merchants', 'advanced', 'low');
    }


    public static function merchant_hours_metabox($post)
    {
        $description = get_post_meta($post->ID, 'description', true);

        if ($description) {
            $content = '<textarea name="_merchant_description" style="min-height: 10em">'. $description .'</textarea>';
        }
        else {
            $content = '<textarea name="_merchant_description"></textarea>';
        }

        echo $content;
    }


    public static function merchant_description_save($post_id)
    {
        if (isset($_POST['_merchant_description'])) {
            $color_hex = (string) $_POST['_merchant_description'];
            update_post_meta($post_id, 'description', $color_hex);
        }
    }


    public static function run()
    {
        add_action('add_meta_boxes', [self::class, 'merchant_description_picker']);
        add_action('save_post', [self::class, 'merchant_description_save'], 10, 1);
    }   
}