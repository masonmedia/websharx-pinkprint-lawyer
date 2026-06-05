<?php
/**
 * Template Name: Blog Archive — Grid
 *
 * Featured post: set `_ppl_featured_post` = '1' on a post via its meta box
 * (register that checkbox in blog-single.php). Falls back to first sticky post,
 * then the most recent published post.
 */

$paged = max( 1, get_query_var( 'paged' ) );

// ── Featured post ─────────────────────────────────────────────────────────────
$featured_query = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'meta_key'       => '_ppl_featured_post',
    'meta_value'     => '1',
] );

$featured_post = $featured_query->have_posts() ? $featured_query->posts[0] : null;
wp_reset_postdata();

// Fallback: first sticky post
if ( ! $featured_post ) {
    $sticky_ids = get_option( 'sticky_posts' );
    if ( ! empty( $sticky_ids ) ) {
        $sticky_q = new WP_Query( [
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'post__in'       => $sticky_ids,
            'orderby'        => 'post__in',
        ] );
        $featured_post = $sticky_q->have_posts() ? $sticky_q->posts[0] : null;
        wp_reset_postdata();
    }
}

// Fallback: most recent post
if ( ! $featured_post ) {
    $fallback_q = new WP_Query( [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
    ] );
    $featured_post = $fallback_q->have_posts() ? $fallback_q->posts[0] : null;
    wp_reset_postdata();
}

// ── Grid posts (exclude featured) ─────────────────────────────────────────────
$exclude_id   = $featured_post ? [ $featured_post->ID ] : [];
$blog_cats    = get_categories( [ 'hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC' ] );

$grid_query = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 8,
    'paged'          => $paged,
    'post__not_in'   => $exclude_id,
] );

get_template_part( 'partials/ppl-head' );
?>
<style>
  .blog-hero-img { width:100%; height:100%; object-fit:cover; display:block; }
  .blog-hero-overlay { background: linear-gradient(to top, rgba(35,13,24,0.65) 0%, rgba(35,13,24,0.1) 60%, transparent 100%); }
  .card-img-portrait { width:100%; aspect-ratio:16/9; object-fit:cover; display:block; }
  .card-img-portrait-placeholder { width:100%; aspect-ratio:16/9; background-color:var(--blush-mid); }
  .cat-pill { font-size:11px; font-family:'DM Sans',sans-serif; font-weight:600; padding:6px 16px; border-radius:50px; border:1.5px solid var(--blush-mid); background:transparent; color:var(--muted-pp); cursor:pointer; transition:all 0.15s ease; text-transform:uppercase; letter-spacing:1.5px; white-space:nowrap; }
  .cat-pill:hover { border-color:var(--pink-deep); color:var(--pink-deep); }
  .cat-pill.active { background:var(--pink-deep); border-color:var(--pink-deep); color:#fff; }
  .page-link-pp { font-size:13px; font-weight:600; font-family:'DM Sans',sans-serif; color:var(--muted-pp); border:1.5px solid var(--blush-mid); border-radius:50px !important; width:40px; height:40px; display:flex; align-items:center; justify-content:center; padding:0; background:transparent; transition:all 0.15s ease; text-decoration:none; }
  .page-link-pp:hover, .page-link-pp.active { background:var(--pink-deep); border-color:var(--pink-deep); color:#fff; }
  .page-link-pp-next { font-size:13px; font-weight:600; font-family:'DM Sans',sans-serif; color:var(--muted-pp); border:1.5px solid var(--blush-mid); border-radius:50px !important; padding:8px 20px; background:transparent; transition:all 0.15s ease; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
  .page-link-pp-next:hover { border-color:var(--pink-deep); color:var(--pink-deep); }
  html[data-theme="dark"] .cat-pill { border-color:rgba(255,255,255,0.15); color:rgba(255,255,255,0.6); }
  html[data-theme="dark"] .cat-pill:hover { border-color:var(--pink-light); color:var(--pink-light); }
  html[data-theme="dark"] .cat-pill.active { background:var(--pink-deep); border-color:var(--pink-deep); color:#fff; }
  html[data-theme="dark"] .page-link-pp { border-color:rgba(255,255,255,0.15); color:rgba(255,255,255,0.6); }
  html[data-theme="dark"] .page-link-pp:hover, html[data-theme="dark"] .page-link-pp.active { background:var(--pink-deep); border-color:var(--pink-deep); color:#fff; }
  html[data-theme="dark"] .page-link-pp-next { border-color:rgba(255,255,255,0.15); color:rgba(255,255,255,0.6); }
  html[data-theme="dark"] .page-link-pp-next:hover { border-color:var(--pink-light); color:var(--pink-light); }
  html[data-theme="dark"] .blog-hero-overlay { background:linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.15) 60%, transparent 100%); }
</style>
</head>
<body class="ppl-blog-archive-grid">

<?php get_template_part( 'partials/ppl-nav' ); ?>

<main>

  <!-- ── Page header ───────────────────────────────────────────────────────── -->
  <section class="bg-blush section-pad" style="padding-top: 80px; padding-bottom: 64px;">
    <div class="container text-center">
      <span class="d-inline-flex align-items-center gap-2 bg-pink-tint text-rose rounded-pill px-3 py-2 fw-semibold mb-4 eyebrow">
        <i class="bi bi-journals"></i> Legal Insights &amp; Editorial
      </span>
      <h1 class="text-plum ls-tight fw-bold mb-4" style="font-size: clamp(2rem, 5vw, 3.5rem); font-family: 'Playfair Display', serif; max-width: 720px; margin-inline: auto;">
        <?php echo esc_html( get_the_title() ?: 'Navigating Law with Deliberate Intent' ); ?>
      </h1>
      <p class="text-muted-pp body-md mx-auto" style="max-width: 560px;">
        Thoughtful analysis at the intersection of law, technology, and professional identity.
      </p>
    </div>
  </section>


  <!-- ── Featured hero post ────────────────────────────────────────────────── -->
  <?php if ( $featured_post ) :
    $feat_cats   = get_the_category( $featured_post->ID );
    $feat_cat    = $feat_cats ? $feat_cats[0] : null;
    $feat_thumb  = get_the_post_thumbnail_url( $featured_post->ID, 'full' );
    $feat_author = get_the_author_meta( 'display_name', $featured_post->post_author );
    $feat_date   = get_the_date( 'M j, Y', $featured_post );
    $read_time   = max( 1, (int) ( str_word_count( strip_tags( $featured_post->post_content ) ) / 200 ) );
    $feat_url    = get_permalink( $featured_post->ID );
  ?>
  <section class="container mb-5">
    <a href="<?php echo esc_url( $feat_url ); ?>" class="d-block text-decoration-none" style="border-radius: 12px; overflow: hidden; position: relative; aspect-ratio: 21/9;">
      <?php if ( $feat_thumb ) : ?>
        <img src="<?php echo esc_url( $feat_thumb ); ?>"
             alt="<?php echo esc_attr( $featured_post->post_title ); ?>"
             class="blog-hero-img" />
      <?php else : ?>
        <div class="blog-hero-img bg-blush-mid"></div>
      <?php endif; ?>

      <div class="blog-hero-overlay position-absolute inset-0" style="inset: 0;"></div>

      <div class="position-absolute text-white" style="bottom: 2.5rem; left: 2.5rem; right: 2.5rem;">
        <?php if ( $feat_cat ) : ?>
        <span class="d-inline-block px-3 py-1 rounded-1 mb-3 fw-semibold eyebrow text-uppercase ls-wide"
              style="background: var(--pink-deep); font-size: 11px;">
          Featured &middot; <?php echo esc_html( $feat_cat->name ); ?>
        </span>
        <?php endif; ?>

        <h2 class="fw-bold ls-tight mb-2 text-white" style="font-family: 'Playfair Display', serif; font-size: clamp(1.5rem, 3vw, 2.5rem); line-height: 1.2; max-width: 680px;">
          <?php echo esc_html( $featured_post->post_title ); ?>
        </h2>

        <p class="mb-0" style="font-size: 15px; opacity: 0.85; max-width: 520px; font-family: 'DM Sans', sans-serif;">
          <?php echo esc_html( wp_trim_words( $featured_post->post_excerpt ?: $featured_post->post_content, 22 ) ); ?>
        </p>

        <div class="d-flex align-items-center gap-2 mt-3" style="font-size: 13px; opacity: 0.75; font-family: 'DM Sans', sans-serif;">
          <span><?php echo esc_html( $feat_author ); ?></span>
          <span>&middot;</span>
          <span><?php echo esc_html( $feat_date ); ?></span>
          <span>&middot;</span>
          <span><?php echo esc_html( $read_time ); ?> min read</span>
        </div>
      </div>
    </a>
  </section>
  <?php endif; ?>


  <!-- ── Category filter pills ─────────────────────────────────────────────── -->
  <?php if ( ! empty( $blog_cats ) ) : ?>
  <div class="bg-white border-top border-bottom border-blush sticky-top" style="top: 73px; z-index: 100; padding: 16px 0;">
    <div class="container">
      <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
        <button class="cat-pill active" data-filter="all">All Posts</button>
        <?php foreach ( $blog_cats as $cat ) : ?>
          <button class="cat-pill" data-filter="<?php echo esc_attr( $cat->slug ); ?>">
            <?php echo esc_html( $cat->name ); ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>


  <!-- ── Post grid ─────────────────────────────────────────────────────────── -->
  <section class="bg-blush section-pad">
    <div class="container">

      <!-- Post count -->
      <p class="text-muted-pp mb-4 body-xs">
        Showing <span id="ppl-post-count"><?php echo esc_html( $grid_query->post_count ); ?></span>
        of <?php echo esc_html( $grid_query->found_posts ); ?> articles
      </p>

      <div class="row g-4" id="ppl-post-grid">
        <?php if ( $grid_query->have_posts() ) : ?>
          <?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); ?>
            <?php
            $cats     = get_the_category();
            $cat      = $cats ? $cats[0] : null;
            $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
            $date     = get_the_date( 'M j, Y' );
            $rt       = max( 1, (int) ( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );
            ?>
            <div class="col-sm-6 col-lg-3" data-post-cat="<?php echo esc_attr( $cat ? $cat->slug : '' ); ?>">
              <div class="card h-100 border-0 rounded-4 overflow-hidden card-lift" style="border: 1px solid var(--blush-mid) !important;">
                <a href="<?php the_permalink(); ?>" class="d-block overflow-hidden">
                  <?php if ( $thumb ) : ?>
                    <img src="<?php echo esc_url( $thumb ); ?>"
                         alt="<?php echo esc_attr( get_the_title() ); ?>"
                         class="card-img-top card-img-portrait"
                         style="transition: transform 0.5s ease;" />
                  <?php else : ?>
                    <div class="card-img-portrait-placeholder"></div>
                  <?php endif; ?>
                </a>

                <div class="card-body d-flex flex-column p-4">
                  <?php if ( $cat ) : ?>
                    <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 stage-tag">
                      <?php echo esc_html( $cat->name ); ?>
                    </p>
                  <?php endif; ?>

                  <h3 class="card-title text-plum fw-bold mb-3 card-h-sm">
                    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-plum">
                      <?php the_title(); ?>
                    </a>
                  </h3>

                  <p class="card-text text-muted-pp body-xs flex-grow-1 mb-3">
                    <?php echo esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ); ?>
                  </p>

                  <div class="d-flex align-items-center justify-content-between pt-3" style="border-top: 1px solid var(--blush-mid); margin-top: auto;">
                    <span class="text-muted-pp" style="font-size: 12px; font-family: 'DM Sans', sans-serif;">
                      <?php echo esc_html( $date ); ?> &middot; <?php echo esc_html( $rt ); ?> min
                    </span>
                    <a href="<?php the_permalink(); ?>" class="card-link d-inline-flex align-items-center gap-1">
                      Read <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        <?php else : ?>
          <div class="col-12 text-center py-5">
            <p class="text-muted-pp body-md">No articles found.</p>
          </div>
        <?php endif; ?>
      </div>


      <!-- ── Pagination ─────────────────────────────────────────────────────── -->
      <?php if ( $grid_query->max_num_pages > 1 ) : ?>
      <div class="d-flex justify-content-center align-items-center gap-2 mt-5 pt-3 flex-wrap">
        <?php
        $paginate_args = [
            'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'    => '?paged=%#%',
            'current'   => $paged,
            'total'     => $grid_query->max_num_pages,
            'prev_text' => '<i class="bi bi-chevron-left"></i>',
            'next_text' => 'Next <i class="bi bi-arrow-right"></i>',
            'type'      => 'array',
        ];

        $links = paginate_links( $paginate_args );

        if ( $links ) :
            foreach ( $links as $link ) :
                $is_next = strpos( $link, 'next' ) !== false;
                $cls     = $is_next ? 'page-link-pp-next' : 'page-link-pp';
                // Replace the anchor class that WP injects
                $link = preg_replace( '/class="[^"]*"/', 'class="' . $cls . '"', $link );
                // Mark active page
                if ( strpos( $link, 'current' ) !== false ) {
                    $link = str_replace( 'class="' . $cls . '"', 'class="' . $cls . ' active"', $link );
                }
                echo wp_kses_post( $link );
            endforeach;
        endif;
        ?>
      </div>
      <?php endif; ?>

    </div>
  </section>


  <!-- ── Newsletter CTA ────────────────────────────────────────────────────── -->
  <section class="bg-plum section-pad">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-6">
          <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow">Stay in the Loop</p>
          <h2 class="text-white ls-tight fw-bold display-6 mb-4">New articles, every week.</h2>
          <p class="text-light-75 body-md mb-5">Practical legal insights, no noise — delivered straight to your inbox.</p>
          <div class="d-flex">
            <?php
            // If using a newsletter plugin (e.g. Mailchimp for WP), replace this with its shortcode:
            // echo do_shortcode('[mc4wp_form id="XXX"]');
            ?>
            <input type="email" placeholder="Your email address"
              style="flex:1;background:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.15);border-right:none;border-radius:10px 0 0 10px;padding:14px 20px;font-size:15px;color:#fff;font-family:'DM Sans',sans-serif;" />
            <button style="background:var(--pink-deep);color:#fff;font-size:15px;font-weight:600;border-radius:0 10px 10px 0;padding:14px 24px;border:none;white-space:nowrap;font-family:'DM Sans',sans-serif;cursor:pointer;">
              Subscribe
            </button>
          </div>
          <p class="text-light-60 mt-3" style="font-size: 12px;">No spam. Unsubscribe at any time.</p>
        </div>
      </div>
    </div>
  </section>

</main>

<?php get_template_part( 'partials/ppl-footer' ); ?>

<script>
(function () {
  // ── Category filter ──────────────────────────────────────────────────────
  const pills   = document.querySelectorAll('.cat-pill');
  const cards   = document.querySelectorAll('#ppl-post-grid [data-post-cat]');
  const countEl = document.getElementById('ppl-post-count');

  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      pills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');

      const filter = pill.dataset.filter;
      let visible  = 0;

      cards.forEach(card => {
        const show = filter === 'all' || card.dataset.postCat === filter;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
      });

      if (countEl) countEl.textContent = visible;
    });
  });

  // ── Card image hover scale ───────────────────────────────────────────────
  document.querySelectorAll('#ppl-post-grid article').forEach(card => {
    const img = card.querySelector('img');
    if (!img) return;
    card.addEventListener('mouseenter', () => { img.style.transform = 'scale(1.05)'; });
    card.addEventListener('mouseleave', () => { img.style.transform = 'scale(1)'; });
  });
})();
</script>
