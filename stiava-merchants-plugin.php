<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              
 * @since             1.0.2
 * @package           Stiava Merchants Plugin
 *
 * @stiava-merchants-plugin
 * Plugin Name:       Stiava Merchants Plugin
 * Plugin URI:        TODO
 * Description:       Created by Jeremy Stiava, McKelvey School of Engineering, Class of 2024
 * Version:           1.0.1
 * Author:            Stiava
 * Author URI:        
 * License:           MIT
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use StiavaMerchantsApp\Includes\Core;
use StiavaMerchantsApp\Includes\Endpoints;

function jsmp_deactivate()
{
    // For example, if you want to drop the tables created by the plugin, you can use the following code:
    global $wpdb;
    $table_name1 = $wpdb->prefix . 'jsmp_components';
    $table_name2 = $wpdb->prefix . 'jsmp_commeta';
    $table_name3 = $wpdb->prefix . 'jsmp_hours';

    $wpdb->query("DROP TABLE IF EXISTS $table_name1");
    $wpdb->query("DROP TABLE IF EXISTS $table_name2");
    $wpdb->query("DROP TABLE IF EXISTS $table_name3");
    delete_option('jal_db_version');
}

function run_stiava_merchants_app()
{

    Core::run();

    $eps = new Endpoints();
    $eps->run();

    register_deactivation_hook(__FILE__, 'jsmp_deactivate');

}
run_stiava_merchants_app();




// // Update custom meta field
// function save_encoded_hours_metadata($id = false, $post = false)
// {
//     if ($post->post_title == "Auto Draft") {
//         return;
//     }

//     if ($post->post_type == 'merchants') {
//         $data = [];

//         // Hours
//         $normal_hours = get_field('hours_of_operation', $id);
//         $data = process_hours($normal_hours);
//         $encoded_data = json_encode($data);
//         update_post_meta($id, 'hours_regular', $encoded_data);
//     }
// }

// add_action('save_post', 'save_encoded_hours_metadata', 10, 2);


// function check_venues_exists($merchant_id)
// {
//     global $wpdb;

//     $components_table = $wpdb->prefix . 'jsmp_components';

//     $query = $wpdb->prepare(
//         "SELECT COUNT(*)
//         FROM $components_table
//         WHERE merchant_id = %d
//         AND com_type = %s",
//         $merchant_id,
//         "Venues"
//     );

//     $count = $wpdb->get_var($query);

//     return $count > 0 ? true : false;
// }


// // Special Hours, not-scheduled
// function save_encoded_special_hours(
//     $id = false,
//     $name,
//     $hours,
//     $timeline = false,
//     $config = false,
//     $start = false,
//     $end = false
// ) {

//     if ($config == "special") {
//         $data['name'] = $name;
//         $data = process_hours($hours);
//         $data['start'] = $start;
//         $data['end'] = $end;
//         return $data;
//     }

//     // TODO: Idk but a lot

// }

// function washu_dining_get_merchants($request)
// {

//     $params = $request->get_query_params();

//     $args = [
//         'post_type' => 'merchants',
//         'numberposts' => -1,
//     ];
//     $posts = get_posts($args);

//     $data = [];

//     foreach ($posts as $i => $post) {

//         $id = $post->ID;
//         $custom_fields = get_fields($id);

//         $print_terms = [];
//         $categories = get_the_terms($id, 'merchant_categories');
//         if ($categories != false) {
//             foreach ($categories as $category) {
//                 array_push($print_terms, $category->term_id);
//             }
//             ;
//         }

//         $data[$i]['id'] = $id;
//         $data[$i]['title'] = get_the_title($id);
//         $data[$i]['description'] = $custom_fields['description'];
//         $data[$i]['link'] = get_the_permalink($id);
//         $data[$i]['categories'] = $print_terms;


//         $data[$i]['location'] = [floatval($custom_fields['latitude']), floatval($custom_fields['longitude'])];

//         $data[$i]['venues'] = false;
//         if (boolval(get_post_meta($id, 'hasVenues')[0])) {
//             $components = get_components_of_merchant_with_commeta($id);
//             if (empty($components)) {
//                 $components = false;
//             }
//             $data[$i]['venues'] = $components;
//         }

//         try {
//             $regular = get_post_meta($id, 'hours_regular');
//             $data[$i]['schedule']['regular'] = json_decode($regular[0]);
//         } catch (Error $e) {
//             $data[$i]['schedule']['regular'] = false;
//         }

//         $request = array(
//             "merchant" => $id,
//             "today" => $request['date']
//         );
//         $special_hours = get_merchant_special_hours($id, intval($params['date']));

//         if (empty($special_hours)) {
//             $special_hours = false;
//         }
//         $data[$i]['schedule']['special'] = $special_hours;


//         $icon = wp_get_attachment_image_url(get_field('icon', $id), 'large');
//         $data[$i]['esthetics']['image'] = get_the_post_thumbnail_url($id, 'medium');
//         $data[$i]['esthetics']['icon'] = $icon;

//         if ($icon == false) {
//             $data[$i]['esthetics']['icon_type'] = "use_text_only";
//         } else {
//             $data[$i]['esthetics']['icon_type'] = $custom_fields['icon_type'];
//         }

//         $data[$i]['esthetics']['primary_color'] = $custom_fields['primary_color'];
//         $data[$i]['esthetics']['secondary_color'] = get_contrast_color($data[$i]['esthetics']['primary_color']);

//         $data[$i]['esthetics']['navigate'] = $custom_fields['navigation'];

//     }

//     return $data;
// }

// Callback function for adding a component via REST

function add_jsmp_component_callback($request)
{
    $component_data = $request->get_json_params(); // Retrieve the JSON data from the request

    // Call the add_jsmp_component function
    $inserted_id = add_jsmp_component(json_encode($component_data));

    // Return the inserted ID or an error response
    if ($inserted_id) {
        update_post_meta(intval($component_data['merchant_id']), 'hasVenues', true);
        return array('inserted_id' => $inserted_id);
    } else {
        return new WP_Error('insert_error', 'Failed to insert component.', array('status' => 500));
    }
}


// // Callback function for adding a component via REST
// function get_processed_hours_callback($request)
// {

//     // Call the add_jsmp_component function
//     $processed = process_hours($request['string']);

//     $processedToString = hoursAbstrator($processed, 0);

//     // Return the inserted ID or an error response
//     if ($processedToString) {
//         return $processedToString;
//     } else {
//         return new WP_Error('insert_error', 'Failed to insert component.', array('status' => 500));
//     }
// }

// // Callback function for adding a component via REST
// function get_merchant_hours_callback($request)
// {
//     $merchant = intval($request['merchant']);
//     $today = intval($request['today']);

//     $results = get_merchant_special_hours($merchant, $today);
//     return $results;

// }



// // Callback function for retrieving components by merchant_id
// function get_components_of_merchant_callback($request)
// {
//     $merchant_id = $request->get_param('merchant_id'); // Retrieve the merchant_id from the request parameter

//     $components = [];

//     // Return the components as the response
//     return new WP_REST_Response($components, 200);
// }

// // Callback function for retrieving components by merchant_id
// function get_components_callback($request)
// {
//     $params = $request->get_params(); // Retrieve the merchant_id from the request parameter

//     if ($params['merchant'] !== null) {

//         if ($params['metadata']) {
//             $components = get_components_of_merchant_with_commeta($params['merchant']);
//             return new WP_REST_Response($components, 200);
//         }

//         $components = get_components_of_merchant($params['merchant']);
//         return new WP_REST_Response($components, 200);
//     }


//     if ($params['metadata']) {
//         $components = get_components_with_commeta();
//         return new WP_REST_Response($components, 200);
//     }

//     $components = get_components();
//     return new WP_REST_Response($components, 200);

// }



// // Callback function for checking permissions with nonce
// function jsmp_permissions_callback($request)
// {
//     $nonce = $request->get_header('X-WP-Nonce'); // Retrieve nonce value from request header

//     // TODO: FOR TEST PURPOSES ONLY
//     return true;

//     if (empty($nonce) || !wp_verify_nonce($nonce, 'jsmp_nonce_action')) {
//         return new WP_Error('invalid_nonce', 'Invalid or missing nonce.', array('status' => 403));
//     }

//     // Additional permission checks can be performed here

//     // Return true if the permission checks pass
//     return true;
// }

// // $component_data = '{
// //     "merchant_id": 1,
// //     "title": "Sample Component",
// //     "description": "This is a sample component.",
// //     "com_type": "Type A",
// //     "commeta": [{"meta_key": "Date", "meta_value": 20230623}, {}]
// // }';

// // $inserted_id = add_jsmp_component($component_data);



// function add_jsmp_hours($hours_data)
// {
//     global $wpdb;

//     $hours_table = $wpdb->prefix . 'jsmp_hours';

//     $data = json_decode($hours_data, true);

//     // JSON encode the 'days' and 'schemes' values
//     $data['days'] = json_encode($data['days']);
//     $data['schemes'] = json_encode($data['schemes']);

//     $wpdb->insert($hours_table, $data);

//     $inserted_index = $wpdb->insert_id;
//     // Optional: Return the inserted row ID
//     return $inserted_index;
// }






// // $commeta_data = '{
// //     "com_id": 1,
// //     "meta_key": "Some Key",
// //     "meta_value": "Some Value"
// // }';

// // $inserted_id = add_jsmp_commeta($commeta_data);
// function add_jsmp_commeta($commeta_data)
// {
//     global $wpdb;

//     $table_name = $wpdb->prefix . 'jsmp_commeta';

//     $data = json_decode($commeta_data, true);

//     $wpdb->insert($table_name, $data);

//     // Optional: Return the inserted row ID
//     return $wpdb->insert_id;
// }




// function get_components_of_merchant($merchant_id)
// {
//     global $wpdb;

//     $table_name = $wpdb->prefix . 'jsmp_components';

//     $query = $wpdb->prepare("SELECT * FROM $table_name WHERE merchant_id = %d", $merchant_id);
//     $results = $wpdb->get_results($query);

//     return $results;
// }


// function get_merchant_special_hours($id, $today)
// {
//     global $wpdb;

//     $hours_table = $wpdb->prefix . 'jsmp_hours';

//     // Prepare the SQL query
//     $sql = "SELECT * FROM $hours_table
//             WHERE merchant_id = $id
//             AND (`start` IS NULL OR `start` < $today)";

//     // Prepare and execute the query
//     $statement = $wpdb->prepare($sql);
//     $wpdb->query($statement);
//     $results = $wpdb->get_results($statement);

//     foreach ($results as &$item) {
//         $item->days = json_decode($item->days);
//         $item->schemes = json_decode($item->schemes);
//         $item->asString = json_decode($item->asString);
//     }

//     return $results;

// }



// function get_components()
// {
//     global $wpdb;

//     $table_name = $wpdb->prefix . 'jsmp_components';

//     $query = $wpdb->prepare("SELECT ID, merchant_id, title, description, com_type FROM $table_name");
//     $results = $wpdb->get_results($query, ARRAY_A);

//     return $results;
// }


// function get_components_with_commeta()
// {
//     global $wpdb;

//     $components_table = $wpdb->prefix . 'jsmp_components';
//     $commeta_table = $wpdb->prefix . 'jsmp_commeta';

//     $query = "SELECT c.*, m.meta_key, m.meta_value
//               FROM $components_table AS c
//               LEFT JOIN $commeta_table AS m ON c.ID = m.com_id";

//     $results = $wpdb->get_results($query, ARRAY_A);

//     $components = array();

//     foreach ($results as $row) {
//         $component_id = $row['ID'];

//         if (!isset($components[$component_id])) {
//             // Create a new component entry
//             $components[$component_id] = array(
//                 'ID' => $component_id,
//                 'merchant_id' => $row['merchant_id'],
//                 'title' => $row['title'],
//                 'description' => $row['description'],
//                 'com_type' => $row['com_type'],
//                 'commeta' => array()
//             );
//         }

//         if ($row['meta_key'] == "") {
//             continue;
//         }

//         // Add commeta to the component
//         $components[$component_id]['commeta'][] = array(
//             $row['meta_key'] => json_decode($row['meta_value'], true)
//         );
//     }


//     return array_values($components);
// }


// function get_components_of_merchant_with_commeta($merchant_id)
// {
//     global $wpdb;

//     $components_table = $wpdb->prefix . 'jsmp_components';
//     $commeta_table = $wpdb->prefix . 'jsmp_commeta';
//     $hours_table = $wpdb->prefix . 'jsmp_hours';

//     $query = $wpdb->prepare(
//         "SELECT c.*, m.meta_key, m.meta_value, h.name, h.days, h.schemes, h.end, h.asString
//         FROM $components_table AS c
//         LEFT JOIN $commeta_table AS m ON c.ID = m.com_id
//         LEFT JOIN $hours_table AS h ON c.ID = h.com_id
//         WHERE c.merchant_id = %d",
//         $merchant_id
//     );

//     $results = $wpdb->get_results($query, ARRAY_A);
//     // return $results;

//     $components = array();

//     foreach ($results as $row) {
//         $component_id = $row['ID'];

//         if (!isset($components[$component_id])) {
//             // Create a new component entry
//             $components[$component_id] = array(
//                 'ID' => $component_id,
//                 'merchant_id' => $row['merchant_id'],
//                 'title' => $row['title'],
//                 'description' => $row['description'],
//                 'com_type' => $row['com_type'],
//                 'commeta' => array(),
//                 'hours' => array(),
//             );
//         }

//         if ($row['meta_key'] != "") {
//             // Add commeta to the component
//             $components[$component_id]['commeta'][] = array(
//                 $row['meta_key'] => json_decode($row['meta_value'], true)
//             );
//         }

//         // Add hours to the component, name cannot be null
//         if ($row['name'] != null) {
//             $components[$component_id]['hours'] = array(
//                 'name' => $row['name'],
//                 'days' => json_decode($row['days'], true),
//                 'schemes' => json_decode($row['schemes'], true),
//                 'start' => $row['start'],
//                 'end' => $row['end'],
//                 'asString' => implode("\n", json_decode($row['asString'], true))
//             );
//         }
//     }

//     return array_values($components);
// }