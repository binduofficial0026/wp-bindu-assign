<?php
/*
Plugin Name: WP Bindu Mangotra
Description: A custom plugin for managing product coupons.
Version: 1.0
Author: Bindu Mangotra
Text Domain: wp-bindu 
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WPBINDU_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Create custom table on activation
register_activation_hook( __FILE__, 'wpbindu_create_table' );
function wpbindu_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'coupons_bindu_mangotra';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(200) NOT NULL,
        description text NULL,
        coupon_amount decimal(10,2) NOT NULL,
        image_url varchar(255) NULL,
        category varchar(100) NOT NULL,
        availability text NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

// Admin menu
add_action( 'admin_menu', 'wpbindu_register_menus' );
function wpbindu_register_menus() {
    add_menu_page(
        __( 'Products', 'wp-bindu' ),
        __( 'Products', 'wp-bindu' ),
        'edit_posts',  // admin + editor access
        'wpbindu-products',
        'wpbindu_list_coupons',
        'dashicons-cart',
        6
    );

    add_submenu_page(
        'wpbindu-products',
        __( 'Coupons', 'wp-bindu' ),
        __( 'Coupons', 'wp-bindu' ),
        'edit_posts',
        'wpbindu-products',
        'wpbindu_list_coupons'
    );

    add_submenu_page(
        'wpbindu-products',
        __( 'Add Coupon', 'wp-bindu' ),
        __( 'Add Coupon', 'wp-bindu' ),
        'edit_posts',
        'wpbindu-add-coupon',
        'wpbindu_add_coupon_page'
    );

    add_submenu_page(
        null, // hidden menu, only accessible via link
        __( 'Edit Coupon', 'wp-bindu' ),
        __( 'Edit Coupon', 'wp-bindu' ),
        'edit_posts',
        'wpbindu-edit-coupon',
        'wpbindu_edit_coupon_page'
    );
}

// Include files
require_once WPBINDU_PLUGIN_DIR . 'includes/add-coupon.php';
require_once WPBINDU_PLUGIN_DIR . 'includes/list-coupons.php';
require_once WPBINDU_PLUGIN_DIR . 'includes/edit-coupon.php';

add_action( 'admin_enqueue_scripts', 'wpbindu_enqueue_admin_scripts' );
function wpbindu_enqueue_admin_scripts( $hook ) {
    if ( strpos( $hook, 'wpbindu' ) !== false ) {
        wp_enqueue_media();
        wp_enqueue_script( 'wpbindu-media', plugin_dir_url( __FILE__ ) . '/assets/media.js', [ 'jquery' ], '1.0', true );
    }
}


register_uninstall_hook(__FILE__, 'wpbindu_delete_plugin_tables');

function wpbindu_delete_plugin_tables() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'coupons_bindu_mangotra';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

?>
