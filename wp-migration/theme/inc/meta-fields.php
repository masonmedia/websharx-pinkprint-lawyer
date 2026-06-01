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
        // DARK THEME FIELDS (ppl_dk_*)
        // Add here when dark-theme.html is migrated — same file, same function.
        // ══════════════════════════════════════════════════════════════════════
    ];

    foreach ( $fields as $key ) {
        register_post_meta( 'page', $key, $str );
    }
}
