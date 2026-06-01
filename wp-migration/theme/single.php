<?php
/**
 * Single post template.
 */

// Related posts: same category, exclude current
$cats        = get_the_category();
$cat_ids     = wp_list_pluck( $cats, 'term_id' );
$related     = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 4,
    'post__not_in'   => [ get_the_ID() ],
    'category__in'   => $cat_ids,
    'orderby'        => 'rand',
] );

$author_id     = get_the_author_meta( 'ID' );
$author_name   = get_the_author_meta( 'display_name' );
$author_bio    = get_the_author_meta( 'description' );
$author_avatar = get_avatar_url( $author_id, [ 'size' => 72 ] );
$read_time     = max( 1, (int) ( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );
$primary_cat   = $cats ? $cats[0] : null;
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<style>
  .post-hero     { padding: 80px 0 64px; }
  .post-hero-img { width: 100%; max-height: 520px; object-fit: cover; object-position: center top; display: block; border-radius: 16px; }
  .post-content  { font-size: 17px; line-height: 1.85; color: var(--plum); }
  .post-content p  { margin-bottom: 1.5rem; }
  .post-content h2 { font-size: 1.6rem; margin-top: 2.5rem; margin-bottom: 1rem; color: var(--plum); }
  .post-content h3 { font-size: 1.25rem; margin-top: 2rem; margin-bottom: 0.75rem; color: var(--plum); }
  .post-content ul, .post-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
  .post-content li { margin-bottom: 0.5rem; }
  .post-content a  { color: var(--pink-deep); text-decoration: underline; text-underline-offset: 3px; }
  .post-content blockquote {
    margin: 2rem 0; padding: 1.5rem 2rem;
    background: var(--blush); border-radius: 12px;
    font-style: italic; font-size: 1.1rem; line-height: 1.7;
    color: var(--plum-soft); font-family: 'Playfair Display', serif;
  }
  .post-content blockquote p { margin-bottom: 0; }
  .post-content .drop-cap::first-letter {
    font-family: 'Playfair Display', serif; font-size: 4.5rem; font-weight: 700;
    float: left; line-height: 0.75; margin-right: 8px; margin-top: 6px;
    color: var(--pink-deep);
  }
  .sidebar-widget       { background: var(--blush); border-radius: 16px; padding: 24px; margin-bottom: 24px; }
  .sidebar-widget-title { font-size: 11px; font-family: 'DM Sans', sans-serif; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--muted-pp); margin-bottom: 16px; }
  .sidebar-category-link { display: flex; align-items: center; justify-content: space-between; text-decoration: none; color: var(--plum); font-size: 14px; font-weight: 500; padding: 8px 0; border-bottom: 1px solid var(--blush-mid); }
  .sidebar-category-link:hover { color: var(--pink-deep); }
  .sidebar-category-link:last-child { border-bottom: none; padding-bottom: 0; }
  .sidebar-related-img   { width: 64px; height: 64px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
  .sidebar-related-title { font-size: 13px; font-weight: 600; line-height: 1.35; color: var(--plum); text-decoration: none; }
  .sidebar-related-title:hover { color: var(--pink-deep); }
  .post-tag       { font-size: 12px; font-family: 'DM Sans', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; background: var(--blush); color: var(--muted-pp); border-radius: 50px; padding: 6px 14px; text-decoration: none; }
  .post-tag:hover { background: var(--pink-tint); color: var(--pink-deep); }
  .author-card    { background: var(--plum); border-radius: 16px; padding: 32px; }
  .author-avatar  { width: 72px; height: 72px; object-fit: cover; object-position: top center; border-radius: 50%; border: 3px solid rgba(255,137,197,0.3); }
  .stage-tag { font-size: 11px; font-family: 'DM Sans', sans-serif; }
</style>
</head>
<body class="bg-white ppl-single">
<?php get_template_part( 'partials/ppl-nav' ); ?>


<!-- POST HERO -->
<section class="bg-blush post-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center justify-content-center gap-2 mb-4 text-muted-pp" style="font-size:13px; font-family:'DM Sans',sans-serif;">
          <a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="text-muted-pp text-decoration-none">Blog</a>
          <i class="bi bi-chevron-right" style="font-size:10px;"></i>
          <?php if ( $primary_cat ) : ?>
            <a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="text-rose text-decoration-none fw-semibold"><?php echo esc_html( $primary_cat->name ); ?></a>
          <?php endif; ?>
        </div>
        <h1 class="display-5 fw-bold text-plum ls-tight mb-4"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?>
          <p class="text-muted-pp body-lead mb-5"><?php the_excerpt(); ?></p>
        <?php endif; ?>
        <!-- Post meta -->
        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
          <div class="d-flex align-items-center gap-2">
            <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" style="width:40px;height:40px;object-fit:cover;object-position:top;border-radius:50%;border:2px solid var(--pink-tint-mid);" />
            <span class="text-plum fw-semibold" style="font-size:14px;"><?php echo esc_html( $author_name ); ?></span>
          </div>
          <span class="text-muted-pp" style="font-size:13px;">&middot;</span>
          <span class="text-muted-pp" style="font-size:13px;"><i class="bi bi-calendar3 me-1"></i><?php the_date( 'M j, Y' ); ?></span>
          <span class="text-muted-pp" style="font-size:13px;">&middot;</span>
          <span class="text-muted-pp" style="font-size:13px;"><i class="bi bi-clock me-1"></i><?php echo esc_html( $read_time ); ?> min read</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED IMAGE -->
<?php if ( has_post_thumbnail() ) : ?>
<div class="container" style="padding-top:40px;">
  <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'full' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="post-hero-img" />
</div>
<?php endif; ?>


<!-- CONTENT + SIDEBAR -->
<section class="bg-white" style="padding: 64px 0 96px;">
  <div class="container">
    <div class="row g-5">

      <!-- MAIN CONTENT -->
      <div class="col-lg-8">
        <article class="post-content">
          <?php the_content(); ?>
        </article>

        <!-- Tags -->
        <?php $tags = get_the_tags(); if ( $tags ) : ?>
        <div class="d-flex flex-wrap gap-2 mt-5 pt-4" style="border-top:1px solid var(--blush-mid);">
          <span class="text-muted-pp me-1 body-xs fw-semibold" style="align-self:center;">Tagged:</span>
          <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="post-tag"><?php echo esc_html( $tag->name ); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Share -->
        <div class="d-flex align-items-center gap-3 mt-4 pt-4" style="border-top:1px solid var(--blush-mid);">
          <span class="text-muted-pp body-xs fw-semibold text-uppercase ls-wide" style="font-size:11px;">Share</span>
          <a href="https://www.instagram.com/" class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none" style="width:36px;height:36px;" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( get_permalink() ); ?>" class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none" style="width:36px;height:36px;" aria-label="Share on LinkedIn" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a>
          <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none" style="width:36px;height:36px;" aria-label="Share on X" target="_blank" rel="noopener noreferrer"><i class="bi bi-twitter-x"></i></a>
          <button onclick="navigator.clipboard.writeText('<?php echo esc_js( get_permalink() ); ?>'); this.title='Copied!';" class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum border-0" style="width:36px;height:36px;cursor:pointer;" aria-label="Copy link"><i class="bi bi-link-45deg"></i></button>
        </div>

        <!-- Author bio -->
        <div class="author-card mt-5">
          <div class="d-flex align-items-start gap-4">
            <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="author-avatar" />
            <div>
              <p class="text-pink fw-semibold text-uppercase ls-wide mb-1 eyebrow">Written by</p>
              <h4 class="text-white fw-bold mb-2" style="font-size:1.1rem;"><?php echo esc_html( $author_name ); ?></h4>
              <?php if ( $author_bio ) : ?>
                <p class="text-light-75 mb-3" style="font-size:14px;line-height:1.6;"><?php echo esc_html( $author_bio ); ?></p>
              <?php endif; ?>
              <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="text-decoration-none fw-semibold" style="font-size:13px;color:var(--pink-light);">Read full bio <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
        </div>

        <!-- Prev / Next navigation -->
        <?php
        $prev = get_previous_post();
        $next = get_next_post();
        if ( $prev || $next ) :
        ?>
        <div class="row g-3 mt-5">
          <div class="col-6">
            <?php if ( $prev ) : ?>
            <a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="d-block bg-blush rounded-4 p-4 text-decoration-none h-100">
              <p class="text-muted-pp mb-2 eyebrow fw-semibold text-uppercase ls-wide"><i class="bi bi-arrow-left me-1"></i> Previous</p>
              <p class="text-plum fw-semibold mb-0" style="font-size:14px;line-height:1.4;"><?php echo esc_html( $prev->post_title ); ?></p>
            </a>
            <?php endif; ?>
          </div>
          <div class="col-6">
            <?php if ( $next ) : ?>
            <a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="d-block bg-blush rounded-4 p-4 text-decoration-none h-100 text-end">
              <p class="text-muted-pp mb-2 eyebrow fw-semibold text-uppercase ls-wide">Next <i class="bi bi-arrow-right ms-1"></i></p>
              <p class="text-plum fw-semibold mb-0" style="font-size:14px;line-height:1.4;"><?php echo esc_html( $next->post_title ); ?></p>
            </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- /col-lg-8 -->


      <!-- SIDEBAR -->
      <div class="col-lg-4">
        <div style="position:sticky;top:100px;">

          <!-- Author / CTA -->
          <div class="sidebar-widget">
            <p class="sidebar-widget-title">About the Author</p>
            <div class="d-flex align-items-center gap-3 mb-3">
              <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" style="width:52px;height:52px;object-fit:cover;object-position:top;border-radius:50%;" />
              <div>
                <p class="text-plum fw-semibold mb-0" style="font-size:14px;"><?php echo esc_html( $author_name ); ?></p>
                <p class="text-muted-pp mb-0" style="font-size:12px;"><?php echo esc_html( get_the_author_meta( 'user_description', $author_id ) ?: 'Practicing Attorney &amp; Mentor' ); ?></p>
              </div>
            </div>
            <?php if ( $author_bio ) : ?>
              <p class="text-muted-pp mb-3" style="font-size:13px;line-height:1.6;"><?php echo esc_html( wp_trim_words( $author_bio, 20 ) ); ?></p>
            <?php endif; ?>
            <a href="#" class="btn btn-rose rounded-3 fw-semibold w-100 py-2" style="font-size:13px;">Book a 1-on-1 Session</a>
          </div>

          <!-- Categories -->
          <?php
          $all_cats = get_categories( [ 'hide_empty' => true ] );
          if ( $all_cats ) :
          ?>
          <div class="sidebar-widget">
            <p class="sidebar-widget-title">Browse by Category</p>
            <?php foreach ( $all_cats as $i => $cat ) : ?>
              <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="sidebar-category-link" <?php echo ( $i === count( $all_cats ) - 1 ) ? 'style="border-bottom:none;padding-bottom:0;"' : ''; ?>>
                <?php echo esc_html( $cat->name ); ?>
                <span class="bg-pink-tint text-rose rounded-pill px-2 py-1 fw-semibold" style="font-size:11px;"><?php echo esc_html( $cat->count ); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Related posts -->
          <?php if ( $related->have_posts() ) : ?>
          <div class="sidebar-widget">
            <p class="sidebar-widget-title">Related Articles</p>
            <div class="d-flex flex-column gap-3">
              <?php while ( $related->have_posts() ) : $related->the_post(); ?>
              <a href="<?php the_permalink(); ?>" class="d-flex align-items-start gap-3 text-decoration-none">
                <?php if ( has_post_thumbnail() ) : ?>
                  <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'thumbnail' ) ); ?>" alt="" class="sidebar-related-img" />
                <?php else : ?>
                  <div class="sidebar-related-img bg-blush-mid"></div>
                <?php endif; ?>
                <span class="sidebar-related-title"><?php the_title(); ?></span>
              </a>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- Newsletter -->
          <div class="sidebar-widget" style="background:var(--plum);">
            <p class="text-pink fw-semibold text-uppercase ls-wide mb-2 eyebrow">Stay in the Loop</p>
            <h4 class="text-white fw-bold mb-2" style="font-size:1.05rem;">New articles, weekly.</h4>
            <p class="text-light-75 mb-3" style="font-size:13px;line-height:1.55;">Practical guidance for every stage of the legal journey.</p>
            <input type="email" placeholder="Your email address" style="background:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.15);border-radius:8px;padding:12px 16px;font-size:14px;color:#fff;width:100%;font-family:'DM Sans',sans-serif;box-sizing:border-box;" />
            <button style="background:var(--pink-deep);color:#fff;font-size:14px;font-weight:600;border-radius:8px;padding:12px 16px;border:none;width:100%;margin-top:8px;font-family:'DM Sans',sans-serif;cursor:pointer;">Subscribe</button>
          </div>

        </div>
      </div><!-- /sidebar -->

    </div><!-- /row -->
  </div>
</section>


<!-- MORE FROM THE BLOG -->
<?php if ( $related->post_count > 0 ) : ?>
<section class="bg-blush section-pad">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between mb-5">
      <div>
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow">Keep Reading</p>
        <h2 class="text-plum ls-tight fw-bold display-6 mb-0">More from the Blog</h2>
      </div>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="btn btn-outline-plum rounded-3 px-4 py-3 fw-semibold d-none d-md-inline-flex" style="font-size:14px;">View All Posts</a>
    </div>
    <div class="row g-4">
      <?php
      $related->rewind_posts();
      $more_count = 0;
      while ( $related->have_posts() && $more_count < 4 ) : $related->the_post(); $more_count++;
        $r_cats  = get_the_category();
        $r_cname = $r_cats ? $r_cats[0]->name : '';
        $r_thumb = get_the_post_thumbnail_url( null, 'medium' );
      ?>
      <div class="col-sm-6 col-lg-3">
        <div class="bg-white rounded-4 p-4 h-100 d-flex flex-column card-lift">
          <?php if ( $r_thumb ) : ?>
          <div style="border-radius:12px;overflow:hidden;margin-bottom:20px;">
            <img src="<?php echo esc_url( $r_thumb ); ?>" alt="" style="width:100%;height:160px;object-fit:cover;display:block;" />
          </div>
          <?php endif; ?>
          <?php if ( $r_cname ) : ?>
          <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 stage-tag"><?php echo esc_html( $r_cname ); ?></p>
          <?php endif; ?>
          <h4 class="text-plum mb-3 fw-bold" style="font-size:1.05rem;line-height:1.35;"><?php the_title(); ?></h4>
          <a href="<?php the_permalink(); ?>" class="card-link d-inline-flex align-items-center gap-1 mt-auto" style="font-size:14px;">Read <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <div class="text-center mt-4 d-md-none">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="btn btn-outline-plum rounded-3 px-4 py-3 fw-semibold" style="font-size:14px;">View All Posts</a>
    </div>
  </div>
</section>
<?php endif; ?>


<?php get_template_part( 'partials/ppl-footer' ); ?>
