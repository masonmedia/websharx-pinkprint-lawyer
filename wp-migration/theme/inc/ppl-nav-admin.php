<?php
// ── Seed options from existing data on first use ───────────────────────────

add_action( 'admin_init', 'ppl_seed_nav_options' );
function ppl_seed_nav_options() {
    $header = get_option( 'ppl_header_nav' );
    if ( ! $header ) {
        $links = [];
        $locations = get_nav_menu_locations();
        if ( ! empty( $locations['primary'] ) ) {
            $items = wp_get_nav_menu_items( $locations['primary'] );
            if ( $items ) {
                foreach ( $items as $item ) {
                    if ( $item->menu_item_parent != 0 ) continue;
                    $links[] = [ 'label' => $item->title, 'url' => $item->url ];
                }
            }
        }
        // Fallback to hardcoded links if no primary menu assigned
        if ( ! $links ) {
            $links = [
                [ 'label' => 'Home',       'url' => home_url( '/' ) ],
                [ 'label' => 'About',      'url' => '#' ],
                [ 'label' => 'Shop',       'url' => '#' ],
                [ 'label' => 'Membership', 'url' => '#' ],
                [ 'label' => 'Contact',    'url' => '#' ],
            ];
        }
        update_option( 'ppl_header_nav', $links );
    }

    $footer = get_option( 'ppl_footer_nav' );
    $footer_empty = ! $footer || empty( $footer['cols'] ) || ! array_filter( array_column( $footer['cols'], 'links' ) );
    if ( $footer_empty ) {
        $cols = [
            [ 'heading' => 'About',       'links' => [ ['label'=>'Shakierah Smith','url'=>'#'], ['label'=>'Our Mission','url'=>'#'], ['label'=>'The Book','url'=>'#'], ['label'=>'Press','url'=>'#'] ] ],
            [ 'heading' => 'Shop',        'links' => [ ['label'=>'Browse the Shop','url'=>'#'], ['label'=>'Book a Session','url'=>'#'] ] ],
            [ 'heading' => 'Membership',  'links' => [ ['label'=>'Join','url'=>'#'], ['label'=>'Member Login','url'=>'#'] ] ],
            [ 'heading' => 'Legal/Admin', 'links' => [ ['label'=>'Contact','url'=>'#'], ['label'=>'Privacy Policy','url'=>'#'], ['label'=>'Terms of Use','url'=>'#'] ] ],
        ];
        update_option( 'ppl_footer_nav', [ 'columns' => 4, 'cols' => $cols ] );
    }
}

// ── Register admin submenu pages ───────────────────────────────────────────

add_action( 'admin_menu', 'ppl_register_nav_admin_pages' );
function ppl_register_nav_admin_pages() {
    add_theme_page( 'Header Navigation', 'Header Nav', 'manage_options', 'ppl-header-nav', 'ppl_render_header_nav_page' );
    add_theme_page( 'Footer Navigation', 'Footer Nav', 'manage_options', 'ppl-footer-nav', 'ppl_render_footer_nav_page' );
}

// ── Save: header ───────────────────────────────────────────────────────────

add_action( 'admin_post_ppl_save_header_nav', 'ppl_save_header_nav' );
function ppl_save_header_nav() {
    check_admin_referer( 'ppl_header_nav_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

    $links = [];
    foreach ( (array) ( $_POST['nav'] ?? [] ) as $item ) {
        $label = sanitize_text_field( $item['label'] ?? '' );
        $url   = esc_url_raw( $item['url'] ?? '' );
        if ( $label && $url ) {
            $links[] = [ 'label' => $label, 'url' => $url ];
        }
    }
    update_option( 'ppl_header_nav', $links );
    wp_safe_redirect( admin_url( 'themes.php?page=ppl-header-nav&saved=1' ) );
    exit;
}

// ── Save: footer ───────────────────────────────────────────────────────────

add_action( 'admin_post_ppl_save_footer_nav', 'ppl_save_footer_nav' );
function ppl_save_footer_nav() {
    check_admin_referer( 'ppl_footer_nav_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

    $columns = max( 2, min( 4, intval( $_POST['footer_columns'] ?? 3 ) ) );
    $cols = [];
    foreach ( (array) ( $_POST['cols'] ?? [] ) as $col ) {
        $heading = sanitize_text_field( $col['heading'] ?? '' );
        $links = [];
        foreach ( (array) ( $col['links'] ?? [] ) as $link ) {
            $label = sanitize_text_field( $link['label'] ?? '' );
            $url   = esc_url_raw( $link['url'] ?? '' );
            if ( $label && $url ) {
                $links[] = [ 'label' => $label, 'url' => $url ];
            }
        }
        $cols[] = [ 'heading' => $heading, 'links' => $links ];
    }
    update_option( 'ppl_footer_nav', [ 'columns' => $columns, 'cols' => $cols ] );
    wp_safe_redirect( admin_url( 'themes.php?page=ppl-footer-nav&saved=1' ) );
    exit;
}

// ── Shared: page select dropdown ───────────────────────────────────────────

function ppl_nav_pages_select( $selected_url = '' ) {
    $pages   = get_pages( [ 'sort_column' => 'post_title', 'post_status' => 'publish' ] );
    $matched = false;
    $out     = '<select class="ppl-page-select">';
    $out    .= '<option value="">— Select a page —</option>';
    foreach ( $pages as $page ) {
        $url = get_permalink( $page->ID );
        $sel = '';
        if ( $url === $selected_url ) { $sel = ' selected'; $matched = true; }
        $out .= '<option value="' . esc_url( $url ) . '" data-title="' . esc_attr( $page->post_title ) . '"' . $sel . '>' . esc_html( $page->post_title ) . '</option>';
    }
    $out .= '<option value="custom"' . ( $selected_url && ! $matched ? ' selected' : '' ) . '>— Custom URL —</option>';
    $out .= '</select>';
    return $out;
}

// ── Shared: render one link row ────────────────────────────────────────────

function ppl_nav_link_row( $prefix, $idx, $label = '', $url = '', $disabled = false ) {
    $d = $disabled ? ' disabled' : '';
    ob_start(); ?>
    <div class="ppl-link-row">
        <input type="text" name="<?php echo esc_attr( "{$prefix}[{$idx}][label]" ); ?>" value="<?php echo esc_attr( $label ); ?>" placeholder="Label" class="ppl-label-field"<?php echo $d; ?> />
        <?php echo ppl_nav_pages_select( $url ); ?>
        <input type="text" name="<?php echo esc_attr( "{$prefix}[{$idx}][url]" ); ?>" value="<?php echo esc_url( $url ); ?>" placeholder="https://" class="ppl-url-field"<?php echo $d; ?> />
        <button type="button" class="ppl-remove-link" title="Remove">×</button>
    </div>
    <?php return ob_get_clean();
}

// ── Shared: admin styles ───────────────────────────────────────────────────

function ppl_nav_admin_styles() { ?>
<style>
.ppl-nav-wrap { overflow-x: hidden; }
.ppl-nav-wrap h1 { margin-bottom: 20px; }
.ppl-saved { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px 16px; border-radius: 4px; margin-bottom: 20px; display: inline-block; }
.ppl-row-header { display: flex; gap: 10px; padding: 0 12px; margin-bottom: 4px; }
.ppl-row-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #888; }
.ppl-row-header .h-label  { flex: 1.2; }
.ppl-row-header .h-page   { flex: 1.8; }
.ppl-row-header .h-url    { flex: 1.8; }
.ppl-row-header .h-remove { width: 28px; flex-shrink: 0; }
.ppl-link-row { display: flex; align-items: center; gap: 10px; background: #f9f9f9; border: 1px solid #e2e2e2; padding: 8px 12px; border-radius: 4px; margin-bottom: 6px; }
.ppl-link-row .ppl-label-field { flex: 1.2; min-width: 0; }
.ppl-link-row .ppl-page-select { flex: 1.8; min-width: 0; }
.ppl-link-row .ppl-url-field   { flex: 1.8; min-width: 0; }
.ppl-remove-link { color: #c0392b; background: none; border: none; font-size: 20px; line-height: 1; cursor: pointer; padding: 0; flex-shrink: 0; width: 28px; text-align: center; }
.ppl-remove-link:hover { color: #e74c3c; }
.ppl-add-link-row { display: flex; align-items: center; gap: 12px; margin-top: 6px; }
.ppl-create-page { font-size: 13px; color: #2271b1; text-decoration: none; }
.ppl-create-page:hover { text-decoration: underline; }
/* Footer column selector */
.ppl-col-count { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
.ppl-col-count strong { font-size: 13px; }
.ppl-col-btn { padding: 5px 16px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 600; }
.ppl-col-btn.active { background: #7b3f6e; color: #fff; border-color: #7b3f6e; }
/* Footer column grid */
.ppl-cols-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; margin-bottom: 24px; }
.ppl-col-panel { min-width: 0; }
.ppl-col-panel { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 16px; }
.ppl-col-panel.ppl-hidden { display: none; }
.ppl-col-heading-row { margin-bottom: 14px; }
.ppl-col-heading-row label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #888; margin-bottom: 4px; }
.ppl-col-heading-row input { width: 100%; box-sizing: border-box; }
/* Compact rows inside columns */
.ppl-col-panel .ppl-link-row { flex-wrap: wrap; gap: 6px; }
.ppl-col-panel .ppl-link-row .ppl-label-field,
.ppl-col-panel .ppl-link-row .ppl-page-select,
.ppl-col-panel .ppl-link-row .ppl-url-field { flex: 1 1 100%; }
@media (min-width: 1400px) {
    .ppl-col-panel .ppl-link-row { flex-wrap: nowrap; }
    .ppl-col-panel .ppl-link-row .ppl-label-field { flex: 1.2; }
    .ppl-col-panel .ppl-link-row .ppl-page-select { flex: 1.8; }
    .ppl-col-panel .ppl-link-row .ppl-url-field   { flex: 1.8; }
}
</style>
<?php }

// ── Header Nav page ────────────────────────────────────────────────────────

function ppl_render_header_nav_page() {
    $links = (array) get_option( 'ppl_header_nav', [] );
    ppl_nav_admin_styles();
    ?>
    <div class="wrap ppl-nav-wrap">
        <h1>Header Navigation</h1>
        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <p class="ppl-saved">&#10003; Changes saved.</p>
        <?php endif; ?>

        <p style="color:#666;margin-bottom:20px;">Each link appears in the top nav bar. Drag to reorder by saving in the order you want.</p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="ppl_save_header_nav" />
            <?php wp_nonce_field( 'ppl_header_nav_nonce' ); ?>

            <div class="ppl-row-header">
                <span class="h-label">Label</span>
                <span class="h-page">Page</span>
                <span class="h-url">URL</span>
                <span class="h-remove"></span>
            </div>
            <div id="ppl-header-links" data-next-idx="<?php echo count( $links ); ?>">
                <?php foreach ( $links as $i => $link ) : ?>
                    <?php echo ppl_nav_link_row( 'nav', $i, $link['label'], $link['url'] ); ?>
                <?php endforeach; ?>
            </div>
            <div class="ppl-add-link-row">
                <button type="button" class="button ppl-add-link" data-container="ppl-header-links" data-prefix="nav">+ Add Link</button>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" target="_blank" class="ppl-create-page">+ Manage pages ↗</a>
            </div>

            <p style="margin-top:24px"><input type="submit" class="button button-primary" value="Save Changes" /></p>
        </form>
    </div>
    <?php ppl_nav_admin_script( 'header' ); ?>
    <?php
}

// ── Footer Nav page ────────────────────────────────────────────────────────

function ppl_render_footer_nav_page() {
    $data    = (array) get_option( 'ppl_footer_nav', [] );
    $columns = max( 2, min( 4, intval( $data['columns'] ?? 3 ) ) );
    $saved   = (array) ( $data['cols'] ?? [] );
    // Always work with exactly 4 column slots, pad with empty
    $cols = array_replace(
        [ ['heading'=>'','links'=>[]], ['heading'=>'','links'=>[]], ['heading'=>'','links'=>[]], ['heading'=>'','links'=>[]] ],
        array_slice( $saved, 0, 4 )
    );
    ppl_nav_admin_styles();
    ?>
    <div class="wrap ppl-nav-wrap">
        <h1>Footer Navigation</h1>
        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <p class="ppl-saved">&#10003; Changes saved.</p>
        <?php endif; ?>

        <p style="color:#666;margin-bottom:20px;">Set the number of columns, give each a heading, then add links below.</p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="ppl_save_footer_nav" />
            <?php wp_nonce_field( 'ppl_footer_nav_nonce' ); ?>
            <input type="hidden" name="footer_columns" id="footer_columns" value="<?php echo esc_attr( $columns ); ?>" />

            <div class="ppl-col-count">
                <strong>Columns:</strong>
                <?php foreach ( [ 2, 3, 4 ] as $n ) : ?>
                    <button type="button" class="ppl-col-btn<?php echo $columns === $n ? ' active' : ''; ?>" data-cols="<?php echo $n; ?>"><?php echo $n; ?></button>
                <?php endforeach; ?>
            </div>

            <div class="ppl-cols-grid" id="ppl-cols-grid" data-cols="<?php echo esc_attr( $columns ); ?>">
                <?php foreach ( $cols as $ci => $col ) :
                    $hidden   = $ci >= $columns;
                    $dis      = $hidden ? ' disabled' : '';
                    $links    = (array) ( $col['links'] ?? [] );
                ?>
                <div class="ppl-col-panel<?php echo $hidden ? ' ppl-hidden' : ''; ?>" data-col="<?php echo $ci; ?>">
                    <div class="ppl-col-heading-row">
                        <label>Column Heading</label>
                        <input type="text" name="cols[<?php echo $ci; ?>][heading]" value="<?php echo esc_attr( $col['heading'] ); ?>" placeholder="e.g. About"<?php echo $dis; ?> />
                    </div>
                    <div class="ppl-col-links" data-col="<?php echo $ci; ?>" data-next-idx="<?php echo count( $links ); ?>">
                        <?php foreach ( $links as $li => $link ) : ?>
                            <?php echo ppl_nav_link_row( "cols[{$ci}][links]", $li, $link['label'], $link['url'], $hidden ); ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="ppl-add-link-row">
                        <button type="button" class="button ppl-add-link" data-container="ppl-col-links" data-col="<?php echo $ci; ?>" data-prefix="cols[<?php echo $ci; ?>][links]"<?php echo $dis; ?>>+ Add Link</button>
                        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" target="_blank" class="ppl-create-page">+ Manage pages ↗</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <p><input type="submit" class="button button-primary" value="Save Changes" /></p>
        </form>
    </div>
    <?php ppl_nav_admin_script( 'footer' ); ?>
    <?php
}

// ── Shared: admin JS ───────────────────────────────────────────────────────

function ppl_nav_admin_script( $page ) {
    $pages     = get_pages( [ 'sort_column' => 'post_title', 'post_status' => 'publish' ] );
    $pages_js  = [];
    foreach ( $pages as $p ) {
        $pages_js[] = [ 'url' => get_permalink( $p->ID ), 'title' => $p->post_title ];
    }
    ?>
    <script>
    (function () {
        var pages = <?php echo wp_json_encode( $pages_js ); ?>;

        // Build page select HTML (no name attr — it's a helper only)
        function pageSelectHTML() {
            var h = '<select class="ppl-page-select"><option value="">— Select a page —</option>';
            pages.forEach(function (p) {
                h += '<option value="' + p.url + '" data-title="' + p.title + '">' + p.title + '</option>';
            });
            h += '<option value="custom">— Custom URL —</option></select>';
            return h;
        }

        function makeRow(prefix, idx) {
            return '<div class="ppl-link-row">' +
                '<input type="text" name="' + prefix + '[' + idx + '][label]" placeholder="Label" class="ppl-label-field" />' +
                pageSelectHTML() +
                '<input type="text" name="' + prefix + '[' + idx + '][url]" placeholder="https://" class="ppl-url-field" />' +
                '<button type="button" class="ppl-remove-link" title="Remove">&times;</button>' +
                '</div>';
        }

        // Add link
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.ppl-add-link');
            if (!btn || btn.disabled) return;

            var container;
            if (btn.dataset.container === 'ppl-header-links') {
                container = document.getElementById('ppl-header-links');
            } else {
                container = document.querySelector('.ppl-col-links[data-col="' + btn.dataset.col + '"]');
            }
            var idx = parseInt(container.dataset.nextIdx, 10);
            container.dataset.nextIdx = idx + 1;
            container.insertAdjacentHTML('beforeend', makeRow(btn.dataset.prefix, idx));
        });

        // Remove link
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.ppl-remove-link');
            if (btn) btn.closest('.ppl-link-row').remove();
        });

        // Page select → auto-fill URL (and label if blank)
        document.addEventListener('change', function (e) {
            var sel = e.target.closest('.ppl-page-select');
            if (!sel) return;
            var row      = sel.closest('.ppl-link-row');
            var urlInput = row.querySelector('.ppl-url-field');
            var lblInput = row.querySelector('.ppl-label-field');
            var val      = sel.value;
            if (val && val !== 'custom') {
                urlInput.value = val;
                if (!lblInput.value) {
                    lblInput.value = sel.options[sel.selectedIndex].dataset.title || '';
                }
            }
        });

        <?php if ( $page === 'footer' ) : ?>
        // Column count buttons
        document.querySelectorAll('.ppl-col-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var n = parseInt(btn.dataset.cols, 10);
                document.getElementById('footer_columns').value = n;
                document.getElementById('ppl-cols-grid').dataset.cols = n;

                document.querySelectorAll('.ppl-col-btn').forEach(function (b) {
                    b.classList.toggle('active', parseInt(b.dataset.cols, 10) === n);
                });

                document.querySelectorAll('.ppl-col-panel').forEach(function (panel) {
                    var ci     = parseInt(panel.dataset.col, 10);
                    var hide   = ci >= n;
                    panel.classList.toggle('ppl-hidden', hide);
                    panel.querySelectorAll('input, select, textarea, button.ppl-add-link').forEach(function (el) {
                        el.disabled = hide;
                    });
                });
            });
        });
        <?php endif; ?>
    })();
    </script>
    <?php
}
