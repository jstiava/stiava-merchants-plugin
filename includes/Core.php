<?php

namespace StiavaMerchantsApp\Includes;

use StiavaMerchantsApp\Core\ColorPicker;
use StiavaMerchantsApp\Core\MerchantIconPicker;
use StiavaMerchantsApp\Core\DescriptionMetabox;
use StiavaMerchantsApp\Core\CoordinatesMetabox;
use StiavaMerchantsApp\Core\ComponentsDashboard;

/**
 * Installation database reconfigurations
 * Custom post types
 * Custom post type metadata
 */
class Core
{

    public static function jsmp_install()
    {
        global $wpdb;
        global $jal_db_version;
        $jal_db_version = '1.1';

        $table_name1 = $wpdb->prefix . 'jsmp_components';
        $charset_collate = $wpdb->get_charset_collate();
        $sql1 = "CREATE TABLE $table_name1 (
        ID BIGINT(20) NOT NULL AUTO_INCREMENT,
        merchant_id BIGINT(20),
        title VARCHAR(255) NOT NULL,
        description TEXT,
        com_type VARCHAR(50) NOT NULL,
        PRIMARY KEY (ID),
        INDEX idx_merchant_id (merchant_id),
        INDEX idx_com_type (com_type)
    ) $charset_collate;";

        $table_name2 = $wpdb->prefix . 'jsmp_commeta';
        $charset_collate = $wpdb->get_charset_collate(); // Retrieve the charset collate again
        $sql2 = "CREATE TABLE $table_name2 (
        ID BIGINT(20) NOT NULL AUTO_INCREMENT,
        com_id BIGINT(20) NOT NULL,
        meta_key VARCHAR(255) NOT NULL,
        meta_value LONGTEXT,
        PRIMARY KEY (ID)
    ) $charset_collate;";

        $table_name3 = $wpdb->prefix . 'jsmp_hours';
        $charset_collate = $wpdb->get_charset_collate();
        $sql3 = "CREATE TABLE $table_name3 (
        ID BIGINT(20) NOT NULL AUTO_INCREMENT,
        merchant_id BIGINT(20),
        com_id BIGINT(20),
        name TEXT NOT NULL,
        start BIGINT(10),
        days JSON,
        schemes JSON,
        asString TEXT NOT NULL,
        end BIGINT(10),
        type ENUM('standard', 'special', 'closure'),
        priority INT,
        PRIMARY KEY (ID),
        INDEX idx_merchant_priority (merchant_id, priority)
    ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);


        // Store the plugin version or any other relevant data in options
        add_option('jal_db_version', $jal_db_version);
    }

    public static function jsmp_custom_post_types()
    {
        register_post_type(
            'merchants',
            array(
                'labels' => array(
                    'name' => __('Merchants'),
                    'singular_name' => __('Merchant')
                ),
                'supports' => array(
                    'title',
                    'editor',
                    'excerpt',
                    'author',
                    'thumbnail',
                    'comments',
                    'revisions',
                    'custom-fields',
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => get_template_directory_uri() . '/icons/bear_bucks_icon.svg',
                'rewrite' => array('slug' => 'merchant'),
                'hierarchical' => false,
                'show_in_rest' => true,
                'revisions' => false,
            )
        );

        register_taxonomy(
            'merchant_categories',
            'merchants',
            array(
                'hierarchical' => true,
                'label' => __('Categories'),
                'singular_name' => __('Category'),
                'rewrite' => true,
                'show_in_rest' => true,
                'query_var' => true,
            )
        );

        register_taxonomy(
            'merchant_locations',
            'merchants',
            array(
                'hierarchical' => true,
                'label' => __('Locations'),
                'singular_name' => __('Location'),
                'rewrite' => true,
                'show_in_rest' => true,
                'query_var' => true,
            )
        );

        register_taxonomy(
            'merchant_nutrition',
            'merchants',
            array(
                'hierarchical' => true,
                'label' => __('Nutritional Factors'),
                'singular_name' => __('Factor'),
                'rewrite' => true,
                'show_in_rest' => true,
                'query_var' => true,
            )
        );

        register_taxonomy(
            'merchant_campus',
            'merchants',
            array(
                'hierarchical' => false,
                'label' => __('Campuses'),
                'singular_name' => __('Campus'),
                'rewrite' => true,
                'show_in_rest' => true,
                'query_var' => true,
            )
        );

        register_taxonomy(
            'merchant_currencies',
            'merchants',
            array(
                'hierarchical' => false,
                'label' => __('Currencies'),
                'singular_name' => __('Currency'),
                'rewrite' => true,
                'show_in_rest' => true,
                'query_var' => true,
            )
        );
    }

    public static function jsmp_custom_post_metadata()
    {

        register_post_meta('merchants', 'description', [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ]);

        register_post_meta('merchants', 'color_scheme', [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'object',
        ]);

        register_post_meta('merchants', 'icon_id', [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'object',
        ]);

        register_post_meta('merchants', 'icon_type', [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ]);

        register_post_meta('merchants', 'coordinates', [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'object',
        ]);

        register_post_meta('merchants', 'isMarket', [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'boolean',
            'default' => false
        ]);
    }


    public static function run()
    {
        self::jsmp_install();
        add_action('init', [self::class, 'jsmp_custom_post_types']);
        add_action('init', [self::class, 'jsmp_custom_post_metadata']);
        // ColorPicker::run();
        MerchantIconPicker::run();
        DescriptionMetabox::run();
        CoordinatesMetabox::run();
        ComponentsDashboard::run();
    }
}