# Migrate to WordPress — The Pinkprint Lawyer

## Overview

Two parallel implementation approaches, both starting with `index.html` (light theme).
Dark theme follows the same process after light theme is verified.

---

## Approach 1 — Divi (Visual Builder)

### Strategy
Use native Divi modules (Text, Image, Button) with custom CSS in the child theme.
Paste shortcodes into the WP page backend (Code/Text editor), not the visual builder.
Divi parses them on save — the page then opens fully editable in the visual builder.

### Why not JSON import
Divi Library JSON wraps shortcodes in a schema that is version-sensitive and brittle.
Pasting shortcodes directly into the backend editor is more reliable and achieves the same result.

### Editability
| Content type       | Divi module     | How client edits           |
|--------------------|-----------------|----------------------------|
| All text/headings  | et_pb_text      | TinyMCE rich text editor   |
| Hero image         | et_pb_image     | Image picker in builder    |
| About photo        | et_pb_image     | Image picker in builder    |
| Book covers        | et_pb_image     | Image picker in builder    |
| CTA buttons        | et_pb_button    | Button text + URL fields   |
| Section backgrounds| et_pb_section   | Background color field     |

### Files
- `wp-migration/divi/home-shortcodes.txt` — paste into WP page backend for index.html

### Steps to deploy
1. In WP admin, create a new Page titled "Home (Light Theme)"
2. In the page editor, switch to Text/Code view (not Visual)
3. Paste the full contents of `home-shortcodes.txt`
4. Save/Publish
5. Open in Divi Visual Builder — all sections will be present and editable
6. Upload local `assets/` images to WP Media Library and update image modules

### Asset paths to update after paste
- Logo: `assets/logos/1_Primary/The Pinkprint Lawyer_Primary (1).png`
- About photo: currently external CDN URL (fine as-is or replace with WP media)
- Book covers: `assets/book_covers/Book (1)/...`, `Book (2)/...`, `Book (3)/...`
- Pink gavel: `assets/pink-gavel.jpg`

### Repeating for dark-theme.html
Follow the same steps with `wp-migration/divi/dark-shortcodes.txt` (to be generated).

---

## Approach 2 — Custom Page Template + Native WP Meta Fields

### Strategy
- Child theme page template renders the exact HTML from `index.html`
- Hardcoded content replaced with `get_post_meta()` calls
- All fields registered via `register_post_meta()` with `show_in_rest: true`
- Admin metabox provides a clean labeled form for client editing
- Contact form saves entries to a custom post type (`ppl_inquiry`) — no plugin

### File structure (relative to child theme root)
```
inc/
  ppl-helpers.php     ← ppl_get() / ppl_e() helper functions
  meta-fields.php     ← register_post_meta() for all pages (light + dark)
  meta-boxes.php      ← admin metabox UI
  post-types.php      ← ppl_inquiry CPT + its meta
partials/
  ppl-head.php        ← <!DOCTYPE html> through </head> + all shared CSS
  ppl-nav.php         ← navbar + offcanvas mobile nav
  ppl-footer.php      ← footer HTML + scroll JS + wp_footer()
page-home.php         ← Template: Home — Light Theme (includes partials)
page-dark.php         ← Template: Home — Dark Theme (to be created)
functions.php         ← require_once the inc/ files + enqueue scripts
style.css             ← child theme header (required by WP; content lives in ppl-head.php)
```

### How partials work
Every page template is structured as:
```php
<?php /* Template Name: Page Name */ ?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-[page-slug]">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<!-- page-specific sections here -->

<?php get_template_part( 'partials/ppl-footer' ); ?>
```

`ppl-head.php` contains all shared design tokens and utility CSS — no duplication across templates.
`ppl-nav.php` and `ppl-footer.php` are updated once and change everywhere.
Helper functions (`ppl_get`, `ppl_e`) are loaded from `inc/ppl-helpers.php` via `functions.php` before any template runs, so they are available inside all partials.

### Field naming convention
All fields prefixed `ppl_` (Pinkprint Lawyer). Light theme fields are unprefixed by page;
dark theme fields use `ppl_dk_` prefix to avoid collisions in the same DB.

### Meta fields — index.html (1:1 mapping)

#### Hero
| Field key                    | Type   | Label                     |
|------------------------------|--------|---------------------------|
| ppl_hero_eyebrow             | string | Eyebrow label             |
| ppl_hero_heading             | string | H1 heading                |
| ppl_hero_lead                | string | Lead paragraph            |
| ppl_hero_tagline             | string | Italic tagline            |
| ppl_hero_cta_primary_label   | string | Primary CTA text          |
| ppl_hero_cta_primary_url     | string | Primary CTA URL           |
| ppl_hero_cta_secondary_label | string | Secondary CTA text        |
| ppl_hero_cta_secondary_url   | string | Secondary CTA URL         |
| ppl_hero_image_url           | string | Hero image URL            |

#### Credential Bar
| Field key       | Type   | Label              |
|-----------------|--------|--------------------|
| ppl_cred_1      | string | Credential 1 label |
| ppl_cred_2      | string | Credential 2 label |
| ppl_cred_3      | string | Credential 3 label |
| ppl_cred_4      | string | Credential 4 label |
| ppl_cred_5      | string | Credential 5 label |

#### Mission
| Field key            | Type   | Label         |
|----------------------|--------|---------------|
| ppl_mission_eyebrow  | string | Eyebrow       |
| ppl_mission_heading  | string | H2 heading    |
| ppl_mission_body     | string | Body copy     |
| ppl_mission_image_url| string | Section image |

#### Who It's For (3 audience cards)
| Field key              | Type   | Label            |
|------------------------|--------|------------------|
| ppl_audience_eyebrow   | string | Section eyebrow  |
| ppl_audience_heading   | string | Section H2       |
| ppl_audience_subtext   | string | Section subtext  |
| ppl_audience_1_stage   | string | Card 1 stage tag |
| ppl_audience_1_title   | string | Card 1 title     |
| ppl_audience_1_body    | string | Card 1 body      |
| ppl_audience_1_badge   | string | Card 1 badge     |
| ppl_audience_2_stage   | string | Card 2 stage tag |
| ppl_audience_2_title   | string | Card 2 title     |
| ppl_audience_2_body    | string | Card 2 body      |
| ppl_audience_2_badge   | string | Card 2 badge     |
| ppl_audience_3_stage   | string | Card 3 stage tag |
| ppl_audience_3_title   | string | Card 3 title     |
| ppl_audience_3_body    | string | Card 3 body      |
| ppl_audience_3_badge   | string | Card 3 badge     |

#### About Shakierah
| Field key           | Type   | Label         |
|---------------------|--------|---------------|
| ppl_about_eyebrow   | string | Eyebrow       |
| ppl_about_heading   | string | H2 heading    |
| ppl_about_body_1    | string | Paragraph 1   |
| ppl_about_body_2    | string | Paragraph 2   |
| ppl_about_image_url | string | Photo URL     |
| ppl_about_cta_label | string | CTA text      |
| ppl_about_cta_url   | string | CTA URL       |

#### Featured Products (4 cards + session)
| Field key                  | Type   | Label              |
|----------------------------|--------|--------------------|
| ppl_products_eyebrow       | string | Section eyebrow    |
| ppl_products_heading       | string | Section H2         |
| ppl_products_subtext       | string | Section subtext    |
| ppl_product_1_stage        | string | Card 1 stage       |
| ppl_product_1_title        | string | Card 1 title       |
| ppl_product_1_body         | string | Card 1 body        |
| ppl_product_1_cta          | string | Card 1 CTA label   |
| ppl_product_1_cta_url      | string | Card 1 CTA URL     |
| ppl_product_2_stage        | string | Card 2 stage       |
| ppl_product_2_title        | string | Card 2 title       |
| ppl_product_2_body         | string | Card 2 body        |
| ppl_product_2_cta          | string | Card 2 CTA label   |
| ppl_product_2_cta_url      | string | Card 2 CTA URL     |
| ppl_product_3_stage        | string | Card 3 stage       |
| ppl_product_3_title        | string | Card 3 title       |
| ppl_product_3_body         | string | Card 3 body        |
| ppl_product_3_cta          | string | Card 3 CTA label   |
| ppl_product_3_cta_url      | string | Card 3 CTA URL     |
| ppl_product_4_stage        | string | Card 4 stage       |
| ppl_product_4_title        | string | Card 4 title       |
| ppl_product_4_body         | string | Card 4 body        |
| ppl_product_4_cta          | string | Card 4 CTA label   |
| ppl_product_4_cta_url      | string | Card 4 CTA URL     |
| ppl_session_eyebrow        | string | Session card label |
| ppl_session_title          | string | Session card title |
| ppl_session_body           | string | Session card body  |
| ppl_session_cta_label      | string | Session CTA text   |
| ppl_session_cta_url        | string | Session CTA URL    |

#### How It Works (3 steps)
| Field key         | Type   | Label          |
|-------------------|--------|----------------|
| ppl_hiw_eyebrow   | string | Section eyebrow|
| ppl_hiw_heading   | string | Section H2     |
| ppl_hiw_subtext   | string | Section subtext|
| ppl_step_1_title  | string | Step 1 title   |
| ppl_step_1_body   | string | Step 1 body    |
| ppl_step_2_title  | string | Step 2 title   |
| ppl_step_2_body   | string | Step 2 body    |
| ppl_step_3_title  | string | Step 3 title   |
| ppl_step_3_body   | string | Step 3 body    |

#### Testimonials (3)
| Field key                | Type   | Label        |
|--------------------------|--------|--------------|
| ppl_testimonials_eyebrow | string | Eyebrow      |
| ppl_testimonials_heading | string | H2           |
| ppl_testimonial_1_quote  | string | Quote 1      |
| ppl_testimonial_1_name   | string | Name 1       |
| ppl_testimonial_1_role   | string | Role 1       |
| ppl_testimonial_2_quote  | string | Quote 2      |
| ppl_testimonial_2_name   | string | Name 2       |
| ppl_testimonial_2_role   | string | Role 2       |
| ppl_testimonial_3_quote  | string | Quote 3      |
| ppl_testimonial_3_name   | string | Name 3       |
| ppl_testimonial_3_role   | string | Role 3       |

#### Book Spotlight
| Field key            | Type   | Label           |
|----------------------|--------|-----------------|
| ppl_book_eyebrow     | string | Eyebrow         |
| ppl_book_heading     | string | H2              |
| ppl_book_body        | string | Body copy       |
| ppl_book_cta_label   | string | CTA text        |
| ppl_book_cta_url     | string | CTA URL         |
| ppl_book_cover_1_url | string | Book 1 image URL|
| ppl_book_cover_2_url | string | Book 2 image URL|
| ppl_book_cover_3_url | string | Book 3 image URL|

#### Start Here (3 paths)
| Field key                   | Type   | Label           |
|-----------------------------|--------|-----------------|
| ppl_start_eyebrow           | string | Eyebrow         |
| ppl_start_heading           | string | H2              |
| ppl_start_body              | string | Subtext         |
| ppl_start_path_1_badge      | string | Path 1 badge    |
| ppl_start_path_1_title      | string | Path 1 title    |
| ppl_start_path_1_body       | string | Path 1 body     |
| ppl_start_path_1_cta        | string | Path 1 CTA text |
| ppl_start_path_1_cta_url    | string | Path 1 CTA URL  |
| ppl_start_path_2_badge      | string | Path 2 badge    |
| ppl_start_path_2_title      | string | Path 2 title    |
| ppl_start_path_2_body       | string | Path 2 body     |
| ppl_start_path_2_cta        | string | Path 2 CTA text |
| ppl_start_path_2_cta_url    | string | Path 2 CTA URL  |
| ppl_start_path_3_badge      | string | Path 3 badge    |
| ppl_start_path_3_title      | string | Path 3 title    |
| ppl_start_path_3_body       | string | Path 3 body     |
| ppl_start_path_3_cta        | string | Path 3 CTA text |
| ppl_start_path_3_cta_url    | string | Path 3 CTA URL  |
| ppl_start_cta_label         | string | Bottom CTA text |
| ppl_start_cta_url           | string | Bottom CTA URL  |

#### Contact
| Field key             | Type   | Label    |
|-----------------------|--------|----------|
| ppl_contact_eyebrow   | string | Eyebrow  |
| ppl_contact_heading   | string | H2       |
| ppl_contact_body_1    | string | Para 1   |
| ppl_contact_body_2    | string | Para 2   |

#### Contact form entries (custom post type: ppl_inquiry)
| Meta key         | Label         |
|------------------|---------------|
| ppl_inq_name     | Name          |
| ppl_inq_email    | Email         |
| ppl_inq_type     | Inquiry type  |
| ppl_inq_message  | Message       |
| ppl_inq_status   | Status (new/read/archived) |

### REST API exposure
All fields registered with `show_in_rest: true`.
Access via: `GET https://pinkprint.wpenginepowered.com/wp-json/wp/v2/pages/{id}?_fields=meta`
The `meta` key in the response contains all `ppl_` fields — ready for Nuxt consumption.

### Steps to deploy (child theme)
1. Copy files to child theme — maintain the same directory structure:
   - `inc/ppl-helpers.php`
   - `inc/meta-fields.php`
   - `inc/meta-boxes.php`
   - `inc/post-types.php`
   - `partials/ppl-head.php`
   - `partials/ppl-nav.php`
   - `partials/ppl-footer.php`
   - `page-home.php`
2. Add the contents of `functions-additions.php` to the child theme's `functions.php`
   (append to the existing file — do not replace it)
3. Upload `assets/` folder to the child theme root so asset paths resolve correctly:
   - `assets/logos/1_Primary/The Pinkprint Lawyer_Primary (1).png`
   - `assets/book_covers/Book (1)/...`, `Book (2)/...`, `Book (3)/...`
   - `assets/pink-gavel.jpg`
4. In WP admin → Pages → Add New:
   - Set Page Attributes → Template to **"Home — Light Theme"**
   - Fill in the meta fields in the PPL Content metabox below the editor
   - Any field left blank will display its hardcoded default — safe to publish before all fields are populated
5. Set this page as the static front page: Settings → Reading → "A static page" → Front Page

### Adding a new page
1. Copy the three-line shell above into a new file, e.g. `page-about.php`
2. Set the `Template Name` comment at the top, e.g. `/* Template Name: About */`
3. Add page-specific sections between the nav and footer partials
4. Register any new meta fields in `inc/meta-fields.php` and add their inputs to `inc/meta-boxes.php`
5. In WP admin, create a new Page and set its template to the new template name

### Repeating for dark-theme.html
- Dark theme fields use `ppl_dk_` prefix — added to same `inc/meta-fields.php`
- New template: `page-dark.php` with `/* Template Name: Home — Dark Theme */`
- Same partial files are reused; dark sections replace the light ones between the nav and footer includes
- Same metabox file handles both (checks template assignment)

---

## Scaling out — additional pages and features

### Arbitrary pages (Privacy Policy, Terms of Service, About, etc.)

These are standard WP pages and do not need a custom template. Two options:

**Option A — WP default template (simplest)**
Create the page in WP Admin, write content in the block editor. The page will inherit the active theme's `page.php` or `singular.php` template. If the Divi child theme is active, this gives the client a Divi-editable page automatically. No developer work required.

**Option B — Shared PPL template**
Create `page-default.php` using the same partial shell (head, nav, footer) with a single content area in the middle:
```php
<?php /* Template Name: PPL Default */ ?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white">
<?php get_template_part( 'partials/ppl-nav' ); ?>
<section class="section-pad">
  <div class="container" style="max-width:780px;">
    <?php the_content(); ?>
  </div>
</section>
<?php get_template_part( 'partials/ppl-footer' ); ?>
```
Client selects "PPL Default" in Page Attributes and writes their content in the standard block editor. Nav and footer update automatically when changed in the partials. This is the recommended approach for on-brand prose pages.

---

### Shop / ecommerce

**Plugin:** WooCommerce (free core, one-time setup)

**How it fits each approach:**

| Approach | How shop pages are styled |
|---|---|
| Custom template | Create `woocommerce/` folder in child theme. Copy only the templates you need to restyle from `wp-content/plugins/woocommerce/templates/`. Use the same CSS tokens and partials. WooCommerce checks child theme first. |
| Divi | Divi has built-in WooCommerce modules (Shop, Cart, Checkout). Add them to pages as normal Divi sections. |
| Nuxt headless | WooCommerce exposes a REST API (`/wp-json/wc/v3/products`). Nuxt fetches products and renders them with the same component library. Requires a WooCommerce API key (read-only for the frontend). |

**Developer control:** You own all WooCommerce template overrides in the child theme. Client manages products, prices, and orders in WP Admin → Products. Client cannot touch layout or CSS.

**What to do now:** Nothing — WooCommerce can be installed at any time without touching existing templates. Register a placeholder "Shop" page in WP Admin so the nav link resolves. When ready, install WooCommerce, assign its pages (shop, cart, checkout), and add child theme template overrides.

---

### Membership integration

**Plugin options:** MemberPress (paid, most polished) or Paid Memberships Pro / PMPro (free, highly capable)

**How it fits each approach:**

| Approach | Integration |
|---|---|
| Custom template | Gate content in templates with `pmpro_hasMembershipLevel()` or MemberPress conditionals. Membership login/account pages are plugin-owned — style them by copying plugin templates into the child theme or using the plugin's CSS hooks. |
| Divi | Both plugins ship Divi-compatible shortcodes. Wrap Divi sections in `[pmpro_content_message]` or `[mepr-active]` shortcodes to show/hide content by tier. |
| Nuxt headless | Membership state requires server-side auth (cookies or JWT). Nuxt middleware checks membership status via a custom WP REST endpoint or plugin-provided JWT endpoint before rendering gated content. This is the most complex integration and should be planned after the WP layer is verified. |

**Developer control:** You install and configure the plugin (tiers, pricing, redirect URLs). Client manages members and tiers in WP Admin → Memberships. Client has no access to gating logic or template code.

**What to do now:** Install the plugin on staging, configure one membership tier end-to-end, and verify the login/account flow looks correct with the PPL brand CSS. Leave production untouched until verified.

---

### Blog

WP has this natively. No plugin needed.

**Custom template approach:**
1. Create `archive.php` and `single.php` in the child theme using the partial shell
2. `archive.php` renders a grid of post cards — same card style as product cards
3. `single.php` renders the post content with the PPL typography and nav/footer
4. In WP Admin → Settings → Reading, assign a "Posts page" (a blank page titled "Blog")

**Divi approach:** Divi's Blog module handles the archive automatically. Create a page, drop in a Blog module, style it with the existing CSS.

**Nuxt headless:** WP REST API exposes posts at `/wp-json/wp/v2/posts`. Same `useFetch` pattern as pages.

---

## Approach 3 — Headless Nuxt (planned, after approaches 1 & 2)

- WP REST API endpoint: `https://pinkprint.wpenginepowered.com/wp-json/wp/v2/`
- All `ppl_` meta fields already exposed via `show_in_rest: true`
- Nuxt fetches page meta on `useFetch` / `useAsyncData`
- Components map 1:1 to sections — same structure as page template
- To be scaffolded after WP implementation is verified

### REST API surface by feature

| Feature | Endpoint | Auth required |
|---|---|---|
| Pages (meta fields) | `/wp-json/wp/v2/pages/{id}?_fields=meta` | No (public) |
| Blog posts | `/wp-json/wp/v2/posts` | No (public) |
| WooCommerce products | `/wp-json/wc/v3/products` | Yes (API key) |
| Membership status | Custom endpoint or plugin JWT | Yes (user token) |
