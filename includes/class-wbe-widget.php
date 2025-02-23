<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class WBE_Product_Booking_Widget extends Widget_Base {

    public function get_name() {
        return 'wbe_product_booking';
    }

    public function get_title() {
        return __('WooCommerce Product Booking', 'woocommerce-booking-elementor');
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return ['woocommerce-elements'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'woocommerce-booking-elementor'),
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Product ID', 'woocommerce-booking-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $product_id = $settings['product_id'];

        if (!$product_id) {
            echo __('Please enter a valid Product ID.', 'woocommerce-booking-elementor');
            return;
        }

        $product = wc_get_product($product_id);

        if (!$product) {
            echo __('Product not found.', 'woocommerce-booking-elementor');
            return;
        }

        $title = $product->get_title();
        $featured_image = $product->get_image();
        $categories = $product->get_category_ids();
        $location = get_post_meta($product_id, '_booking_location', true);
        $expiry_date = get_post_meta($product_id, '_booking_expiry_date', true);
        $coupon_code = get_post_meta($product_id, '_booking_coupon_code', true);
        $discount_price = get_post_meta($product_id, '_booking_discount_price', true);
        $price = $product->get_price();

        echo '<div class="wbe-product-booking">';
        echo '<h2>' . esc_html($title) . '</h2>';
        echo '<div class="wbe-featured-image">' . $featured_image . '</div>';
        echo '<div class="wbe-categories">' . implode(', ', array_map('get_cat_name', $categories)) . '</div>';
        echo '<div class="wbe-location">' . esc_html($location) . '</div>';
        echo '<div class="wbe-expiry-date">' . esc_html($expiry_date) . '</div>';
        echo '<div class="wbe-coupon-code">' . esc_html($coupon_code) . '</div>';
        echo '<div class="wbe-discount-price">' . esc_html($discount_price) . '</div>';
        echo '<div class="wbe-price">' . esc_html($price) . '</div>';
        echo '</div>';
    }
}