<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( __( 'Hello %s,', 'woo-payment-reminder' ), esc_html( $order->get_billing_first_name() ) ); ?></p>

<p>
    <?php printf( 
        __( 'We noticed that your order <strong>#%s</strong> is still awaiting payment.', 'woo-payment-reminder' ), 
        esc_html( $order->get_order_number() ) 
    ); ?>
</p>

<p><?php _e( 'To complete your purchase, please proceed with the payment at your earliest convenience.', 'woo-payment-reminder' ); ?></p>

<p><?php _e( 'If you have already completed the payment, please contact us.', 'woo-payment-reminder' ); ?></p>

<?php // do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
<?php do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email ); ?>
<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>



<p><?php _e( 'Thank you for shopping with us.', 'woo-payment-reminder' ); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
