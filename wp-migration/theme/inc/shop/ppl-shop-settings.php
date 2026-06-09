<?php
/**
 * Pinkprint Shop — Admin Settings Page
 * Settings > Pinkprint Shop
 *
 * Stores: ppl_shop_settings (array)
 *   stripe_secret_key, stripe_webhook_secret, test_mode
 *   products: [ { price_id, file_url }, ... ]  (indexed, matches shop grid order)
 *   bundle:   { price_id, file_url }
 */

add_action( 'admin_menu', 'ppl_shop_settings_menu' );

function ppl_shop_settings_menu() {
    add_options_page(
        'Pinkprint Shop',
        'Pinkprint Shop',
        'manage_options',
        'ppl-shop',
        'ppl_render_shop_settings'
    );
}

add_action( 'admin_init', 'ppl_shop_settings_init' );

function ppl_shop_settings_init() {
    register_setting( 'ppl_shop', 'ppl_shop_settings', [
        'sanitize_callback' => 'ppl_sanitize_shop_settings',
    ] );
}

function ppl_sanitize_shop_settings( $input ) {
    $clean = [];

    $clean['stripe_secret_key']    = sanitize_text_field( $input['stripe_secret_key']    ?? '' );
    $clean['stripe_webhook_secret'] = sanitize_text_field( $input['stripe_webhook_secret'] ?? '' );
    $clean['test_mode']            = ! empty( $input['test_mode'] ) ? '1' : '0';
    $clean['download_expiry_days'] = max( 1, (int) ( $input['download_expiry_days'] ?? 7 ) );
    $clean['download_limit']       = max( 1, (int) ( $input['download_limit'] ?? 10 ) );

    $clean['products'] = [];
    if ( isset( $input['products'] ) && is_array( $input['products'] ) ) {
        foreach ( $input['products'] as $product ) {
            $clean['products'][] = [
                'label'    => sanitize_text_field( $product['label']    ?? '' ),
                'price_id' => sanitize_text_field( $product['price_id'] ?? '' ),
                'file_url' => esc_url_raw( $product['file_url']         ?? '' ),
            ];
        }
    }

    $clean['bundle'] = [
        'price_id' => sanitize_text_field( $input['bundle']['price_id'] ?? '' ),
        'file_url' => esc_url_raw( $input['bundle']['file_url']         ?? '' ),
    ];

    return $clean;
}

function ppl_get_shop_settings() {
    return wp_parse_args( get_option( 'ppl_shop_settings', [] ), [
        'stripe_secret_key'    => '',
        'stripe_webhook_secret' => '',
        'test_mode'            => '0',
        'download_expiry_days' => 7,
        'download_limit'       => 10,
        'products'             => [],
        'bundle'               => [ 'price_id' => '', 'file_url' => '' ],
    ] );
}

function ppl_render_shop_settings() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $s = ppl_get_shop_settings();

    $inp = 'style="width:100%;max-width:480px;padding:6px 10px;border:1px solid #ddd;border-radius:4px;font-size:14px;"';
    $lbl = 'style="display:block;font-weight:600;margin-bottom:4px;font-size:13px;"';
    $td1 = 'style="width:220px;vertical-align:top;padding:12px 16px 12px 0;"';
    $td2 = 'style="vertical-align:top;padding:12px 0;"';
    $h2s = 'style="font-size:13px;text-transform:uppercase;letter-spacing:1px;color:#c43670;font-weight:700;margin:20px 0 10px;border-top:1px solid #f0d0e0;padding-top:14px;"';
    $note = 'style="font-size:12px;color:#888;margin-top:4px;"';
    ?>
    <div class="wrap">
      <h1 style="display:flex;align-items:center;gap:10px;">
        <span style="color:#c43670;">&#9670;</span> Pinkprint Shop Settings
      </h1>

      <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
        <div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>
      <?php endif; ?>

      <form method="post" action="options.php">
        <?php settings_fields( 'ppl_shop' ); ?>

        <table style="width:100%;max-width:760px;border-collapse:collapse;">

          <!-- STRIPE KEYS -->
          <tr><td colspan="2"><p <?php echo $h2s; ?>>Stripe Configuration</p></td></tr>

          <tr>
            <td <?php echo $td1; ?>><label <?php echo $lbl; ?>>Secret Key</label><p <?php echo $note; ?>>Starts with <code>sk_live_</code> or <code>sk_test_</code></p></td>
            <td <?php echo $td2; ?>><input type="password" name="ppl_shop_settings[stripe_secret_key]" value="<?php echo esc_attr( $s['stripe_secret_key'] ); ?>" <?php echo $inp; ?> autocomplete="new-password" /></td>
          </tr>

          <tr>
            <td <?php echo $td1; ?>><label <?php echo $lbl; ?>>Webhook Secret</label><p <?php echo $note; ?>>Starts with <code>whsec_</code> — from Stripe Dashboard → Webhooks</p></td>
            <td <?php echo $td2; ?>><input type="password" name="ppl_shop_settings[stripe_webhook_secret]" value="<?php echo esc_attr( $s['stripe_webhook_secret'] ); ?>" <?php echo $inp; ?> autocomplete="new-password" /></td>
          </tr>

          <tr>
            <td <?php echo $td1; ?>><label <?php echo $lbl; ?>>Test Mode</label><p <?php echo $note; ?>>Enable while testing with Stripe test keys</p></td>
            <td <?php echo $td2; ?> style="padding-top:16px;">
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="ppl_shop_settings[test_mode]" value="1" <?php checked( $s['test_mode'], '1' ); ?> style="width:16px;height:16px;accent-color:#c43670;" />
                <span style="font-size:13px;">Test mode active</span>
              </label>
            </td>
          </tr>

          <!-- WEBHOOK URL -->
          <tr>
            <td <?php echo $td1; ?>><label <?php echo $lbl; ?>>Webhook URL</label><p <?php echo $note; ?>>Add this URL in Stripe → Webhooks. Listen for <code>checkout.session.completed</code></p></td>
            <td <?php echo $td2; ?> style="padding-top:16px;">
              <code style="background:#f5f5f5;padding:6px 10px;border-radius:4px;font-size:13px;display:inline-block;"><?php echo esc_url( admin_url( 'admin-post.php?action=ppl_stripe_webhook' ) ); ?></code>
            </td>
          </tr>

          <!-- DOWNLOAD SETTINGS -->
          <tr><td colspan="2"><p <?php echo $h2s; ?>>Download Settings</p></td></tr>

          <tr>
            <td <?php echo $td1; ?>><label <?php echo $lbl; ?>>Link Expiry (days)</label><p <?php echo $note; ?>>Download links expire after this many days</p></td>
            <td <?php echo $td2; ?>><input type="number" name="ppl_shop_settings[download_expiry_days]" value="<?php echo esc_attr( $s['download_expiry_days'] ); ?>" min="1" max="365" style="width:80px;padding:6px 10px;border:1px solid #ddd;border-radius:4px;font-size:14px;" /></td>
          </tr>

          <tr>
            <td <?php echo $td1; ?>><label <?php echo $lbl; ?>>Download Limit</label><p <?php echo $note; ?>>Max times each link can be used</p></td>
            <td <?php echo $td2; ?>><input type="number" name="ppl_shop_settings[download_limit]" value="<?php echo esc_attr( $s['download_limit'] ); ?>" min="1" max="100" style="width:80px;padding:6px 10px;border:1px solid #ddd;border-radius:4px;font-size:14px;" /></td>
          </tr>

          <!-- PRODUCTS -->
          <tr><td colspan="2"><p <?php echo $h2s; ?>>Products (must match Shop page grid order)</p></td></tr>

          <?php
          $product_count = max( 3, count( $s['products'] ) );
          for ( $i = 0; $i < $product_count; $i++ ) :
            $p     = $s['products'][ $i ] ?? [ 'label' => '', 'price_id' => '', 'file_url' => '' ];
            $label = $p['label'] ?: ( 'Product ' . ( $i + 1 ) );
          ?>
          <tr style="background:<?php echo $i % 2 ? '#fafafa' : '#fff'; ?>;">
            <td <?php echo $td1; ?>>
              <strong style="font-size:13px;color:#333;">Product <?php echo esc_html( $i + 1 ); ?></strong>
              <input type="text" name="ppl_shop_settings[products][<?php echo esc_attr( $i ); ?>][label]" value="<?php echo esc_attr( $p['label'] ); ?>" placeholder="Label (optional)" <?php echo $inp; ?> style="width:100%;max-width:200px;margin-top:6px;padding:4px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;" />
            </td>
            <td <?php echo $td2; ?>>
              <label <?php echo $lbl; ?>>Stripe Price ID</label>
              <input type="text" name="ppl_shop_settings[products][<?php echo esc_attr( $i ); ?>][price_id]" value="<?php echo esc_attr( $p['price_id'] ); ?>" placeholder="price_xxxxxxxxxxxxxxxxxxxxxxxx" <?php echo $inp; ?> />
              <label <?php echo $lbl; ?> style="margin-top:10px;">Download File URL</label>
              <input type="url" name="ppl_shop_settings[products][<?php echo esc_attr( $i ); ?>][file_url]" value="<?php echo esc_attr( $p['file_url'] ); ?>" placeholder="https://..." <?php echo $inp; ?> />
              <p <?php echo $note; ?>>Can be an S3 URL, CDN URL, or wp-content URL. Use a hard-to-guess path for security.</p>
            </td>
          </tr>
          <?php endfor; ?>

          <!-- BUNDLE -->
          <tr><td colspan="2"><p <?php echo $h2s; ?>>Bundle</p></td></tr>
          <tr>
            <td <?php echo $td1; ?>>
              <strong style="font-size:13px;color:#333;">Complete Collection Bundle</strong>
            </td>
            <td <?php echo $td2; ?>>
              <label <?php echo $lbl; ?>>Stripe Price ID</label>
              <input type="text" name="ppl_shop_settings[bundle][price_id]" value="<?php echo esc_attr( $s['bundle']['price_id'] ); ?>" placeholder="price_xxxxxxxxxxxxxxxxxxxxxxxx" <?php echo $inp; ?> />
              <label <?php echo $lbl; ?> style="margin-top:10px;">Download File URL</label>
              <input type="url" name="ppl_shop_settings[bundle][file_url]" value="<?php echo esc_attr( $s['bundle']['file_url'] ); ?>" placeholder="https://..." <?php echo $inp; ?> />
              <p <?php echo $note; ?>>Bundle file URL — a ZIP of all guides, or a landing page with multiple links.</p>
            </td>
          </tr>

        </table>

        <?php submit_button( 'Save Settings', 'primary', 'submit', true, [ 'style' => 'margin-top:20px;background:#c43670;border-color:#a02d5c;' ] ); ?>
      </form>
    </div>
    <?php
}
