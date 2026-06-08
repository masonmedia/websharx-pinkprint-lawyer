<?php
/**
 * Admin metabox for PPL page content fields.
 * Renders on pages using the "Home" or "Home — Dark Theme" template.
 *
 * Also registers a Post Options meta box on all posts (blog-single.php fields).
 */

add_action( 'add_meta_boxes', 'ppl_add_page_meta_box' );

// ── Post meta box (blog-single.php) ────────────────────────────────────────

add_action( 'add_meta_boxes', 'ppl_add_post_meta_box' );

function ppl_add_post_meta_box() {
    add_meta_box(
        'ppl_post_options',
        'Pinkprint Post Options',
        'ppl_render_post_meta_box',
        'post',
        'side',
        'high'
    );
}

function ppl_render_post_meta_box( $post ) {
    wp_nonce_field( 'ppl_save_post_meta', 'ppl_post_meta_nonce' );

    $get = fn( $key ) => get_post_meta( $post->ID, $key, true );
    $v   = fn( $key, $default = '' ) => esc_attr( $get( $key ) ?: $default );

    $s_inp  = 'style="width:100%;padding:6px 8px;margin-bottom:12px;box-sizing:border-box;border:1px solid #ddd;border-radius:5px;"';
    $s_ta   = 'style="width:100%;padding:6px 8px;margin-bottom:12px;box-sizing:border-box;border:1px solid #ddd;border-radius:5px;resize:vertical;"';
    $s_lbl  = 'style="display:block;font-size:12px;font-weight:600;margin-bottom:3px;color:#555;"';
    $s_head = 'style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#c43670;font-weight:700;margin:16px 0 8px;border-top:1px solid #f0d0e0;padding-top:12px;"';

    $featured = (bool) $get( '_ppl_featured_post' );
    ?>
    <!-- Featured toggle -->
    <label style="display:flex;align-items:center;gap:8px;margin-bottom:16px;cursor:pointer;">
      <input type="checkbox" name="_ppl_featured_post" value="1" <?php checked( $featured ); ?>
             style="width:16px;height:16px;accent-color:#c43670;" />
      <span style="font-size:13px;font-weight:600;color:#c43670;">Feature on Blog Archive Hero</span>
    </label>
    <p style="font-size:11px;color:#888;margin-top:-10px;margin-bottom:16px;">Only one post should be featured at a time.</p>

    <p <?php echo $s_head; ?>>Author Override</p>
    <p style="font-size:11px;color:#888;margin-bottom:10px;">Leave blank to use the post author's profile.</p>

    <label <?php echo $s_lbl; ?>>Display Name</label>
    <input type="text" name="_ppl_post_author_name" value="<?php echo $v( '_ppl_post_author_name' ); ?>" <?php echo $s_inp; ?> />

    <label <?php echo $s_lbl; ?>>Role / Title</label>
    <input type="text" name="_ppl_post_author_role" value="<?php echo $v( '_ppl_post_author_role' ); ?>" placeholder="e.g. Practicing Attorney &amp; Mentor" <?php echo $s_inp; ?> />

    <label <?php echo $s_lbl; ?>>Short Bio</label>
    <textarea name="_ppl_post_author_bio" rows="3" <?php echo $s_ta; ?>><?php echo esc_textarea( $get( '_ppl_post_author_bio' ) ); ?></textarea>

    <label <?php echo $s_lbl; ?>>Photo URL</label>
    <?php
    $photo_url = $get( '_ppl_post_author_photo' );
    $display   = $photo_url ? 'block' : 'none';
    $btn_rem   = $photo_url ? 'inline-block' : 'none';
    ?>
    <div class="ppl-img-picker" style="margin-bottom:12px;">
      <img src="<?php echo esc_url( $photo_url ); ?>" class="ppl-img-preview"
           style="max-width:80px;height:80px;object-fit:cover;border-radius:50%;display:<?php echo $display; ?>;margin-bottom:6px;" />
      <input type="hidden" name="_ppl_post_author_photo" value="<?php echo esc_attr( $photo_url ); ?>" class="ppl-img-url" />
      <div style="display:flex;gap:6px;flex-wrap:wrap;">
        <button type="button" class="button ppl-choose-img" style="font-size:11px;">Select Photo</button>
        <button type="button" class="button ppl-remove-img" style="display:<?php echo $btn_rem; ?>;font-size:11px;">Remove</button>
      </div>
    </div>

    <p <?php echo $s_head; ?>>In-Article CTA Block</p>
    <p style="font-size:11px;color:#888;margin-bottom:10px;">Appears mid-article. Leave blank to hide.</p>

    <label <?php echo $s_lbl; ?>>Heading</label>
    <input type="text" name="_ppl_post_cta_title" value="<?php echo $v( '_ppl_post_cta_title' ); ?>" <?php echo $s_inp; ?> />

    <label <?php echo $s_lbl; ?>>Body Copy</label>
    <textarea name="_ppl_post_cta_body" rows="2" <?php echo $s_ta; ?>><?php echo esc_textarea( $get( '_ppl_post_cta_body' ) ); ?></textarea>

    <label <?php echo $s_lbl; ?>>Button Label</label>
    <input type="text" name="_ppl_post_cta_btn_label" value="<?php echo $v( '_ppl_post_cta_btn_label' ); ?>" <?php echo $s_inp; ?> />

    <label <?php echo $s_lbl; ?>>Button URL</label>
    <input type="text" name="_ppl_post_cta_btn_url" value="<?php echo $v( '_ppl_post_cta_btn_url' ); ?>" <?php echo $s_inp; ?> />
    <?php
}

add_action( 'save_post_post', 'ppl_save_post_meta_box' );

function ppl_save_post_meta_box( $post_id ) {
    if ( ! isset( $_POST['ppl_post_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_post_meta_nonce'] ) ), 'ppl_save_post_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Checkbox: save '1' or delete key when unchecked
    if ( ! empty( $_POST['_ppl_featured_post'] ) ) {
        update_post_meta( $post_id, '_ppl_featured_post', '1' );
    } else {
        delete_post_meta( $post_id, '_ppl_featured_post' );
    }

    $text_fields = [
        '_ppl_post_author_name',
        '_ppl_post_author_role',
        '_ppl_post_author_bio',
        '_ppl_post_author_photo',
        '_ppl_post_cta_title',
        '_ppl_post_cta_body',
        '_ppl_post_cta_btn_label',
        '_ppl_post_cta_btn_url',
    ];

    foreach ( $text_fields as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
        }
    }
}


function ppl_add_page_meta_box() {
    $screen = get_current_screen();
    if ( 'page' !== $screen->post_type ) return;

    global $post;
    if ( ! $post ) return;

    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( in_array( basename( $template ), [ 'page-home.php', 'page-dark.php' ], true ) ) {
        add_meta_box( 'ppl_page_content', 'Page Content', 'ppl_render_meta_box', 'page', 'normal', 'high' );
    } elseif ( basename( $template ) === 'page-about.php' ) {
        add_meta_box( 'ppl_about_page_content', 'Page Content', 'ppl_render_about_meta_box', 'page', 'normal', 'high' );
    }
}

add_action( 'admin_enqueue_scripts', 'ppl_enqueue_media_uploader' );

function ppl_enqueue_media_uploader( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
    wp_enqueue_media();
}

// ── Helpers ────────────────────────────────────────────────────────────────

function ppl_render_meta_box( $post ) {
    wp_nonce_field( 'ppl_save_meta', 'ppl_meta_nonce' );

    $get = fn( $key ) => get_post_meta( $post->ID, $key, true );
    $v   = fn( $key, $default = '' ) => esc_attr( $get( $key ) ?: $default );
    $t   = fn( $key, $default = '' ) => esc_textarea( $get( $key ) ?: $default );
    $j   = fn( $key ) => (array) json_decode( $get( $key ) ?: '[]', true );

    $s = 'style="width:100%;padding:6px 8px;margin-bottom:4px;box-sizing:border-box;border:1px solid #ddd;border-radius:5px;"';

    $section = fn( $label ) =>
        '<tr><td colspan="2" style="padding:0;height:36px;"></td></tr>'
        . '<tr><td colspan="2" style="padding:0 0 20px;border-top:2px solid #e5e5e5;padding-top:20px;"><strong style="font-size:13px;text-transform:uppercase;letter-spacing:1px;color:#c43670;">'
        . esc_html( $label ) . '</strong></td></tr>';

    $row = function( $label, $key, $type = 'text', $default = '' ) use ( $post, $s, $v, $t ) {
        $val   = ( $type === 'textarea' ) ? $t( $key, $default ) : $v( $key, $default );
        $input = $type === 'textarea'
            ? "<textarea name=\"{$key}\" rows=\"3\" {$s} style=\"width:100%;padding:6px 8px;margin-bottom:4px;box-sizing:border-box;border:1px solid #ddd;border-radius:5px;\">{$val}</textarea>"
            : "<input type=\"text\" name=\"{$key}\" value=\"{$val}\" {$s} />";
        return '<tr><th style="text-align:left;padding:4px 8px 4px 0;width:200px;vertical-align:top;padding-top:8px;">'
            . esc_html( $label ) . '</th><td>' . $input . '</td></tr>';
    };

    $img_row = function( $label, $key, $default = '' ) use ( $post ) {
        $url     = get_post_meta( $post->ID, $key, true ) ?: $default;
        $display = $url ? 'block' : 'none';
        $btn_rem = $url ? 'inline-block' : 'none';
        $out  = '<tr>';
        $out .= '<th style="text-align:left;padding:4px 8px 4px 0;width:200px;vertical-align:top;padding-top:8px;">' . esc_html( $label ) . '</th>';
        $out .= '<td>';
        $out .= '<div class="ppl-img-picker">';
        $out .= '<img src="' . esc_url( $url ) . '" class="ppl-img-preview" style="max-width:200px;height:auto;display:' . $display . ';margin-bottom:6px;border-radius:6px;display:' . $display . ';" />';
        $out .= '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $url ) . '" class="ppl-img-url" />';
        $out .= '<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:12px;">';
        $out .= '<button type="button" class="button ppl-choose-img">Select Image</button>';
        $out .= '<button type="button" class="button ppl-remove-img" style="display:' . $btn_rem . ';">Remove</button>';
        $out .= '<span class="ppl-img-path" style="font-size:11px;color:#888;word-break:break-all;display:' . ( $url ? 'inline' : 'none' ) . ';">' . esc_html( $url ) . '</span>';
        $out .= '</div>';
        $out .= '</div>';
        $out .= '</td></tr>';
        return $out;
    };

    echo '<table style="width:100%;border-collapse:collapse;">';

    // ── HERO ──────────────────────────────────────────────────────────────
    echo $section( 'Hero' );
    echo $row( 'Eyebrow label',      'ppl_hero_eyebrow',              'text',     'Practicing Attorney & Mentor' );
    echo $row( 'H1 heading',         'ppl_hero_heading',              'text',     'Your Pinkprint for Law School, the Bar, and Beyond.' );
    echo $row( 'Lead paragraph',     'ppl_hero_lead',                 'textarea', 'I am Shakierah Smith — a first-generation law graduate, practicing attorney, published researcher, and dedicated mentor for students navigating law school and the legal profession.' );
    echo $row( 'Italic tagline',     'ppl_hero_tagline',              'text',     'This is not about perfection. It is about preparation.' );
    echo $row( 'Primary CTA text',   'ppl_hero_cta_primary_label',   'text',     'Start Here' );
    echo $row( 'Primary CTA URL',    'ppl_hero_cta_primary_url',     'text',     '#' );
    echo $row( 'Secondary CTA text', 'ppl_hero_cta_secondary_label', 'text',     'Explore the Pinkprints' );
    echo $row( 'Secondary CTA URL',  'ppl_hero_cta_secondary_url',   'text',     '#' );
    echo $img_row( 'Hero image',     'ppl_hero_image_url',     'https://images.unsplash.com/vector-1775025870074-892399cbf787?q=80&w=1172&auto=format&fit=crop' );

    // ── CREDENTIAL BAR ────────────────────────────────────────────────────
    echo $section( 'Credential Bar' );
    $cred_defaults = [ 'First-Generation Graduate', 'Practicing Attorney', 'Published Researcher', 'Dedicated Mentor', 'Author' ];
    for ( $i = 1; $i <= 5; $i++ ) {
        echo $row( "Credential {$i}", "ppl_cred_{$i}", 'text', $cred_defaults[ $i - 1 ] );
    }

    // ── MISSION ───────────────────────────────────────────────────────────
    echo $section( 'Mission' );
    echo $row( 'Eyebrow',    'ppl_mission_eyebrow', 'text',     'My Mission' );
    echo $row( 'H2 heading', 'ppl_mission_heading', 'text',     'Law school does not come with a clear set of instructions.' );
    echo $row( 'Body copy',  'ppl_mission_body',    'textarea', 'I created The Pinkprint Lawyer to help students navigate complex systems with guidance. Here, you will find resources that are practical, evidence-informed, and designed to help you move through each stage of this journey with clarity, confidence, and intention.' );
    echo $img_row( 'Image', 'ppl_mission_image_url', 'https://images.unsplash.com/photo-1591692400544-1ad3f63a911d?q=80&w=1732&auto=format&fit=crop' );

    // ── FULL BLEED IMAGE ──────────────────────────────────────────────────
    echo $section( 'Full Bleed Image' );
    echo $img_row( 'Background image', 'ppl_fullbleed_image_url', get_stylesheet_directory_uri() . '/assets/pink-gavel.jpg' );

    // ── WHO IT'S FOR ──────────────────────────────────────────────────────
    echo $section( "Who It's For" );
    echo $row( 'Section eyebrow', 'ppl_audience_eyebrow', 'text',     'Who This Platform Serves' );
    echo $row( 'Section H2',      'ppl_audience_heading', 'text',     'Built for every stage of the legal journey.' );
    echo $row( 'Section subtext', 'ppl_audience_subtext', 'textarea', 'Built for students who want direction without intimidation. There is already enough of that in law school.' );
    echo '<tr><td colspan="2">';
    ppl_render_repeater( 'ppl_audience_items', $j( 'ppl_audience_items' ), [
        [ 'key' => 'stage', 'label' => 'Stage tag',  'type' => 'text' ],
        [ 'key' => 'title', 'label' => 'Title',       'type' => 'text' ],
        [ 'key' => 'body',  'label' => 'Body',        'type' => 'textarea' ],
        [ 'key' => 'badge', 'label' => 'Badge label', 'type' => 'text' ],
    ], 'Audience Card', [
        [ 'stage' => '01 — Aspiring',  'title' => 'Pre-Law Students',                  'body' => 'You are preparing for law school and want to make well-informed decisions before day one.',                                                          'badge' => 'Pre-Law Pinkprint' ],
        [ 'stage' => '02 — Current',   'title' => 'Law Students',                      'body' => 'You are managing coursework, examinations, and professional opportunities. You want structure, not overwhelm.',                                        'badge' => 'Study System' ],
        [ 'stage' => '03 — Graduates', 'title' => 'Recent Graduates & Bar Candidates', 'body' => 'You are transitioning into the profession and want to approach what comes next with confidence and clarity.',                                          'badge' => 'Bar Prep Guide' ],
    ] );
    echo '</td></tr>';

    // ── ABOUT ─────────────────────────────────────────────────────────────
    echo $section( 'About Shakierah' );
    echo $row( 'Eyebrow',     'ppl_about_eyebrow',   'text',     'About The Pinkprint' );
    echo $row( 'H2 heading',  'ppl_about_heading',   'text',     'Too many law students are expected to simply "figure it out".' );
    echo $row( 'Paragraph 1', 'ppl_about_body_1',    'textarea', 'I did not enter law school with a built-in roadmap or a family of attorneys. I came in as a first-generation student, learning the language, the expectations, and the unspoken rules of the profession in real time — often through trial and error. What I quickly recognized was that intelligence and work ethic were not the issue. Access to clear, honest guidance was.' );
    echo $row( 'Paragraph 2', 'ppl_about_body_2',    'textarea', 'My path was defined by preparation, discipline, and a deep commitment to excellence. I graduated in the top five percent of my class at the University at Buffalo School of Law. Out of this came The Pinkprint Lawyer — a guide for law students left to just "figure it out".' );
    echo $img_row( 'Photo',   'ppl_about_image_url',   'https://cdn.rit.edu/images/news/2024-04/WEB-20240210_shakira-smith_nycalumniupdate_0011_jamod.jpg' );
    echo $row( 'CTA text',    'ppl_about_cta_label', 'text',     'Read the Full Story' );
    echo $row( 'CTA URL',     'ppl_about_cta_url',   'text',     '#' );

    // ── FEATURED PRODUCTS ─────────────────────────────────────────────────
    echo $section( 'Featured Products' );
    echo $row( 'Section eyebrow', 'ppl_products_eyebrow', 'text',     'Featured Digital Products' );
    echo $row( 'Section H2',      'ppl_products_heading', 'text',     'Each pinkprint addresses a specific stage of your journey.' );
    echo $row( 'Section subtext', 'ppl_products_subtext', 'textarea', 'Each pinkprint is designed with clear takeaways and realistic strategies you can apply immediately. Choose what fits your current season.' );
    echo '<tr><td colspan="2">';
    ppl_render_repeater( 'ppl_products_items', $j( 'ppl_products_items' ), [
        [ 'key' => 'stage',   'label' => 'Stage',    'type' => 'text' ],
        [ 'key' => 'title',   'label' => 'Title',    'type' => 'text' ],
        [ 'key' => 'body',    'label' => 'Body',     'type' => 'textarea' ],
        [ 'key' => 'cta',     'label' => 'CTA text', 'type' => 'text' ],
        [ 'key' => 'cta_url', 'label' => 'CTA URL',  'type' => 'text' ],
    ], 'Product', [
        [ 'stage' => 'Stage 01 · Aspiring',  'title' => 'Pre-Law Preparation',     'body' => 'Pre-law preparation and planning tools to help you make well-informed decisions before day one.',              'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
        [ 'stage' => 'Stage 02 · Current',   'title' => 'Law School Study System', 'body' => 'Law school study systems and academic strategy to help you manage coursework and examinations.',               'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
        [ 'stage' => 'Stage 03 · Graduates', 'title' => 'Bar Exam & Career Prep',  'body' => 'Examination preparation, internship positioning, and career development.',                                     'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
        [ 'stage' => 'Stage 04 · Attorneys', 'title' => 'Early Career Guidance',   'body' => 'Post-graduate and early-career resources for attorneys entering the profession with confidence.',               'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
    ] );
    echo '</td></tr>';

    // ── SESSION CARD ──────────────────────────────────────────────────────
    echo $section( '1-on-1 Session Card' );
    echo $row( 'Label',    'ppl_session_eyebrow',   'text',     '1-on-1 Strategy Session' );
    echo $row( 'Title',    'ppl_session_title',     'text',     'Pre-Law, Law School & Post-Law Strategy Meeting' );
    echo $row( 'Body',     'ppl_session_body',      'textarea', 'A focused, one-hour session tailored to where you are in your journey — from choosing a law school to navigating the bar and early career.' );
    echo $row( 'CTA text', 'ppl_session_cta_label', 'text',     'Book a Session' );
    echo $row( 'CTA URL',  'ppl_session_cta_url',   'text',     '#' );

    // ── HOW IT WORKS ──────────────────────────────────────────────────────
    echo $section( 'How It Works' );
    echo $row( 'Section eyebrow', 'ppl_hiw_eyebrow', 'text',     'How It Works' );
    echo $row( 'Section H2',      'ppl_hiw_heading', 'text',     'Three steps to moving forward with clarity.' );
    echo $row( 'Section subtext', 'ppl_hiw_subtext', 'text',     'One intentional step at a time.' );
    $step_defaults = [
        1 => [ 'Find Your Stage',       'Identify where you are in your legal journey — pre-law, enrolled, or post-graduate. This determines everything that follows.' ],
        2 => [ 'Choose Your Pinkprint', 'Select the resource that fits your current season. Each pinkprint is purpose-built with clear takeaways you can apply immediately.' ],
        3 => [ 'Move with Clarity',     'Apply the strategies, follow the frameworks, and advance through your legal education with intention — not guesswork.' ],
    ];
    for ( $i = 1; $i <= 3; $i++ ) {
        echo $row( "Step {$i} title", "ppl_step_{$i}_title", 'text',     $step_defaults[ $i ][0] );
        echo $row( "Step {$i} body",  "ppl_step_{$i}_body",  'textarea', $step_defaults[ $i ][1] );
    }

    // ── TESTIMONIALS ──────────────────────────────────────────────────────
    echo $section( 'Testimonials' );
    echo $row( 'Section eyebrow', 'ppl_testimonials_eyebrow', 'text', 'Student Stories' );
    echo $row( 'Section H2',      'ppl_testimonials_heading', 'text', 'Real students. Real results.' );
    echo '<tr><td colspan="2">';
    ppl_render_repeater( 'ppl_testimonials_items', $j( 'ppl_testimonials_items' ), [
        [ 'key' => 'quote', 'label' => 'Quote', 'type' => 'textarea' ],
        [ 'key' => 'name',  'label' => 'Name',  'type' => 'text' ],
        [ 'key' => 'role',  'label' => 'Role',  'type' => 'text' ],
    ], 'Testimonial', [
        [ 'quote' => '"The Pinkprint gave me a framework I could actually use. I went into my 1L year knowing exactly what to expect and how to handle it."', 'name' => 'Alicia M.',   'role' => '1L · Howard University School of Law' ],
        [ 'quote' => '"As a first-generation student, I had no idea what I didn\'t know. This platform filled every gap — from study strategy to understanding the bar process."',    'name' => 'Jordan T.',   'role' => '2L · Temple University Beasley School of Law' ],
        [ 'quote' => '"The bar prep pinkprint helped me structure my schedule and approach the exam with confidence instead of panic. Genuinely life-changing."',                     'name' => 'Danielle R.', 'role' => 'Recent Graduate · Bar Candidate' ],
    ] );
    echo '</td></tr>';

    // ── BOOK SPOTLIGHT ────────────────────────────────────────────────────
    echo $section( 'Book Spotlight' );
    echo $row( 'Eyebrow',   'ppl_book_eyebrow',    'text',     'Now Available' );
    echo $row( 'H2',        'ppl_book_heading',    'text',     'The Pinkprint Guides' );
    echo $row( 'Body copy', 'ppl_book_body',       'textarea', 'Three guides. One through-line. Each Pinkprint meets you at a different stage of the legal journey — from your first steps into law school through the bar exam and into the profession.' );
    echo $row( 'CTA text',  'ppl_book_cta_label',  'text',     'Shop the Guides' );
    echo $row( 'CTA URL',   'ppl_book_cta_url',    'text',     '#' );
    echo '<tr><td colspan="2">';
    ppl_render_repeater( 'ppl_book_covers', $j( 'ppl_book_covers' ), [
        [ 'key' => 'url', 'label' => 'Cover image', 'type' => 'image' ],
    ], 'Book Cover', [
        [ 'url' => '' ],
        [ 'url' => '' ],
        [ 'url' => '' ],
    ] );
    echo '</td></tr>';

    // ── START HERE ────────────────────────────────────────────────────────
    echo $section( 'Start Here' );
    echo $row( 'Eyebrow', 'ppl_start_eyebrow', 'text',     'Find Your Path' );
    echo $row( 'H2',      'ppl_start_heading', 'text',     'Not sure where to start?' );
    echo $row( 'Subtext', 'ppl_start_body',    'textarea', 'If you are unsure which resource is right for you, start here. Choose your current stage and we will point you to exactly what you need.' );
    echo '<tr><td colspan="2">';
    ppl_render_repeater( 'ppl_start_paths', $j( 'ppl_start_paths' ), [
        [ 'key' => 'badge',   'label' => 'Badge',    'type' => 'text' ],
        [ 'key' => 'title',   'label' => 'Title',    'type' => 'text' ],
        [ 'key' => 'body',    'label' => 'Body',     'type' => 'textarea' ],
        [ 'key' => 'cta',     'label' => 'CTA text', 'type' => 'text' ],
        [ 'key' => 'cta_url', 'label' => 'CTA URL',  'type' => 'text' ],
    ], 'Path', [
        [ 'badge' => 'New to Law School',  'title' => 'Just Getting Started',  'body' => 'Explore the Pre-Law Pinkprint and foundation resources designed to set you up before day one.',            'cta' => 'Explore Pre-Law Resources',  'cta_url' => '#' ],
        [ 'badge' => 'Currently Enrolled', 'title' => 'In the Middle of It',   'body' => 'Find study systems, academic strategy guides, and exam prep frameworks built for active students.',         'cta' => 'Explore Study Resources',    'cta_url' => '#' ],
        [ 'badge' => 'Post-Graduate',      'title' => 'Preparing for the Bar', 'body' => 'Access bar prep frameworks and early-career transition tools for graduates entering the profession.',        'cta' => 'Explore Bar & Career Prep',  'cta_url' => '#' ],
    ] );
    echo '</td></tr>';
    echo $row( 'Bottom CTA text', 'ppl_start_cta_label', 'text', 'Start Here' );
    echo $row( 'Bottom CTA URL',  'ppl_start_cta_url',   'text', '#' );

    // ── CONTACT ───────────────────────────────────────────────────────────
    echo $section( 'Contact' );
    echo $row( 'Eyebrow',     'ppl_contact_eyebrow', 'text',     'Contact' );
    echo $row( 'H2',          'ppl_contact_heading', 'text',     'Get in Touch' );
    echo $row( 'Paragraph 1', 'ppl_contact_body_1',  'textarea', 'I am always open to thoughtful conversation and meaningful opportunities. This page is the best way to get in touch for professional inquiries, collaborations, or invitations.' );
    echo $row( 'Paragraph 2', 'ppl_contact_body_2',  'textarea', 'Whether you are reaching out with a question or exploring a potential partnership, I appreciate clarity and intention, and I do my best to respond with the same.' );

    echo '</table>';

    ppl_repeater_script();
}

// ── About page meta box ────────────────────────────────────────────────────

function ppl_render_about_meta_box( $post ) {
    wp_nonce_field( 'ppl_save_meta', 'ppl_meta_nonce' );

    $get = fn( $key ) => get_post_meta( $post->ID, $key, true );
    $v   = fn( $key, $default = '' ) => esc_attr( $get( $key ) ?: $default );
    $t   = fn( $key, $default = '' ) => esc_textarea( $get( $key ) ?: $default );
    $j   = fn( $key ) => (array) json_decode( $get( $key ) ?: '[]', true );

    $s = 'style="width:100%;padding:6px 8px;margin-bottom:4px;box-sizing:border-box;border:1px solid #ddd;border-radius:5px;"';

    $section = fn( $label ) =>
        '<tr><td colspan="2" style="padding:0;height:36px;"></td></tr>'
        . '<tr><td colspan="2" style="padding:0 0 20px;border-top:2px solid #e5e5e5;padding-top:20px;"><strong style="font-size:13px;text-transform:uppercase;letter-spacing:1px;color:#c43670;">'
        . esc_html( $label ) . '</strong></td></tr>';

    $row = function( $label, $key, $type = 'text', $default = '' ) use ( $post, $s, $v, $t ) {
        $val   = ( $type === 'textarea' ) ? $t( $key, $default ) : $v( $key, $default );
        $input = $type === 'textarea'
            ? "<textarea name=\"{$key}\" rows=\"3\" {$s} style=\"width:100%;padding:6px 8px;margin-bottom:4px;box-sizing:border-box;border:1px solid #ddd;border-radius:5px;\">{$val}</textarea>"
            : "<input type=\"text\" name=\"{$key}\" value=\"{$val}\" {$s} />";
        return '<tr><th style="text-align:left;padding:4px 8px 4px 0;width:200px;vertical-align:top;padding-top:8px;">'
            . esc_html( $label ) . '</th><td>' . $input . '</td></tr>';
    };

    $img_row = function( $label, $key, $default = '' ) use ( $post ) {
        $url     = get_post_meta( $post->ID, $key, true ) ?: $default;
        $display = $url ? 'block' : 'none';
        $btn_rem = $url ? 'inline-block' : 'none';
        $out  = '<tr>';
        $out .= '<th style="text-align:left;padding:4px 8px 4px 0;width:200px;vertical-align:top;padding-top:8px;">' . esc_html( $label ) . '</th>';
        $out .= '<td>';
        $out .= '<div class="ppl-img-picker">';
        $out .= '<img src="' . esc_url( $url ) . '" class="ppl-img-preview" style="max-width:200px;height:auto;display:' . $display . ';margin-bottom:6px;border-radius:6px;" />';
        $out .= '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $url ) . '" class="ppl-img-url" />';
        $out .= '<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:12px;">';
        $out .= '<button type="button" class="button ppl-choose-img">Select Image</button>';
        $out .= '<button type="button" class="button ppl-remove-img" style="display:' . $btn_rem . ';">Remove</button>';
        $out .= '<span class="ppl-img-path" style="font-size:11px;color:#888;word-break:break-all;display:' . ( $url ? 'inline' : 'none' ) . ';">' . esc_html( $url ) . '</span>';
        $out .= '</div>';
        $out .= '</div>';
        $out .= '</td></tr>';
        return $out;
    };

    echo '<table style="width:100%;border-collapse:collapse;">';

    // ── HERO ──────────────────────────────────────────────────────────────
    echo $section( 'Hero' );
    echo $row( 'Eyebrow label', 'ppl_abt_hero_eyebrow', 'text',     'About Shakierah Smith' );
    echo $row( 'H1 heading',    'ppl_abt_hero_heading', 'text',     'I learned the hard way <span class="text-rose d-table"> so you don\'t have to.</span>' );
    echo $row( 'Body copy',     'ppl_abt_hero_body',    'textarea', 'I did not enter law school with a built-in roadmap or a family of attorneys. I came in as a first-generation student, learning the language, the expectations, and the unspoken rules of the profession in real time; often, through trial and error.' );
    echo $img_row( 'Hero image', 'ppl_abt_hero_image_url', get_stylesheet_directory_uri() . '/assets/images/pp-about-hero.png' );

    // ── KPI STRIP ─────────────────────────────────────────────────────────
    echo $section( 'KPI Strip' );
    $kpi_defaults = [
        1 => [ '3.9',     "Cumulative GPA\nUB School of Law" ],
        2 => [ 'Top 5%',  "Class Rank\nUB School of Law" ],
        3 => [ '4.0',     "Cumulative GPA\nRochester Institute of Technology" ],
        4 => [ 'Top 1%',  "University Ranking\nRochester Institute of Technology" ],
    ];
    for ( $i = 1; $i <= 4; $i++ ) {
        echo $row( "KPI {$i} number", "ppl_abt_kpi_{$i}_num",   'text',     $kpi_defaults[ $i ][0] );
        echo $row( "KPI {$i} label (new line = line break)", "ppl_abt_kpi_{$i}_label", 'textarea', $kpi_defaults[ $i ][1] );
    }

    // ── MY STORY ──────────────────────────────────────────────────────────
    echo $section( 'My Story (Accordion)' );
    echo $row( 'Section eyebrow', 'ppl_abt_story_eyebrow', 'text', 'About The Pinkprint Lawyer' );
    echo $row( 'Section H2',      'ppl_abt_story_heading', 'text', 'My story, in chapters.' );
    echo '<tr><td colspan="2">';
    ppl_render_repeater( 'ppl_abt_story_items', $j( 'ppl_abt_story_items' ), [
        [ 'key' => 'title', 'label' => 'Chapter title',                                   'type' => 'text' ],
        [ 'key' => 'body',  'label' => 'Body (separate paragraphs with a blank line)',    'type' => 'textarea' ],
    ], 'Chapter', [
        [ 'title' => 'Where It Began',
          'body'  => "The Pinkprint Lawyer began with a straightforward realization: too many law students are expected to simply \u{201C}figure it out.\u{201D} I did not enter law school with a built-in roadmap or a family of attorneys. I came in as a first-generation student, learning the language, the expectations, and the unspoken rules of the profession in real time; often, through trial and error. What I quickly recognized was that intelligence and work ethic were not the issue. Access to clear, honest guidance was." ],
        [ 'title' => 'Through Law School',
          'body'  => 'My path through law school was defined by preparation, discipline, and a deep commitment to excellence. I graduated in the top five percent of my class at the University at Buffalo School of Law with a cumulative 3.9 GPA, earning induction into the Order of the Coif and receiving multiple academic and service-based awards, including the Max Koren Award, the Monique E. Emdin Award, the Promise Prize Scholar Award from the Change Create Transform Foundation, and the John L. Hargrave Award from the Minority Bar Foundation.' ],
        [ 'title' => 'Building Community & Research',
          'body'  => "I served as the inaugural Diversity, Equity, and Inclusion Editor of the Buffalo Law Review, a role I helped create to promote inclusivity within the journal, and founded the First-Generation Law Students Association, providing structure and community for students navigating law school without a traditional roadmap.\n\nAlongside my legal training, I spent years immersed in research, writing, and teaching-oriented roles. I served as a Faculty Research Scholar under Professor Guyora Binder, a Writing Fellow under Professor Kate Rowan, and held faculty assistantships with Professor Matthew Steilen, Professor Rebecca French, and Dean Gargano. My research contributions have been recognized in publications at the Columbia Law Review, Harvard Law & Policy Review, Emory Law Journal, and in a leading criminal law casebook. I also co-authored a peer-reviewed article in the Criminal Justice Review, and my scholarly work has been featured through RIT and UB Law.\n\nI also participated in the Criminal Justice Advocacy Clinic at the University at Buffalo School of Law, which allowed me to connect with real clients in a meaningful way and deepened my understanding of the human impact of legal work." ],
        [ 'title' => 'Before Law School',
          'body'  => "Before law school, I completed a Master of Science in Criminal Justice and a Bachelor of Science in Criminal Justice and Communication at the Rochester Institute of Technology, graduating with a cumulative 4.0 GPA and ranking in the top 1% of the entire university. During my studies, I received multiple academic honors, including the RIT Outstanding Undergraduate Scholar Award, the Thomas C. Castellano Award, the Richard B. Lewis Award, the Kearse Undergraduate Writing Award, the Shaw & McKay Award, and the Center for Public Safety Initiatives\u{2019} Excellence in Research Award. I was selected as the College of Liberal Arts 2018 Undergraduate Commencement Speaker and participated in the McNair Scholars Program, the RIT Honors Program, and the Higher Education Opportunity Program (HEOP). I also contributed to a nationally funded body-worn camera research project through the Bureau of Justice Assistance." ],
        [ 'title' => 'Practicing Today',
          'body'  => "Today, I practice law in real estate, immigration law, business law, and family law, and serve as the New York City Partner and Co-Owner of Smith & Singleton Law, a Black-owned law firm grounded in excellence, equity, and intentional advocacy. The firm officially launched on May 17, 2026, intentionally marking the 72nd anniversary of the Supreme Court\u{2019}s decision in Brown v. Board of Education; a moment that continues to shape how we think about access, opportunity, and the law\u{2019}s role in social change.\n\nMy experience across private practice at Fried, Frank, Harris, Shriver & Jacobson LLP, the United States Attorney\u{2019}s Office, the United States Court of Appeals for the Third Circuit—the second highest court in the United States—and academic institutions has given me a clear understanding of how legal careers are built. Not just on paper, but in real life." ],
        [ 'title' => 'Why The Pinkprint Lawyer Exists',
          'body'  => "I have seen what works, what is often left unsaid, and where students are most likely to feel unsure or unsupported.\n\nThe Pinkprint Lawyer exists for students who want to approach this journey with confidence rather than confusion. It is for those who want to understand the why behind each step, not just the steps themselves. And it is for anyone who has ever thought, \u{201C}I wish someone had explained this sooner.\u{201D}\n\nHere, I share the guidance I once needed: clearly, honestly, and without gatekeeping." ],
    ] );
    echo '</td></tr>';

    // ── MY MISSION ────────────────────────────────────────────────────────
    echo $section( 'My Mission' );
    echo $row( 'Eyebrow',        'ppl_abt_mission_eyebrow', 'text',     'My Mission' );
    echo $row( 'H2 heading',     'ppl_abt_mission_heading', 'text',     'My mission is to help law students move through this profession with clarity, confidence, and intention.' );
    echo $row( 'Italic subtext', 'ppl_abt_mission_subtext', 'text',     'Not fear, confusion, or unnecessary pressure.' );
    echo $row( 'Paragraph 1',    'ppl_abt_mission_body_1',  'textarea', 'Too often, legal education operates on silence and assumption. Students are expected to already know the rules, the language, and the strategy, even when no one has taken the time to explain them. That gap does not reflect a lack of ability; it reflects a lack of access.' );
    echo $row( 'Paragraph 2 (bold closing line)', 'ppl_abt_mission_body_2', 'text', 'The Pinkprint Lawyer exists to close that gap.' );
    $mission_card_defaults = [
        1 => [ 'Preparation', 'At the core of everything I create is a belief that preparation builds confidence. When students understand how the system works (academically, professionally, and culturally), they show up differently. They ask better questions. They make more informed decisions. They stop second-guessing whether they belong.' ],
        2 => [ 'Empowerment', "I am deeply committed to empowerment without gatekeeping. That means sharing information clearly, explaining the \u{201C}why\u{201D} behind the advice, and respecting the fact that every student\u{2019}s path looks different. There is no single way to succeed in law, but there are ways to move through it more intentionally." ],
        3 => [ 'Guidance',    'The Pinkprint Lawyer is here to offer structure where there is overwhelm, reassurance where there is doubt, and guidance that is both honest and practical. My goal is not only to help students succeed in law school, but to help them build careers they feel confident standing behind.' ],
    ];
    for ( $i = 1; $i <= 3; $i++ ) {
        echo $row( "Card {$i} badge", "ppl_abt_mission_card_{$i}_badge", 'text',     $mission_card_defaults[ $i ][0] );
        echo $row( "Card {$i} body",  "ppl_abt_mission_card_{$i}_body",  'textarea', $mission_card_defaults[ $i ][1] );
    }
    echo $row( 'Pull quote', 'ppl_abt_mission_quote', 'textarea', "This work isn\u{2019}t just about surviving the process, but rather, about understanding it and moving through it with purpose." );

    // ── START HERE ────────────────────────────────────────────────────────
    echo $section( 'Start Here' );
    echo $row( 'Eyebrow', 'ppl_abt_start_eyebrow', 'text',     'Start Here' );
    echo $row( 'H2',      'ppl_abt_start_heading', 'text',     'If you are new here, welcome. You do not have to sort everything out on your own.' );
    echo $row( 'Subtext', 'ppl_abt_start_body',    'textarea', 'This page is designed to help you orient quickly, based on where you are right now. No scrolling for hours, no guessing which pinkprint fits your situation. Just a clear path forward.' );
    $start_card_defaults = [
        1 => [ 'New to Law School?', 'If you are still planning, applying, or preparing for your first semester, this path is for you. You will find resources that help you understand what matters early, so you can start strong and avoid common missteps that cost students time, confidence, and opportunities.' ],
        2 => [ 'Already in Law School?', 'If you are in the middle of the experience (juggling classes, outlining, exams, internships, and everything that comes with being a law student), this path is for you. The goal here is simple: help you build systems that reduce overwhelm and strengthen performance.' ],
        3 => [ 'Just Graduated | First Position?', 'If you are stepping into the next chapter (bar prep, job searching, or your first role in the profession), this path is for you. Transition seasons can feel unclear, even when you have done everything \u{201C}right.\u{201D} This section helps you approach what comes next with direction and confidence.' ],
    ];
    for ( $i = 1; $i <= 3; $i++ ) {
        echo $row( "Card {$i} badge", "ppl_abt_start_card_{$i}_badge", 'text',     $start_card_defaults[ $i ][0] );
        echo $row( "Card {$i} body",  "ppl_abt_start_card_{$i}_body",  'textarea', $start_card_defaults[ $i ][1] );
    }
    echo $row( 'Closing line 1 (bold)', 'ppl_abt_start_closing_1', 'text',     'Wherever you are in the journey, the goal is the same:' );
    echo $row( 'Closing line 2',        'ppl_abt_start_closing_2', 'textarea', 'Less confusion, and more clarity. A plan you can actually follow!' );
    echo $row( 'Button text', 'ppl_abt_start_cta_label', 'text', 'Start Here' );
    echo $row( 'Button URL',  'ppl_abt_start_cta_url',   'text', '#' );

    // ── CONTACT CTA ───────────────────────────────────────────────────────
    echo $section( 'Contact CTA' );
    echo $row( 'Eyebrow',     'ppl_abt_contact_eyebrow',   'text',     'Contact' );
    echo $row( 'H2',          'ppl_abt_contact_heading',   'text',     'Get in Touch' );
    echo $row( 'Body copy',   'ppl_abt_contact_body',      'textarea', 'I am always open to thoughtful conversation and meaningful opportunities — questions, collaborations, speaking engagements, or media inquiries. Reach out and I will do my best to respond with clarity and intention.' );
    echo $row( 'Button text', 'ppl_abt_contact_cta_label', 'text',     'Contact Me' );
    echo $row( 'Button URL',  'ppl_abt_contact_cta_url',   'text',     '/contact' );

    // ── DISCLAIMER ────────────────────────────────────────────────────────
    echo $section( 'Disclaimer' );
    echo $row( 'Disclaimer text', 'ppl_abt_disclaimer', 'textarea', 'The Pinkprint Lawyer is an educational platform. Nothing on this site constitutes legal advice, nor does any content create an attorney–client relationship.' );

    echo '</table>';

    ppl_repeater_script();
}

// ── Repeater renderer ──────────────────────────────────────────────────────

function ppl_render_repeater( $meta_key, $rows, $fields, $row_label, $defaults ) {
    if ( empty( $rows ) ) {
        $rows = $defaults;
    }

    $row_style = 'background:#fdf2f7;border:1px solid #f0d0e0;border-radius:6px;padding:12px;margin-bottom:8px;position:relative;';
    $lbl_style = 'display:block;font-size:12px;color:#555;margin-bottom:2px;';
    $inp_style = 'width:100%;padding:5px 7px;margin-bottom:8px;box-sizing:border-box;border:1px solid #ddd;border-radius:3px;';
    $ta_style  = 'width:100%;padding:5px 7px;margin-bottom:8px;box-sizing:border-box;border:1px solid #ddd;border-radius:3px;resize:vertical;';
    $btn_rem   = 'position:absolute;top:8px;right:8px;background:#c43670;color:#fff;border:none;border-radius:4px;padding:3px 8px;font-size:11px;cursor:pointer;';
    $btn_add   = 'background:#c43670;color:#fff;border:none;border-radius:4px;padding:6px 14px;font-size:12px;cursor:pointer;margin-top:4px;';
    $num_style = 'font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#c43670;font-weight:600;margin-bottom:8px;';

    echo "<div class=\"ppl-repeater\" data-key=\"{$meta_key}\" style=\"margin:12px 0;\">";
    echo "<div class=\"ppl-repeater-rows\">";

    foreach ( $rows as $idx => $row ) {
        $row = (array) $row;
        echo "<div class=\"ppl-repeater-row\" style=\"{$row_style}\">";
        echo "<p style=\"{$num_style}\">{$row_label} " . ( $idx + 1 ) . "</p>";
        echo "<button type=\"button\" class=\"ppl-remove-row\" style=\"{$btn_rem}\">Remove</button>";
        foreach ( $fields as $field ) {
            $fname = esc_attr( "{$meta_key}[{$idx}][{$field['key']}]" );
            $fval  = $row[ $field['key'] ] ?? '';
            echo "<label style=\"{$lbl_style}\">" . esc_html( $field['label'] ) . "</label>";
            if ( $field['type'] === 'image' ) {
                $display = $fval ? 'block' : 'none';
                $btn_r   = $fval ? 'inline-block' : 'none';
                echo "<div class=\"ppl-img-picker\">";
                echo "<img src=\"" . esc_url( $fval ) . "\" class=\"ppl-img-preview\" style=\"max-width:160px;height:auto;display:{$display};margin-bottom:6px;border-radius:4px;\" />";
                echo "<input type=\"hidden\" name=\"{$fname}\" value=\"" . esc_attr( $fval ) . "\" class=\"ppl-img-url\" />";
                echo "<br><button type=\"button\" class=\"button ppl-choose-img\">Select Image</button> ";
                echo "<button type=\"button\" class=\"button ppl-remove-img\" style=\"display:{$btn_r};\">Remove</button>";
                echo "</div><br/>";
            } elseif ( $field['type'] === 'textarea' ) {
                echo "<textarea name=\"{$fname}\" rows=\"2\" style=\"{$ta_style}\">" . esc_textarea( $fval ) . "</textarea>";
            } else {
                echo "<input type=\"text\" name=\"{$fname}\" value=\"" . esc_attr( $fval ) . "\" style=\"{$inp_style}\" />";
            }
        }
        echo "</div>";
    }

    echo "</div>";

    // Hidden template for JS cloning
    echo "<template class=\"ppl-row-template\">";
    echo "<div class=\"ppl-repeater-row\" style=\"{$row_style}\">";
    echo "<p class=\"ppl-row-num\" style=\"{$num_style}\">{$row_label} __NUM__</p>";
    echo "<button type=\"button\" class=\"ppl-remove-row\" style=\"{$btn_rem}\">Remove</button>";
    foreach ( $fields as $field ) {
        echo "<label style=\"{$lbl_style}\">" . esc_html( $field['label'] ) . "</label>";
        if ( $field['type'] === 'image' ) {
            echo "<div class=\"ppl-img-picker\">";
            echo "<img src=\"\" class=\"ppl-img-preview\" style=\"max-width:160px;height:auto;display:none;margin-bottom:6px;border-radius:4px;\" />";
            echo "<input type=\"hidden\" name=\"{$meta_key}[__IDX__][{$field['key']}]\" value=\"\" class=\"ppl-img-url\" />";
            echo "<br><button type=\"button\" class=\"button ppl-choose-img\">Select Image</button> ";
            echo "<button type=\"button\" class=\"button ppl-remove-img\" style=\"display:none;\">Remove</button>";
            echo "</div><br/>";
        } elseif ( $field['type'] === 'textarea' ) {
            echo "<textarea name=\"{$meta_key}[__IDX__][{$field['key']}]\" rows=\"2\" style=\"{$ta_style}\"></textarea>";
        } else {
            echo "<input type=\"text\" name=\"{$meta_key}[__IDX__][{$field['key']}]\" style=\"{$inp_style}\" />";
        }
    }
    echo "</div>";
    echo "</template>";

    echo "<button type=\"button\" class=\"ppl-add-row\" data-label=\"{$row_label}\" style=\"{$btn_add}\">+ Add {$row_label}</button>";
    echo "</div>";
}

// ── Scripts (repeater + media picker) ─────────────────────────────────────

function ppl_repeater_script() {
    ?>
    <script>
    (function () {

        // ── Media picker ──────────────────────────────────────────────────
        function initMediaPicker( picker ) {
            var btn    = picker.querySelector( '.ppl-choose-img' );
            var rem    = picker.querySelector( '.ppl-remove-img' );
            var input  = picker.querySelector( '.ppl-img-url' );
            var preview = picker.querySelector( '.ppl-img-preview' );
            if ( ! btn ) return;

            btn.addEventListener( 'click', function () {
                var frame = wp.media({
                    title:    'Select Image',
                    button:   { text: 'Use this image' },
                    multiple: false,
                    library:  { type: 'image' },
                });
                frame.on( 'select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    var path = picker.querySelector('span.ppl-img-path');
                    input.value    = attachment.url;
                    preview.src    = attachment.url;
                    preview.style.display = 'block';
                    if ( path ) { path.textContent = attachment.url; path.style.display = 'block'; }
                    if ( rem ) rem.style.display = 'inline-block';
                });
                frame.open();
            });

            if ( rem ) {
                rem.addEventListener( 'click', function () {
                    var path = picker.querySelector('span.ppl-img-path');
                    input.value = '';
                    preview.src = '';
                    preview.style.display = 'none';
                    if ( path ) { path.textContent = ''; path.style.display = 'none'; }
                    rem.style.display = 'none';
                });
            }
        }

        document.querySelectorAll( '.ppl-img-picker' ).forEach( initMediaPicker );

        // ── Repeater ──────────────────────────────────────────────────────
        document.querySelectorAll('.ppl-add-row').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var repeater = btn.closest('.ppl-repeater');
                var rows     = repeater.querySelector('.ppl-repeater-rows');
                var tmpl     = repeater.querySelector('.ppl-row-template');
                var idx      = rows.children.length;
                var num      = idx + 1;
                var clone    = tmpl.content.cloneNode(true);

                clone.querySelectorAll('[name]').forEach(function (el) {
                    el.name = el.name.replace(/__IDX__/g, idx);
                });
                clone.querySelectorAll('.ppl-row-num').forEach(function (el) {
                    el.textContent = el.textContent.replace('__NUM__', num);
                });

                rows.appendChild(clone);
                var newRow = rows.lastElementChild;
                attachRemove(newRow);
                newRow.querySelectorAll('.ppl-img-picker').forEach(initMediaPicker);
                renumber(rows, btn.dataset.label);
            });
        });

        document.querySelectorAll('.ppl-repeater-rows').forEach(function (rows) {
            Array.from(rows.children).forEach(function (row) { attachRemove(row); });
        });

        function attachRemove(row) {
            var btn = row.querySelector('.ppl-remove-row');
            if (!btn) return;
            btn.addEventListener('click', function () {
                var rows  = btn.closest('.ppl-repeater-rows');
                var label = btn.closest('.ppl-repeater').querySelector('.ppl-add-row').dataset.label;
                row.remove();
                reindex(rows, label);
            });
        }

        function reindex(rows, label) {
            Array.from(rows.children).forEach(function (row, idx) {
                row.querySelectorAll('[name]').forEach(function (el) {
                    el.name = el.name.replace(/\[\d+\]/g, '[' + idx + ']');
                });
                var num = row.querySelector('.ppl-row-num');
                if (num) num.textContent = label + ' ' + (idx + 1);
            });
        }

        function renumber(rows, label) {
            Array.from(rows.children).forEach(function (row, idx) {
                var num = row.querySelector('.ppl-row-num');
                if (num) num.textContent = label + ' ' + (idx + 1);
            });
        }

    })();
    </script>
    <?php
}

// ── Save ───────────────────────────────────────────────────────────────────

add_action( 'save_post_page', 'ppl_save_meta_box' );

function ppl_save_meta_box( $post_id ) {
    if ( ! isset( $_POST['ppl_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ppl_meta_nonce'] ) ), 'ppl_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_page', $post_id ) ) return;

    $repeater_keys = [
        'ppl_audience_items',
        'ppl_products_items',
        'ppl_testimonials_items',
        'ppl_book_covers',
        'ppl_start_paths',
        'ppl_abt_story_items',
    ];

    foreach ( $repeater_keys as $key ) {
        if ( isset( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) ) {
            $rows = array_map( function( $row ) {
                return array_map( 'sanitize_textarea_field', array_map( 'wp_unslash', (array) $row ) );
            }, $_POST[ $key ] );
            update_post_meta( $post_id, $key, wp_json_encode( array_values( $rows ) ) );
        }
    }

    foreach ( $_POST as $key => $val ) {
        if ( ! str_starts_with( $key, 'ppl_' ) ) continue;
        if ( $key === 'ppl_meta_nonce' ) continue;
        if ( in_array( $key, $repeater_keys, true ) ) continue;
        update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $val ) ) );
    }
}
