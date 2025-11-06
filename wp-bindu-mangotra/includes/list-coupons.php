<?php
function wpbindu_list_coupons() { 
    global $wpdb;
    $table_name = $wpdb->prefix . 'coupons_bindu_mangotra';

    // Delete record if requested
    if ( isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) ) {
        $wpdb->delete( $table_name, [ 'id' => intval($_GET['id']) ] );
        echo '<div class="updated"><p>' . __( 'Coupon deleted successfully.', 'wp-bindu' ) . '</p></div>';
    }

    $coupons = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" );
    ?>
    <div class="wrap">
        <h1><?php _e('Coupons', 'wp-bindu'); ?> <a href="?page=wpbindu-add-coupon" class="page-title-action"><?php _e('Add New', 'wp-bindu'); ?></a></h1>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Image', 'wp-bindu'); ?></th>
                    <th><?php _e('Title', 'wp-bindu'); ?></th>
                    <th><?php _e('Coupon Amount', 'wp-bindu'); ?></th>
                    <th><?php _e('Category', 'wp-bindu'); ?></th>
                    <th><?php _e('Availability', 'wp-bindu'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( $coupons ) :
                    foreach ( $coupons as $coupon ) : ?>
                        <tr>
                            <td><?php if($coupon->image_url) echo '<img src="' . esc_url($coupon->image_url) . '" width="50" />'; ?></td>
                            <td>
                                <strong>
                                    <a href="?page=wpbindu-edit-coupon&id=<?php echo $coupon->id; ?>"><?php echo esc_html($coupon->title); ?></a>
                                </strong>
                                <div class="row-actions">
                                    <span class="edit"><a href="?page=wpbindu-edit-coupon&id=<?php echo $coupon->id; ?>"><?php _e('Edit', 'wp-bindu'); ?></a></span> | 
                                    <span class="trash"><a href="?page=wpbindu-products&action=delete&id=<?php echo $coupon->id; ?>" onclick="return confirm('Are you sure?')"><?php _e('Delete', 'wp-bindu'); ?></a></span>
                                </div>
                            </td>
                            <td><?php echo esc_html($coupon->coupon_amount); ?></td>
                            <td><?php echo esc_html($coupon->category); ?></td>
                            <td><?php echo esc_html($coupon->availability); ?></td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr><td colspan="5"><?php _e('No coupons found.', 'wp-bindu'); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>
