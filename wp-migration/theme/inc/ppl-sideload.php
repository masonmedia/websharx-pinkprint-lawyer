<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', function () {
    add_media_page(
        'Add Image From URL',
        'Add From URL',
        'upload_files',
        'add-image-from-url',
        'ppl_sideload_render_page'
    );
} );

function ppl_sideload_render_page() {
    $result = null;

    if ( isset( $_POST['ppl_sideload_url'] ) ) {
        check_admin_referer( 'ppl_sideload' );

        if ( ! current_user_can( 'upload_files' ) ) {
            wp_die( 'Insufficient permissions.' );
        }

        $url    = esc_url_raw( trim( $_POST['ppl_sideload_url'] ) );
        $title  = isset( $_POST['ppl_sideload_title'] ) ? sanitize_text_field( $_POST['ppl_sideload_title'] ) : '';
        $result = ppl_sideload_image( $url, $title );
    }

    ?>
    <div class="wrap">
        <h1>Add Image From URL</h1>

        <?php if ( $result !== null ) : ?>
            <?php if ( is_wp_error( $result ) ) : ?>
                <div class="notice notice-error"><p><?php echo esc_html( $result->get_error_message() ); ?></p></div>
            <?php else : ?>
                <div class="notice notice-success">
                    <p>Image added. Attachment ID: <strong><?php echo esc_html( $result ); ?></strong></p>
                    <?php echo wp_get_attachment_image( $result, 'medium' ); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post">
            <?php wp_nonce_field( 'ppl_sideload' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="ppl_sideload_url">Image URL</label></th>
                    <td>
                        <input type="url" id="ppl_sideload_url" name="ppl_sideload_url"
                               class="regular-text" placeholder="https://example.com/image.jpg" required />
                        <p class="description">Supported: jpg, jpeg, png, gif, webp, svg, avif</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="ppl_sideload_title">Title (optional)</label></th>
                    <td>
                        <input type="text" id="ppl_sideload_title" name="ppl_sideload_title"
                               class="regular-text" placeholder="Leave blank to use filename" />
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Add to Media Library' ); ?>
        </form>
    </div>
    <?php
}

/**
 * Sideload a remote image into the WP media library.
 *
 * @param string $url   Remote image URL (http/https only).
 * @param string $title Optional attachment title.
 * @return int|WP_Error Attachment ID on success.
 */
function ppl_sideload_image( string $url, string $title = '' ) {
    if ( empty( $url ) ) {
        return new WP_Error( 'empty_url', 'URL cannot be empty.' );
    }

    $scheme = wp_parse_url( $url, PHP_URL_SCHEME );
    if ( ! in_array( $scheme, [ 'http', 'https' ], true ) ) {
        return new WP_Error( 'invalid_scheme', 'Only http and https URLs are allowed.' );
    }

    $host = wp_parse_url( $url, PHP_URL_HOST );
    if ( ppl_sideload_is_private_host( $host ) ) {
        return new WP_Error( 'private_host', 'Requests to private or internal addresses are not allowed.' );
    }

    $ext     = strtolower( pathinfo( wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
    $allowed = [ 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif' ];
    if ( ! in_array( $ext, $allowed, true ) ) {
        return new WP_Error( 'invalid_extension', "File extension .$ext is not allowed." );
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $attachment_id = media_sideload_image( $url, 0, $title ?: null, 'id' );

    if ( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    $mime = get_post_mime_type( $attachment_id );
    if ( ! str_starts_with( $mime, 'image/' ) ) {
        wp_delete_attachment( $attachment_id, true );
        return new WP_Error( 'not_an_image', 'The URL did not point to a valid image file.' );
    }

    return $attachment_id;
}

function ppl_sideload_is_private_host( string $host ): bool {
    if ( in_array( strtolower( $host ), [ 'localhost', 'local' ], true ) ) {
        return true;
    }

    $ip = gethostbyname( $host );

    return filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) === false;
}
