<?php
add_action('wp_enqueue_scripts', 'child_theme_scripts');
function child_theme_scripts() {
    $ppl_templates = [ 'page-home.php', 'page-default.php', 'page-blog-archive.php', 'single.php', 'page-contact.php' ];
    if ( ! is_page_template( $ppl_templates ) ) {
        wp_enqueue_style('child-style', get_stylesheet_uri());
    }
    wp_enqueue_script('child-custom-js', get_stylesheet_directory_uri() . '/js/custom.js', ['jquery'], null, true);
}

// ── Includes ───────────────────────────────────────────────────────────────

require_once get_stylesheet_directory() . '/inc/ppl-helpers.php';
require_once get_stylesheet_directory() . '/inc/meta-fields.php';
require_once get_stylesheet_directory() . '/inc/meta-boxes.php';
require_once get_stylesheet_directory() . '/inc/post-types.php';

// ── Nav menu locations ─────────────────────────────────────────────────────

add_action( 'after_setup_theme', 'ppl_register_menus' );

function ppl_register_menus() {
    register_nav_menus( [
        'primary'        => 'Primary Navigation',
        'footer_about'   => 'Footer — About column',
        'footer_shop'    => 'Footer — Shop column',
        'footer_member'  => 'Footer — Membership column',
        'footer_legal'   => 'Footer — Legal/Admin column',
    ] );
    add_theme_support( 'custom-logo', [
        'flex-width'  => true,
        'flex-height' => true,
    ] );
}

// ── Enqueue scripts & styles ───────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', 'ppl_enqueue_assets' );

function ppl_enqueue_assets() {
    // Only load on PPL page templates
    if ( ! is_page_template( [ 'page-home.php', 'page-default.php', 'page-contact.php' ] ) ) return;

    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        [],
        '5.3.3'
    );

    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
        [],
        '1.11.3'
    );

    wp_enqueue_style(
        'ppl-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_script(
        'bootstrap-bundle',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.3',
        true
    );

    wp_localize_script( 'bootstrap-bundle', 'pplData', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ] );
}

// ── REST API — expose meta fields ──────────────────────────────────────────
// Fields are already show_in_rest: true via register_post_meta().
// Access: GET /wp-json/wp/v2/pages/{id}?_fields=id,slug,meta
// The meta object will contain all ppl_* keys.

// ── Contact form styles — late priority to override Divi ──────────────────

add_action( 'wp_head', 'ppl_contact_form_styles', 999 );

function ppl_contact_form_styles() {
    if ( ! is_page_template( [ 'page-home.php', 'page-contact.php' ] ) ) return;
    ?>
    <style>
    input[type=text].ppl-form-input,
    input[type=email].ppl-form-input {
        background-color: rgba(255,255,255,0.08) !important;
        border: 1.5px solid rgba(255,255,255,0.15) !important;
        color: #fff !important;
        padding: 14px 20px !important;
        border-radius: 10px !important;
    }
    input[type=text].ppl-form-input::placeholder,
    input[type=email].ppl-form-input::placeholder { color: rgba(255,255,255,0.35) !important; }
    </style>
    <?php
}
