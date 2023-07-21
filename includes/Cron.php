<?php

namespace StiavaMerchantsApp\Includes;

class Cron 
{
    // Define the scheduled task
    private static function my_cron_job() {
        // Perform your task here
        // This function will be executed when the cron event is triggered
        // Add your custom code here
        // For example, you can send emails, update data, perform cleanup, etc.
        // You can also call other functions or include other files

        // Log the execution
        error_log('WP-Cron job executed!');
        self::send_email_to_site_owner();
    }

    private static function send_email_to_site_owner() {
        $site_owner_email = get_option('admin_email'); // Get the site owner's email address
        $subject = 'Hello from your WordPress site';
        $message = 'This is a test email from your WordPress site.';
    
        wp_mail($site_owner_email, $subject, $message);
    
        // Log the execution to the debug log
        error_log('Email sent to site owner: ' . $site_owner_email);
    }

    // Schedule the cron event
    private static function schedule_my_cron_job() {
    
        // Log the execution to the debug log
        error_log('Cron job scheduled!');
        
        if (!wp_next_scheduled([self::class, 'my_cron_hook'])) {
            $timestamp = strtotime('14:05:00');
            wp_schedule_event($timestamp, 'daily', [self::class, 'my_cron_hook']);
        }
    }

    public static function run() {
        error_log('Made it to CRON run');
        // Hook the scheduling function to activate the cron event
        register_activation_hook(__FILE__, [self::class, 'schedule_my_cron_job']);

        // Hook the task function to the cron event
        add_action('my_cron_hook', [self::class, 'my_cron_job']);
    }

    public static function uninstall() {
        error_log('Uninstalling hook');
        // Hook the unscheduling function to deactivate the cron event
        register_deactivation_hook(__FILE__, 'wp_clear_scheduled_hook', 'my_cron_hook');
    }

    
}