<?php
/**
 * Template Name: Shop
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-shop">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<?php
// ── State banners (post-checkout redirect — success is handled inside the modal) ──
$ppl_state = isset( $_GET['ppl'] ) ? sanitize_key( $_GET['ppl'] ) : '';
$ppl_messages = [
    'cancel'       => [ 'cancel',  'bi-x-circle-fill',           'Payment cancelled.',       'No charge was made. You can try again whenever you\'re ready.' ],
    'stripe-error' => [ 'error',   'bi-exclamation-circle-fill', 'Something went wrong.',    'We couldn\'t connect to the payment processor. Please try again or contact us.' ],
    'no-price'     => [ 'error',   'bi-exclamation-circle-fill', 'Product not configured.',  'This product isn\'t ready for purchase yet. Please check back soon or contact us.' ],
    'config-error' => [ 'error',   'bi-exclamation-circle-fill', 'Shop not configured.',     'Payment processing hasn\'t been set up yet. Please contact us directly.' ],
];
if ( $ppl_state && isset( $ppl_messages[ $ppl_state ] ) ) :
    [ $type, $icon, $title, $body ] = $ppl_messages[ $ppl_state ];
?>
<div class="ppl-state-banner ppl-state-banner--<?php echo esc_attr( $type ); ?>">
  <div class="container d-flex align-items-start gap-3">
    <i class="bi <?php echo esc_attr( $icon ); ?> ppl-state-banner__icon flex-shrink-0"></i>
    <div>
      <p class="fw-bold mb-1 ppl-state-banner__title"><?php echo esc_html( $title ); ?></p>
      <p class="mb-0 body-sm text-muted-pp"><?php echo esc_html( $body ); ?></p>
    </div>
  </div>
</div>
<?php endif; ?>



<!-- PRODUCT GRID -->
<section id="ppl-products" class="bg-blush section-pad">
  <div class="container">

    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_grid_eyebrow', 'The Guides' ); ?></p>
      <h2 class="text-plum ls-tight fw-bold display-5 mb-3"><?php ppl_e( 'ppl_shop_grid_heading', 'Welcome to the Pinkprint Shop' ); ?></h2>
      <p class="mx-auto text-muted-pp mw-520 body-md"><?php ppl_e( 'ppl_shop_grid_subtext', 'Whether you are preparing for law school, navigating it, or stepping into the profession — there is a guide built for exactly where you are.' ); ?></p>
    </div>

    <?php
    $prod_raw     = get_post_meta( get_the_ID(), 'ppl_shop_items', true );
    $prod_decoded = $prod_raw ? json_decode( $prod_raw, true ) : null;
    $prod_items   = ( is_array( $prod_decoded ) && $prod_decoded ) ? $prod_decoded : [
      [ 'cover_url' => '', 'badge' => 'Bestseller', 'stage' => 'Stage 01 · Pre-Law',     'title' => 'The Pre-Law Pinkprint',     'subtitle' => 'Your blueprint for getting in — and hitting the ground running.',       'body' => 'Decision-making frameworks, school selection strategy, personal statement guidance, and everything you need to prepare before day one of law school.', 'price' => '$27', 'icon' => 'bi-mortarboard-fill',      'stripe_price_id' => '' ],
      [ 'cover_url' => '', 'badge' => '',            'stage' => 'Stage 02 · Law School',  'title' => 'The Law School Pinkprint',  'subtitle' => 'A proven study system for surviving — and thriving — in 1L and beyond.', 'body' => 'Briefing methods, exam strategy, outlining systems, and time management frameworks designed around the actual demands of legal education.',              'price' => '$27', 'icon' => 'bi-book-fill',             'stripe_price_id' => '' ],
      [ 'cover_url' => '', 'badge' => 'New',         'stage' => 'Stage 03 · Bar & Career', 'title' => 'The Bar & Career Pinkprint','subtitle' => 'Cross the finish line and step into the profession with a plan.',          'body' => 'Bar exam scheduling, MBE and essay strategies, networking frameworks, and career positioning tools for the critical post-graduation period.',            'price' => '$27', 'icon' => 'bi-clipboard2-check-fill', 'stripe_price_id' => '' ],
    ];

    $count     = count( $prod_items );
    $col_class = $count === 1 ? 'col-md-8 col-lg-6' : ( $count === 2 ? 'col-md-6 col-lg-5' : 'col-md-6 col-lg-4' );

    // Resolve Stripe price IDs: meta box value takes priority, falls back to Settings page
    $settings = function_exists( 'ppl_get_shop_settings' ) ? ppl_get_shop_settings() : [];
    ?>

    <div class="row g-4 justify-content-center" id="ppl-shop-grid">
      <?php foreach ( $prod_items as $idx => $item ) :
        $item      = (array) $item;
        $has_cover = ! empty( $item['cover_url'] );
        $badge     = $item['badge'] ?? '';
        $icon      = $item['icon'] ?? 'bi-journal-bookmark';
        $price_id  = ! empty( $item['stripe_price_id'] )
            ? $item['stripe_price_id']
            : ( $settings['products'][ $idx ]['price_id'] ?? '' );
        $offcanvas_id = 'ppl-product-detail-' . $idx;
      ?>
      <div class="<?php echo esc_attr( $col_class ); ?> fade-up ppl-stagger-<?php echo esc_attr( $idx ); ?>">

        <!-- Card (triggers offcanvas) -->
        <div class="bg-white rounded-4 overflow-hidden h-100 d-flex flex-column ppl-product-card position-relative"
             data-bs-toggle="offcanvas"
             data-bs-target="#<?php echo esc_attr( $offcanvas_id ); ?>">

          <!-- Cover -->
          <div class="position-relative">
            <?php if ( $has_cover ) : ?>
              <img src="<?php echo esc_url( $item['cover_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ?? '' ); ?>" class="w-100 ppl-card-cover" />
            <?php else : ?>
              <div class="d-flex align-items-center justify-content-center ppl-card-cover-placeholder">
                <i class="bi <?php echo esc_attr( $icon ); ?> ppl-card-icon"></i>
              </div>
            <?php endif; ?>

            <?php if ( $badge ) : ?>
              <span class="position-absolute top-0 start-0 m-3 ppl-badge"><?php echo esc_html( $badge ); ?></span>
            <?php endif; ?>

            <div class="position-absolute bottom-0 end-0 m-3">
              <span class="fw-bold rounded-3 px-3 py-2 ppl-price-pill"><?php echo esc_html( $item['price'] ?? '' ); ?></span>
            </div>
          </div>

          <!-- Body -->
          <div class="p-4 d-flex flex-column flex-grow-1">
            <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php echo esc_html( $item['stage'] ?? '' ); ?></p>
            <h3 class="text-plum fw-bold mb-2 card-h"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
            <p class="text-muted-pp fw-semibold mb-3 body-sm" style="font-style:italic;"><?php echo esc_html( $item['subtitle'] ?? '' ); ?></p>
            <p class="text-muted-pp mb-4 body-sm flex-grow-1"><?php echo esc_html( $item['body'] ?? '' ); ?></p>

            <div class="ppl-card-open-btn position-absolute bottom-0 end-0 m-3">
              <span class="ppl-card-open-circle" aria-hidden="true">+</span>
            </div>
          </div>

        </div><!-- /.ppl-product-card -->
      </div>

      <!-- Offcanvas: Product Detail -->
      <div class="offcanvas offcanvas-bottom ppl-product-offcanvas h-100" tabindex="-1" id="<?php echo esc_attr( $offcanvas_id ); ?>" aria-labelledby="<?php echo esc_attr( $offcanvas_id ); ?>-label">
        <div class="offcanvas-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
          <h5 class="offcanvas-title text-plum fw-bold mb-0" id="<?php echo esc_attr( $offcanvas_id ); ?>-label"><?php echo esc_html( $item['title'] ?? '' ); ?></h5>
          <div class="d-flex align-items-center gap-3">
            <button type="button"
                    class="btn btn-outline-secondary btn-sm rounded-3 position-relative"
                    data-bs-toggle="offcanvas" data-bs-target="#ppl-cart-offcanvas"
                    aria-label="Cart">
              <i class="bi bi-cart3 fs-5"></i>
              <span class="ppl-cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background:var(--pink-deep);font-size:9px;display:none;">0</span>
            </button>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
        </div>
        <div class="offcanvas-body p-0">
          <div class="row g-0 h-100">

            <!-- Left: Product image -->
            <div class="col-md-6 p-3 ppl-offcanvas-img-col">
              <?php if ( $has_cover ) : ?>
                <img src="<?php echo esc_url( $item['cover_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ?? '' ); ?>" class="w-100 h-100 rounded-3 ppl-offcanvas-cover" />
              <?php else : ?>
                <div class="d-flex align-items-center justify-content-center h-100 rounded-3 ppl-offcanvas-cover-placeholder">
                  <i class="bi <?php echo esc_attr( $icon ); ?> ppl-offcanvas-icon"></i>
                </div>
              <?php endif; ?>
            </div>

            <!-- Right: Product details + checkout flow -->
            <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center overflow-y-auto">
              <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php echo esc_html( $item['stage'] ?? '' ); ?></p>
              <h2 class="text-plum fw-bold mb-2 font-serif"><?php echo esc_html( $item['title'] ?? '' ); ?></h2>
              <p class="text-muted-pp fw-semibold mb-3 body-sm" style="font-style:italic;"><?php echo esc_html( $item['subtitle'] ?? '' ); ?></p>
              <p class="text-muted-pp mb-3 body-sm"><?php echo esc_html( $item['body'] ?? '' ); ?></p>

              <div class="mb-3">
                <span class="fw-bold text-plum d-block mb-2 ppl-price-display" data-unit-price="<?php echo esc_attr( $item['price'] ?? '' ); ?>"><?php echo esc_html( $item['price'] ?? '' ); ?></span>
                <label class="form-label body-xs text-muted-pp fw-semibold mb-1">Quantity</label>
                <select class="form-select form-select-sm rounded-3 ppl-qty-select" style="width:80px;">
                  <?php for ( $q = 1; $q <= 10; $q++ ) : ?>
                  <option value="<?php echo $q; ?>"><?php echo $q; ?></option>
                  <?php endfor; ?>
                </select>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-12 col-sm-6">
                  <button type="button"
                          class="btn btn-secondary rounded-3 px-4 py-3 fw-semibold w-100 ppl-offcanvas-add-to-cart"
                          data-price-id="<?php echo esc_attr( $price_id ); ?>"
                          data-title="<?php echo esc_attr( $item['title'] ?? '' ); ?>"
                          data-price="<?php echo esc_attr( $item['price'] ?? '' ); ?>"
                          data-idx="<?php echo esc_attr( $idx ); ?>">
                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                  </button>
                </div>
                <div class="col-12 col-sm-6">
                  <button type="button"
                          class="btn btn-rose rounded-3 px-4 py-3 fw-semibold w-100 ppl-offcanvas-checkout"
                          data-price-id="<?php echo esc_attr( $price_id ); ?>"
                          data-title="<?php echo esc_attr( $item['title'] ?? '' ); ?>"
                          data-price="<?php echo esc_attr( $item['price'] ?? '' ); ?>"
                          data-idx="<?php echo esc_attr( $idx ); ?>">
                    Checkout <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg>
                  </button>
                </div>
              </div>

              <!-- Share -->
              <?php
              $share_url   = esc_url( get_permalink() . '?product=' . $idx );
              $share_title = esc_attr( $item['title'] ?? '' );
              ?>
              <hr />
              <div class="d-flex align-items-center gap-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener" class="ppl-share-btn" aria-label="Share on Facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/" target="_blank" rel="noopener" class="ppl-share-btn" aria-label="Share on Instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" rel="noopener" class="ppl-share-btn" aria-label="Share on X"><i class="bi bi-twitter-x"></i></a>
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&description=<?php echo $share_title; ?>" target="_blank" rel="noopener" class="ppl-share-btn" aria-label="Share on Pinterest"><i class="bi bi-pinterest"></i></a>
              </div>
            </div>

          </div>
        </div>
      </div><!-- /.offcanvas -->

      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- BUNDLE BANNER -->
<?php
$bundle_price_id = ppl_get( 'ppl_shop_bundle_stripe_price_id' )
    ?: ( $settings['bundle']['price_id'] ?? '' );
?>
<section class="bg-plum section-pad">
  <div class="container">
    <div class="bg-plum-mid rounded-4 p-4 p-md-5">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <p class="text-pink fw-semibold text-uppercase ls-wide mb-1 eyebrow"><i class="bi bi-collection-fill me-2"></i><?php ppl_e( 'ppl_shop_bundle_eyebrow', 'Complete Collection' ); ?></p>
          <h2 class="text-white ls-tight fw-bold display-6 mb-3"><?php ppl_e( 'ppl_shop_bundle_heading', 'Get all three guides and save.' ); ?></h2>
          <p class="text-light-75 body-lead mb-0"><?php ppl_e( 'ppl_shop_bundle_body', 'The full Pinkprint collection gives you a complete roadmap from your first steps into pre-law all the way through bar prep and career launch. One investment, every stage covered.' ); ?></p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <div class="mb-3">
            <p class="text-light-50 mb-1 body-xs text-uppercase ls-wide">Bundle Price</p>
            <p class="text-white fw-bold mb-0 ppl-bundle-price pb-4"><?php ppl_e( 'ppl_shop_bundle_price', '$67' ); ?></p>
            <p class="text-light-50 body-xs"><?php ppl_e( 'ppl_shop_bundle_savings', 'Save $14 vs. buying individually' ); ?></p>
          </div>
          <button type="button" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold"
                  data-bs-toggle="offcanvas" data-bs-target="#ppl-bundle-offcanvas">
            <i class="bi bi-cart3 me-2"></i><?php ppl_e( 'ppl_shop_bundle_cta', 'Add to Cart' ); ?>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Offcanvas: Bundle Detail -->
<div class="offcanvas offcanvas-bottom ppl-product-offcanvas h-100" tabindex="-1" id="ppl-bundle-offcanvas" aria-labelledby="ppl-bundle-offcanvas-label">
  <div class="offcanvas-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
    <h5 class="offcanvas-title text-plum fw-bold mb-0" id="ppl-bundle-offcanvas-label"><?php ppl_e( 'ppl_shop_bundle_heading', 'The Complete Collection' ); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="row g-0 h-100">

      <!-- Left: placeholder visual -->
      <div class="col-md-6 p-3 ppl-offcanvas-img-col">
        <div class="d-flex align-items-center justify-content-center h-100 rounded-3 ppl-offcanvas-cover-placeholder">
          <i class="bi bi-collection-fill ppl-offcanvas-icon"></i>
        </div>
      </div>

      <!-- Right: bundle details + checkout -->
      <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center overflow-y-auto">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_bundle_eyebrow', 'Complete Collection' ); ?></p>
        <h2 class="text-plum fw-bold mb-2 font-serif"><?php ppl_e( 'ppl_shop_bundle_heading', 'The Complete Collection' ); ?></h2>
        <p class="text-muted-pp fw-semibold mb-3 body-sm fst-italic"><?php ppl_e( 'ppl_shop_bundle_subtitle', 'Every stage. One investment.' ); ?></p>
        <p class="text-muted-pp mb-3 body-sm"><?php ppl_e( 'ppl_shop_bundle_body', 'The full Pinkprint collection gives you a complete roadmap from your first steps into pre-law all the way through bar prep and career launch. One investment, every stage covered.' ); ?></p>

        <div class="mb-3">
          <span class="fw-bold text-plum d-block mb-2 ppl-price-display"><?php ppl_e( 'ppl_shop_bundle_price', '$67' ); ?></span>
          <p class="text-muted-pp body-xs mb-0"><?php ppl_e( 'ppl_shop_bundle_savings', 'Save $14 vs. buying individually' ); ?></p>
        </div>

        <?php if ( $bundle_price_id ) : ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
          <?php wp_nonce_field( 'ppl_checkout', 'ppl_checkout_nonce' ); ?>
          <input type="hidden" name="action" value="ppl_stripe_checkout" />
          <input type="hidden" name="ppl_product_type" value="bundle" />
          <input type="hidden" name="ppl_return_url" value="<?php echo esc_url( get_permalink() ); ?>" />
          <button type="submit" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold w-100">
            Checkout <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg>
          </button>
        </form>
        <?php else : ?>
        <button type="button" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold w-100">
          Checkout <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg>
        </button>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div><!-- /.offcanvas bundle -->


<!-- Cart Offcanvas (right) -->
<div class="offcanvas offcanvas-end ppl-cart-offcanvas" tabindex="-1" id="ppl-cart-offcanvas" aria-labelledby="ppl-cart-offcanvas-label" style="width:min(400px,100vw);">
  <div class="offcanvas-header border-bottom px-4 py-3">
    <h5 class="offcanvas-title text-plum fw-bold mb-0" id="ppl-cart-offcanvas-label">
      <i class="bi bi-cart3 me-2"></i>Your Cart
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column p-0" style="overflow:hidden;">

    <!-- Empty state -->
    <div class="ppl-cart-empty text-center py-5 px-4 flex-grow-1 flex-column align-items-center justify-content-center" style="display:flex;">
      <i class="bi bi-cart3 text-muted-pp mb-3" style="font-size:48px;opacity:0.25;"></i>
      <p class="text-muted-pp body-sm mb-0">Your cart is empty.</p>
    </div>

    <!-- Items list -->
    <div class="ppl-cart-items px-4 py-3" style="display:none; flex:1 1 0; min-height:0; overflow-y:auto;"></div>

    <!-- Footer: total + checkout -->
    <div class="ppl-cart-footer border-top px-4 py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-semibold text-plum body-sm">Subtotal</span>
        <span class="fw-bold text-plum ppl-cart-total" style="font-size:1.3rem;font-family:'Playfair Display',serif;">$0</span>
      </div>
      <button type="button" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold w-100"
              data-bs-toggle="modal" data-bs-target="#ppl-checkout-modal">
        Checkout <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg>
      </button>
      <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-3 fw-semibold w-100 mt-2"
              data-bs-dismiss="offcanvas">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short me-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.146 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"/></svg>Keep shopping
      </button>
    </div>

  </div>
</div><!-- /.offcanvas cart -->

<!-- Checkout Modal -->
<div class="modal fade" id="ppl-checkout-modal" tabindex="-1" aria-labelledby="ppl-checkout-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 overflow-hidden border-0">

      <!-- ── Payment view ── -->
      <div id="ppl-checkout-payment-view">
        <div class="modal-header border-0 px-4 pt-4 pb-0">
          <h5 class="modal-title text-plum fw-bold font-serif" id="ppl-checkout-modal-label">Complete your order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4">
          <div class="row g-4">

            <!-- Left: payment form -->
            <div class="col-md-7" id="ppl-stripe-mount">
              <p class="text-rose fw-semibold text-uppercase ls-wide mb-3 eyebrow" style="font-size:11px;">Payment details</p>

              <div class="ppl-mock-field mb-3">
                <label class="form-label body-xs fw-semibold text-plum mb-1">Name on card</label>
                <input type="text" class="form-control rounded-3" placeholder="Jane Smith" />
              </div>
              <div class="ppl-mock-field mb-3">
                <label class="form-label body-xs fw-semibold text-plum mb-1">Card number</label>
                <div class="input-group">
                  <input type="text" class="form-control rounded-3" placeholder="1234 5678 9012 3456" maxlength="19" />
                  <span class="input-group-text bg-white border-start-0 rounded-end-3"><i class="bi bi-credit-card text-muted-pp"></i></span>
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-6 ppl-mock-field">
                  <label class="form-label body-xs fw-semibold text-plum mb-1">Expiry</label>
                  <input type="text" class="form-control rounded-3" placeholder="MM / YY" maxlength="7" />
                </div>
                <div class="col-6 ppl-mock-field">
                  <label class="form-label body-xs fw-semibold text-plum mb-1">CVC</label>
                  <input type="text" class="form-control rounded-3" placeholder="123" maxlength="4" />
                </div>
              </div>

              <p class="body-xs text-muted-pp mt-2 mb-0"><i class="bi bi-lock-fill me-1"></i>Secured by Stripe. Your card details are never stored.</p>
            </div>

            <!-- Right: order summary -->
            <div class="col-md-5">
              <p class="text-rose fw-semibold text-uppercase ls-wide mb-3 eyebrow" style="font-size:11px;">Order summary</p>
              <div id="ppl-checkout-summary" class="mb-3"></div>
              <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                <span class="fw-semibold text-plum body-sm">Total</span>
                <span class="fw-bold text-plum ppl-checkout-total" style="font-size:1.3rem;font-family:'Playfair Display',serif;">$0</span>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer border-0 px-4 pb-4 pt-0">
          <button type="button" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold w-100" id="ppl-mock-pay-btn">
            <span class="ppl-pay-label">Pay now <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg></span>
            <span class="ppl-pay-loading d-none"><span class="spinner-border spinner-border-sm me-2"></span>Processing...</span>
          </button>
        </div>
      </div><!-- /#ppl-checkout-payment-view -->

      <!-- ── Success view ── -->
      <div id="ppl-checkout-success-view" style="display:none;">
        <div class="modal-header border-0 px-4 pt-4 pb-0 justify-content-end">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 pb-5 pt-2 text-center">
          <div class="ppl-success-icon-wrap mx-auto mb-4">
            <i class="bi bi-check-lg ppl-success-check"></i>
          </div>
          <h4 class="text-plum fw-bold font-serif mb-2" style="font-size:1.6rem;">Purchase complete.</h4>
          <p class="text-muted-pp body-md mb-1">Your download link is on its way.</p>
          <p class="text-muted-pp body-sm mb-4">Check your email — if it doesn't arrive within a few minutes, check your spam folder.</p>
          <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold body-sm" data-bs-dismiss="modal">
            Back to shop
          </button>
        </div>
      </div><!-- /#ppl-checkout-success-view -->

    </div>
  </div>
</div>


<!-- FEATURES — WHAT YOU GET -->
<section class="bg-white section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_inside_eyebrow', 'What You Get' ); ?></p>
      <h2 class="text-plum ls-tight fw-bold display-5 mb-3"><?php ppl_e( 'ppl_shop_inside_heading', 'Every guide is built the same way — intentionally.' ); ?></h2>
    </div>
    <?php
    $feat_raw     = get_post_meta( get_the_ID(), 'ppl_shop_feature_items', true );
    $feat_decoded = $feat_raw ? json_decode( $feat_raw, true ) : null;
    $feat_items   = ( is_array( $feat_decoded ) && $feat_decoded ) ? $feat_decoded : [
      [ 'icon' => 'bi-map-fill',        'title' => 'Clear Stage Frameworks',    'body' => 'Each guide maps out the exact actions, decisions, and mindset shifts needed at your specific stage.' ],
      [ 'icon' => 'bi-journal-check',   'title' => 'Actionable Checklists',     'body' => 'No theory without practice. Every section ends with steps you can take immediately.' ],
      [ 'icon' => 'bi-chat-quote-fill', 'title' => 'Real-World Language',       'body' => "Written the way a mentor explains it — not the way a textbook presents it." ],
      [ 'icon' => 'bi-arrow-repeat',    'title' => 'Lifetime Access & Updates', 'body' => 'Buy once. Every future edition is yours at no additional cost.' ],
    ];
    $feat_col = count( $feat_items ) <= 3 ? 'col-sm-6 col-lg-4' : 'col-sm-6 col-lg-3';
    ?>
    <div class="row g-4 justify-content-center">
      <?php foreach ( $feat_items as $i => $feat ) :
        $feat = (array) $feat;
      ?>
      <div class="<?php echo esc_attr( $feat_col ); ?> fade-up ppl-stagger-<?php echo esc_attr( $i ); ?>">
        <div class="bg-blush rounded-4 p-4 h-100 d-flex flex-column">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $feat['icon'] ?? 'bi-star-fill' ); ?> fs-icon-lg"></i>
          </div>
          <h4 class="text-plum fw-bold mb-2 card-h-sm"><?php echo esc_html( $feat['title'] ?? '' ); ?></h4>
          <p class="text-muted-pp mb-0 body-sm"><?php echo esc_html( $feat['body'] ?? '' ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- SESSION CTA -->
<section class="bg-grape section-pad">
  <div class="container">
    <div class="bg-plum rounded-4 overflow-hidden">
      <div class="row g-0">

        <!-- Left: text + CTA -->
        <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center">
          <p class="text-pink fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_session_eyebrow', 'Not sure where to start?' ); ?></p>
          <h3 class="text-white fw-bold mb-3 font-serif"><?php ppl_e( 'ppl_shop_session_heading', 'Book a 1-on-1 Strategy Session' ); ?></h3>
          <p class="text-light-75 mb-4 body-sm"><?php ppl_e( 'ppl_shop_session_body', 'A focused, one-hour session tailored to exactly where you are in your legal journey. Walk away with a clear plan.' ); ?></p>
          <div>
            <button type="button" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold"
                    data-bs-toggle="offcanvas" data-bs-target="#ppl-session-offcanvas">
              <?php ppl_e( 'ppl_shop_session_cta', 'Book a Session' ); ?> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg>
            </button>
          </div>
        </div>

        <!-- Right: image -->
        <div class="col-md-6 ppl-session-img-col">
          <?php
          $session_img = ppl_get( 'ppl_shop_session_image_url' );
          if ( $session_img ) : ?>
            <img src="<?php echo esc_url( $session_img ); ?>" alt="Book a strategy session" class="w-100 h-100 ppl-session-img" />
          <?php else : ?>
            <div class="d-flex align-items-center justify-content-center h-100 ppl-session-img-placeholder">
              <i class="bi bi-calendar2-check-fill ppl-session-icon"></i>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</section>


<!-- Offcanvas: Strategy Session -->
<div class="offcanvas offcanvas-bottom ppl-product-offcanvas h-100" tabindex="-1" id="ppl-session-offcanvas" aria-labelledby="ppl-session-offcanvas-label">
  <div class="offcanvas-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
    <h5 class="offcanvas-title text-plum fw-bold mb-0" id="ppl-session-offcanvas-label"><?php ppl_e( 'ppl_shop_session_heading', 'Book a 1-on-1 Strategy Session' ); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="row g-0 h-100">

      <!-- Left: image -->
      <div class="col-md-6 p-3 ppl-offcanvas-img-col">
        <?php
        $session_offcanvas_img = ppl_get( 'ppl_shop_session_image_url' );
        if ( $session_offcanvas_img ) : ?>
          <img src="<?php echo esc_url( $session_offcanvas_img ); ?>" alt="Book a strategy session" class="w-100 h-100 rounded-3 ppl-offcanvas-cover" />
        <?php else : ?>
          <div class="d-flex align-items-center justify-content-center h-100 rounded-3 ppl-offcanvas-cover-placeholder">
            <i class="bi bi-calendar2-check-fill ppl-offcanvas-icon"></i>
          </div>
        <?php endif; ?>
      </div>

      <!-- Right: session details + CTA -->
      <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center overflow-y-auto">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_session_eyebrow', 'Not sure where to start?' ); ?></p>
        <h2 class="text-plum fw-bold mb-2 font-serif"><?php ppl_e( 'ppl_shop_session_heading', 'Book a 1-on-1 Strategy Session' ); ?></h2>
        <p class="text-muted-pp fw-semibold mb-3 body-sm fst-italic"><?php ppl_e( 'ppl_shop_session_subtitle', 'One hour. Clear direction.' ); ?></p>
        <p class="text-muted-pp mb-3 body-sm"><?php ppl_e( 'ppl_shop_session_body', 'A focused, one-hour session tailored to exactly where you are in your legal journey. Walk away with a clear plan.' ); ?></p>
        <div class="mb-4">
          <span class="fw-bold text-plum d-block mb-1 ppl-price-display"><?php ppl_e( 'ppl_shop_session_price', '$150' ); ?></span>
          <p class="text-muted-pp body-xs mb-0"><?php ppl_e( 'ppl_shop_session_price_note', 'One-time session fee' ); ?></p>
        </div>

        <a href="<?php echo esc_url( ppl_get( 'ppl_shop_session_url', '#' ) ); ?>" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold">
          <?php ppl_e( 'ppl_shop_session_cta', 'Book a Session' ); ?> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg>
        </a>
      </div>

    </div>
  </div>
</div><!-- /.offcanvas session -->


<!-- CONTACT -->
<section class="bg-blush section-pad">
  <div class="container">
    <div class="text-center mw-560 mx-auto">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_contact_eyebrow', 'Get in Touch' ); ?></p>
      <h2 class="text-plum ls-tight fw-bold display-6 mb-3 font-serif"><?php ppl_e( 'ppl_shop_contact_heading', "Questions? We're here." ); ?></h2>
      <p class="text-muted-pp body-md mb-4"><?php ppl_e( 'ppl_shop_contact_body', 'Whether you have a question about a guide, need help with your order, or just want to talk through your next step — reach out.' ); ?></p>
      <a href="<?php echo esc_url( ppl_get( 'ppl_shop_contact_url', get_page_link( get_page_by_path( 'contact' ) ) ) ); ?>" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold">
        <?php ppl_e( 'ppl_shop_contact_cta', 'Contact Us' ); ?> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/></svg>
      </a>
    </div>
  </div>
</section>


<style>
/* Product card */
.ppl-product-card { box-shadow: 0 4px 24px rgba(35,13,24,0.07); transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer; }
.ppl-product-card:hover { transform: translateY(-6px); box-shadow: 0 20px 56px rgba(35,13,24,0.13); }

/* Card cover image / placeholder */
.ppl-card-cover { height: 280px; object-fit: cover; display: block; }
.ppl-card-cover-placeholder { height: 280px; background: linear-gradient(135deg, var(--plum) 0%, var(--plum-soft) 100%); }
.ppl-card-icon { font-size: 64px; color: rgba(255,137,197,0.35); }

/* Badge */
.ppl-badge { background: var(--pink-deep); color: #fff; padding: 5px 14px; font-size: 11px; font-family: 'DM Sans', sans-serif; letter-spacing: 1px; font-weight: 600; border-radius: 50rem; }

/* Price pill on card cover */
.ppl-price-pill { background: rgba(35,13,24,0.88); color: #fff; font-size: 1.1rem; font-family: 'DM Sans', sans-serif; backdrop-filter: blur(6px); }

/* Stagger animation helpers */
<?php for ( $s = 0; $s < 6; $s++ ) : ?>
.ppl-stagger-<?php echo $s; ?> { --stagger: <?php echo $s; ?>; }
<?php endfor; ?>

/* Offcanvas */
.ppl-product-offcanvas { border-radius: 20px 20px 0 0; }
.ppl-product-offcanvas .offcanvas-body { overflow-y: auto; }
.ppl-product-offcanvas .ppl-offcanvas-img-col { min-height: 280px; }

/* Offcanvas cover */
.ppl-offcanvas-cover { object-fit: cover; display: block; }
.ppl-offcanvas-cover-placeholder { background: linear-gradient(135deg, var(--plum) 0%, var(--plum-soft) 100%); min-height: 320px; }
.ppl-offcanvas-icon { font-size: 96px; color: rgba(255,137,197,0.3); }

/* Price display */
.ppl-price-display { font-size: 2rem; font-family: 'Playfair Display', serif; }

/* Share */
.ppl-share-btn { color: #aaa; font-size: 22px; transition: color 0.15s; text-decoration: none; }
.ppl-share-btn:hover { color: var(--pink-deep); }

/* Session CTA image column */
.ppl-session-img-col { min-height: 400px; }
.ppl-session-img { object-fit: cover; display: block; }
.ppl-session-img-placeholder { background: var(--plum-mid); }
.ppl-session-icon { font-size: 80px; color: rgba(255,137,197,0.25); }
@media (min-width: 768px) { .ppl-session-img-col { min-height: 500px; } }

/* Bundle price */
.ppl-bundle-price { font-size: 2.5rem; font-family: 'Playfair Display', serif; }

/* Grape background */
.bg-grape { background: var(--plum-mid); }

/* Card open (+) button */
.ppl-card-open-circle { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: var(--plum); color: #fff; font-size: 24px; line-height: 1; font-weight: 300; pointer-events: none; transition: transform 0.2s ease, background 0.2s ease; }
.ppl-product-card:hover .ppl-card-open-circle { transform: scale(1.1); background: var(--pink-deep); }

/* Cart offcanvas */
.ppl-cart-item { border-bottom: 1px solid rgba(0,0,0,0.07); }
.ppl-cart-item:last-child { border-bottom: none; }
.ppl-qty-btn { background: var(--blush, #fdf0f5); border: 1px solid rgba(0,0,0,0.1); border-radius: 50%; width: 24px; height: 24px; font-size: 15px; line-height: 1; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; padding: 0; flex-shrink: 0; }
.ppl-qty-btn:hover { background: #f5d0e8; }
.ppl-cart-remove { background: none; border: none; padding: 0; cursor: pointer; text-decoration: underline; font-size: 11px; color: #999; }
.ppl-cart-remove:hover { color: var(--pink-deep); }
@keyframes ppl-badge-pop { 0%,100% { transform: translate(-50%,-50%) scale(1); } 50% { transform: translate(-50%,-50%) scale(1.5); } }
.ppl-badge-pop { animation: ppl-badge-pop 0.25s ease; }

/* Checkout modal */
.ppl-mock-field .form-control { border-color: #e8d0de; color: var(--plum); background: #fdf5f9; padding: 0.6rem 0.85rem; font-size: 0.95rem; }
.ppl-mock-field .form-control:focus { border-color: var(--pink-deep); background: #fff; box-shadow: 0 0 0 3px rgba(196,54,112,0.12); }
.ppl-mock-field .input-group-text { border-color: #e8d0de; background: #fdf5f9; }

/* Checkout success view */
.ppl-success-icon-wrap { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--pink-deep) 0%, var(--plum) 100%); display: flex; align-items: center; justify-content: center; }
.ppl-success-check { font-size: 38px; color: #fff; line-height: 1; }

/* State banners */
.ppl-state-banner { border-bottom-width: 1px; border-bottom-style: solid; padding: 18px 0; }
.ppl-state-banner--success { background: rgba(196,54,112,0.1); border-color: rgba(196,54,112,0.3); }
.ppl-state-banner--success .ppl-state-banner__icon,
.ppl-state-banner--success .ppl-state-banner__title { color: var(--pink-deep); }
.ppl-state-banner--cancel { background: rgba(100,100,100,0.1); border-color: rgba(100,100,100,0.3); }
.ppl-state-banner--cancel .ppl-state-banner__icon,
.ppl-state-banner--cancel .ppl-state-banner__title { color: #555; }
.ppl-state-banner--error { background: rgba(220,50,50,0.1); border-color: rgba(220,50,50,0.3); }
.ppl-state-banner--error .ppl-state-banner__icon,
.ppl-state-banner--error .ppl-state-banner__title { color: #c0392b; }
.ppl-state-banner__icon { font-size: 22px; margin-top: 2px; }
</style>

<script>
(function () {
  // ── Offcanvas quantity → price display ─────────────────────────────────────
  document.querySelectorAll('.ppl-product-offcanvas').forEach(function (oc) {
    var qty     = oc.querySelector('.ppl-qty-select');
    var display = oc.querySelector('.ppl-price-display');
    if (!qty || !display) return;
    var raw = display.dataset.unitPrice || '';
    var num = parseFloat(raw.replace(/[^0-9.]/g, ''));
    var sym = raw.replace(/[0-9.,]/g, '').trim() || '$';
    if (isNaN(num)) return;
    qty.addEventListener('change', function () {
      display.textContent = sym + (num * parseInt(this.value, 10)).toFixed(2);
    });
  });

  // ── Cart module ─────────────────────────────────────────────────────────────
  var CART_KEY = 'ppl_cart';

  function load() {
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch(e) { return []; }
  }
  function save(items) { localStorage.setItem(CART_KEY, JSON.stringify(items)); }

  function addItem(priceId, title, price, idx) {
    var items    = load();
    var num      = parseFloat(price.replace(/[^0-9.]/g, '')) || 0;
    var existing = items.find(function(i) { return i.price_id === priceId; });
    if (existing) { existing.qty += 1; } else {
      items.push({ price_id: priceId, title: title, price: price, price_num: num, qty: 1, idx: parseInt(idx, 10) });
    }
    save(items);
    refresh();
    popBadges();
  }

  function removeItem(priceId) { save(load().filter(function(i) { return i.price_id !== priceId; })); refresh(); }

  function updateQty(priceId, qty) {
    qty = parseInt(qty, 10);
    if (qty < 1) { removeItem(priceId); return; }
    var items = load();
    var item  = items.find(function(i) { return i.price_id === priceId; });
    if (item) { item.qty = qty; save(items); refresh(); }
  }

  function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function refresh() {
    var items = load();
    var count = items.reduce(function(s, i) { return s + i.qty; }, 0);
    var total = items.reduce(function(s, i) { return s + (i.price_num * i.qty); }, 0);
    var sym   = (items.length && items[0].price.replace(/[0-9.,]/g,'').trim()) || '$';

    document.querySelectorAll('.ppl-cart-badge').forEach(function(b) {
      b.textContent  = count;
      b.style.display = count ? '' : 'none';
    });

    var empty  = document.querySelector('.ppl-cart-empty');
    var list   = document.querySelector('.ppl-cart-items');
    var footer = document.querySelector('.ppl-cart-footer');
    var totalEl = document.querySelector('.ppl-cart-total');
    if (!list) return;

    if (!items.length) {
      if (empty)  empty.style.display  = '';
      list.style.display   = 'none';
      return;
    }

    if (empty)  empty.style.display  = 'none';
    list.style.display   = '';
    if (totalEl) totalEl.textContent = sym + total.toFixed(2);

    list.innerHTML = items.map(function(item) {
      return '<div class="ppl-cart-item d-flex align-items-start gap-3 py-3">'
        + '<div class="flex-grow-1">'
        +   '<p class="fw-semibold text-plum mb-2 body-sm">' + esc(item.title) + '</p>'
        +   '<div class="d-flex align-items-center gap-2">'
        +     '<button class="ppl-qty-btn" data-pid="' + esc(item.price_id) + '" data-delta="-1" aria-label="Decrease">−</button>'
        +     '<span class="body-xs fw-semibold px-1">' + item.qty + '</span>'
        +     '<button class="ppl-qty-btn" data-pid="' + esc(item.price_id) + '" data-delta="1" aria-label="Increase">+</button>'
        +   '</div>'
        + '</div>'
        + '<div class="text-end flex-shrink-0">'
        +   '<p class="fw-bold text-plum mb-1 body-sm">' + sym + (item.price_num * item.qty).toFixed(2) + '</p>'
        +   '<button class="ppl-cart-remove" data-pid="' + esc(item.price_id) + '">Remove</button>'
        + '</div>'
        + '</div>';
    }).join('');

    list.querySelectorAll('.ppl-qty-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var pid  = this.dataset.pid;
        var it   = load().find(function(i) { return i.price_id === pid; });
        if (it) updateQty(pid, it.qty + parseInt(this.dataset.delta, 10));
      });
    });
    list.querySelectorAll('.ppl-cart-remove').forEach(function(btn) {
      btn.addEventListener('click', function() { removeItem(this.dataset.pid); });
    });
  }

  function popBadges() {
    document.querySelectorAll('.ppl-cart-badge').forEach(function(b) {
      b.classList.remove('ppl-badge-pop');
      void b.offsetWidth;
      b.classList.add('ppl-badge-pop');
    });
  }

  // ── Delegated listeners (offcanvas add-to-cart / checkout) ──────────────────
  document.addEventListener('click', function(e) {
    var addBtn = e.target.closest('.ppl-offcanvas-add-to-cart');
    if (addBtn) {
      addItem(addBtn.dataset.priceId, addBtn.dataset.title, addBtn.dataset.price, addBtn.dataset.idx);
      var cartEl = document.getElementById('ppl-cart-offcanvas');
      if (cartEl && window.bootstrap) {
        bootstrap.Offcanvas.getOrCreateInstance(cartEl).show();
      }
    }

    var checkoutBtn = e.target.closest('.ppl-offcanvas-checkout');
    if (checkoutBtn) {
      addItem(checkoutBtn.dataset.priceId, checkoutBtn.dataset.title, checkoutBtn.dataset.price, checkoutBtn.dataset.idx);
      var modalEl = document.getElementById('ppl-checkout-modal');
      if (modalEl && window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
      }
    }
  });

  // Populate cart JSON before checkout form submit
  var cartForm = document.getElementById('ppl-cart-form');
  if (cartForm) {
    cartForm.addEventListener('submit', function() {
      var payload = load().map(function(i) {
        return { price_id: i.price_id, title: i.title, qty: i.qty, idx: i.idx };
      });
      document.getElementById('ppl-cart-payload').value = JSON.stringify(payload);
    });
  }

  // Re-run refresh whenever the cart offcanvas is shown
  var cartOC = document.getElementById('ppl-cart-offcanvas');
  if (cartOC) {
    cartOC.addEventListener('show.bs.offcanvas', function() { refresh(); });
  }

  refresh();

  // ── Checkout modal: populate summary + mock pay ──────────────────────────────
  var checkoutModal = document.getElementById('ppl-checkout-modal');
  if (checkoutModal) {
    checkoutModal.addEventListener('show.bs.modal', function () {
      var items    = load();
      var total    = items.reduce(function(s,i){ return s+(i.price_num*i.qty);}, 0);
      var sym      = (items.length && items[0].price.replace(/[0-9.,]/g,'').trim()) || '$';
      var summary  = document.getElementById('ppl-checkout-summary');
      var totalEl  = checkoutModal.querySelector('.ppl-checkout-total');
      if (summary) {
        summary.innerHTML = items.map(function(item, i) {
          return '<div class="d-flex justify-content-between align-items-center py-2' + (i < items.length - 1 ? ' border-bottom' : '') + '">'
            + '<span class="body-sm text-plum fw-semibold">' + esc(item.title) + (item.qty > 1 ? ' <span class="text-muted-pp fw-normal">×'+item.qty+'</span>' : '') + '</span>'
            + '<span class="body-sm fw-bold text-plum">' + sym + (item.price_num * item.qty).toFixed(2) + '</span>'
            + '</div>';
        }).join('');
      }
      if (totalEl) totalEl.textContent = sym + total.toFixed(2);
    });

    document.getElementById('ppl-mock-pay-btn').addEventListener('click', function() {
      var label   = this.querySelector('.ppl-pay-label');
      var loading = this.querySelector('.ppl-pay-loading');
      label.classList.add('d-none');
      loading.classList.remove('d-none');
      setTimeout(function() {
        save([]);
        refresh();
        showCheckoutSuccess();
      }, 1200);
    });
  }

  function showCheckoutSuccess() {
    var modalEl = document.getElementById('ppl-checkout-modal');
    if (!modalEl) return;
    var payView     = document.getElementById('ppl-checkout-payment-view');
    var successView = document.getElementById('ppl-checkout-success-view');
    if (payView)     payView.style.display     = 'none';
    if (successView) successView.style.display = '';

    if (window.bootstrap) {
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    }

    // Clean the URL without reloading
    var url = window.location.pathname + window.location.hash;
    history.replaceState(null, '', url);
  }

  // Auto-open success modal on ?ppl=success redirect
  if (new URLSearchParams(window.location.search).get('ppl') === 'success') {
    document.addEventListener('DOMContentLoaded', function() { showCheckoutSuccess(); }, { once: true });
    // Fallback if DOMContentLoaded already fired
    if (document.readyState !== 'loading') showCheckoutSuccess();
  }

  // Reset modal to payment view when it's closed after success
  var checkoutModalEl = document.getElementById('ppl-checkout-modal');
  if (checkoutModalEl) {
    checkoutModalEl.addEventListener('hidden.bs.modal', function() {
      var payView     = document.getElementById('ppl-checkout-payment-view');
      var successView = document.getElementById('ppl-checkout-success-view');
      if (payView)     payView.style.display     = '';
      if (successView) successView.style.display = 'none';
      var payBtn = document.getElementById('ppl-mock-pay-btn');
      if (payBtn) {
        payBtn.querySelector('.ppl-pay-label').classList.remove('d-none');
        payBtn.querySelector('.ppl-pay-loading').classList.add('d-none');
      }
    });
  }
}());
</script>

<?php get_template_part( 'partials/ppl-footer' ); ?>
