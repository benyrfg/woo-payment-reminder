<?php
/**
 * Plugin Name: Woo Payment Reminder
 * Plugin URI: https://github.com/nobodyguy/woo-payment-reminder
 * Description: Automatically sends payment reminder emails after a configurable number of days.
 * Version: 1.0.0
 * Author: Jan Gnip
 * Author URI: https://github.com/nobodyguy
 * Text Domain: woo-payment-reminder
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WPR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Load plugin translations
function wpr_load_textdomain() {
    load_plugin_textdomain( 'woo-payment-reminder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wpr_load_textdomain' );

// Register custom email template
function wpr_register_custom_email( $email_classes ) {
    require_once plugin_dir_path( __FILE__ ) . 'classes/class-wc-email-payment-reminder.php';
    $email_classes['WC_Email_Payment_Reminder'] = new WC_Email_Payment_Reminder();
    return $email_classes;
}
add_filter( 'woocommerce_email_classes', 'wpr_register_custom_email' );

// Schedule cron job on activation
function wpr_schedule_cron() {
    if ( ! wp_next_scheduled( 'wpr_send_payment_reminders' ) ) {
        wp_schedule_event( time(), 'hourly', 'wpr_send_payment_reminders' );
    }
}
register_activation_hook( __FILE__, 'wpr_schedule_cron' );

// Remove cron job on deactivation
function wpr_remove_cron() {
    wp_clear_scheduled_hook( 'wpr_send_payment_reminders' );
}
register_deactivation_hook( __FILE__, 'wpr_remove_cron' );

// Send payment reminders via cron
function wpr_send_payment_reminders() {
    $email = WC()->mailer()->emails['WC_Email_Payment_Reminder'];
    if ( ! $email || 'yes' !== $email->enabled ) {
        return;
    }

    $hours_to_send_first  = intval( $email->get_option( 'hours_to_send_first', 168 ) );
    $hours_to_send_second = intval( $email->get_option( 'hours_to_send_second', 240 ) );
    $enable_second        = $email->get_option( 'enable_second_reminder', 'yes' ) === 'yes';
    $reminder_interval    = 3;

    $args = [
        'status'       => ['pending', 'on-hold'],
        'date_created' => '<' . strtotime( "-{$hours_to_send_first} hours" ),
    ];

    $orders = wc_get_orders( $args );

    foreach ( $orders as $order ) {
        $order_id = $order->get_id();
        $last_reminder_sent = $order->get_meta( '_payment_reminder_sent' );

        if ( $last_reminder_sent && ( time() - $last_reminder_sent ) < ( $reminder_interval * DAY_IN_SECONDS ) ) {
            continue;
        }

        $hours_since_order = ( time() - $order->get_date_created()->getTimestamp() ) / HOUR_IN_SECONDS;

        // Send second reminder only if enabled and order is old enough
        if ( $last_reminder_sent ) {
            if ( ! $enable_second || $hours_since_order < $hours_to_send_second ) {
                continue;
            }
        }

        do_action( 'send_payment_reminder_email', $order_id );
        $order->update_meta_data( '_payment_reminder_sent', time() );
        $note = $last_reminder_sent
            ? __( 'Second payment reminder email sent to customer.', 'woo-payment-reminder' )
            : __( 'Payment reminder email sent to customer.', 'woo-payment-reminder' );
        $order->add_order_note( $note );
        $order->save();
    }
}
add_action( 'wpr_send_payment_reminders', 'wpr_send_payment_reminders' );
