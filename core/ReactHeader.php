<?php

namespace StiavaMerchantsApp\Core;

use StiavaMerchantsApp\Includes\Deploy;

class ReactHeader
{
    public static function react_header_deployment($hook)
    {
        // if (is_page_template('header-react.php') != $hook) {
        //     return;
        // };
        Deploy::stiava_react_header();
    }

    public static function react_header_save($post_id)
    {
        // Does not nothing right now
    }

    public static function run()
    {
        add_action('wp_enqueue_scripts', [self::class, 'react_header_deployment']);
        add_action('save_post', [self::class, 'react_header_save'], 10, 1);
    }
}