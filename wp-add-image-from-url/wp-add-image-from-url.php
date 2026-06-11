<?php
/**
 * Plugin Name: WP Add Image From URL
 * Description: Sideload a remote image into the WordPress media library.
 * Version: 1.0.0
 * Author: Pinkprint Lawyer
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Admin menu
add_action( 'admin_menu', function () {
    add_media_page(
        'Add Image From URL',
        'Add From URL',
        'upload_files',
        'add-image-from-url',
        'waifu_render_page'
    );
} );

// Render admin page
function waifu_render_page() {
    $result = null;

    if ( isset( $_POST['waifu_url'] ) ) {
        check_admin_referer( 'waifu_sideload' );

        if ( ! current_user_can( 'upload_files' ) ) {
            wp_die( 'Insufficient permissions.' );
        }

        $url = esc_url_raw( trim( $_POST['waifu_url'] ) );
        $result = waifu_sideload( $url );
    }

    ?>
    <div class="wrap">
        <h1>Add Image From URL</h1>

        <?php if ( $result !== null ) : ?>
            <?php if ( is_wp_error( $result ) ) : ?>
                <div class="notice notice-error"><p><?php echo esc_html( $result->get_error_message() ); ?></p></div>
            <?php else : ?>
                <div class="notice notice-success">
                    <p>Image added to media library. Attachment ID: <strong><?php echo esc_html( $result ); ?></strong></p>
                    <?php echo wp_get_attachment_image( $result, 'medium' ); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post">
            <?php wp_nonce_field( 'waifu_sideload' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="waifu_url">Image URL</label></th>
                    <td>
                        <input
                            type="url"
                            id="waifu_url"
                            name="waifu_url"
                            class="regular-text"
                            placeholder="https://example.com/image.jpg"
                            required
                        />
                        <p class="description">Supported formats: jpg, jpeg, png, gif, webp, svg, avif</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="waifu_title">Title (optional)</label></th>
                    <td>
                        <input
                            type="text"
                            id="waifu_title"
                            name="waifu_title"
                            class="regular-text"
                            placeholder="Leave blank to use filename"
                        />
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Add to Media Library' ); ?>
        </form>
    </div>
    <?php
}

/**
 * Sideload an image from a URL into the media library.
 *
 * @param string $url   Remote image URL.
 * @return int|WP_Error Attachment ID on success, WP_Error on failure.
 */
function waifu_sideload( string $url ) {
    if ( empty( $url ) ) {
        return new WP_Error( 'empty_url', 'URL cannot be empty.' );
    }

    // Validate URL scheme — only http/https allowed
    $scheme = wp_parse_url( $url, PHP_URL_SCHEME );
    if ( ! in_array( $scheme, [ 'http', 'https' ], true ) ) {
        return new WP_Error( 'invalid_scheme', 'Only http and https URLs are allowed.' );
    }

    // Block private/internal IP ranges (SSRF protection)
    $host = wp_parse_url( $url, PHP_URL_HOST );
    if ( waifu_is_private_host( $host ) ) {
        return new WP_Error( 'private_host', 'Requests to private or internal addresses are not allowed.' );
    }

    // Validate file extension before fetching
    $path = wp_parse_url( $url, PHP_URL_PATH );
    $ext  = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
    $allowed = [ 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif' ];
    if ( ! in_array( $ext, $allowed, true ) ) {
        return new WP_Error( 'invalid_extension', "File extension .$ext is not allowed." );
    }

    // WP functions needed for sideloading
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $title = isset( $_POST['waifu_title'] ) ? sanitize_text_field( $_POST['waifu_title'] ) : '';

    $attachment_id = media_sideload_image( $url, 0, $title ?: null, 'id' );

    if ( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    // Verify the uploaded file is actually an image (checks MIME type, not extension)
    $mime = get_post_mime_type( $attachment_id );
    if ( ! str_starts_with( $mime, 'image/' ) ) {
        wp_delete_attachment( $attachment_id, true );
        return new WP_Error( 'not_an_image', 'The URL did not point to a valid image file.' );
    }

    return $attachment_id;
}

/**
 * Returns true if the host resolves to a private/internal IP (SSRF protection).
 */
function waifu_is_private_host( string $host ): bool {
    // Block localhost aliases
    if ( in_array( strtolower( $host ), [ 'localhost', 'local' ], true ) ) {
        return true;
    }

    $ip = gethostbyname( $host );

    return filter_var(
        $ip,
        FILTER_VALIDATE_IP,
        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    ) === false;
}
