<?php
function wpbindu_add_coupon_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'coupons_bindu_mangotra';

    $error = '';
    $success = '';

    if (isset($_POST['wpbindu_submit_coupon'])) {

        // Sanitize input
        $title = sanitize_text_field($_POST['title'] ?? '');
        $description = sanitize_textarea_field($_POST['description'] ?? '');
        $coupon_amount = sanitize_text_field($_POST['coupon_amount'] ?? '');
        $category = sanitize_text_field($_POST['category'] ?? '');
        $availability = isset($_POST['availability']) ? implode(',', array_map('sanitize_text_field', $_POST['availability'])) : '';
        $image_url = esc_url_raw($_POST['image_url'] ?? '');

        // Server-side validation
        $missing_fields = [];

        if (empty($title)) {
            $missing_fields[] = __('Title', 'wp-bindu');
        }

        if (empty($coupon_amount)) {
            $missing_fields[] = __('Coupon Amount', 'wp-bindu');
        }


        if (!empty($missing_fields)) {
            $error = sprintf(
                __('Please fill the following required fields: %s', 'wp-bindu'),
                implode(', ', $missing_fields)
            );
        } elseif (!is_numeric($coupon_amount)) {
            $error = __('Coupon Amount must be a number.', 'wp-bindu');
        } else {
            // Insert into DB
            $wpdb->insert($table_name, [
                'title' => $title,
                'description' => $description,
                'coupon_amount' => $coupon_amount,
                'category' => $category,
                'availability' => $availability,
                'image_url' => $image_url
            ]);
            $success = __('Coupon added successfully.', 'wp-bindu');
            $_POST = []; // Clear form
        }
    }
    ?>
    <div class="wrap">
        <h1><?php _e('Add Coupon', 'wp-bindu'); ?></h1>

        <?php if ($error) echo '<div class="error"><p>' . esc_html($error) . '</p></div>'; ?>
        <?php if ($success) echo '<div class="updated"><p>' . esc_html($success) . '</p></div>'; ?>

        <form method="post" id="wpbindu_add_coupon_form">
            <table class="form-table">
                <!-- Title -->
                <tr>
                    <th><label><?php _e('Title', 'wp-bindu'); ?> *</label></th>
                    <td><input type="text" name="title" value="<?php echo esc_attr($_POST['title'] ?? ''); ?>" class="regular-text"></td>
                </tr>

                <!-- Description -->
                <tr>
                    <th><label><?php _e('Description', 'wp-bindu'); ?></label></th>
                    <td><textarea name="description" rows="4" cols="50"><?php echo esc_textarea($_POST['description'] ?? ''); ?></textarea></td>
                </tr>

                <!-- Coupon Amount -->
                <tr>
                    <th><label><?php _e('Coupon Amount', 'wp-bindu'); ?> *</label></th>
                    <td><input type="number" name="coupon_amount" value="<?php echo esc_attr($_POST['coupon_amount'] ?? ''); ?>" class="regular-text" step="any" min="0"></td>
                </tr>

                <!-- Image -->
                <tr>
                    <th><label><?php _e('Image', 'wp-bindu'); ?></label></th>
                    <td>
                        <input type="hidden" name="image_url" id="image_url" value="<?php echo esc_url($_POST['image_url'] ?? ''); ?>">
                        <input type="button" class="button" value="<?php _e('Upload Image', 'wp-bindu'); ?>" id="upload_image_button">
                        <input type="button" class="button" value="<?php _e('Remove Image', 'wp-bindu'); ?>" id="remove_image_button" style="display:<?php echo !empty($_POST['image_url']) ? 'inline-block' : 'none'; ?>;">
                        <br><br>
                        <img id="image_preview" src="<?php echo esc_url($_POST['image_url'] ?? ''); ?>" style="max-width:200px; display:<?php echo !empty($_POST['image_url']) ? 'block' : 'none'; ?>;">
                    </td>
                </tr>

                <!-- Category -->
                <tr>
                    <th><label><?php _e('Category', 'wp-bindu'); ?> *</label></th>
                    <td>
                        <select name="category">
                            <option value=""><?php _e('--Select--', 'wp-bindu'); ?></option>
                            <option value="Mobile" <?php selected($_POST['category'] ?? '', 'Mobile'); ?>>Mobile</option>
                            <option value="Computer" <?php selected($_POST['category'] ?? '', 'Computer'); ?>>Computer</option>
                            <option value="Electronics" <?php selected($_POST['category'] ?? '', 'Electronics'); ?>>Electronics</option>
                        </select>
                    </td>
                </tr>

                <!-- Availability -->
                <tr>
                    <th><label><?php _e('Availability', 'wp-bindu'); ?></label></th>
                    <td>
                        <?php
                        $selected_availability = $_POST['availability'] ?? [];
                        if (!is_array($selected_availability)) $selected_availability = [];
                        ?>
                        <label><input type="checkbox" name="availability[]" value="Client" <?php checked(in_array('Client', $selected_availability)); ?>> Client</label>
                        <label><input type="checkbox" name="availability[]" value="Distributor" <?php checked(in_array('Distributor', $selected_availability)); ?>> Distributor</label>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="wpbindu_submit_coupon" class="button-primary" value="<?php _e('Save Coupon', 'wp-bindu'); ?>">
            </p>
        </form>
    </div>
<?php
}
