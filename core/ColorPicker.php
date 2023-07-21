<?php

namespace StiavaMerchantsApp\Core;

class ColorPicker
{

    public static function my_plugin_admin_scripts($hook)
    {
        if ('post.php' != $hook && 'post-new.php' != $hook) {
            return;
        }
        wp_enqueue_script('wp-color-picker-alpha', plugins_url('../js/wp-color-picker-alpha.min.js', __FILE__), array('jquery'), '3.0.1', true);
        wp_enqueue_script('wp-color-picker-init', plugins_url('../js/wp-color-picker-init.js', __FILE__), array('jquery'), '3.0.1', true);
    }

    public static function merchant_primary_color_picker()
    {
        add_meta_box(
            'colorpickerdiv', 
            __('Merchant Primary Color', 'text-domain'),
             [self::class, 'merchant_primary_color_metabox'],
              'merchants', 'side', 'low');
    }


    public static function merchant_primary_color_metabox($post)
    {
        $color_scheme = json_decode(get_post_meta($post->ID, 'color_scheme', true));

        if ($color_scheme) {
            $content = '<input type="text" class="color-picker" data-alpha-enabled="true" name="_merchant_primary_color" value="'. $color_scheme->primary .'"/>';
            $content .= '<input type="hidden" id="_merchant_secondary_color" name="_merchant_secondary_color" value="'. $color_scheme->secondary.'"/>';
        }
        else {
            $content = '<input type="text" class="color-picker" data-alpha-enabled="true" name="_merchant_primary_color"/>';
            $content .= '<input type="hidden" id="_merchant_secondary_color" name="_merchant_secondary_color" value=""/>';
        }

        echo $content;
    }


    public static function merchant_color_scheme_save($post_id)
    {
        if (isset($_POST['_merchant_primary_color']) && isset($_POST['_merchant_secondary_color'])) {

            $primary = (string) $_POST['_merchant_primary_color'];
            $secondary = (string) $_POST['_merchant_secondary_color'];
            update_post_meta($post_id, 'color_scheme', json_encode([
                "primary" => $primary,
                "secondary" => $secondary
            ]));

        }
    }


    public static function run()
    {
        add_action('admin_enqueue_scripts', [self::class, 'my_plugin_admin_scripts']);
        add_action('add_meta_boxes', [self::class, 'merchant_primary_color_picker']);
        add_action('save_post', [self::class, 'merchant_color_scheme_save'], 10, 1);
    }
}