<?php
/*
Plugin Name: WooCommerce Booking Elementor Support
Description: Adds WooCommerce product booking support with Elementor.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define('WBE_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WBE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once WBE_PLUGIN_PATH . 'includes/class-wbe-booking.php';

// Load booking functionality
function wbe_init() {
    new WBE_Booking();
}
add_action('plugins_loaded', 'wbe_init');

// Load Elementor widget only if Elementor is fully initialized
function wbe_register_elementor_widgets() {
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . __('WooCommerce Booking Elementor Support requires Elementor to be installed and activated.', 'woocommerce-booking-elementor') . '</p>';
            echo '</div>';
        });
        return;
    }

    require_once WBE_PLUGIN_PATH . 'includes/class-wbe-widget.php';

    // Register Elementor widget
    add_action('elementor/widgets/register', function($widgets_manager) {
        $widgets_manager->register_widget_type(new WBE_Product_Booking_Widget());
    });
}
add_action('elementor/init', 'wbe_register_elementor_widgets');

// Enqueue scripts and styles
function wbe_enqueue_scripts() {
    wp_enqueue_style('wbe-style', WBE_PLUGIN_URL . 'assets/css/style.css');
    wp_enqueue_script('wbe-script', WBE_PLUGIN_URL . 'assets/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'wbe_enqueue_scripts');
