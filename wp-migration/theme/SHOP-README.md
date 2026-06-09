# Pinkprint Shop — Setup & Checkout Flow

## How the checkout works

```
User clicks "Checkout" on the cart
        ↓
POST → /wp-admin/admin-post.php (action: ppl_cart_checkout)
        ↓
 [Stripe key configured?]
    NO  → Test checkout page (mock confirmation)
    YES → Stripe creates a hosted Checkout Session → redirect to stripe.com
        ↓
User pays on Stripe
        ↓
Stripe redirects back to shop page with ?ppl=success
Stripe webhook fires → saves order in WP → emails download link to customer
        ↓
Customer clicks link in email → download token validated → PDF delivered
```

---

## Test mode (no Stripe key)

When no Stripe secret key is configured, checkout redirects to a built-in test page at `/?ppl_test_checkout=<token>`.

- Shows the order summary
- Asks for an email address
- "Confirm Test Purchase" runs the full post-payment flow: saves a `ppl_order` post and sends the download email
- No money is charged, no Stripe involved

This lets you test the complete order + email flow before the client has a Stripe account.

---

## Stripe setup (when client is ready)

### 1. Create a Stripe account
Have the client go to stripe.com and create an account.

### 2. Enter keys in WP Admin
Go to **WP Admin → Shop Settings** and fill in:

| Field | Where to find it |
|---|---|
| Stripe Secret Key | Stripe Dashboard → Developers → API keys → Secret key (`sk_live_...`) |
| Stripe Webhook Secret | Generated in step 4 below (`whsec_...`) |

Start with test keys (`sk_test_...`) to verify everything works before going live.

### 3. Create products in Stripe
For each guide + the bundle:
1. Stripe Dashboard → Products → Add product
2. Set a one-time price
3. Copy the **Price ID** (`price_xxxxxxxxxxxxxxxxxxxxxxxx`)

Enter those Price IDs in **WP Admin → Shop Settings** next to each product.

### 4. Register the webhook
1. Stripe Dashboard → Developers → Webhooks → Add endpoint
2. Endpoint URL: `https://yoursite.com/wp-admin/admin-post.php?action=ppl_stripe_webhook`
3. Event to listen for: `checkout.session.completed`
4. Copy the **Signing secret** (`whsec_...`) → paste into WP Admin → Shop Settings

### 5. Set file URLs
In **WP Admin → Shop Settings**, add the download URL for each product PDF. These can be hosted anywhere (WP media library, Google Drive direct link, S3, etc.).

### 6. Go live
Swap test keys for live keys (`sk_live_...`, live price IDs) and you're done.

---

## Settings reference

All settings live in **WP Admin → Shop Settings** (stored in `wp_options` as `ppl_shop_settings`).

| Setting | Description |
|---|---|
| Stripe Secret Key | API key from Stripe |
| Stripe Webhook Secret | Webhook signing secret from Stripe |
| Download Expiry Days | How long the emailed download link is valid (default: 7) |
| Download Limit | Max times the link can be used (default: 10) |
| Product Price IDs | Stripe Price ID per guide (must match grid order) |
| Product File URLs | URL of the PDF for each guide |
| Bundle Price ID | Stripe Price ID for the bundle |
| Bundle File URL | Not used for bundles (individual file URLs are used) |
