# Nuxt Headless Setup

Consuming the WordPress REST API in a Nuxt 3 app.
All `ppl_*` meta fields are already exposed via `show_in_rest: true` — no WP plugin needed.

---

## 1. Environment

```env
# .env
NUXT_PUBLIC_WP_API=https://pinkprint.wpenginepowered.com/wp-json/wp/v2
```

```ts
// nuxt.config.ts
export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      wpApi: process.env.NUXT_PUBLIC_WP_API,
    },
  },
})
```

---

## 2. Fetching a page

Identify pages by slug, not ID (IDs change between environments).

```ts
// composables/useWpPage.ts
export async function useWpPage(slug: string) {
  const { public: { wpApi } } = useRuntimeConfig()

  const { data } = await useFetch(`${wpApi}/pages`, {
    query: { slug, _fields: 'id,slug,title,meta' },
  })

  const page = computed(() => data.value?.[0] ?? null)
  const meta = computed(() => page.value ? normalizePageMeta(page.value.meta) : null)

  return { page, meta }
}
```

---

## 3. Normalising meta — parse repeater JSON strings

Repeater fields are stored and returned as JSON strings. Parse them once here
so every component receives plain arrays.

```ts
// composables/usePageMeta.ts
const REPEATER_KEYS = [
  'ppl_audience_items',
  'ppl_products_items',
  'ppl_testimonials_items',
  'ppl_book_covers',
  'ppl_start_paths',
] as const

export function normalizePageMeta(meta: Record<string, string>) {
  return Object.fromEntries(
    Object.entries(meta).map(([k, v]) => {
      if ((REPEATER_KEYS as readonly string[]).includes(k)) {
        try {
          return [k, JSON.parse(v || '[]')]
        } catch {
          return [k, []]
        }
      }
      return [k, v]
    })
  )
}
```

After normalisation the repeater keys are arrays of objects, e.g.:

```ts
meta.ppl_audience_items
// [{ stage: '01 — Aspiring', title: 'Pre-Law Students', body: '...', badge: '...' }, ...]

meta.ppl_products_items
// [{ stage, title, body, cta, cta_url }, ...]

meta.ppl_testimonials_items
// [{ quote, name, role }, ...]

meta.ppl_book_covers
// [{ url }, ...]

meta.ppl_start_paths
// [{ badge, title, body, cta, cta_url }, ...]
```

---

## 4. Image URLs — limitation to know

Image fields (`ppl_hero_image_url`, `ppl_mission_image_url`, `ppl_about_image_url`,
`ppl_book_covers[].url`) store plain URL strings, not WP attachment IDs.

**This means:** no srcset, no alt text, no responsive sizes from the WP media library.

**Options:**

a) **Keep plain URLs (current approach)** — simplest, fine if images are
   managed externally or don't need srcset. Add `alt` text as a separate
   meta field if needed (e.g. `ppl_hero_image_alt`).

b) **Switch to attachment IDs** — store the WP media attachment ID instead
   of the URL in each image field, then resolve via the REST API:
   ```
   GET /wp/v2/media/{attachment_id}?_fields=source_url,alt_text,media_details
   ```
   `media_details.sizes` gives you full srcset data. More work up front,
   better for SEO and responsive images.

For now, use option (a) and supply `alt` props manually in components.

---

## 5. Credential bar

`ppl_cred_1` through `ppl_cred_5` are separate scalar fields. Collect them
into an array in the component rather than in the composable:

```ts
const creds = computed(() =>
  [1, 2, 3, 4, 5]
    .map(i => meta.value?.[`ppl_cred_${i}`])
    .filter(Boolean)
)
```

---

## 6. How It Works steps

Steps are flat scalars (`ppl_step_1_title`, `ppl_step_1_body`, etc.). Same pattern:

```ts
const steps = computed(() =>
  [1, 2, 3].map(i => ({
    title: meta.value?.[`ppl_hiw_step_${i}_title`] ?? '',
    body:  meta.value?.[`ppl_hiw_step_${i}_body`]  ?? '',
  }))
)
```

Wait — field names in `meta-fields.php` are `ppl_step_N_title` / `ppl_step_N_body`
(no `hiw_` prefix). Use those exact keys:

```ts
const steps = computed(() =>
  [1, 2, 3].map(i => ({
    title: meta.value?.[`ppl_step_${i}_title`] ?? '',
    body:  meta.value?.[`ppl_step_${i}_body`]  ?? '',
  }))
)
```

---

## 7. Dark theme

Dark theme fields will use the `ppl_dk_*` prefix (registered in the same
`ppl_register_meta_fields()` function when `page-dark.php` is migrated).

When that happens, add the dark repeater keys to `REPEATER_KEYS` in
`usePageMeta.ts` — e.g. `ppl_dk_audience_items`, `ppl_dk_products_items`, etc.

Fetch the dark home page by its slug the same way:

```ts
const { meta } = await useWpPage('home-dark')
```

---

## 8. CORS

WP Engine blocks cross-origin REST API requests by default.
Add this to the child theme's `functions.php`:

```php
add_action( 'rest_api_init', function () {
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
    add_filter( 'rest_pre_serve_request', function ( $value ) {
        header( 'Access-Control-Allow-Origin: https://your-nuxt-domain.com' );
        header( 'Access-Control-Allow-Methods: GET' );
        return $value;
    } );
} );
```

Replace the origin with your Nuxt domain (or `*` during local dev only).

Also extend the CORS filter to allow POST for the contact form endpoint:

```php
header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
header( 'Access-Control-Allow-Headers: Content-Type' );
```

---

## 9. Contact form — WP REST endpoint

Form submissions POST to a custom route that saves a `ppl_inquiry` CPT entry.
No plugin required — registered in `inc/post-types.php` alongside the CPT definition.

### WP side — register the route

```php
// inc/post-types.php (add alongside ppl_inquiry CPT registration)
add_action( 'rest_api_init', function () {
    register_rest_route( 'ppl/v1', '/inquiries', [
        'methods'             => 'POST',
        'callback'            => 'ppl_handle_inquiry',
        'permission_callback' => '__return_true',
        'args' => [
            'name'    => [ 'required' => true,  'sanitize_callback' => 'sanitize_text_field' ],
            'email'   => [ 'required' => true,  'sanitize_callback' => 'sanitize_email' ],
            'type'    => [ 'required' => false, 'sanitize_callback' => 'sanitize_text_field' ],
            'message' => [ 'required' => true,  'sanitize_callback' => 'sanitize_textarea_field' ],
        ],
    ] );
} );

function ppl_handle_inquiry( WP_REST_Request $request ) {
    $post_id = wp_insert_post( [
        'post_type'   => 'ppl_inquiry',
        'post_status' => 'publish',
        'post_title'  => sanitize_text_field( $request['name'] ) . ' — ' . current_time( 'mysql' ),
    ] );

    if ( is_wp_error( $post_id ) ) {
        return new WP_Error( 'insert_failed', 'Could not save inquiry.', [ 'status' => 500 ] );
    }

    update_post_meta( $post_id, 'ppl_inq_name',    $request['name'] );
    update_post_meta( $post_id, 'ppl_inq_email',   $request['email'] );
    update_post_meta( $post_id, 'ppl_inq_type',    $request['type'] ?? '' );
    update_post_meta( $post_id, 'ppl_inq_message', $request['message'] );
    update_post_meta( $post_id, 'ppl_inq_status',  'new' );

    return new WP_REST_Response( [ 'success' => true, 'id' => $post_id ], 201 );
}
```

Endpoint: `POST https://pinkprint.wpenginepowered.com/wp-json/ppl/v1/inquiries`

### Nuxt side — composable

```ts
// composables/useContactForm.ts
export function useContactForm() {
  const { public: { wpApi } } = useRuntimeConfig()
  const endpoint = wpApi.replace('/wp/v2', '') + '/ppl/v1/inquiries'

  const submitting = ref(false)
  const submitted  = ref(false)
  const error      = ref<string | null>(null)

  async function submit(payload: {
    name: string
    email: string
    type?: string
    message: string
  }) {
    submitting.value = true
    error.value = null

    try {
      await $fetch(endpoint, { method: 'POST', body: payload })
      submitted.value = true
    } catch (e: any) {
      error.value = e?.data?.message ?? 'Something went wrong. Please try again.'
    } finally {
      submitting.value = false
    }
  }

  return { submit, submitting, submitted, error }
}
```

### Usage in the contact section component

```vue
<script setup lang="ts">
const { submit, submitting, submitted, error } = useContactForm()
const form = reactive({ name: '', email: '', type: '', message: '' })
</script>

<template>
  <div v-if="submitted">
    <p>Thank you — we'll be in touch shortly.</p>
  </div>
  <form v-else @submit.prevent="submit(form)">
    <!-- fields bound to form.name, form.email, etc. -->
    <p v-if="error" class="text-danger">{{ error }}</p>
    <button type="submit" :disabled="submitting">
      {{ submitting ? 'Sending…' : 'Send Message' }}
    </button>
  </form>
</template>
```

---

## Field reference

| Section | Key(s) | Type |
|---|---|---|
| Hero | `ppl_hero_eyebrow` `ppl_hero_heading` `ppl_hero_lead` `ppl_hero_tagline` `ppl_hero_cta_primary_label` `ppl_hero_cta_primary_url` `ppl_hero_cta_secondary_label` `ppl_hero_cta_secondary_url` `ppl_hero_image_url` | string |
| Credential bar | `ppl_cred_1` … `ppl_cred_5` | string |
| Mission | `ppl_mission_eyebrow` `ppl_mission_heading` `ppl_mission_body` `ppl_mission_image_url` | string |
| Who It's For | `ppl_audience_eyebrow` `ppl_audience_heading` `ppl_audience_subtext` `ppl_audience_items` | string / **JSON array** |
| About | `ppl_about_eyebrow` `ppl_about_heading` `ppl_about_body_1` `ppl_about_body_2` `ppl_about_image_url` `ppl_about_cta_label` `ppl_about_cta_url` | string |
| Featured Products | `ppl_products_eyebrow` `ppl_products_heading` `ppl_products_subtext` `ppl_products_items` | string / **JSON array** |
| Session Card | `ppl_session_eyebrow` `ppl_session_title` `ppl_session_body` `ppl_session_cta_label` `ppl_session_cta_url` | string |
| How It Works | `ppl_hiw_eyebrow` `ppl_hiw_heading` `ppl_hiw_subtext` `ppl_step_{1-3}_title` `ppl_step_{1-3}_body` | string |
| Testimonials | `ppl_testimonials_eyebrow` `ppl_testimonials_heading` `ppl_testimonials_items` | string / **JSON array** |
| Book Spotlight | `ppl_book_eyebrow` `ppl_book_heading` `ppl_book_body` `ppl_book_cta_label` `ppl_book_cta_url` `ppl_book_covers` | string / **JSON array** |
| Start Here | `ppl_start_eyebrow` `ppl_start_heading` `ppl_start_body` `ppl_start_paths` `ppl_start_cta_label` `ppl_start_cta_url` | string / **JSON array** |
| Contact | `ppl_contact_eyebrow` `ppl_contact_heading` `ppl_contact_body_1` `ppl_contact_body_2` | string |
