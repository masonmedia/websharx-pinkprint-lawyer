# Pinkprint Lawyer — Theme & Mockups

WordPress child theme and static mockups for the Pinkprint Lawyer client site.

---

## Project Structure

```
/                         Static mockup HTML files (pre-WP)
wp-migration/theme/       WordPress child theme
  functions.php           Asset enqueue, AJAX handlers, contact form styles
  inc/post-types.php      Custom post type + contact form submission logic
  page-home.php           Homepage template
  page-contact.php        Dedicated contact page template
  page-default.php        Default page template
  page-blog-archive.php   Blog archive template
  single.php              Single post template
  partials/
    ppl-head.php          <head>, global CSS variables, dark mode styles
    ppl-nav.php           Sticky navbar with dark mode toggle
    ppl-footer.php        Site footer
```

---

## WordPress Theme

### Custom Post Type — Contact Submissions

Submissions from the contact form are saved as the `ppl_inquiry` CPT, visible in the WP admin under **Contact Submissions**. Each record stores:

| Meta key         | Value            |
|-----------------|-----------------|
| `ppl_inq_name`  | Sender name      |
| `ppl_inq_email` | Sender email     |
| `ppl_inq_type`  | Inquiry category |
| `ppl_inq_message` | Message body   |
| `ppl_inq_status` | `new` on create |

An admin email notification is sent for every submission with a direct link to the record.

### Contact Form

Primary path is AJAX (`wp_ajax_ppl_contact_json`) with a standard POST fallback (`admin_post_ppl_contact`) for no-JS.

Spam protection:
- **Nonce** verification on every submission
- **Honeypot** hidden field (`ppl_website`) — bots fill it, humans don't
- **Time check** — submissions within 3 seconds of page load are silently dropped

On success the form resets and a Bootstrap modal confirms the message was sent. Errors are injected inline above the form fields.

### Dark Mode

Toggled via buttons in the desktop nav and mobile offcanvas. State is persisted to `localStorage` as `ppl-theme`. The initial value is applied in a blocking `<script>` in `<head>` to prevent flash on load.

Dark overrides (`html[data-theme="dark"]`) live in `ppl-head.php` and cover:
- Navbar and offcanvas
- Section backgrounds vs. card backgrounds (distinguished by `rounded-4`/`rounded-5`)
- Hero image blend mode
- Typography, borders, buttons, footer social icons

---

## Changes — 2026-06-04

- **Contact form — AJAX handler** (`ppl_handle_contact_json`): new primary submission path returns JSON, triggers success modal instead of a page redirect.
- **Spam hardening**: added honeypot field and 3-second time check to both the AJAX and standard POST handlers.
- **Email validation**: switched from truthy check to `is_email()` in both handlers.
- **Success modal**: Bootstrap modal with check icon replaces inline alert banners on the home page contact section.
- **`pplData.ajaxurl`**: localized via `wp_localize_script` so the fetch target is always correct regardless of WP install path.
- **`page-contact.php`**: added as a standalone contact page template; registered in asset enqueue and contact form style conditions.
- **CPT labels**: renamed from "Inquiries / Inquiry" to "Contact Submissions / Submission".
- **Dark mode toggle**: added moon/sun icon buttons to desktop nav and mobile offcanvas; icon and label update on toggle.
- **Dark mode CSS**: comprehensive overrides in `ppl-head.php` — nav, offcanvas, sections, cards, hero, text, buttons, credential bar, footer.
- **Flash prevention**: blocking `<script>` in `<head>` applies saved theme before first paint.
- **Logo fix**: replaced inline `width:auto` style with utility classes on the desktop logo `<img>`.
