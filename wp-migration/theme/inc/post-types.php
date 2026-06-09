<?php
/**
 * Custom post types: ppl_inquiry (contact form), ppl_order (shop orders)
 */

// ── ppl_order ──────────────────────────────────────────────────────────────────

add_action( 'init', 'ppl_register_order_cpt' );

function ppl_register_order_cpt() {
    register_post_type( 'ppl_order', [
        'label'           => 'Orders',
        'labels'          => [
            'name'          => 'Shop Orders',
            'singular_name' => 'Order',
            'menu_name'     => 'Shop Orders',
            'all_items'     => 'All Orders',
            'view_item'     => 'View Order',
        ],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => true,
        'menu_icon'       => 'dashicons-cart',
        'supports'        => [ 'title' ],
        'capability_type' => 'post',
        'show_in_rest'    => false,
    ] );
}

add_action( 'add_meta_boxes', 'ppl_order_detail_metabox' );

function ppl_order_detail_metabox() {
    add_meta_box( 'ppl_order_detail', 'Order Details', 'ppl_render_order_detail', 'ppl_order', 'normal', 'high' );
}

function ppl_render_order_detail( $post ) {
    $get = fn( $key ) => get_post_meta( $post->ID, $key, true );

    $fields = [
        '_ppl_order_email'         => 'Email',
        '_ppl_order_name'          => 'Name',
        '_ppl_order_product_label' => 'Product',
        '_ppl_order_amount'        => 'Amount (cents)',
        '_ppl_order_status'        => 'Status',
        '_ppl_order_stripe_session'=> 'Stripe Session ID',
        '_ppl_order_token'         => 'Download Token',
        '_ppl_order_token_expires' => 'Token Expires',
        '_ppl_order_download_count'=> 'Download Count',
        '_ppl_order_file_url'      => 'File URL',
    ];

    echo '<table style="width:100%;border-collapse:collapse;">';
    foreach ( $fields as $key => $label ) {
        $val = esc_html( $get( $key ) );

        if ( $key === '_ppl_order_token_expires' && $val ) {
            $ts  = (int) $get( $key );
            $val = esc_html( gmdate( 'Y-m-d H:i:s', $ts ) . ( time() > $ts ? ' (expired)' : ' (active)' ) );
        }

        if ( $key === '_ppl_order_amount' && $val ) {
            $val = '$' . number_format( (int) $get( $key ) / 100, 2 );
        }

        if ( $key === '_ppl_order_token' && $val ) {
            $download_url = add_query_arg( 'ppl_download', esc_attr( $get( $key ) ), home_url( '/' ) );
            $val .= ' — <a href="' . esc_url( $download_url ) . '" target="_blank">Test download link</a>';
        }

        echo '<tr style="border-bottom:1px solid #eee;">';
        echo '<th style="text-align:left;padding:9px 8px;width:160px;color:#555;font-weight:600;font-size:13px;">' . esc_html( $label ) . '</th>';
        echo '<td style="padding:9px 8px;font-size:13px;">' . wp_kses_post( $val ) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// ── ppl_inquiry ────────────────────────────────────────────────────────────────

/**
 * Custom post type: ppl_inquiry
 * Stores contact form submissions. No plugin required.
 */

add_action( 'init', 'ppl_register_inquiry_cpt' );

function ppl_register_inquiry_cpt() {
    register_post_type( 'ppl_inquiry', [
        'label'               => 'Contact Submissions',
        'labels'              => [
            'name'          => 'Contact Submissions',
            'singular_name' => 'Submission',
            'menu_name'     => 'Contact Submissions',
            'all_items'     => 'All Submissions',
            'view_item'     => 'View Submission',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-email-alt',
        'supports'            => [ 'title' ],
        'capability_type'     => 'post',
        'show_in_rest'        => false, // submissions are private
    ] );
}

// Register meta for inquiry entries
add_action( 'init', 'ppl_register_inquiry_meta' );

function ppl_register_inquiry_meta() {
    $fields = [
        'ppl_inq_name',
        'ppl_inq_email',
        'ppl_inq_type',
        'ppl_inq_message',
        'ppl_inq_status',   // new | read | archived
    ];
    foreach ( $fields as $key ) {
        register_post_meta( 'ppl_inquiry', $key, [
            'type'         => 'string',
            'single'       => true,
            'show_in_rest' => false,
        ] );
    }
}

// Handle contact form submission — standard POST (noscript fallback)
add_action( 'admin_post_nopriv_ppl_contact', 'ppl_handle_contact_form' );
add_action( 'admin_post_ppl_contact',        'ppl_handle_contact_form' );

// Handle contact form submission — AJAX/fetch (JS primary path)
add_action( 'wp_ajax_nopriv_ppl_contact_json', 'ppl_handle_contact_json' );
add_action( 'wp_ajax_ppl_contact_json',        'ppl_handle_contact_json' );

function ppl_handle_contact_json() {
    if ( ! isset( $_POST['ppl_contact_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_contact_nonce'] ) ), 'ppl_contact_submit' ) ) {
        wp_send_json_error( 'Invalid request.', 403 );
    }

    if ( ! empty( $_POST['ppl_website'] ) ) {
        wp_send_json_success(); // silent honeypot drop
    }

    $loaded_at = isset( $_POST['ppl_ts'] ) ? (int) $_POST['ppl_ts'] : 0;
    if ( $loaded_at === 0 || ( time() - $loaded_at ) < 3 ) {
        wp_send_json_success(); // silent time-check drop
    }

    $name    = sanitize_text_field( wp_unslash( $_POST['ppl_name']    ?? '' ) );
    $email   = sanitize_email(      wp_unslash( $_POST['ppl_email']   ?? '' ) );
    $type    = sanitize_text_field( wp_unslash( $_POST['ppl_type']    ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['ppl_message'] ?? '' ) );

    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_send_json_error( 'Please fill in all required fields.' );
    }

    $post_id = wp_insert_post( [
        'post_type'   => 'ppl_inquiry',
        'post_title'  => $name . ' — ' . gmdate( 'Y-m-d H:i' ),
        'post_status' => 'publish',
    ] );

    if ( ! $post_id || is_wp_error( $post_id ) ) {
        wp_send_json_error( 'Could not save your message. Please try again.' );
    }

    update_post_meta( $post_id, 'ppl_inq_name',    $name );
    update_post_meta( $post_id, 'ppl_inq_email',   $email );
    update_post_meta( $post_id, 'ppl_inq_type',    $type );
    update_post_meta( $post_id, 'ppl_inq_message', $message );
    update_post_meta( $post_id, 'ppl_inq_status',  'new' );

    $admin_url = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
    $subject   = "[Pinkprint Lawyer] New submission from {$name}";
    $body      = "A new contact submission has been saved.\n\nName: {$name}\nEmail: {$email}\nType: {$type}\n\nMessage:\n{$message}\n\nView in admin:\n{$admin_url}";
    $headers   = [ "Reply-To: {$name} <{$email}>", 'Content-Type: text/plain; charset=UTF-8' ];
    wp_mail( get_option( 'admin_email' ), $subject, $body, $headers );

    wp_send_json_success();
}

function ppl_handle_contact_form() {
    $redirect_back = wp_get_referer() ?: home_url( '/' );

    // Nonce
    if ( ! isset( $_POST['ppl_contact_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_contact_nonce'] ) ), 'ppl_contact_submit' ) ) {
        wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect_back ) );
        exit;
    }

    // Honeypot — must be empty
    if ( ! empty( $_POST['ppl_website'] ) ) {
        wp_safe_redirect( add_query_arg( 'contact', 'success', $redirect_back ) ); // silent drop
        exit;
    }

    // Time check — bots submit in under 3 seconds
    $loaded_at = isset( $_POST['ppl_ts'] ) ? (int) $_POST['ppl_ts'] : 0;
    if ( $loaded_at === 0 || ( time() - $loaded_at ) < 3 ) {
        wp_safe_redirect( add_query_arg( 'contact', 'success', $redirect_back ) ); // silent drop
        exit;
    }

    $name    = sanitize_text_field( wp_unslash( $_POST['ppl_name']    ?? '' ) );
    $email   = sanitize_email(      wp_unslash( $_POST['ppl_email']   ?? '' ) );
    $type    = sanitize_text_field( wp_unslash( $_POST['ppl_type']    ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['ppl_message'] ?? '' ) );

    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect_back ) );
        exit;
    }

    // Save to DB — primary record
    $post_id = wp_insert_post( [
        'post_type'   => 'ppl_inquiry',
        'post_title'  => $name . ' — ' . gmdate( 'Y-m-d H:i' ),
        'post_status' => 'publish',
    ] );

    if ( ! $post_id || is_wp_error( $post_id ) ) {
        wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect_back ) );
        exit;
    }

    update_post_meta( $post_id, 'ppl_inq_name',    $name );
    update_post_meta( $post_id, 'ppl_inq_email',   $email );
    update_post_meta( $post_id, 'ppl_inq_type',    $type );
    update_post_meta( $post_id, 'ppl_inq_message', $message );
    update_post_meta( $post_id, 'ppl_inq_status',  'new' );

    // Email notification — secondary, non-blocking
    $admin_url = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
    $subject   = "[Pinkprint Lawyer] New submission from {$name}";
    $body      = "A new contact submission has been saved.\n\nName: {$name}\nEmail: {$email}\nType: {$type}\n\nMessage:\n{$message}\n\nView in admin:\n{$admin_url}";
    $headers   = [ "Reply-To: {$name} <{$email}>", 'Content-Type: text/plain; charset=UTF-8' ];
    wp_mail( get_option( 'admin_email' ), $subject, $body, $headers );

    wp_safe_redirect( add_query_arg( 'contact', 'success', $redirect_back ) );
    exit;
}

// Inquiry detail metabox in admin
add_action( 'add_meta_boxes', 'ppl_inquiry_detail_metabox' );

function ppl_inquiry_detail_metabox() {
    add_meta_box(
        'ppl_inquiry_detail',
        'Inquiry Details',
        'ppl_render_inquiry_detail',
        'ppl_inquiry',
        'normal',
        'high'
    );
}

function ppl_render_inquiry_detail( $post ) {
    $fields = [
        'ppl_inq_name'    => 'Name',
        'ppl_inq_email'   => 'Email',
        'ppl_inq_type'    => 'Type',
        'ppl_inq_message' => 'Message',
        'ppl_inq_status'  => 'Status',
    ];
    echo '<table style="width:100%;border-collapse:collapse;">';
    foreach ( $fields as $key => $label ) {
        $value = esc_html( get_post_meta( $post->ID, $key, true ) );
        echo '<tr style="border-bottom:1px solid #eee;">';
        echo '<th style="text-align:left;padding:10px 8px;width:120px;color:#555;">' . esc_html( $label ) . '</th>';
        if ( $key === 'ppl_inq_status' ) {
            $current = get_post_meta( $post->ID, $key, true ) ?: 'new';
            wp_nonce_field( 'ppl_inquiry_status', 'ppl_inquiry_status_nonce' );
            echo '<td style="padding:10px 8px;"><select name="ppl_inq_status">';
            foreach ( [ 'new', 'read', 'archived' ] as $s ) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr( $s ),
                    selected( $current, $s, false ),
                    esc_html( ucfirst( $s ) )
                );
            }
            echo '</select></td>';
        } else {
            echo '<td style="padding:10px 8px;">' . nl2br( $value ) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}

add_action( 'save_post_ppl_inquiry', 'ppl_save_inquiry_status' );

function ppl_save_inquiry_status( $post_id ) {
    if ( ! isset( $_POST['ppl_inquiry_status_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_inquiry_status_nonce'] ) ), 'ppl_inquiry_status' ) ) return;
    if ( isset( $_POST['ppl_inq_status'] ) ) {
        update_post_meta( $post_id, 'ppl_inq_status', sanitize_text_field( wp_unslash( $_POST['ppl_inq_status'] ) ) );
    }
}
