<?php

namespace StiavaMerchantsApp\Core;

use StiavaMerchantsApp\Includes\Deploy;

class ComponentsDashboard
{
    public static function components_dashboard_deployment($hook)
    {
        if ('post.php' != $hook && 'post-new.php' != $hook) {
            return;
        };
        Deploy::stiava_react_hours_manager();
    }

    public static function components_dashboard_picker()
    {
        add_meta_box('componentsmetabox', __('Manage Hours of Operations', 'text-domain'), [self::class, 'components_dashboard_metabox'], 'merchants', 'normal', 'low');
    }

    public static function components_dashboard_metabox($post)
    {
        $content .= '<div id="root"></div>';
        echo $content;
    }

    public static function components_dashboard_save($post_id)
    {
        // Does not nothing right now
    }

    public static function run()
    {
        add_action('admin_enqueue_scripts', [self::class, 'components_dashboard_deployment']);
        add_action('add_meta_boxes', [self::class, 'components_dashboard_picker']);
        add_action('save_post', [self::class, 'components_dashboard_save'], 10, 1);
    }
}