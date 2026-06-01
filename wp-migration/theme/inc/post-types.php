<?php
/**
 * Custom post type: ppl_inquiry
 * Stores contact form submissions. No plugin required.
 */

add_action( 'init', 'ppl_register_inquiry_cpt' );

function ppl_register_inquiry_cpt() {
    register_post_type( 'ppl_inquiry', [
        'label'               => 'Inquiries',
        'labels'              => [
            'name'          => 'Inquiries',
            'singular_name' => 'Inquiry',
            'menu_name'     => 'Inquiries',
            'all_items'     => 'All Inquiries',
            'view_item'     => 'View Inquiry',
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

// Handle contact form submission
add_action( 'admin_post_nopriv_ppl_contact', 'ppl_handle_contact_form' );
add_action( 'admin_post_ppl_contact',        'ppl_handle_contact_form' );

function ppl_handle_contact_form() {
    // Verify nonce
    if ( ! isset( $_POST['ppl_contact_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_contact_nonce'] ) ), 'ppl_contact_submit' ) ) {
        wp_die( 'Invalid request.', 'Error', [ 'response' => 403 ] );
    }

    $name    = sanitize_text_field( wp_unslash( $_POST['ppl_name']    ?? '' ) );
    $email   = sanitize_email(      wp_unslash( $_POST['ppl_email']   ?? '' ) );
    $type    = sanitize_text_field( wp_unslash( $_POST['ppl_type']    ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['ppl_message'] ?? '' ) );

    if ( ! $name || ! $email || ! $message ) {
        wp_safe_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
        exit;
    }

    $post_id = wp_insert_post( [
        'post_type'   => 'ppl_inquiry',
        'post_title'  => $name . ' — ' . gmdate( 'Y-m-d H:i' ),
        'post_status' => 'publish',
    ] );

    if ( $post_id ) {
        update_post_meta( $post_id, 'ppl_inq_name',    $name );
        update_post_meta( $post_id, 'ppl_inq_email',   $email );
        update_post_meta( $post_id, 'ppl_inq_type',    $type );
        update_post_meta( $post_id, 'ppl_inq_message', $message );
        update_post_meta( $post_id, 'ppl_inq_status',  'new' );
    }

    wp_safe_redirect( add_query_arg( 'contact', 'success', wp_get_referer() ) );
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
