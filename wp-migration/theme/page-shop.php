<?php
/**
 * Template Name: Shop
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-shop">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<?php
// ── State banners (post-checkout redirect) ────────────────────────────────────
$ppl_state = isset( $_GET['ppl'] ) ? sanitize_key( $_GET['ppl'] ) : '';
$ppl_messages = [
    'success'      => [ 'success', 'bi-check-circle-fill',       'Purchase complete!',       "Check your email — your download link is on its way. If it doesn't arrive within a few minutes, check your spam folder." ],
    'cancel'       => [ 'cancel',  'bi-x-circle-fill',           'Payment cancelled.',        'No charge was made. You can try again whenever you\'re ready.' ],
    'stripe-error' => [ 'error',   'bi-exclamation-circle-fill', 'Something went wrong.',     'We couldn\'t connect to the payment processor. Please try again or contact us.' ],
    'no-price'     => [ 'error',   'bi-exclamation-circle-fill', 'Product not configured.',   'This product isn\'t ready for purchase yet. Please check back soon or contact us.' ],
    'config-error' => [ 'error',   'bi-exclamation-circle-fill', 'Shop not configured.',      'Payment processing hasn\'t been set up yet. Please contact us directly.' ],
];
if ( $ppl_state && isset( $ppl_messages[ $ppl_state ] ) ) :
    [ $type, $icon, $title, $body ] = $ppl_messages[ $ppl_state ];
    $bg    = $type === 'success' ? 'rgba(196,54,112,0.1)' : ( $type === 'cancel' ? 'rgba(100,100,100,0.1)' : 'rgba(220,50,50,0.1)' );
    $color = $type === 'success' ? 'var(--pink-deep)' : ( $type === 'cancel' ? '#555' : '#c0392b' );
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


<!-- HERO (commented out)
<?php
$hero_img = ppl_get( 'ppl_shop_hero_image_url' );
$hero_style = $hero_img
    ? 'background-image:url(' . esc_url( $hero_img ) . ');background-size:cover;background-position:center;position:relative;'
    : 'position:relative;';
?>
<section class="hero-pad" style="<?php echo esc_attr( $hero_style ); ?>">
  <?php if ( $hero_img ) : ?>
  <div style="position:absolute;inset:0;background:rgba(35,13,24,0.82);z-index:0;"></div>
  <?php else : ?>
  <div style="position:absolute;inset:0;background:var(--plum);z-index:0;"></div>
  <?php endif; ?>
  <div class="container" style="position:relative;z-index:1;">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="d-inline-flex align-items-center gap-2 bg-pink-tint text-rose rounded-pill px-3 py-2 fw-semibold mb-4 eyebrow">
          <i class="bi bi-bag-heart-fill"></i> [ppl_shop_eyebrow] The Pinkprint Collection
        </span>
        <h1 class="display-4 fw-bold text-white ls-tight mb-4">[ppl_shop_heading] Every guide, every stage of your journey.</h1>
        <p class="body-lead text-light-75 mb-5">[ppl_shop_lead] Each Pinkprint is a purpose-built roadmap for where you are right now — practical, direct, and designed by someone who has been through it.</p>
        <div class="d-flex flex-wrap gap-3">
          <a href="#ppl-products" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold">Browse Guides <i class="bi bi-arrow-down ms-1"></i></a>
          <a href="#" class="btn btn-outline-light rounded-3 px-4 py-3 fw-semibold">Book a Session</a>
        </div>
      </div>
    </div>
  </div>
</section>
-->


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
        <div class="bg-white rounded-4 overflow-hidden h-100 d-flex flex-column ppl-product-card"
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

            <div class="mt-auto">
              <button type="button" class="btn btn-sm btn-secondary rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-download me-2"></i>Add to Cart
              </button>
            </div>
          </div>

        </div><!-- /.ppl-product-card -->
      </div>

      <!-- Offcanvas: Product Detail -->
      <div class="offcanvas offcanvas-bottom ppl-product-offcanvas h-100" tabindex="-1" id="<?php echo esc_attr( $offcanvas_id ); ?>" aria-labelledby="<?php echo esc_attr( $offcanvas_id ); ?>-label">
        <div class="offcanvas-header border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
          <h5 class="offcanvas-title text-plum fw-bold mb-0" id="<?php echo esc_attr( $offcanvas_id ); ?>-label"><?php echo esc_html( $item['title'] ?? '' ); ?></h5>
          <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 position-relative" aria-label="Cart">
              <i class="bi bi-cart3 fs-5"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background:var(--pink-deep);font-size:9px;">0</span>
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
              <h2 class="text-plum fw-bold mb-2" style="font-family:'Playfair Display',serif;"><?php echo esc_html( $item['title'] ?? '' ); ?></h2>
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
                  <button type="button" class="btn btn-secondary rounded-3 px-4 py-3 fw-semibold w-100">
                    <i class="bi bi-download me-2"></i>Add to Cart
                  </button>
                </div>
                <div class="col-12 col-sm-6">
                  <?php if ( $price_id ) : ?>
                  <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="h-100">
                    <?php wp_nonce_field( 'ppl_checkout', 'ppl_checkout_nonce' ); ?>
                    <input type="hidden" name="action" value="ppl_stripe_checkout" />
                    <input type="hidden" name="ppl_product_type" value="product" />
                    <input type="hidden" name="ppl_product_idx" value="<?php echo esc_attr( $idx ); ?>" />
                    <input type="hidden" name="ppl_return_url" value="<?php echo esc_url( get_permalink() ); ?>" />
                    <button type="submit" class="btn btn-plum rounded-3 px-4 py-3 fw-semibold w-100">
                      Checkout <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                  </form>
                  <?php else : ?>
                  <button type="button" class="btn btn-plum rounded-3 px-4 py-3 fw-semibold w-100">
                    Checkout <i class="bi bi-arrow-right ms-1"></i>
                  </button>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Share -->
              <?php
              $share_url   = esc_url( get_permalink() . '?product=' . $idx );
              $share_title = esc_attr( $item['title'] ?? '' );
              ?>
              <hr style="border-color:#e0e0e0;" />
              <div class="d-flex align-items-center gap-2">
                <span class="text-muted-pp body-xs fw-semibold me-1">Share:</span>
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
          <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow"><i class="bi bi-collection-fill me-2"></i><?php ppl_e( 'ppl_shop_bundle_eyebrow', 'Complete Collection' ); ?></p>
          <h2 class="text-white ls-tight fw-bold display-6 mb-3"><?php ppl_e( 'ppl_shop_bundle_heading', 'Get all three guides and save.' ); ?></h2>
          <p class="text-light-75 body-lead mb-0"><?php ppl_e( 'ppl_shop_bundle_body', 'The full Pinkprint collection gives you a complete roadmap from your first steps into pre-law all the way through bar prep and career launch. One investment, every stage covered.' ); ?></p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <div class="mb-3">
            <p class="text-light-50 mb-1 body-xs text-uppercase ls-wide">Bundle Price</p>
            <p class="text-white fw-bold mb-0" style="font-size:2.5rem;font-family:'Playfair Display',serif;"><?php ppl_e( 'ppl_shop_bundle_price', '$67' ); ?></p>
            <p class="text-light-50 body-xs"><?php ppl_e( 'ppl_shop_bundle_savings', 'Save $14 vs. buying individually' ); ?></p>
          </div>
          <?php if ( $bundle_price_id ) : ?>
          <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'ppl_checkout', 'ppl_checkout_nonce' ); ?>
            <input type="hidden" name="action" value="ppl_stripe_checkout" />
            <input type="hidden" name="ppl_product_type" value="bundle" />
            <input type="hidden" name="ppl_return_url" value="<?php echo esc_url( get_permalink() ); ?>" />
            <button type="submit" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold">
              <?php ppl_e( 'ppl_shop_bundle_cta', 'Get the Bundle' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </button>
          </form>
          <?php else : ?>
          <button class="btn btn-rose rounded-3 px-4 py-3 fw-semibold" disabled style="opacity:0.5;cursor:not-allowed;">Coming Soon</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>


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
      <div class="<?php echo esc_attr( $feat_col ); ?> fade-up" style="--stagger:<?php echo esc_attr( $i ); ?>">
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
<section class="bg-blush section-pad">
  <div class="container">
    <div class="bg-plum rounded-4 overflow-hidden">
      <div class="row g-0">

        <!-- Left: text + CTA -->
        <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center">
          <p class="text-pink fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_session_eyebrow', 'Not sure where to start?' ); ?></p>
          <h3 class="text-white fw-bold mb-3" style="font-family:'Playfair Display',serif;"><?php ppl_e( 'ppl_shop_session_heading', 'Book a 1-on-1 Strategy Session' ); ?></h3>
          <p class="text-light-75 mb-4 body-sm"><?php ppl_e( 'ppl_shop_session_body', 'A focused, one-hour session tailored to exactly where you are in your legal journey. Walk away with a clear plan.' ); ?></p>
          <div>
            <a href="<?php echo esc_url( ppl_get( 'ppl_shop_session_url', '#' ) ); ?>" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold">
              <?php ppl_e( 'ppl_shop_session_cta', 'Book a Session' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>

        <!-- Right: image -->
        <div class="col-md-6 ppl-session-img-col">
          <?php
          $session_img = ppl_get( 'ppl_shop_session_image_url' );
          if ( $session_img ) : ?>
            <img src="<?php echo esc_url( $session_img ); ?>" alt="Book a strategy session" class="w-100 h-100" style="object-fit:cover;display:block;" />
          <?php else : ?>
            <div class="d-flex align-items-center justify-content-center h-100" style="background:var(--plum-mid);">
              <i class="bi bi-calendar2-check-fill" style="font-size:80px;color:rgba(255,137,197,0.25);"></i>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</section>


<style>
.ppl-product-card { box-shadow: 0 4px 24px rgba(35,13,24,0.07); transition: transform 0.2s ease, box-shadow 0.2s ease; }
.ppl-product-card:hover { transform: translateY(-6px); box-shadow: 0 20px 56px rgba(35,13,24,0.13); }
.ppl-product-offcanvas { height: 100vh; border-radius: 20px 20px 0 0; }
.ppl-product-offcanvas .offcanvas-body { overflow-y: auto; }
.ppl-product-offcanvas .ppl-offcanvas-img-col { min-height: 280px; }
.ppl-share-btn { color: #aaa; font-size: 18px; transition: color 0.15s; text-decoration: none; }
.ppl-share-btn:hover { color: var(--pink-deep); }
.ppl-session-img-col { min-height: 400px; }
@media (min-width: 768px) { .ppl-session-img-col { min-height: 500px; } }
</style>

<?php get_template_part( 'partials/ppl-footer' ); ?>
