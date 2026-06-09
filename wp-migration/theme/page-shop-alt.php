<?php
/**
 * Template Name: Shop (Alternate)
 *
 * Alternate layout inspired by editorial boutique store aesthetics:
 * bold hero with oversized type, a sticky filter strip, asymmetric
 * featured-product spotlight, uniform grid below, and a social-proof bar.
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-shop ppl-shop-alt">
<?php get_template_part( 'partials/ppl-nav' ); ?>


<!-- ═══════════════════════════════════════════════════
     HERO — full-width editorial split
     Left: oversized headline + CTA
     Right: stacked product peek cards
     ═══════════════════════════════════════════════════ -->
<section class="ppl-alt-hero overflow-hidden">
  <div class="container-fluid px-0">
    <div class="row g-0 min-vh-85 align-items-stretch">

      <!-- Left panel -->
      <div class="col-lg-6 bg-plum d-flex align-items-center px-5 px-lg-7 py-6">
        <div style="max-width:520px;">
          <div class="d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2 mb-5 ppl-alt-eyebrow-pill">
            <span class="dot-pink"></span>
            <span class="text-pink fw-semibold body-xs text-uppercase ls-wide"><?php ppl_e( 'ppl_shop_eyebrow', 'The Pinkprint Collection' ); ?></span>
          </div>

          <h1 class="ppl-alt-hero-h fw-bold text-white mb-4">
            <?php ppl_e( 'ppl_shop_alt_hero_line1', 'Every guide.' ); ?><br>
            <em class="text-pink"><?php ppl_e( 'ppl_shop_alt_hero_line2', 'Every stage.' ); ?></em>
          </h1>

          <p class="body-lead text-light-75 mb-6" style="max-width:400px;">
            <?php ppl_e( 'ppl_shop_lead', 'Each Pinkprint is a purpose-built roadmap for where you are right now — practical, direct, and designed by someone who has been through it.' ); ?>
          </p>

          <div class="d-flex flex-wrap gap-3 align-items-center">
            <a href="#ppl-products" class="btn btn-rose rounded-3 px-5 py-3 fw-semibold fs-btn">
              <?php ppl_e( 'ppl_shop_alt_cta_browse', 'Shop the Collection' ); ?> <i class="bi bi-arrow-down ms-2"></i>
            </a>
            <a href="<?php echo esc_url( ppl_get( 'ppl_shop_session_url', '#' ) ); ?>" class="btn btn-ghost-light rounded-3 px-4 py-3 fw-semibold fs-btn">
              <?php ppl_e( 'ppl_shop_alt_cta_session', 'Book a Session' ); ?>
            </a>
          </div>

          <!-- Micro stats -->
          <div class="d-flex gap-5 mt-6 pt-5 border-top border-white-10">
            <div>
              <p class="text-white fw-bold mb-0" style="font-size:1.75rem;font-family:'Playfair Display',serif;"><?php ppl_e( 'ppl_shop_alt_stat1_val', '3' ); ?></p>
              <p class="text-light-50 body-xs mb-0 text-uppercase ls-wide"><?php ppl_e( 'ppl_shop_alt_stat1_label', 'Guides' ); ?></p>
            </div>
            <div>
              <p class="text-white fw-bold mb-0" style="font-size:1.75rem;font-family:'Playfair Display',serif;"><?php ppl_e( 'ppl_shop_alt_stat2_val', '1,200+' ); ?></p>
              <p class="text-light-50 body-xs mb-0 text-uppercase ls-wide"><?php ppl_e( 'ppl_shop_alt_stat2_label', 'Students Helped' ); ?></p>
            </div>
            <div>
              <p class="text-white fw-bold mb-0" style="font-size:1.75rem;font-family:'Playfair Display',serif;"><?php ppl_e( 'ppl_shop_alt_stat3_val', '4.9★' ); ?></p>
              <p class="text-light-50 body-xs mb-0 text-uppercase ls-wide"><?php ppl_e( 'ppl_shop_alt_stat3_label', 'Avg. Rating' ); ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right panel — stacked product peek cards -->
      <div class="col-lg-6 bg-blush d-none d-lg-flex align-items-center justify-content-center p-5">
        <div class="ppl-alt-hero-stack">
          <?php
          $peek_cards = [
            [ 'bi-mortarboard-fill', 'Pre-Law',      'Stage 01',  'plum'    ],
            [ 'bi-book-fill',        'Law School',   'Stage 02',  'rose'    ],
            [ 'bi-clipboard2-check-fill', 'Bar & Career', 'Stage 03', 'plum' ],
          ];
          foreach ( $peek_cards as $i => $card ) :
          ?>
          <div class="ppl-alt-peek-card ppl-alt-peek-card--<?php echo esc_attr( $i + 1 ); ?> rounded-4 d-flex align-items-center gap-3 px-4 py-3 fade-up" style="--stagger:<?php echo esc_attr( $i ); ?>">
            <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 icon-52">
              <i class="bi <?php echo esc_attr( $card[0] ); ?> fs-icon-lg"></i>
            </div>
            <div>
              <p class="text-rose fw-semibold text-uppercase ls-wide mb-0 eyebrow"><?php echo esc_html( $card[2] ); ?></p>
              <p class="text-plum fw-bold mb-0" style="font-family:'Playfair Display',serif;font-size:1.1rem;"><?php echo esc_html( $card[1] ); ?> Pinkprint</p>
            </div>
            <i class="bi bi-arrow-right text-muted-pp ms-auto"></i>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════
     MARQUEE TRUST STRIP
     ═══════════════════════════════════════════════════ -->
<div class="ppl-alt-marquee-wrap bg-rose py-3 overflow-hidden">
  <div class="ppl-alt-marquee d-flex gap-5 align-items-center">
    <?php
    $marquee_items = [
      [ 'bi-download',           'Instant Download'   ],
      [ 'bi-arrow-repeat',       'Lifetime Updates'   ],
      [ 'bi-person-check-fill',  'Built by a Lawyer'  ],
      [ 'bi-shield-check',       'Secure Checkout'    ],
      [ 'bi-star-fill',          '4.9 Star Reviews'   ],
      [ 'bi-file-earmark-pdf',   'PDF Format'         ],
      [ 'bi-download',           'Instant Download'   ],
      [ 'bi-arrow-repeat',       'Lifetime Updates'   ],
      [ 'bi-person-check-fill',  'Built by a Lawyer'  ],
      [ 'bi-shield-check',       'Secure Checkout'    ],
      [ 'bi-star-fill',          '4.9 Star Reviews'   ],
      [ 'bi-file-earmark-pdf',   'PDF Format'         ],
    ];
    foreach ( $marquee_items as $mi ) :
    ?>
    <span class="d-inline-flex align-items-center gap-2 text-white fw-semibold body-sm text-nowrap">
      <i class="bi <?php echo esc_attr( $mi[0] ); ?>"></i>
      <?php echo esc_html( $mi[1] ); ?>
    </span>
    <span class="text-white-50" aria-hidden="true">·</span>
    <?php endforeach; ?>
  </div>
</div>


<!-- ═══════════════════════════════════════════════════
     FEATURED SPOTLIGHT — first/bestselling guide
     Asymmetric: large visual left, rich detail right
     ═══════════════════════════════════════════════════ -->
<section class="bg-white section-pad">
  <div class="container">

    <div class="d-flex align-items-center justify-content-between mb-5">
      <div>
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-1 eyebrow"><?php ppl_e( 'ppl_shop_alt_feat_eyebrow', 'Featured Guide' ); ?></p>
        <h2 class="text-plum fw-bold ls-tight display-6 mb-0"><?php ppl_e( 'ppl_shop_alt_feat_heading', 'Start here.' ); ?></h2>
      </div>
      <a href="#ppl-products" class="btn btn-outline-plum rounded-3 px-4 py-2 fw-semibold d-none d-md-inline-flex align-items-center gap-2">
        <?php ppl_e( 'ppl_shop_alt_feat_see_all', 'See all guides' ); ?> <i class="bi bi-arrow-down"></i>
      </a>
    </div>

    <div class="row g-0 rounded-4 overflow-hidden card-lift" style="box-shadow:0 8px 40px rgba(35,13,24,0.10);">

      <!-- Visual -->
      <div class="col-lg-5 ppl-alt-spotlight-visual d-flex align-items-center justify-content-center" style="min-height:400px;background:linear-gradient(145deg,var(--plum) 0%,var(--plum-soft) 100%);">
        <?php
        $feat_cover = ppl_get( 'ppl_shop_feat_cover_url', '' );
        if ( $feat_cover ) :
        ?>
          <img src="<?php echo esc_url( $feat_cover ); ?>" alt="The Pre-Law Pinkprint" class="w-100 h-100" style="object-fit:cover;display:block;" />
        <?php else : ?>
          <div class="text-center p-5">
            <i class="bi bi-mortarboard-fill" style="font-size:96px;color:rgba(255,137,197,0.35);display:block;margin-bottom:1rem;"></i>
            <span class="badge rounded-pill fw-bold px-4 py-2" style="background:var(--rose);color:#fff;font-size:0.85rem;">Bestseller</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Detail -->
      <div class="col-lg-7 bg-blush p-5 p-lg-6 d-flex flex-column justify-content-center">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow">Stage 01 · Pre-Law</p>
        <h3 class="text-plum fw-bold ls-tight mb-3" style="font-size:2rem;font-family:'Playfair Display',serif;">
          <?php ppl_e( 'ppl_shop_feat_title', 'The Pre-Law Pinkprint' ); ?>
        </h3>
        <p class="text-muted-pp fw-semibold mb-4 body-md" style="font-style:italic;">
          <?php ppl_e( 'ppl_shop_feat_subtitle', 'Your blueprint for getting in — and hitting the ground running.' ); ?>
        </p>

        <!-- Bullet checklist -->
        <ul class="list-unstyled mb-5">
          <?php
          $feat_bullets = [
            'Decision frameworks for choosing the right law school',
            'Personal statement guidance from a practicing attorney',
            'LSAT strategy and timeline planning',
            'What to do before day one of 1L',
          ];
          foreach ( $feat_bullets as $b ) :
          ?>
          <li class="d-flex align-items-start gap-3 mb-3">
            <span class="ppl-alt-check flex-shrink-0"><i class="bi bi-check-lg"></i></span>
            <span class="text-muted-pp body-sm"><?php echo esc_html( $b ); ?></span>
          </li>
          <?php endforeach; ?>
        </ul>

        <div class="d-flex align-items-center gap-4 flex-wrap">
          <div>
            <p class="text-muted-pp body-xs mb-0 text-uppercase ls-wide">Price</p>
            <p class="text-plum fw-bold mb-0" style="font-size:2rem;font-family:'Playfair Display',serif;">
              <?php ppl_e( 'ppl_shop_feat_price', '$27' ); ?>
            </p>
          </div>
          <a href="<?php echo esc_url( ppl_get( 'ppl_shop_feat_buy_url', '#' ) ); ?>" class="btn btn-rose rounded-3 px-5 py-3 fw-semibold flex-grow-1 flex-md-grow-0 text-center" target="_blank" rel="noopener">
            <?php ppl_e( 'ppl_shop_feat_cta', 'Get This Guide' ); ?> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>

        <p class="text-muted-pp body-xs mt-3 mb-0 d-flex align-items-center gap-2">
          <i class="bi bi-download"></i> Instant download &nbsp;·&nbsp;
          <i class="bi bi-file-earmark-pdf"></i> PDF format &nbsp;·&nbsp;
          <i class="bi bi-arrow-repeat"></i> Lifetime updates
        </p>
      </div>

    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════
     PRODUCT GRID — all guides
     ═══════════════════════════════════════════════════ -->
<section id="ppl-products" class="bg-blush section-pad">
  <div class="container">

    <div class="text-center mb-6">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_grid_eyebrow', 'The Guides' ); ?></p>
      <h2 class="text-plum fw-bold ls-tight display-5 mb-3"><?php ppl_e( 'ppl_shop_grid_heading', 'Find the right Pinkprint for your stage.' ); ?></h2>
      <p class="mx-auto text-muted-pp mw-520 body-md"><?php ppl_e( 'ppl_shop_grid_subtext', 'Whether you are preparing for law school, navigating it, or stepping into the profession — there is a guide built for exactly where you are.' ); ?></p>
    </div>

    <?php
    $shop_defaults = [
      [
        'stage'      => 'Stage 01 · Pre-Law',
        'title'      => 'The Pre-Law Pinkprint',
        'subtitle'   => 'Your blueprint for getting in — and hitting the ground running.',
        'body'       => 'Decision-making frameworks, school selection strategy, personal statement guidance, and everything you need to prepare before day one of law school.',
        'price'      => '$27',
        'badge'      => 'Bestseller',
        'cover_url'  => '',
        'buy_url'    => '#',
        'icon'       => 'bi-mortarboard-fill',
      ],
      [
        'stage'      => 'Stage 02 · Law School',
        'title'      => 'The Law School Pinkprint',
        'subtitle'   => 'A proven study system for surviving — and thriving — in 1L and beyond.',
        'body'       => 'Briefing methods, exam strategy, outlining systems, and time management frameworks designed around the actual demands of legal education.',
        'price'      => '$27',
        'badge'      => '',
        'cover_url'  => '',
        'buy_url'    => '#',
        'icon'       => 'bi-book-fill',
      ],
      [
        'stage'      => 'Stage 03 · Bar & Career',
        'title'      => 'The Bar & Career Pinkprint',
        'subtitle'   => 'Cross the finish line and step into the profession with a plan.',
        'body'       => 'Bar exam scheduling, MBE and essay strategies, networking frameworks, and career positioning tools for the critical post-graduation period.',
        'price'      => '$27',
        'badge'      => 'New',
        'cover_url'  => '',
        'buy_url'    => '#',
        'icon'       => 'bi-clipboard2-check-fill',
      ],
    ];

    $raw     = get_post_meta( get_the_ID(), 'ppl_shop_items', true );
    $decoded = $raw ? json_decode( $raw, true ) : null;
    $items   = ( is_array( $decoded ) && count( $decoded ) ) ? $decoded : $shop_defaults;
    ?>

    <!-- Alt grid: horizontal card rows instead of equal columns -->
    <div class="d-flex flex-column gap-4" id="ppl-shop-grid">
      <?php foreach ( $items as $idx => $item ) :
        $item      = (array) $item;
        $has_cover = ! empty( $item['cover_url'] );
        $badge     = $item['badge'] ?? '';
        $icon      = $item['icon'] ?? 'bi-journal-bookmark';
        $flip      = ( $idx % 2 !== 0 ); // alternate visual side
      ?>
      <div class="ppl-alt-row-card rounded-4 overflow-hidden d-flex flex-column flex-md-row<?php echo $flip ? ' flex-md-row-reverse' : ''; ?> fade-up" style="--stagger:<?php echo esc_attr( $idx ); ?>;box-shadow:0 4px 24px rgba(35,13,24,0.07);">

        <!-- Visual column -->
        <div class="ppl-alt-row-visual flex-shrink-0 d-flex align-items-center justify-content-center position-relative" style="width:100%;max-width:300px;min-height:220px;background:linear-gradient(135deg,var(--plum) 0%,var(--plum-soft) 100%);">
          <?php if ( $has_cover ) : ?>
            <img src="<?php echo esc_url( $item['cover_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ?? '' ); ?>" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0;" />
          <?php else : ?>
            <i class="bi <?php echo esc_attr( $icon ); ?>" style="font-size:72px;color:rgba(255,137,197,0.35);"></i>
          <?php endif; ?>

          <?php if ( $badge ) : ?>
            <span class="position-absolute top-0 start-0 m-3 badge rounded-pill fw-semibold" style="background:var(--rose);color:#fff;padding:6px 14px;"><?php echo esc_html( $badge ); ?></span>
          <?php endif; ?>
        </div>

        <!-- Content column -->
        <div class="bg-white flex-grow-1 p-4 p-md-5 d-flex flex-column justify-content-center">
          <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-3">
            <p class="text-rose fw-semibold text-uppercase ls-wide mb-0 eyebrow"><?php echo esc_html( $item['stage'] ?? '' ); ?></p>
            <span class="fw-bold rounded-3 px-3 py-1" style="background:var(--blush);color:var(--plum);font-size:1.1rem;font-family:'DM Sans',sans-serif;white-space:nowrap;"><?php echo esc_html( $item['price'] ?? '' ); ?></span>
          </div>

          <h3 class="text-plum fw-bold mb-2" style="font-family:'Playfair Display',serif;font-size:1.5rem;"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
          <p class="text-muted-pp fw-semibold mb-3 body-sm" style="font-style:italic;"><?php echo esc_html( $item['subtitle'] ?? '' ); ?></p>
          <p class="text-muted-pp mb-4 body-sm"><?php echo esc_html( $item['body'] ?? '' ); ?></p>

          <div class="d-flex align-items-center gap-3 flex-wrap mt-auto">
            <a href="<?php echo esc_url( $item['buy_url'] ?? '#' ); ?>" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold" target="_blank" rel="noopener">
              Get This Guide <i class="bi bi-arrow-right ms-1"></i>
            </a>
            <span class="text-muted-pp body-xs d-flex align-items-center gap-1"><i class="bi bi-download"></i> Instant download</span>
            <span class="text-muted-pp body-xs d-flex align-items-center gap-1"><i class="bi bi-arrow-repeat"></i> Lifetime updates</span>
          </div>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- ═══════════════════════════════════════════════════
     BUNDLE BANNER — full-bleed bold stripe
     ═══════════════════════════════════════════════════ -->
<section class="ppl-alt-bundle bg-rose section-pad">
  <div class="container">
    <div class="row align-items-center g-4 g-lg-5">
      <div class="col-lg-2 d-none d-lg-flex justify-content-center">
        <i class="bi bi-collection-fill" style="font-size:80px;color:rgba(255,255,255,0.25);"></i>
      </div>
      <div class="col-lg-7">
        <p class="text-white fw-semibold text-uppercase ls-wide mb-2 eyebrow"><i class="bi bi-collection-fill me-2"></i><?php ppl_e( 'ppl_shop_bundle_eyebrow', 'Complete Collection' ); ?></p>
        <h2 class="text-white ls-tight fw-bold display-6 mb-2"><?php ppl_e( 'ppl_shop_bundle_heading', 'Get all three guides and save.' ); ?></h2>
        <p class="text-white-75 body-md mb-0"><?php ppl_e( 'ppl_shop_bundle_body', 'The full Pinkprint collection gives you a complete roadmap from your first steps into pre-law all the way through bar prep and career launch.' ); ?></p>
      </div>
      <div class="col-lg-3 text-lg-end">
        <p class="text-white fw-bold mb-1" style="font-size:2.75rem;font-family:'Playfair Display',serif;line-height:1;"><?php ppl_e( 'ppl_shop_bundle_price', '$67' ); ?></p>
        <p class="text-white-75 body-xs mb-3"><?php ppl_e( 'ppl_shop_bundle_savings', 'Save $14 vs. buying individually' ); ?></p>
        <a href="<?php echo esc_url( ppl_get( 'ppl_shop_bundle_url', '#' ) ); ?>" class="btn btn-white-plum rounded-3 px-5 py-3 fw-semibold w-100">
          <?php ppl_e( 'ppl_shop_bundle_cta', 'Get the Bundle' ); ?> <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════
     SOCIAL PROOF — testimonial strip
     ═══════════════════════════════════════════════════ -->
<section class="bg-white section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_alt_reviews_eyebrow', 'Student Reviews' ); ?></p>
      <h2 class="text-plum fw-bold ls-tight display-6 mb-0"><?php ppl_e( 'ppl_shop_alt_reviews_heading', 'What readers are saying.' ); ?></h2>
    </div>
    <div class="row g-4">
      <?php
      $testimonials = [
        [
          'quote'  => 'The Pre-Law Pinkprint gave me a concrete plan when I felt completely lost. I used the school selection framework and got into my top choice.',
          'name'   => 'Jasmine T.',
          'detail' => '1L at Howard University School of Law',
          'stars'  => 5,
        ],
        [
          'quote'  => 'I wish I had the Law School Pinkprint before 1L started. The outlining system alone saved my GPA. Practical advice I could actually use.',
          'name'   => 'Monique R.',
          'detail' => '2L, top 10% of class',
          'stars'  => 5,
        ],
        [
          'quote'  => 'Passed the bar on my first attempt using the Bar & Career Pinkprint\'s study schedule. The MBE strategy is unlike anything I found anywhere else.',
          'name'   => 'Aaliyah S.',
          'detail' => 'Licensed attorney, class of 2025',
          'stars'  => 5,
        ],
      ];
      foreach ( $testimonials as $i => $t ) :
      ?>
      <div class="col-md-4 fade-up" style="--stagger:<?php echo esc_attr( $i ); ?>">
        <div class="bg-blush rounded-4 p-4 h-100 d-flex flex-column">
          <!-- Stars -->
          <div class="d-flex gap-1 mb-3">
            <?php for ( $s = 0; $s < $t['stars']; $s++ ) : ?>
            <i class="bi bi-star-fill text-rose" style="font-size:0.85rem;"></i>
            <?php endfor; ?>
          </div>
          <p class="text-plum body-md mb-4 flex-grow-1" style="font-style:italic;">"<?php echo esc_html( $t['quote'] ); ?>"</p>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <div class="ppl-alt-avatar rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
              <i class="bi bi-person-fill text-rose"></i>
            </div>
            <div>
              <p class="text-plum fw-bold mb-0 body-sm"><?php echo esc_html( $t['name'] ); ?></p>
              <p class="text-muted-pp mb-0 body-xs"><?php echo esc_html( $t['detail'] ); ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════
     WHAT'S INSIDE — feature grid
     ═══════════════════════════════════════════════════ -->
<section class="bg-plum section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-pink fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_inside_eyebrow', 'What You Get' ); ?></p>
      <h2 class="text-white fw-bold ls-tight display-6 mb-0"><?php ppl_e( 'ppl_shop_inside_heading', 'Every guide is built the same way — intentionally.' ); ?></h2>
    </div>
    <div class="row g-3">
      <?php
      $features = [
        [ 'bi-map-fill',        'Clear Stage Frameworks',    'Each guide maps out the exact actions, decisions, and mindset shifts needed at your specific stage.'   ],
        [ 'bi-journal-check',   'Actionable Checklists',     'No theory without practice. Every section ends with steps you can take immediately.'                    ],
        [ 'bi-chat-quote-fill', 'Real-World Language',       'Written the way a mentor explains it — not the way a textbook presents it.'                             ],
        [ 'bi-arrow-repeat',    'Lifetime Access & Updates', 'Buy once. Every future edition is yours at no additional cost.'                                          ],
      ];
      foreach ( $features as $i => $feat ) :
      ?>
      <div class="col-sm-6 col-lg-3 fade-up" style="--stagger:<?php echo esc_attr( $i ); ?>">
        <div class="ppl-alt-feature-tile rounded-4 p-4 h-100 d-flex flex-column">
          <div class="icon-wrap-dim rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $feat[0] ); ?> fs-icon-lg" style="color:var(--pink-light);"></i>
          </div>
          <h4 class="text-white fw-bold mb-2 card-h-sm"><?php echo esc_html( $feat[1] ); ?></h4>
          <p class="text-light-75 mb-0 body-sm"><?php echo esc_html( $feat[2] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════
     SESSION CTA — centered minimal card
     ═══════════════════════════════════════════════════ -->
<section class="bg-blush section-pad">
  <div class="container">
    <div class="ppl-alt-session-card bg-white rounded-4 p-5 p-md-6 text-center mx-auto" style="max-width:680px;box-shadow:0 8px 40px rgba(35,13,24,0.08);">
      <div class="icon-wrap-tint rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width:72px;height:72px;">
        <i class="bi bi-calendar2-check-fill fs-icon-lg"></i>
      </div>
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_shop_session_eyebrow', 'Not sure where to start?' ); ?></p>
      <h3 class="text-plum fw-bold mb-3" style="font-size:1.75rem;font-family:'Playfair Display',serif;"><?php ppl_e( 'ppl_shop_session_heading', 'Book a 1-on-1 Strategy Session' ); ?></h3>
      <p class="text-muted-pp body-md mb-5 mx-auto" style="max-width:480px;"><?php ppl_e( 'ppl_shop_session_body', 'A focused, one-hour session tailored to exactly where you are in your legal journey. Walk away with a clear plan.' ); ?></p>
      <a href="<?php echo esc_url( ppl_get( 'ppl_shop_session_url', '#' ) ); ?>" class="btn btn-rose rounded-3 px-5 py-3 fw-semibold">
        <?php ppl_e( 'ppl_shop_session_cta', 'Book a Session' ); ?> <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════
     PAGE-SCOPED STYLES
     ═══════════════════════════════════════════════════ -->
<style>
/* Hero */
.ppl-alt-hero-h { font-size: clamp(2.75rem, 5.5vw, 4.5rem); line-height: 1.05; font-family: 'Playfair Display', serif; }
.min-vh-85      { min-height: 85vh; }
.px-lg-7        { padding-left: 4.5rem !important; padding-right: 4.5rem !important; }
.mb-6           { margin-bottom: 3rem !important; }
.mt-6           { margin-top: 3rem !important; }
.pt-5           { padding-top: 2rem !important; }
.py-6           { padding-top: 3.5rem !important; padding-bottom: 3.5rem !important; }
.border-white-10 { border-color: rgba(255,255,255,0.12) !important; }

/* Eyebrow pill */
.ppl-alt-eyebrow-pill { background: rgba(255,137,197,0.12); }

/* Peek cards */
.ppl-alt-hero-stack      { display: flex; flex-direction: column; gap: 1rem; width: 100%; max-width: 360px; }
.ppl-alt-peek-card       { background: #fff; box-shadow: 0 4px 20px rgba(35,13,24,0.10); cursor: default; }
.ppl-alt-peek-card--1    { margin-left: 0; }
.ppl-alt-peek-card--2    { margin-left: 2rem; }
.ppl-alt-peek-card--3    { margin-left: 1rem; }

/* Marquee */
.ppl-alt-marquee-wrap { white-space: nowrap; }
.ppl-alt-marquee      { animation: ppl-scroll 28s linear infinite; display: inline-flex; }
@keyframes ppl-scroll  { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.ppl-alt-marquee-wrap:hover .ppl-alt-marquee { animation-play-state: paused; }

/* Spotlight */
.ppl-alt-check {
  width: 22px; height: 22px;
  background: var(--rose); border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 0.75rem; flex-shrink: 0;
}

/* Row cards */
.ppl-alt-row-card { transition: transform .25s ease, box-shadow .25s ease; }
.ppl-alt-row-card:hover { transform: translateY(-4px); box-shadow: 0 16px 48px rgba(35,13,24,0.13) !important; }
.ppl-alt-row-visual { width: 100%; }
@media (min-width: 768px) { .ppl-alt-row-visual { max-width: 260px; min-height: 260px; } }

/* Bundle */
.ppl-alt-bundle .text-white-75  { color: rgba(255,255,255,0.8) !important; }
.btn-white-plum { background: #fff; color: var(--plum); border: 2px solid transparent; }
.btn-white-plum:hover { background: var(--blush); color: var(--plum); }
.btn-ghost-light { border: 2px solid rgba(255,255,255,0.35); color: #fff; background: transparent; }
.btn-ghost-light:hover { background: rgba(255,255,255,0.1); color: #fff; }
.btn-outline-plum { border: 2px solid var(--plum); color: var(--plum); background: transparent; }
.btn-outline-plum:hover { background: var(--plum); color: #fff; }

/* Testimonials */
.ppl-alt-avatar { width: 40px; height: 40px; background: var(--blush-deep, #f5d6e6); }

/* Feature tiles */
.ppl-alt-feature-tile { background: rgba(255,255,255,0.06); }

/* Misc */
.fs-btn { font-size: 1rem; }
.mw-520 { max-width: 520px; }
.text-light-50 { color: rgba(255,255,255,0.5) !important; }
</style>

<?php get_template_part( 'partials/ppl-footer' ); ?>
