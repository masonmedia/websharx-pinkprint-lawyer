<?php
/**
 * Register custom post meta for PPL pages.
 * All fields use show_in_rest: true for REST API / Nuxt consumption.
 *
 * Light theme fields: ppl_*
 * Dark theme fields:  ppl_dk_* (added below when dark-theme.html is migrated)
 *
 * Repeatable sections store a JSON array in a single meta key:
 *   ppl_audience_items, ppl_products_items, ppl_testimonials_items,
 *   ppl_book_covers, ppl_start_paths
 */

add_action( 'init', 'ppl_register_meta_fields' );

function ppl_register_meta_fields() {

    $str = [
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ];

    $fields = [

        // ── HERO ──────────────────────────────────────────────────────────────
        'ppl_hero_eyebrow',
        'ppl_hero_heading',
        'ppl_hero_lead',
        'ppl_hero_tagline',
        'ppl_hero_cta_primary_label',
        'ppl_hero_cta_primary_url',
        'ppl_hero_cta_secondary_label',
        'ppl_hero_cta_secondary_url',
        'ppl_hero_image_url',

        // ── CREDENTIAL BAR ────────────────────────────────────────────────────
        'ppl_cred_1',
        'ppl_cred_2',
        'ppl_cred_3',
        'ppl_cred_4',
        'ppl_cred_5',

        // ── MISSION ───────────────────────────────────────────────────────────
        'ppl_mission_eyebrow',
        'ppl_mission_heading',
        'ppl_mission_body',
        'ppl_mission_image_url',

        // ── WHO IT'S FOR — repeater key ───────────────────────────────────────
        'ppl_audience_eyebrow',
        'ppl_audience_heading',
        'ppl_audience_subtext',
        'ppl_audience_items', // JSON: [{stage, title, body, badge}, ...]

        // ── ABOUT ─────────────────────────────────────────────────────────────
        'ppl_about_eyebrow',
        'ppl_about_heading',
        'ppl_about_body_1',
        'ppl_about_body_2',
        'ppl_about_image_url',
        'ppl_about_cta_label',
        'ppl_about_cta_url',

        // ── FEATURED PRODUCTS — repeater key ──────────────────────────────────
        'ppl_products_eyebrow',
        'ppl_products_heading',
        'ppl_products_subtext',
        'ppl_products_items', // JSON: [{stage, title, body, cta, cta_url}, ...]

        // ── SESSION CARD ──────────────────────────────────────────────────────
        'ppl_session_eyebrow',
        'ppl_session_title',
        'ppl_session_body',
        'ppl_session_cta_label',
        'ppl_session_cta_url',

        // ── HOW IT WORKS (fixed 3 steps) ──────────────────────────────────────
        'ppl_hiw_eyebrow',
        'ppl_hiw_heading',
        'ppl_hiw_subtext',
        'ppl_step_1_title',
        'ppl_step_1_body',
        'ppl_step_2_title',
        'ppl_step_2_body',
        'ppl_step_3_title',
        'ppl_step_3_body',

        // ── TESTIMONIALS — repeater key ────────────────────────────────────────
        'ppl_testimonials_eyebrow',
        'ppl_testimonials_heading',
        'ppl_testimonials_items', // JSON: [{quote, name, role}, ...]

        // ── BOOK SPOTLIGHT ────────────────────────────────────────────────────
        'ppl_book_eyebrow',
        'ppl_book_heading',
        'ppl_book_body',
        'ppl_book_cta_label',
        'ppl_book_cta_url',
        'ppl_book_covers', // JSON: [{url}, ...]

        // ── START HERE — repeater key ──────────────────────────────────────────
        'ppl_start_eyebrow',
        'ppl_start_heading',
        'ppl_start_body',
        'ppl_start_paths',   // JSON: [{badge, title, body, cta, cta_url}, ...]
        'ppl_start_cta_label',
        'ppl_start_cta_url',

        // ── FULL BLEED IMAGE ──────────────────────────────────────────────────
        'ppl_fullbleed_image_url',

        // ── CONTACT ───────────────────────────────────────────────────────────
        'ppl_contact_eyebrow',
        'ppl_contact_heading',
        'ppl_contact_body_1',
        'ppl_contact_body_2',

        // ══════════════════════════════════════════════════════════════════════
        // ABOUT PAGE FIELDS (ppl_abt_*) — page-about.php
        // ══════════════════════════════════════════════════════════════════════

        // ── HERO ──────────────────────────────────────────────────────────────
        'ppl_abt_hero_eyebrow',
        'ppl_abt_hero_heading',
        'ppl_abt_hero_body',
        'ppl_abt_hero_image_url',
        'ppl_abt_hero_bg_image_url',

        // ── KPI STRIP (fixed 4) ───────────────────────────────────────────────
        'ppl_abt_kpi_1_num',
        'ppl_abt_kpi_1_label',
        'ppl_abt_kpi_2_num',
        'ppl_abt_kpi_2_label',
        'ppl_abt_kpi_3_num',
        'ppl_abt_kpi_3_label',
        'ppl_abt_kpi_4_num',
        'ppl_abt_kpi_4_label',

        // ── MY STORY — repeater key ───────────────────────────────────────────
        'ppl_abt_story_eyebrow',
        'ppl_abt_story_heading',
        'ppl_abt_story_items', // JSON: [{title, body}, ...] — body paragraphs separated by blank lines

        // ── MY MISSION ────────────────────────────────────────────────────────
        'ppl_abt_mission_eyebrow',
        'ppl_abt_mission_heading',
        'ppl_abt_mission_subtext',
        'ppl_abt_mission_body_1',
        'ppl_abt_mission_body_2',
        'ppl_abt_mission_card_1_badge',
        'ppl_abt_mission_card_1_body',
        'ppl_abt_mission_card_2_badge',
        'ppl_abt_mission_card_2_body',
        'ppl_abt_mission_card_3_badge',
        'ppl_abt_mission_card_3_body',
        'ppl_abt_mission_quote',

        // ── START HERE ────────────────────────────────────────────────────────
        'ppl_abt_start_eyebrow',
        'ppl_abt_start_heading',
        'ppl_abt_start_body',
        'ppl_abt_start_card_1_badge',
        'ppl_abt_start_card_1_body',
        'ppl_abt_start_card_2_badge',
        'ppl_abt_start_card_2_body',
        'ppl_abt_start_card_3_badge',
        'ppl_abt_start_card_3_body',
        'ppl_abt_start_closing_1',
        'ppl_abt_start_closing_2',
        'ppl_abt_start_cta_label',
        'ppl_abt_start_cta_url',

        // ── CONTACT CTA ───────────────────────────────────────────────────────
        'ppl_abt_contact_eyebrow',
        'ppl_abt_contact_heading',
        'ppl_abt_contact_body',
        'ppl_abt_contact_cta_label',
        'ppl_abt_contact_cta_url',

        // ── DISCLAIMER ────────────────────────────────────────────────────────
        'ppl_abt_disclaimer',

        // ══════════════════════════════════════════════════════════════════════
        // CREDENTIALS PAGE FIELDS (ppl_crd_*) — page-about-credentials.php
        // ══════════════════════════════════════════════════════════════════════

        // ── PAGE HEADER ───────────────────────────────────────────────────────
        'ppl_crd_header_eyebrow',
        'ppl_crd_header_heading',
        'ppl_crd_header_body',

        // ── FULL BLEED IMAGE ──────────────────────────────────────────────────
        'ppl_crd_fullbleed_image_url',

        // ── STAT STRIP (fixed 4) ──────────────────────────────────────────────
        'ppl_crd_stat_1_num',
        'ppl_crd_stat_1_label',
        'ppl_crd_stat_2_num',
        'ppl_crd_stat_2_label',
        'ppl_crd_stat_3_num',
        'ppl_crd_stat_3_label',
        'ppl_crd_stat_4_num',
        'ppl_crd_stat_4_label',

        // ── EDUCATION & HONORS — repeater key ─────────────────────────────────
        'ppl_crd_education_eyebrow',
        'ppl_crd_education_heading',
        'ppl_crd_education_items', // JSON: [{title, body}, ...]

        // ── BAR ADMISSIONS — repeater key ─────────────────────────────────────
        'ppl_crd_bar_eyebrow',
        'ppl_crd_bar_heading',
        'ppl_crd_bar_items', // JSON: [{icon, state, date}, ...]

        // ── PROFESSIONAL EXPERIENCE — repeater key ────────────────────────────
        'ppl_crd_experience_eyebrow',
        'ppl_crd_experience_heading',
        'ppl_crd_experience_items', // JSON: [{icon, period, title, body}, ...]

        // ── LEADERSHIP & SERVICE — repeater key ───────────────────────────────
        'ppl_crd_leadership_eyebrow',
        'ppl_crd_leadership_heading',
        'ppl_crd_leadership_items', // JSON: [{icon, title, period}, ...]

        // ── PUBLICATIONS & RECOGNITION — repeater key ─────────────────────────
        'ppl_crd_publications_eyebrow',
        'ppl_crd_publications_heading',
        'ppl_crd_publications_items', // JSON: [{icon, title, body}, ...]

        // ── DISCLAIMER CTA ────────────────────────────────────────────────────
        'ppl_crd_disclaimer_eyebrow',
        'ppl_crd_disclaimer_heading',
        'ppl_crd_disclaimer_body',

        // ── CONTACT CTA ───────────────────────────────────────────────────────
        'ppl_crd_contact_eyebrow',
        'ppl_crd_contact_heading',
        'ppl_crd_contact_body',
        'ppl_crd_contact_cta_label',
        'ppl_crd_contact_cta_url',

        // ══════════════════════════════════════════════════════════════════════
        // SHOP PAGE FIELDS (ppl_shop_*) — page-shop.php
        // ══════════════════════════════════════════════════════════════════════

        // ── SHOP: HERO ────────────────────────────────────────────────────────
        'ppl_shop_hero_image_url',
        'ppl_shop_eyebrow',
        'ppl_shop_heading',
        'ppl_shop_lead',
        'ppl_shop_cta_primary_label',
        'ppl_shop_cta_secondary_label',
        'ppl_shop_cta_secondary_url',
        'ppl_shop_trust_items', // JSON: [{icon, title, body}, ...]

        // ── SHOP: PRODUCT GRID ────────────────────────────────────────────────
        'ppl_shop_grid_eyebrow',
        'ppl_shop_grid_heading',
        'ppl_shop_grid_subtext',
        'ppl_shop_items', // JSON: [{stage, title, subtitle, body, price, badge, cover_url, icon, stripe_price_id}, ...]

        // ── SHOP: BUNDLE BANNER ───────────────────────────────────────────────
        'ppl_shop_bundle_eyebrow',
        'ppl_shop_bundle_heading',
        'ppl_shop_bundle_body',
        'ppl_shop_bundle_price',
        'ppl_shop_bundle_savings',
        'ppl_shop_bundle_cta',
        'ppl_shop_bundle_stripe_price_id',

        // ── SHOP: FEATURES ("WHAT'S INSIDE") ─────────────────────────────────
        'ppl_shop_inside_eyebrow',
        'ppl_shop_inside_heading',
        'ppl_shop_feature_items', // JSON: [{icon, title, body}, ...]

        // ── SHOP: SESSION CTA ─────────────────────────────────────────────────
        'ppl_shop_session_eyebrow',
        'ppl_shop_session_heading',
        'ppl_shop_session_body',
        'ppl_shop_session_cta',
        'ppl_shop_session_url',
        'ppl_shop_session_image_url',

        // ══════════════════════════════════════════════════════════════════════
        // DARK THEME FIELDS (ppl_dk_*)
        // Add here when dark-theme.html is migrated — same file, same function.
        // ══════════════════════════════════════════════════════════════════════
    ];

    foreach ( $fields as $key ) {
        register_post_meta( 'page', $key, $str );
    }

    // ── Post fields (blog-single.php) ─────────────────────────────────────────
    $post_fields = [
        '_ppl_featured_post',
        '_ppl_post_author_name',
        '_ppl_post_author_role',
        '_ppl_post_author_bio',
        '_ppl_post_author_photo',
        '_ppl_post_cta_title',
        '_ppl_post_cta_body',
        '_ppl_post_cta_btn_label',
        '_ppl_post_cta_btn_url',
    ];

    $post_str = array_merge( $str, [
        'auth_callback' => function() {
            return current_user_can( 'edit_posts' );
        },
    ] );

    foreach ( $post_fields as $key ) {
        register_post_meta( 'post', $key, $post_str );
    }
}
