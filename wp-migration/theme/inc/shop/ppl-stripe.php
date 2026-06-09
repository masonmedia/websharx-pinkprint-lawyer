<?php
/**
 * Pinkprint Shop — Stripe Integration
 *
 * Handles three things:
 *  1. ppl_stripe_checkout  — creates a Stripe Checkout Session, redirects user
 *  2. ppl_stripe_webhook   — receives checkout.session.completed, saves order, emails download link
 *  3. ppl_download (query var) — verifies token, redirects to file URL
 *
 * No Stripe PHP SDK — all API calls use wp_remote_post.
 */


// ── 1. CHECKOUT SESSION ────────────────────────────────────────────────────────

add_action( 'admin_post_nopriv_ppl_stripe_checkout', 'ppl_handle_stripe_checkout' );
add_action( 'admin_post_ppl_stripe_checkout',        'ppl_handle_stripe_checkout' );

add_action( 'admin_post_nopriv_ppl_cart_checkout', 'ppl_handle_cart_checkout' );
add_action( 'admin_post_ppl_cart_checkout',        'ppl_handle_cart_checkout' );

function ppl_handle_stripe_checkout() {
    if ( ! isset( $_POST['ppl_checkout_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_checkout_nonce'] ) ), 'ppl_checkout' ) ) {
        wp_die( 'Invalid request.', 403 );
    }

    $product_type = sanitize_key( $_POST['ppl_product_type'] ?? '' ); // 'product' or 'bundle'
    $product_idx  = (int) ( $_POST['ppl_product_idx'] ?? 0 );
    $return_url   = esc_url_raw( wp_unslash( $_POST['ppl_return_url'] ?? home_url( '/' ) ) );

    $settings = ppl_get_shop_settings();

    if ( empty( $settings['stripe_secret_key'] ) ) {
        wp_safe_redirect( add_query_arg( 'ppl', 'config-error', $return_url ) );
        exit;
    }

    if ( $product_type === 'bundle' ) {
        $price_id      = $settings['bundle']['price_id'] ?? '';
        $product_label = 'Complete Pinkprint Bundle';
        $meta_idx      = 'bundle';
    } else {
        $price_id      = $settings['products'][ $product_idx ]['price_id'] ?? '';
        $product_label = $settings['products'][ $product_idx ]['label'] ?? ( 'Product ' . ( $product_idx + 1 ) );
        $meta_idx      = (string) $product_idx;
    }

    if ( empty( $price_id ) ) {
        wp_safe_redirect( add_query_arg( 'ppl', 'no-price', $return_url ) );
        exit;
    }

    $session = ppl_stripe_api( 'POST', '/checkout/sessions', [
        'mode'                => 'payment',
        'line_items[0][price]'    => $price_id,
        'line_items[0][quantity]' => 1,
        'success_url'         => add_query_arg( 'ppl', 'success', $return_url ),
        'cancel_url'          => add_query_arg( 'ppl', 'cancel', $return_url ),
        'metadata[product_type]' => $product_type,
        'metadata[product_idx]'  => $meta_idx,
        'metadata[product_label]' => $product_label,
        'payment_intent_data[metadata][product_label]' => $product_label,
        'customer_email'      => '', // Stripe collects it during checkout
        'billing_address_collection' => 'auto',
    ] );

    if ( is_wp_error( $session ) || empty( $session['url'] ) ) {
        wp_safe_redirect( add_query_arg( 'ppl', 'stripe-error', $return_url ) );
        exit;
    }

    wp_redirect( esc_url_raw( $session['url'] ) );
    exit;
}


function ppl_handle_cart_checkout() {
    if ( ! isset( $_POST['ppl_checkout_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_checkout_nonce'] ) ), 'ppl_checkout' ) ) {
        wp_die( 'Invalid request.', 403 );
    }

    $return_url = esc_url_raw( wp_unslash( $_POST['ppl_return_url'] ?? home_url( '/' ) ) );
    $cart       = json_decode( wp_unslash( $_POST['ppl_cart'] ?? '' ), true );

    if ( empty( $cart ) || ! is_array( $cart ) ) {
        wp_safe_redirect( add_query_arg( 'ppl', 'no-price', $return_url ) );
        exit;
    }

    $settings = ppl_get_shop_settings();

    if ( empty( $settings['stripe_secret_key'] ) ) {
        wp_safe_redirect( add_query_arg( 'ppl', 'config-error', $return_url ) );
        exit;
    }

    $line_items   = [];
    $labels       = [];
    $cart_compact = [];

    foreach ( $cart as $entry ) {
        $price_id = sanitize_text_field( $entry['price_id'] ?? '' );
        $qty      = max( 1, (int) ( $entry['qty'] ?? 1 ) );
        $title    = sanitize_text_field( $entry['title'] ?? '' );
        $idx      = isset( $entry['idx'] ) ? (int) $entry['idx'] : -1;

        if ( empty( $price_id ) ) continue;

        $line_items[]   = [ 'price' => $price_id, 'quantity' => $qty ];
        $labels[]       = $qty > 1 ? "{$title} ×{$qty}" : $title;
        $cart_compact[] = [ 'i' => $idx, 'q' => $qty ];
    }

    if ( empty( $line_items ) ) {
        wp_safe_redirect( add_query_arg( 'ppl', 'no-price', $return_url ) );
        exit;
    }

    $product_label = implode( ', ', $labels );

    $params = [
        'mode'                       => 'payment',
        'success_url'                => add_query_arg( 'ppl', 'success', $return_url ),
        'cancel_url'                 => add_query_arg( 'ppl', 'cancel', $return_url ),
        'billing_address_collection' => 'auto',
        'metadata[product_type]'     => 'cart',
        'metadata[product_label]'    => $product_label,
        'metadata[cart_json]'        => wp_json_encode( $cart_compact ),
        'payment_intent_data[metadata][product_label]' => $product_label,
    ];

    foreach ( $line_items as $n => $li ) {
        $params[ "line_items[{$n}][price]" ]    = $li['price'];
        $params[ "line_items[{$n}][quantity]" ] = $li['quantity'];
    }

    $session = ppl_stripe_api( 'POST', '/checkout/sessions', $params );

    if ( is_wp_error( $session ) || empty( $session['url'] ) ) {
        wp_safe_redirect( add_query_arg( 'ppl', 'stripe-error', $return_url ) );
        exit;
    }

    wp_redirect( esc_url_raw( $session['url'] ) );
    exit;
}


// ── 2. WEBHOOK ─────────────────────────────────────────────────────────────────

add_action( 'admin_post_nopriv_ppl_stripe_webhook', 'ppl_handle_stripe_webhook' );

function ppl_handle_stripe_webhook() {
    $payload    = file_get_contents( 'php://input' );
    $sig_header = isset( $_SERVER['HTTP_STRIPE_SIGNATURE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_STRIPE_SIGNATURE'] ) ) : '';
    $settings   = ppl_get_shop_settings();

    if ( ! ppl_verify_stripe_signature( $payload, $sig_header, $settings['stripe_webhook_secret'] ) ) {
        status_header( 400 );
        echo 'Invalid signature.';
        exit;
    }

    $event = json_decode( $payload, true );

    if ( ( $event['type'] ?? '' ) !== 'checkout.session.completed' ) {
        status_header( 200 );
        echo 'OK';
        exit;
    }

    $session      = $event['data']['object'];
    $email        = sanitize_email( $session['customer_details']['email'] ?? '' );
    $name         = sanitize_text_field( $session['customer_details']['name'] ?? '' );
    $session_id   = sanitize_text_field( $session['id'] ?? '' );
    $amount_total = (int) ( $session['amount_total'] ?? 0 );
    $product_type = sanitize_key( $session['metadata']['product_type'] ?? 'product' );
    $product_idx  = sanitize_key( $session['metadata']['product_idx'] ?? '0' );
    $product_label = sanitize_text_field( $session['metadata']['product_label'] ?? 'Pinkprint Guide' );

    if ( ! $email ) {
        status_header( 200 );
        echo 'OK — no email.';
        exit;
    }

    // Cart orders: create one order per item, send combined email
    if ( $product_type === 'cart' ) {
        $cart_compact = json_decode( sanitize_text_field( $session['metadata']['cart_json'] ?? '[]' ), true ) ?: [];
        ppl_handle_cart_order( $cart_compact, $email, $name, $product_label, $session_id, $amount_total, $settings );
        status_header( 200 );
        echo 'OK';
        exit;
    }

    // Resolve file URL
    if ( $product_type === 'bundle' ) {
        $file_url = $settings['bundle']['file_url'] ?? '';
    } else {
        $file_url = $settings['products'][ (int) $product_idx ]['file_url'] ?? '';
    }

    // Generate download token
    $token   = bin2hex( random_bytes( 20 ) );
    $expires = time() + ( (int) $settings['download_expiry_days'] * DAY_IN_SECONDS );

    // Save order
    $order_id = wp_insert_post( [
        'post_type'   => 'ppl_order',
        'post_title'  => $product_label . ' — ' . $email . ' — ' . gmdate( 'Y-m-d H:i' ),
        'post_status' => 'publish',
    ] );

    if ( $order_id && ! is_wp_error( $order_id ) ) {
        update_post_meta( $order_id, '_ppl_order_email',          $email );
        update_post_meta( $order_id, '_ppl_order_name',           $name );
        update_post_meta( $order_id, '_ppl_order_product_type',   $product_type );
        update_post_meta( $order_id, '_ppl_order_product_idx',    $product_idx );
        update_post_meta( $order_id, '_ppl_order_product_label',  $product_label );
        update_post_meta( $order_id, '_ppl_order_stripe_session', $session_id );
        update_post_meta( $order_id, '_ppl_order_amount',         $amount_total );
        update_post_meta( $order_id, '_ppl_order_status',         'complete' );
        update_post_meta( $order_id, '_ppl_order_file_url',       $file_url );
        update_post_meta( $order_id, '_ppl_order_token',          $token );
        update_post_meta( $order_id, '_ppl_order_token_expires',  $expires );
        update_post_meta( $order_id, '_ppl_order_download_count', 0 );
    }

    // Send download email
    ppl_send_download_email( $email, $name, $product_label, $token, $settings );

    status_header( 200 );
    echo 'OK';
    exit;
}

function ppl_send_download_email( $email, $name, $product_label, $token, $settings ) {
    $download_url   = add_query_arg( 'ppl_download', $token, home_url( '/' ) );
    $expiry_days    = (int) ( $settings['download_expiry_days'] ?? 7 );
    $download_limit = (int) ( $settings['download_limit'] ?? 10 );
    $site_name      = get_bloginfo( 'name' );
    $greeting       = $name ? "Hi {$name}," : 'Hi there,';

    $subject = "Your download is ready — {$product_label}";

    $body = "{$greeting}\n\n"
        . "Thank you for your purchase! Your guide is ready to download.\n\n"
        . "Product: {$product_label}\n\n"
        . "Download link:\n{$download_url}\n\n"
        . "This link expires in {$expiry_days} days and can be used up to {$download_limit} times. "
        . "Please save your file once downloaded.\n\n"
        . "If you have any questions, reply to this email.\n\n"
        . "— {$site_name}";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . get_option( 'admin_email' ) . '>',
    ];

    wp_mail( $email, $subject, $body, $headers );

    // Also notify admin
    wp_mail(
        get_option( 'admin_email' ),
        "[{$site_name}] New order — {$product_label} — {$email}",
        "New purchase received.\n\nProduct: {$product_label}\nCustomer: {$name} <{$email}>",
        $headers
    );
}


function ppl_handle_cart_order( $cart_compact, $email, $name, $product_label, $session_id, $amount_total, $settings ) {
    $site_name  = get_bloginfo( 'name' );
    $headers    = [ 'Content-Type: text/plain; charset=UTF-8', 'From: ' . $site_name . ' <' . get_option( 'admin_email' ) . '>' ];
    $expiry_days    = (int) ( $settings['download_expiry_days'] ?? 7 );
    $download_limit = (int) ( $settings['download_limit'] ?? 10 );
    $download_links = [];

    foreach ( $cart_compact as $entry ) {
        $idx        = (int) ( $entry['i'] ?? -1 );
        $file_url   = $idx >= 0 ? ( $settings['products'][ $idx ]['file_url'] ?? '' ) : '';
        $item_label = $idx >= 0 ? ( $settings['products'][ $idx ]['label'] ?? ( 'Product ' . ( $idx + 1 ) ) ) : 'Guide';

        $token   = bin2hex( random_bytes( 20 ) );
        $expires = time() + ( $expiry_days * DAY_IN_SECONDS );

        $order_id = wp_insert_post( [
            'post_type'   => 'ppl_order',
            'post_title'  => $item_label . ' — ' . $email . ' — ' . gmdate( 'Y-m-d H:i' ),
            'post_status' => 'publish',
        ] );

        if ( $order_id && ! is_wp_error( $order_id ) ) {
            update_post_meta( $order_id, '_ppl_order_email',          $email );
            update_post_meta( $order_id, '_ppl_order_name',           $name );
            update_post_meta( $order_id, '_ppl_order_product_type',   'product' );
            update_post_meta( $order_id, '_ppl_order_product_idx',    $idx );
            update_post_meta( $order_id, '_ppl_order_product_label',  $item_label );
            update_post_meta( $order_id, '_ppl_order_stripe_session', $session_id );
            update_post_meta( $order_id, '_ppl_order_amount',         0 );
            update_post_meta( $order_id, '_ppl_order_status',         'complete' );
            update_post_meta( $order_id, '_ppl_order_file_url',       $file_url );
            update_post_meta( $order_id, '_ppl_order_token',          $token );
            update_post_meta( $order_id, '_ppl_order_token_expires',  $expires );
            update_post_meta( $order_id, '_ppl_order_download_count', 0 );
        }

        $download_links[] = [
            'label' => $item_label,
            'url'   => add_query_arg( 'ppl_download', $token, home_url( '/' ) ),
        ];
    }

    // Build combined email
    $greeting  = $name ? "Hi {$name}," : 'Hi there,';
    $links_txt = implode( "\n", array_map( function( $l ) {
        return $l['label'] . ":\n" . $l['url'];
    }, $download_links ) );

    $body = "{$greeting}\n\n"
        . "Thank you for your purchase! Your guides are ready to download.\n\n"
        . "{$links_txt}\n\n"
        . "Each link expires in {$expiry_days} days and can be used up to {$download_limit} times.\n\n"
        . "— {$site_name}";

    wp_mail( $email, "Your downloads are ready — {$product_label}", $body, $headers );
    wp_mail(
        get_option( 'admin_email' ),
        "[{$site_name}] New cart order — {$product_label} — {$email}",
        "New cart purchase.\n\nItems: {$product_label}\nCustomer: {$name} <{$email}>",
        $headers
    );
}


// ── 3. DOWNLOAD ENDPOINT ───────────────────────────────────────────────────────

add_filter( 'query_vars', 'ppl_register_download_query_var' );

function ppl_register_download_query_var( $vars ) {
    $vars[] = 'ppl_download';
    return $vars;
}

add_action( 'template_redirect', 'ppl_handle_download' );

function ppl_handle_download() {
    $token = get_query_var( 'ppl_download' );
    if ( ! $token ) return;

    $token = sanitize_text_field( $token );

    // Find order by token
    $orders = get_posts( [
        'post_type'      => 'ppl_order',
        'posts_per_page' => 1,
        'meta_query'     => [ [ 'key' => '_ppl_order_token', 'value' => $token ] ],
        'fields'         => 'ids',
    ] );

    if ( empty( $orders ) ) {
        wp_die( 'This download link is invalid.', 'Invalid Link', [ 'response' => 404 ] );
    }

    $order_id = $orders[0];
    $settings = ppl_get_shop_settings();

    // Check expiry
    $expires = (int) get_post_meta( $order_id, '_ppl_order_token_expires', true );
    if ( time() > $expires ) {
        wp_die( 'This download link has expired. Please contact us to request a new one.', 'Link Expired', [ 'response' => 410 ] );
    }

    // Check download count
    $count = (int) get_post_meta( $order_id, '_ppl_order_download_count', true );
    $limit = (int) ( $settings['download_limit'] ?? 10 );
    if ( $count >= $limit ) {
        wp_die( 'This download link has reached its maximum use limit. Please contact us if you need assistance.', 'Download Limit Reached', [ 'response' => 403 ] );
    }

    $file_url = get_post_meta( $order_id, '_ppl_order_file_url', true );
    if ( ! $file_url ) {
        wp_die( 'Download file not available. Please contact us.', 'File Not Found', [ 'response' => 404 ] );
    }

    // Increment download count
    update_post_meta( $order_id, '_ppl_order_download_count', $count + 1 );

    // Deliver the file
    wp_redirect( esc_url_raw( $file_url ) );
    exit;
}


// ── STRIPE HELPERS ─────────────────────────────────────────────────────────────

function ppl_stripe_api( $method, $endpoint, $data = [] ) {
    $settings = ppl_get_shop_settings();
    $key      = $settings['stripe_secret_key'];

    if ( empty( $key ) ) {
        return new WP_Error( 'no_key', 'Stripe secret key not configured.' );
    }

    $args = [
        'method'  => strtoupper( $method ),
        'headers' => [
            'Authorization' => 'Bearer ' . $key,
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Stripe-Version' => '2024-04-10',
        ],
        'timeout' => 20,
    ];

    if ( $method === 'POST' && $data ) {
        $args['body'] = http_build_query( $data );
    }

    $response = wp_remote_request( 'https://api.stripe.com/v1' . $endpoint, $args );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( isset( $body['error'] ) ) {
        return new WP_Error( $body['error']['code'] ?? 'stripe_error', $body['error']['message'] ?? 'Stripe error.' );
    }

    return $body;
}

function ppl_verify_stripe_signature( $payload, $sig_header, $webhook_secret ) {
    if ( empty( $sig_header ) || empty( $webhook_secret ) ) return false;

    $parts = [];
    foreach ( explode( ',', $sig_header ) as $part ) {
        $pair = explode( '=', $part, 2 );
        if ( count( $pair ) === 2 ) {
            $parts[ $pair[0] ][] = $pair[1];
        }
    }

    if ( empty( $parts['t'] ) || empty( $parts['v1'] ) ) return false;

    $timestamp = (int) $parts['t'][0];

    if ( abs( time() - $timestamp ) > 300 ) return false; // 5-minute tolerance

    $signed_payload = $timestamp . '.' . $payload;
    $expected       = hash_hmac( 'sha256', $signed_payload, $webhook_secret );

    foreach ( $parts['v1'] as $sig ) {
        if ( hash_equals( $expected, $sig ) ) return true;
    }

    return false;
}
