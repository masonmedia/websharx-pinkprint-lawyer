<?php
/**
 * Template Name: Blog Single Post
 *
 * Use this template for the standard post single view.
 * To replace single.php entirely, rename this file to single.php.
 *
 * Per-post meta fields (editable in the Post editor sidebar):
 *   _ppl_featured_post       — '1' = display as hero on blog-archive-grid
 *   _ppl_post_author_name    — overrides WP display_name in author card
 *   _ppl_post_author_role    — author title/role line
 *   _ppl_post_author_bio     — overrides WP user description
 *   _ppl_post_author_photo   — overrides Gravatar URL
 *   _ppl_post_cta_title      — in-article CTA block heading
 *   _ppl_post_cta_body       — in-article CTA body copy
 *   _ppl_post_cta_btn_label  — CTA button label
 *   _ppl_post_cta_btn_url    — CTA button URL
 */

// ── Post data ─────────────────────────────────────────────────────────────────
$post_id    = get_the_ID();
$cats       = get_the_category();
$primary_cat = $cats ? $cats[0] : null;
$tags        = get_the_tags();
$read_time   = max( 1, (int) ( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );

// ── Author: per-post overrides → WP user meta fallback ───────────────────────
$wp_author_id    = (int) get_post_field( 'post_author', $post_id );
$author_name     = get_post_meta( $post_id, '_ppl_post_author_name', true )
                   ?: get_the_author_meta( 'display_name', $wp_author_id );
$author_role     = get_post_meta( $post_id, '_ppl_post_author_role', true )
                   ?: get_the_author_meta( 'user_description_role', $wp_author_id )
                   ?: 'Practicing Attorney &amp; Mentor';
$author_bio      = get_post_meta( $post_id, '_ppl_post_author_bio', true )
                   ?: get_the_author_meta( 'description', $wp_author_id );
$author_photo    = get_post_meta( $post_id, '_ppl_post_author_photo', true )
                   ?: get_avatar_url( $wp_author_id, [ 'size' => 128 ] );
$author_url      = get_author_posts_url( $wp_author_id );

// ── In-article CTA block ──────────────────────────────────────────────────────
$cta_title   = get_post_meta( $post_id, '_ppl_post_cta_title', true );
$cta_body    = get_post_meta( $post_id, '_ppl_post_cta_body', true );
$cta_btn     = get_post_meta( $post_id, '_ppl_post_cta_btn_label', true );
$cta_url     = get_post_meta( $post_id, '_ppl_post_cta_btn_url', true );
$show_cta    = $cta_title || $cta_btn;

// ── Related posts (same category, randomized) ─────────────────────────────────
$cat_ids = $primary_cat ? [ $primary_cat->term_id ] : [];
$related = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 4,
    'post__not_in'   => [ $post_id ],
    'category__in'   => $cat_ids,
    'orderby'        => 'rand',
] );

// ── Prev / Next ───────────────────────────────────────────────────────────────
$prev_post = get_previous_post();
$next_post = get_next_post();

get_template_part( 'partials/ppl-head' );
?>
<style>
  .ppl-post-content { font-size:18px; line-height:1.85; font-family:'Literata',Georgia,serif; color:var(--plum); }
  .ppl-post-content p { margin-bottom:1.6rem; }
  .ppl-post-content h2 { font-family:'Playfair Display',serif; font-size:clamp(1.5rem,3vw,2rem); font-weight:600; margin-top:3rem; margin-bottom:1.25rem; color:var(--plum); }
  .ppl-post-content h3 { font-family:'Playfair Display',serif; font-size:1.3rem; font-weight:600; margin-top:2.25rem; margin-bottom:0.85rem; color:var(--plum); }
  .ppl-post-content ul, .ppl-post-content ol { margin-bottom:1.6rem; padding-left:1.5rem; }
  .ppl-post-content li { margin-bottom:0.5rem; }
  .ppl-post-content ul li::marker { color:var(--pink-deep); }
  .ppl-post-content a { color:var(--pink-deep); text-decoration:underline; text-underline-offset:3px; }
  .ppl-post-content a:hover { color:var(--plum); }
  .ppl-post-content blockquote { border-left:4px solid var(--pink-deep); margin:2.5rem 0; padding:1rem 1.75rem; font-family:'Playfair Display',serif; font-style:italic; font-size:1.2rem; line-height:1.65; color:var(--plum-soft); }
  .ppl-post-content blockquote p { margin-bottom:0; }
  .ppl-post-content > p:first-of-type::first-letter { font-family:'Playfair Display',serif; font-size:4.5rem; font-weight:700; float:left; line-height:0.8; margin-right:10px; margin-top:8px; color:var(--pink-deep); }
  .post-hero-full-bleed { width:100%; height:70vh; min-height:380px; max-height:640px; object-fit:cover; display:block; }
  .sidebar-widget { background:var(--blush); border-radius:16px; padding:24px; margin-bottom:24px; border:1px solid var(--blush-mid); }
  .sidebar-label { font-size:10px; font-family:'DM Sans',sans-serif; font-weight:700; text-transform:uppercase; letter-spacing:2.5px; color:var(--muted-pp); margin-bottom:16px; }
  .sidebar-cat-link { display:flex; align-items:center; justify-content:space-between; text-decoration:none; color:var(--plum); font-size:14px; font-weight:500; padding:8px 0; border-bottom:1px solid var(--blush-mid); }
  .sidebar-cat-link:last-child { border-bottom:none; padding-bottom:0; }
  .sidebar-cat-link:hover { color:var(--pink-deep); }
  .sidebar-related-img { width:72px; height:72px; flex-shrink:0; object-fit:cover; border-radius:8px; }
  .sidebar-related-img-placeholder { width:72px; height:72px; flex-shrink:0; border-radius:8px; background:var(--blush-mid); }
  .sidebar-related-title { font-size:13px; font-weight:600; line-height:1.4; color:var(--plum); text-decoration:none; }
  .sidebar-related-title:hover { color:var(--pink-deep); }
  .post-tag { font-size:12px; font-family:'DM Sans',sans-serif; font-weight:600; text-transform:uppercase; letter-spacing:1.5px; background:var(--blush); color:var(--muted-pp); border-radius:50px; padding:6px 14px; text-decoration:none; display:inline-block; }
  .post-tag:hover { background:var(--pink-tint); color:var(--pink-deep); }
  html[data-theme="dark"] .ppl-post-content { color:rgba(255,255,255,0.88); }
  html[data-theme="dark"] .ppl-post-content h2, html[data-theme="dark"] .ppl-post-content h3 { color:#fff; }
  html[data-theme="dark"] .ppl-post-content blockquote { color:rgba(255,255,255,0.7); }
  html[data-theme="dark"] .sidebar-widget { background:rgba(255,255,255,0.06); border-color:rgba(255,255,255,0.1); }
  html[data-theme="dark"] .sidebar-cat-link { color:rgba(255,255,255,0.8); border-color:rgba(255,255,255,0.1); }
  html[data-theme="dark"] .sidebar-cat-link:hover { color:var(--pink-light); }
  html[data-theme="dark"] .sidebar-related-title { color:rgba(255,255,255,0.85); }
  html[data-theme="dark"] .sidebar-related-title:hover { color:var(--pink-light); }
  html[data-theme="dark"] .post-tag { background:rgba(255,255,255,0.08); color:rgba(255,255,255,0.55); }
  html[data-theme="dark"] .post-tag:hover { background:rgba(196,54,112,0.2); color:var(--pink-light); }
</style>
</head>
<body class="bg-white ppl-blog-single">

<?php get_template_part( 'partials/ppl-nav' ); ?>

<main>

  <!-- ── Post hero header ──────────────────────────────────────────────────── -->
  <header class="bg-blush" style="padding: 80px 0 60px;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">

          <!-- Breadcrumb + category + date -->
          <div class="d-flex align-items-center justify-content-center gap-2 mb-5">
            <?php if ( $primary_cat ) : ?>
              <a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>"
                 class="text-rose text-decoration-none fw-semibold text-uppercase ls-wide"
                 style="font-size: 12px; font-family: 'DM Sans', sans-serif; letter-spacing: 2px;">
                <?php echo esc_html( $primary_cat->name ); ?>
              </a>
              <span class="d-block" style="width: 32px; height: 1px; background: var(--blush-mid);"></span>
            <?php endif; ?>
            <span class="text-muted-pp" style="font-size: 13px; font-family: 'DM Sans', sans-serif;">
              <?php echo get_the_date( 'M j, Y' ); ?>
            </span>
          </div>

          <!-- Title -->
          <h1 class="text-plum fw-bold ls-tight mb-5"
              style="font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5.5vw, 3.75rem); line-height: 1.1; letter-spacing: -0.02em;">
            <?php the_title(); ?>
          </h1>

          <!-- Excerpt / subtext -->
          <?php if ( has_excerpt() ) : ?>
          <p class="text-muted-pp mx-auto mb-5" style="font-size: 18px; line-height: 1.7; max-width: 640px; font-family: 'Literata', serif;">
            <?php the_excerpt(); ?>
          </p>
          <?php endif; ?>

          <!-- Author meta row -->
          <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
            <img src="<?php echo esc_url( $author_photo ); ?>"
                 alt="<?php echo esc_attr( $author_name ); ?>"
                 style="width: 44px; height: 44px; object-fit: cover; object-position: top; border-radius: 50%; border: 2px solid var(--pink-tint-mid);" />
            <span class="text-plum fw-semibold" style="font-size: 14px; font-family: 'DM Sans', sans-serif;">
              <?php echo esc_html( $author_name ); ?>
            </span>
            <span class="text-muted-pp" style="font-size: 13px;">&middot;</span>
            <span class="text-muted-pp" style="font-size: 13px; font-family: 'DM Sans', sans-serif;">
              <i class="bi bi-clock me-1"></i><?php echo esc_html( $read_time ); ?> min read
            </span>
          </div>

        </div>
      </div>
    </div>
  </header>


  <!-- ── Full-bleed hero image ─────────────────────────────────────────────── -->
  <?php if ( has_post_thumbnail() ) : ?>
  <div style="overflow: hidden;">
    <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'full' ) ); ?>"
         alt="<?php the_title_attribute(); ?>"
         class="post-hero-full-bleed" />
  </div>
  <?php endif; ?>


  <!-- ── Article + Sidebar ─────────────────────────────────────────────────── -->
  <section class="bg-white" style="padding: 72px 0 96px;">
    <div class="container">
      <div class="row g-5">

        <!-- ── Article body ───────────────────────────────────────────────── -->
        <div class="col-lg-8">
          <article class="ppl-post-content">
            <?php the_content(); ?>
          </article>


          <!-- ── In-article CTA block ────────────────────────────────────── -->
          <?php if ( $show_cta ) : ?>
          <div class="my-5 p-5 rounded-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-4"
               style="background: var(--pink-tint); border: 1px solid var(--pink-tint-mid);">
            <div>
              <?php if ( $cta_title ) : ?>
                <h4 class="text-plum fw-bold mb-1" style="font-family: 'Playfair Display', serif; font-size: 1.3rem;">
                  <?php echo esc_html( $cta_title ); ?>
                </h4>
              <?php endif; ?>
              <?php if ( $cta_body ) : ?>
                <p class="text-muted-pp mb-0 body-sm"><?php echo esc_html( $cta_body ); ?></p>
              <?php endif; ?>
            </div>
            <?php if ( $cta_btn && $cta_url ) : ?>
            <a href="<?php echo esc_url( $cta_url ); ?>"
               class="btn btn-plum rounded-3 px-4 py-3 fw-semibold flex-shrink-0"
               style="font-size: 14px; white-space: nowrap;">
              <?php echo esc_html( $cta_btn ); ?>
            </a>
            <?php elseif ( $cta_btn ) : ?>
            <button class="btn btn-plum rounded-3 px-4 py-3 fw-semibold flex-shrink-0" style="font-size: 14px;">
              <?php echo esc_html( $cta_btn ); ?>
            </button>
            <?php endif; ?>
          </div>
          <?php endif; ?>


          <!-- ── Tags ───────────────────────────────────────────────────── -->
          <?php if ( $tags ) : ?>
          <div class="d-flex flex-wrap gap-2 mt-5 pt-4" style="border-top: 1px solid var(--blush-mid);">
            <span class="text-muted-pp body-xs fw-semibold me-1" style="align-self: center; font-size: 11px; text-transform: uppercase; letter-spacing: 2px;">Tagged</span>
            <?php foreach ( $tags as $tag ) : ?>
              <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="post-tag">
                <?php echo esc_html( $tag->name ); ?>
              </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>


          <!-- ── Social share ────────────────────────────────────────────── -->
          <div class="d-flex align-items-center gap-3 mt-4 pt-4" style="border-top: 1px solid var(--blush-mid);">
            <span class="text-muted-pp fw-semibold text-uppercase ls-wide" style="font-size: 11px; font-family: 'DM Sans', sans-serif;">Share</span>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>"
               target="_blank" rel="noopener noreferrer" aria-label="Share on X"
               class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none ppl-social"
               style="width: 36px; height: 36px;">
              <i class="bi bi-twitter-x"></i>
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( get_permalink() ); ?>"
               target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn"
               class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none ppl-social"
               style="width: 36px; height: 36px;">
              <i class="bi bi-linkedin"></i>
            </a>
            <a href="mailto:?subject=<?php echo urlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>"
               aria-label="Share via email"
               class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none ppl-social"
               style="width: 36px; height: 36px;">
              <i class="bi bi-envelope"></i>
            </a>
            <button onclick="navigator.clipboard.writeText('<?php echo esc_js( get_permalink() ); ?>'); this.innerHTML='<i class=\'bi bi-check-lg\'></i>'; setTimeout(()=>this.innerHTML='<i class=\'bi bi-link-45deg\'></i>',1800);"
                    aria-label="Copy link"
                    class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum border-0 ppl-social"
                    style="width: 36px; height: 36px; cursor: pointer;">
              <i class="bi bi-link-45deg"></i>
            </button>
          </div>


          <!-- ── Author bio card ─────────────────────────────────────────── -->
          <div class="rounded-4 p-4 mt-5 d-flex align-items-start gap-4" style="background: var(--plum);">
            <img src="<?php echo esc_url( $author_photo ); ?>"
                 alt="<?php echo esc_attr( $author_name ); ?>"
                 style="width: 72px; height: 72px; object-fit: cover; object-position: top; border-radius: 50%; border: 3px solid rgba(255,137,197,0.3); flex-shrink: 0;" />
            <div>
              <p class="text-pink fw-semibold text-uppercase ls-wide mb-1 eyebrow">Written by</p>
              <h4 class="text-white fw-bold mb-1" style="font-size: 1.1rem;">
                <?php echo esc_html( $author_name ); ?>
              </h4>
              <p class="mb-2" style="font-size: 12px; color: rgba(255,137,197,0.75); font-family: 'DM Sans', sans-serif; text-transform: uppercase; letter-spacing: 1.5px;">
                <?php echo wp_kses_post( $author_role ); ?>
              </p>
              <?php if ( $author_bio ) : ?>
              <p class="text-light-75 mb-3" style="font-size: 14px; line-height: 1.65;">
                <?php echo esc_html( $author_bio ); ?>
              </p>
              <?php endif; ?>
              <a href="<?php echo esc_url( $author_url ); ?>"
                 class="text-decoration-none fw-semibold"
                 style="font-size: 13px; color: var(--pink-light);">
                Read full bio <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>


          <!-- ── Prev / Next navigation ──────────────────────────────────── -->
          <?php if ( $prev_post || $next_post ) : ?>
          <div class="row g-3 mt-5 pt-4" style="border-top: 1px solid var(--blush-mid);">
            <div class="col-6">
              <?php if ( $prev_post ) : ?>
              <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"
                 class="d-block bg-blush rounded-4 p-4 text-decoration-none h-100">
                <p class="text-muted-pp mb-2 eyebrow fw-semibold text-uppercase ls-wide" style="font-size: 11px;">
                  <i class="bi bi-arrow-left me-1"></i> Previous
                </p>
                <p class="text-plum fw-semibold mb-0" style="font-size: 14px; line-height: 1.4;">
                  <?php echo esc_html( $prev_post->post_title ); ?>
                </p>
              </a>
              <?php endif; ?>
            </div>
            <div class="col-6">
              <?php if ( $next_post ) : ?>
              <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"
                 class="d-block bg-blush rounded-4 p-4 text-decoration-none h-100 text-end">
                <p class="text-muted-pp mb-2 eyebrow fw-semibold text-uppercase ls-wide" style="font-size: 11px;">
                  Next <i class="bi bi-arrow-right ms-1"></i>
                </p>
                <p class="text-plum fw-semibold mb-0" style="font-size: 14px; line-height: 1.4;">
                  <?php echo esc_html( $next_post->post_title ); ?>
                </p>
              </a>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>

        </div><!-- /col-lg-8 -->


        <!-- ── Sidebar ─────────────────────────────────────────────────── -->
        <aside class="col-lg-4">
          <div style="position: sticky; top: 100px;">

            <!-- Author card -->
            <div class="sidebar-widget">
              <p class="sidebar-label">About the Author</p>
              <div class="d-flex align-items-center gap-3 mb-3">
                <img src="<?php echo esc_url( $author_photo ); ?>"
                     alt="<?php echo esc_attr( $author_name ); ?>"
                     style="width: 56px; height: 56px; object-fit: cover; object-position: top; border-radius: 50%; border: 2px solid var(--pink-tint-mid);" />
                <div>
                  <p class="text-plum fw-semibold mb-0" style="font-size: 14px;">
                    <?php echo esc_html( $author_name ); ?>
                  </p>
                  <p class="text-muted-pp mb-0" style="font-size: 12px;">
                    <?php echo wp_kses_post( $author_role ); ?>
                  </p>
                </div>
              </div>
              <?php if ( $author_bio ) : ?>
              <p class="text-muted-pp mb-3" style="font-size: 13px; line-height: 1.6;">
                <?php echo esc_html( wp_trim_words( $author_bio, 22 ) ); ?>
              </p>
              <?php endif; ?>
              <a href="<?php echo esc_url( $author_url ); ?>"
                 class="btn btn-ghost-light rounded-3 w-100 py-2 fw-semibold"
                 style="font-size: 13px;">
                Full Bio <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>

            <!-- Newsletter — Bootstrap input-group -->
            <div class="sidebar-widget" style="background: var(--plum); border-color: transparent;">
              <p class="text-pink fw-semibold text-uppercase ls-wide mb-1 eyebrow">Stay in the Loop</p>
              <h4 class="text-white fw-bold mb-2" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">
                The Pinkprint Weekly
              </h4>
              <p class="text-light-75 mb-4" style="font-size: 13px; line-height: 1.55;">
                Practical legal insights delivered to your inbox.
              </p>
              <?php
              // If using Mailchimp for WP: echo do_shortcode('[mc4wp_form id="XXX"]');
              // Bootstrap input-group fallback:
              ?>
              <div class="input-group">
                <input type="email" class="form-control border-0 py-3 px-3"
                  placeholder="Email address"
                  style="background: rgba(255,255,255,0.1); color: #fff; font-size: 14px; font-family: 'DM Sans', sans-serif; border-radius: 8px 0 0 8px !important;"
                  aria-label="Email address" />
                <button class="btn btn-rose px-4 fw-semibold"
                  style="border-radius: 0 8px 8px 0 !important; font-size: 14px; font-family: 'DM Sans', sans-serif; white-space: nowrap;">
                  Subscribe
                </button>
              </div>
              <p class="text-light-50 mt-2" style="font-size: 11px;">No spam. Unsubscribe anytime.</p>
            </div>

            <!-- Browse by category -->
            <?php
            $all_cats = get_categories( [ 'hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC' ] );
            if ( $all_cats ) :
            ?>
            <div class="sidebar-widget">
              <p class="sidebar-label">Browse by Category</p>
              <?php foreach ( $all_cats as $cat ) : ?>
                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                   class="sidebar-cat-link">
                  <?php echo esc_html( $cat->name ); ?>
                  <span class="bg-pink-tint text-rose rounded-pill px-2 py-1 fw-semibold" style="font-size: 10px;">
                    <?php echo esc_html( $cat->count ); ?>
                  </span>
                </a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Related articles -->
            <?php if ( $related->have_posts() ) : ?>
            <div class="sidebar-widget">
              <p class="sidebar-label">Related Stories</p>
              <div class="d-flex flex-column gap-3">
                <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="d-flex align-items-start gap-3 text-decoration-none">
                  <?php if ( has_post_thumbnail() ) : ?>
                    <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'thumbnail' ) ); ?>"
                         alt=""
                         class="sidebar-related-img" />
                  <?php else : ?>
                    <div class="sidebar-related-img-placeholder"></div>
                  <?php endif; ?>
                  <div>
                    <?php
                    $r_cats = get_the_category();
                    if ( $r_cats ) :
                    ?>
                    <span class="text-rose" style="font-size: 10px; font-family: 'DM Sans', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">
                      <?php echo esc_html( $r_cats[0]->name ); ?>
                    </span>
                    <?php endif; ?>
                    <span class="sidebar-related-title d-block mt-1">
                      <?php the_title(); ?>
                    </span>
                  </div>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
              </div>
            </div>
            <?php endif; ?>

          </div>
        </aside><!-- /sidebar -->

      </div><!-- /row -->
    </div>
  </section>


  <!-- ── More from the blog ─────────────────────────────────────────────────── -->
  <?php if ( $related->post_count > 0 ) : ?>
  <section class="bg-blush section-pad">
    <div class="container">
      <div class="d-flex align-items-end justify-content-between mb-5">
        <div>
          <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow">Keep Reading</p>
          <h2 class="text-plum ls-tight fw-bold mb-0" style="font-family: 'Playfair Display', serif; font-size: clamp(1.75rem, 3vw, 2.5rem);">
            More from the Blog
          </h2>
        </div>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>"
           class="btn btn-ghost-light rounded-3 px-4 py-2 fw-semibold d-none d-md-inline-flex"
           style="font-size: 13px;">
          View All Posts <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="row g-4">
        <?php
        $related->rewind_posts();
        $shown = 0;
        while ( $related->have_posts() && $shown < 4 ) : $related->the_post(); $shown++;
          $r_cats  = get_the_category();
          $r_cname = $r_cats ? $r_cats[0]->name : '';
          $r_thumb = get_the_post_thumbnail_url( null, 'medium_large' );
        ?>
        <div class="col-sm-6 col-lg-3">
          <div class="card h-100 border card-lift rounded-4 overflow-hidden" style="border-color: var(--blush-mid) !important;">
            <?php if ( $r_thumb ) : ?>
            <img src="<?php echo esc_url( $r_thumb ); ?>" alt="" class="card-img-top" style="height: 180px; object-fit: cover;" />
            <?php endif; ?>
            <div class="card-body d-flex flex-column p-4">
              <?php if ( $r_cname ) : ?>
              <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 stage-tag"><?php echo esc_html( $r_cname ); ?></p>
              <?php endif; ?>
              <h4 class="card-title text-plum fw-bold mb-3 flex-grow-1" style="font-size: 1.05rem; line-height: 1.35;"><?php the_title(); ?></h4>
              <a href="<?php the_permalink(); ?>" class="card-link d-inline-flex align-items-center gap-1 mt-auto" style="font-size: 13px;">
                Read <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

      <div class="text-center mt-4 d-md-none">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>"
           class="btn btn-ghost-light rounded-3 px-4 py-3 fw-semibold" style="font-size: 14px;">
          View All Posts
        </a>
      </div>
    </div>
  </section>
  <?php endif; ?>

</main>

<?php get_template_part( 'partials/ppl-footer' ); ?>
