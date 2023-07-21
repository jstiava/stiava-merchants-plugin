<?php

function register_add_component_route()
{

    /**
     * POST /components
     */
    register_rest_route(
        'jsmp/v1',
        // Namespace for your custom REST API
        '/components',
        // Endpoint URL
        array(
            'methods' => 'POST',
            // Allow POST method
            'callback' => 'add_jsmp_component_callback',
            // Callback function
            'permission_callback' => 'jsmp_permissions_callback' // Permission callback, change as needed
        )
    );


    /**
     * POST /hours
     */
    register_rest_route(
        'jsmp/v1',
        // Namespace for your custom REST API
        '/hours',
        // Endpoint URL
        array(
            'methods' => 'POST',
            // Allow POST method
            'callback' => 'add_jsmp_hours_callback',
            // Callback function
            'permission_callback' => 'jsmp_permissions_callback' // Permission callback, change as needed
        )
    );


    /**
     * GET /components
     */
    register_rest_route(
        'jsmp/v1',
        '/components',
        array(
            'methods' => 'GET',
            // Allow GET method
            'callback' => 'get_components_callback',
            'permission_callback' => '__return_true',
            'args' => [
                'metadata' => [
                    'required' => false,
                    'type' => 'boolean',
                    'default' => false
                ],
                'merchant' => [
                    'required' => false,
                    'type' => 'integer',
                    'default' => null
                ]
            ],
        )
    );


    /**
     * GET /merchants
     */
    register_rest_route('jsmp/v1', '/merchants', [
        'methods' => 'GET',
        'callback' => 'washu_dining_get_merchants',
        'args' => [
            'day' => [
                'required' => true,
                'type' => 'integer',
            ],
            'date' => [
                'required' => true,
                'type' => 'integer',
            ],
        ],

    ]);

    /**
     * GET /hours
     */
    register_rest_route('jsmp/v1', '/hours', [
        'methods' => 'GET',
        'callback' => 'get_merchant_hours_callback',
        'args' => [
            'merchant' => [
                'required' => true,
                'type' => 'integer',
            ]
        ],

    ]);

    /**
     * GET /hours/callback
     */
    register_rest_route('jsmp/v1', '/hours/callback', [
        'methods' => 'GET',
        'callback' => 'get_processed_hours_callback',
        'args' => [
            'string' => [
                'required' => true,
                'type' => 'string',
            ]
        ],

    ]);



}
add_action('rest_api_init', 'register_add_component_route');