# WP Migration — Install Guide

Two parallel WordPress implementations of `index.html` (light theme).
Dark theme follows the same process after light theme is verified.

---

## Approach A — Custom Page Template + Native WP Meta Fields

The design renders exactly as built. Client edits content through a clean
labeled form in WP Admin. No page builder required.

### 1. Copy files to child theme

Upload these files to your child theme directory on WP Engine
(via SFTP, WP Engine's file manager, or a Git deploy):

```
wp-migration/theme/inc/ppl-helpers.php       → {child-theme}/inc/ppl-helpers.php
wp-migration/theme/inc/meta-fields.php       → {child-theme}/inc/meta-fields.php
wp-migration/theme/inc/meta-boxes.php        → {child-theme}/inc/meta-boxes.php
wp-migration/theme/inc/post-types.php        → {child-theme}/inc/post-types.php
wp-migration/theme/partials/ppl-head.php     → {child-theme}/partials/ppl-head.php
wp-migration/theme/partials/ppl-nav.php      → {child-theme}/partials/ppl-nav.php
wp-migration/theme/partials/ppl-footer.php   → {child-theme}/partials/ppl-footer.php
wp-migration/theme/page-home.php             → {child-theme}/page-home.php
```

### 2. Update functions.php

Open your child theme's `functions.php` and paste the contents of
`wp-migration/theme/functions-additions.php` at the bottom.

This adds four things:
- `require_once` for each `inc/` file
- Nav menu location registration (primary + 4 footer columns)
- Bootstrap + Bootstrap Icons + Google Fonts enqueue (only on PPL templates)
- REST API note (fields are already exposed via `show_in_rest: true`)

### 3. Create the page in WP Admin

1. WP Admin → Pages → Add New
2. Title: `Home (Light Theme)`
3. In Page Attributes (right sidebar) → Template → select **Home — Light Theme**
4. Publish the page
5. The "Page Content" metabox appears below the editor — fill in all fields

   **Repeater sections** (Featured Products, Testimonials, Book Covers, Who It's For, Start Here paths)
   show rows with an **+ Add** button. Click it to add a card; click **Remove** to delete one.
   Rows reindex automatically on save.

### 4. Configure nav and footer menus

1. WP Admin → Appearance → Menus → Create Menu
2. Assign it to the **Primary Navigation** location — this controls both the desktop navbar and mobile drawer
3. Create separate menus for each footer column and assign them to:
   - **Footer — About column**
   - **Footer — Shop column**
   - **Footer — Membership column**
   - **Footer — Legal/Admin column**

Until a menu is assigned, each location falls back to the hardcoded default links — safe to skip this step initially.

### 5. Upload assets to Media Library

Upload these local files to WP Admin → Media → Add New:

| Local file | Copy the URL into this meta field |
|---|---|
| `assets/logos/1_Primary/The Pinkprint Lawyer_Primary (1).png` | *(used in template directly — update path in page-home.php)* |
| `assets/pink-gavel.jpg` | *(hardcoded in template — update path in page-home.php)* |
| `assets/book_covers/Book (1)/.../Front Cover.png` | Book 1 image URL |
| `assets/book_covers/Book (2)/.../Front Cover.png` | Book 2 image URL |
| `assets/book_covers/Book (3)/.../Front Cover.png` | Book 3 image URL |

For the logo and gavel, update the hardcoded paths in `page-home.php`
to use `get_stylesheet_directory_uri()` or the full WP media URL.

### 6. Set as homepage (optional)

WP Admin → Settings → Reading → set "A static page" → Homepage: select the page you just created.

### 7. Verify contact form

Submit the contact form on the live page.
Check WP Admin → Inquiries — the submission should appear there with name, email, type, and message.

### How the client edits content

1. WP Admin → Pages → open the Home page
2. Scroll past the editor to the **Page Content** metabox
3. Edit any field — text areas, image URLs, CTAs
4. Click Update

---

## Approach B — Divi Visual Builder

The page is built with native Divi modules. Client edits text inline
in the Divi visual builder. No HTML is exposed.

### 1. Add the CSS

Copy the full contents of `wp-migration/divi/divi-theme-additions.css`
into **WP Admin → Divi → Theme Options → Custom CSS** tab → paste → Save.

This provides all the colour tokens, typography, card styles, and
component-level CSS that the shortcodes reference via `module_class`.

### 2. Create the page

1. WP Admin → Pages → Add New
2. Title: `Home (Light Theme) — Divi`
3. In the editor, click the **Text** tab (not Visual)
4. Paste the full contents of `wp-migration/divi/home-shortcodes.txt`
5. Click Save / Publish
6. Click **Use Divi Builder** — all sections load

### 3. Upload assets and update image modules

Upload to WP Admin → Media:

| Local file | Update in Divi |
|---|---|
| `assets/logos/1_Primary/The Pinkprint Lawyer_Primary (1).png` | Nav text module → update `<img src>` |
| `assets/pink-gavel.jpg` | Full-bleed section → Section Settings → Background Image |
| `assets/book_covers/Book (1)/.../Front Cover.png` | Book cover et_pb_image module |
| `assets/book_covers/Book (2)/.../Front Cover.png` | Book cover et_pb_image module |
| `assets/book_covers/Book (3)/.../Front Cover.png` | Book cover et_pb_image module |

### 4. How the client edits content

All text, headings, and card copy:
- Click any text block in the visual builder → edit inline, no HTML visible

Card titles and descriptions (blurb modules):
- Click a card → sidebar opens with **Title** and **Content** fields

Buttons:
- Click any button → **Button Text** and **Button URL** in sidebar

Images:
- Click any image module → **Image** field with upload/picker

Section backgrounds:
- Click the section gear icon → **Background** tab

### 5. Contact form

The form POSTs to `/wp-admin/admin-post.php` and is handled by `post-types.php`.
Make sure `post-types.php` is active in the child theme before the form is used.
Submissions appear in WP Admin → Inquiries.

---

## Repeating for dark-theme.html

### Custom template approach
1. Dark theme meta fields: add a `ppl_dk_*` block to `inc/meta-fields.php` (placeholder already in file)
2. Create `page-dark.php` (Template: Home — Dark Theme) following the same structure
3. Create a new WP page, assign the dark template, fill in meta fields

### Divi approach
1. Create `wp-migration/divi/dark-shortcodes.txt` following the same process
2. Create a new WP page, paste shortcodes, activate Divi builder

---

## Adding new pages

### Arbitrary pages (Privacy Policy, Terms, About, etc.)

**Quickest path — PPL Default template:**
1. Copy `wp-migration/theme/page-default.php` to the child theme root (create this file — see below)
2. In WP Admin → Pages → Add New, set Template → **PPL Default**
3. Write content in the standard block editor — nav and footer come from the partials automatically

`page-default.php` is a minimal shell:
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

Any page using this template stays on-brand. Nav and footer changes in the partials propagate here automatically.

**Divi fallback:** If not using the custom template approach, any WP page inherits Divi by default — client can edit it in the visual builder with no developer involvement.

---

## Adding shop, blog, and membership (future)

These can be added at any point without touching existing templates.

### Shop — WooCommerce

1. Install WooCommerce from WP Admin → Plugins → Add New
2. Run the setup wizard (creates Shop, Cart, Checkout, My Account pages)
3. In the child theme, create a `woocommerce/` folder
4. Copy only the templates you want to restyle from `wp-content/plugins/woocommerce/templates/` into `{child-theme}/woocommerce/`
5. Apply the PPL CSS tokens to the copied templates — WooCommerce checks the child theme first

Client manages products and orders in WP Admin → Products. Developer controls all layout via the child theme overrides.

### Blog

1. Create a blank page titled "Blog" in WP Admin
2. WP Admin → Settings → Reading → Posts page → select "Blog"
3. Create `archive.php` and `single.php` in the child theme using the partial shell
4. Client writes posts in WP Admin → Posts — no developer involvement per post

### Membership — Paid Memberships Pro (free) or MemberPress (paid)

1. Install the plugin
2. Configure membership tiers and pricing in WP Admin → Memberships
3. Gate specific pages or post types using the plugin's admin settings (no code needed for basic gating)
4. For template-level gating, wrap sections in `pmpro_hasMembershipLevel()` conditionals in PHP templates

Client manages members and tiers in WP Admin. Developer controls what is gated and how.

---

## REST API (for Nuxt headless, Phase 3)

All `ppl_*` meta fields are registered with `show_in_rest: true`.
No extra plugin or configuration needed.

Fetch page content:
```
GET https://pinkprint.wpenginepowered.com/wp-json/wp/v2/pages/{page_id}?_fields=id,slug,title,meta
```

The `meta` object in the response contains every `ppl_` field.
Use the page slug to identify the correct page in Nuxt `useFetch`.


## Install

WordPress Setup Steps
1. Upload Assets to Media Library
Before anything else, upload these files:

Logo: assets/logos/1_Primary/The Pinkprint Lawyer_Primary (1).png
Pink gavel: assets/pink-gavel.jpg
Book covers: assets/book_covers/Book (1,2,3)/...Front Cover.png
Then update the image URLs in the shortcode files to match what WP assigns.

2. Add CSS
WP Admin → Divi → Theme Options → Custom CSS
Paste the contents of divi-theme-additions.css. Save.

3. Enqueue Bootstrap
WP Admin → Divi → Theme Options → Integration → Add code to the <head>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
Add code before </body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
Save.

4. Build the Header
WP Admin → Divi → Theme Builder

Add Global Header → Build Global Header
In the Divi Builder, switch to Code view
Paste contents of header-shortcodes.txt
Save & Publish
5. Build the Footer
WP Admin → Divi → Theme Builder

Add Global Footer → Build Global Footer
Switch to Code view
Paste contents of footer-shortcodes.txt
Save & Publish
6. Import the Home Page Layout
WP Admin → Divi → Divi Library → Import & Export → Import tab

Upload home-layout-import.json
Click Import Divi Builder Layouts
7. Create the Home Page
WP Admin → Pages → Add New

Title: Home
Click Use Divi Builder
Click Load From Library → Your Saved Layouts
Select "Home Light Theme"
Publish
8. Set as Front Page
WP Admin → Settings → Reading

Front page displays: A static page → select "Home"