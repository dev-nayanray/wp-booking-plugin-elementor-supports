<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WBE_Booking {

    public function __construct() {
        // Add custom fields to WooCommerce products
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_booking_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_booking_fields'));

        // Add booking functionality to the frontend
        add_action('woocommerce_after_add_to_cart_button', array($this, 'add_booking_form'));
    }

    // Add custom fields to the product edit page
    public function add_booking_fields() {
        echo '<div class="options_group">';

        // Booking Location
        woocommerce_wp_text_input(array(
            'id' => '_booking_location',
            'label' => __('Booking Location', 'woocommerce-booking-elementor'),
            'placeholder' => 'Enter location',
            'desc_tip' => true,
            'description' => __('Enter the location for the booking.', 'woocommerce-booking-elementor'),
        ));

        // Expiry Date
        woocommerce_wp_text_input(array(
            'id' => '_booking_expiry_date',
            'label' => __('Expiry Date', 'woocommerce-booking-elementor'),
            'placeholder' => 'YYYY-MM-DD',
            'desc_tip' => true,
            'description' => __('Enter the expiry date for the booking (format: YYYY-MM-DD).', 'woocommerce-booking-elementor'),
        ));

        // Coupon Code
        woocommerce_wp_text_input(array(
            'id' => '_booking_coupon_code',
            'label' => __('Coupon Code', 'woocommerce-booking-elementor'),
            'placeholder' => 'Enter coupon code',
            'desc_tip' => true,
            'description' => __('Enter the coupon code for the booking.', 'woocommerce-booking-elementor'),
        ));

        // Discount Price
        woocommerce_wp_text_input(array(
            'id' => '_booking_discount_price',
            'label' => __('Discount Price', 'woocommerce-booking-elementor'),
            'placeholder' => 'Enter discount price',
            'desc_tip' => true,
            'description' => __('Enter the discount price for the booking (numeric value).', 'woocommerce-booking-elementor'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => '0.01',
                'min' => '0',
            ),
        ));

        echo '</div>';
    }

    // Save custom fields
    public function save_booking_fields($post_id) {
        $fields = array(
            '_booking_location',
            '_booking_expiry_date',
            '_booking_coupon_code',
            '_booking_discount_price',
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                // Sanitize and validate input
                $value = sanitize_text_field($_POST[$field]);

                // Additional validation for specific fields
                if ($field === '_booking_expiry_date' && !$this->validate_date($value)) {
                    $value = ''; // Clear invalid date
                }

                if ($field === '_booking_discount_price' && !is_numeric($value)) {
                    $value = 0; // Set to 0 if not numeric
                }

                update_post_meta($post_id, $field, $value);
            }
        }
    }

    // Validate date format (YYYY-MM-DD)
    private function validate_date($date) {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
        return preg_match($pattern, $date);
    }

    // Add booking form to the frontend
    public function add_booking_form() {
        global $product;
        $product_id = $product->get_id();

        $location = get_post_meta($product_id, '_booking_location', true);
        $expiry_date = get_post_meta($product_id, '_booking_expiry_date', true);
        $coupon_code = get_post_meta($product_id, '_booking_coupon_code', true);
        $discount_price = get_post_meta($product_id, '_booking_discount_price', true);

        echo '<div class="wbe-booking-form">';
        echo '<h3>' . __('Booking Information', 'woocommerce-booking-elementor') . '</h3>';
        echo '<p><strong>' . __('Location:', 'woocommerce-booking-elementor') . '</strong> ' . esc_html($location) . '</p>';
        echo '<p><strong>' . __('Expiry Date:', 'woocommerce-booking-elementor') . '</strong> ' . esc_html($expiry_date) . '</p>';
        echo '<p><strong>' . __('Coupon Code:', 'woocommerce-booking-elementor') . '</strong> ' . esc_html($coupon_code) . '</p>';
        echo '<p><strong>' . __('Discount Price:', 'woocommerce-booking-elementor') . '</strong> ' . esc_html($discount_price) . '</p>';
        echo '</div>';
    }
}