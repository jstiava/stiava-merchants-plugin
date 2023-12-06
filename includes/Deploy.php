<?php

namespace StiavaMerchantsApp\Includes;

use StiavaMerchantsApp\API\GetMenuItems;

class Deploy
{
    /**
     * Register the JavaScript for the admin area.
     * @copyright ReactPress
     * @since    1.0.0
     */
    public static function stiava_react_hours_manager()
    {
        global $post;

        wp_enqueue_script('react-app-admin', plugin_dir_url(__FILE__) . '../js/react-apps-init.js', array('jquery'), '1', true);

        // Send information to the React app
        wp_localize_script('react-app-admin', 'reactAdminContext', array(
            'post' => $post,
            'isMarket' => get_post_meta($post->ID, 'isMarket'),
            'primary' => get_post_meta($post->ID, '_merchant_primary_color')[0],
            'secondary' => get_post_meta($post->ID, '_merchant_secondary_color')[0],
        ));

        // React app
        $react_app_dir = plugin_dir_url(__FILE__) . '../apps/stiava-react-hours-manager/';
        $react_app_build = $react_app_dir . 'build/';

        require_once(__DIR__ . '/../apps/stiava-react-hours-manager/build/asset-manifest.php');

        // Convert the resulting array to JSON and decode it as a PHP array
        $files_data = json_decode($asset_manifest_json);
        if ($files_data === null)
            return;


        if (!property_exists($files_data, 'entrypoints'))
            return false;

        // Get assets links.
        $assets_files = $files_data->entrypoints;

        $js_files = array_filter(
            $assets_files,
            fn($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'js'
        );
        $css_files = array_filter(
            $assets_files,
            fn($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'css'
        );

        // Load css files.
        foreach ($css_files as $index => $css_file) {
            wp_enqueue_style('react-app-' . $index, $react_app_build . $css_file);
        }

        // Load js files.
        foreach ($js_files as $index => $js_file) {
            wp_enqueue_script('react-app-' . $index, $react_app_build . $js_file, array('jquery'), '1', true);
        }
    }


    /**
     * Register the JavaScript for the admin area.
     * @copyright ReactPress
     * @since    1.0.0
     */
    public static function stiava_react_footer()
    {
        // React app
        $react_app_dir = plugin_dir_url(__FILE__) . '../apps/stiava-react-ccs-footer/';
        $react_app_build = $react_app_dir . 'build/';

        require_once(__DIR__ . '/../apps/stiava-react-ccs-footer/build/asset-manifest.php');

        // Convert the resulting array to JSON and decode it as a PHP array
        $files_data = json_decode($asset_manifest_json);
        if ($files_data === null)
            return;


        if (!property_exists($files_data, 'entrypoints'))
            return false;

        // Get assets links.
        $assets_files = $files_data->entrypoints;

        $js_files = array_filter(
            $assets_files,
            fn($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'js'
        );
        $css_files = array_filter(
            $assets_files,
            fn($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'css'
        );

        // Load css files.
        foreach ($css_files as $index => $css_file) {
            wp_enqueue_style('react-footer-app-' . $index, $react_app_build . $css_file);
        }

        // Load js files.
        foreach ($js_files as $index => $js_file) {
            wp_enqueue_script('react-footer-app-' . $index, $react_app_build . $js_file, array('jquery'), '1', true);
        }
    }



    /**
     * Register the JavaScript for the admin area.
     * @copyright ReactPress
     * @since    1.0.0
     */
    public static function stiava_react_header()
    {

        wp_enqueue_script('react-app-admin', plugin_dir_url(__FILE__) . '../js/react-apps-init.js', array('jquery'), '1', true);

        $menu = new GetMenuItems();
        $main_menu = $menu->run("my_main_menu");


        // Send information to the React app
        wp_localize_script('react-app-admin', 'reactAdminContext', array(
            'main_menu' => $main_menu
        ));

        // React app
        $react_app_dir = plugin_dir_url(__FILE__) . '../apps/stiava-react-ccs-header/';
        $react_app_build = $react_app_dir . 'build/';

        require_once(__DIR__ . '/../apps/stiava-react-ccs-header/build/asset-manifest.php');

        // Convert the resulting array to JSON and decode it as a PHP array
        $files_data = json_decode($asset_manifest_json);
        if ($files_data === null)
            return;


        if (!property_exists($files_data, 'entrypoints'))
            return false;

        // Get assets links.
        $assets_files = $files_data->entrypoints;

        $js_files = array_filter(
            $assets_files,
            fn($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'js'
        );
        $css_files = array_filter(
            $assets_files,
            fn($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'css'
        );

        // Load css files.
        foreach ($css_files as $index => $css_file) {
            wp_enqueue_style('react-app-' . $index, $react_app_build . $css_file);
        }

        // Load js files.
        foreach ($js_files as $index => $js_file) {
            wp_enqueue_script('react-app-' . $index, $react_app_build . $js_file, array('jquery'), '1', true);
        }
    }

}