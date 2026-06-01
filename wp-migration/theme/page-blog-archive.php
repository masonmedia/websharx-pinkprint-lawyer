<?php
/**
 * Template Name: Blog Archive
 */

// Fetch all blog categories for filter pills
$blog_cats = get_categories( [ 'hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC' ] );

// Fetch all posts (paged)
$paged = get_query_var( 'paged' ) ?: 1;
$posts_query = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
] );

// Featured: first sticky post, or just the first result
$sticky_ids     = get_option( 'sticky_posts' );
$featured_post  = null;
$remaining_posts = [];

if ( $posts_query->have_posts() ) {
    $all_posts = $posts_query->posts;
    foreach ( $all_posts as $p ) {
        if ( ! $featured_post && in_array( $p->ID, $sticky_ids, true ) ) {
            $featured_post = $p;
        } else {
            $remaining_posts[] = $p;
        }
    }
    if ( ! $featured_post ) {
        $featured_post   = array_shift( $all_posts );
        $remaining_posts = $all_posts;
    }
}
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<style>
  .post-img-wrap { border-radius: 12px; overflow: hidden; margin-bottom: 20px; background: var(--blush-mid); }
  .post-img      { width: 100%; height: 180px; object-fit: cover; display: block; }
  .post-meta     { font-size: 12px; font-family: 'DM Sans', sans-serif; color: var(--muted-pp); }
  .post-title    { font-size: 1.1rem; line-height: 1.35; }
  .filter-bar    { padding: 24px 0; background: #fff; position: sticky; top: 73px; z-index: 100; border-bottom: 1px solid var(--blush-mid); }
  .filter-pill   {
    font-size: 12px; font-family: 'DM Sans', sans-serif; font-weight: 600;
    padding: 8px 18px; border-radius: 50px;
    border: 1.5px solid var(--blush-mid);
    background: transparent; color: var(--muted-pp);
    cursor: pointer; transition: all 0.15s ease;
    text-transform: uppercase; letter-spacing: 1.5px;
  }
  .filter-pill:hover  { border-color: var(--pink-deep); color: var(--pink-deep); }
  .filter-pill.active { background: var(--pink-deep); border-color: var(--pink-deep); color: #fff; }
  .page-link-pp {
    font-size: 14px; font-weight: 600; font-family: 'DM Sans', sans-serif;
    color: var(--muted-pp); border: 1.5px solid var(--blush-mid);
    border-radius: 8px !important; padding: 10px 16px;
    background: transparent; margin: 0 3px;
    transition: all 0.15s ease;
  }
  .page-link-pp:hover, .page-link-pp.active { background: var(--pink-deep); border-color: var(--pink-deep); color: #fff; }
  .card-h-sm { font-size: 1.15rem; }
  .stage-tag { font-size: 11px; font-family: 'DM Sans', sans-serif; }
</style>
</head>
<body class="bg-white ppl-blog-archive">
<?php get_template_part( 'partials/ppl-nav' ); ?>


<!-- HERO -->
<section class="bg-blush" style="padding: 96px 0 80px;">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-7">
        <span class="d-inline-flex align-items-center gap-2 bg-pink-tint text-rose rounded-pill px-3 py-2 fw-semibold mb-4 eyebrow">
          <i class="bi bi-journals"></i> Resources &amp; Insights
        </span>
        <h1 class="display-4 fw-bold text-plum ls-tight mb-4">The Pinkprint Blog</h1>
        <p class="text-muted-pp body-lead mb-5">Practical guidance, honest perspective, and evidence-informed strategy — for every stage of the legal journey.</p>
        <div class="d-flex justify-content-center">
          <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="input-group" style="max-width:440px;">
            <input type="search" name="s" class="form-control border-blush rounded-start-3 py-3 px-4 body-sm" placeholder="Search articles…" value="<?php echo esc_attr( get_search_query() ); ?>" style="border-color:var(--blush-mid); font-family:'DM Sans',sans-serif;" />
            <button type="submit" class="btn btn-rose rounded-end-3 px-4 fw-semibold" style="font-size:14px;">Search</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- FILTER BAR -->
<div class="filter-bar">
  <div class="container">
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
      <button class="filter-pill active" data-filter="all">All Posts</button>
      <?php foreach ( $blog_cats as $cat ) : ?>
        <button class="filter-pill" data-filter="<?php echo esc_attr( $cat->slug ); ?>">
          <?php echo esc_html( $cat->name ); ?>
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<!-- POST GRID -->
<section class="bg-white section-pad">
  <div class="container">

    <?php if ( $featured_post ) :
      setup_postdata( $featured_post );
      $feat_cats     = get_the_category( $featured_post->ID );
      $feat_cat_slug = $feat_cats ? $feat_cats[0]->slug : '';
      $feat_cat_name = $feat_cats ? $feat_cats[0]->name : '';
      $feat_thumb    = get_the_post_thumbnail_url( $featured_post->ID, 'large' );
      $feat_author   = get_the_author_meta( 'display_name', $featured_post->post_author );
      $feat_date     = get_the_date( 'M j, Y', $featured_post );
      $read_time     = max( 1, (int) ( str_word_count( strip_tags( $featured_post->post_content ) ) / 200 ) );
    ?>

    <!-- Featured post -->
    <div class="row g-5 align-items-center mb-5 pb-5" style="border-bottom:1px solid var(--blush-mid);" data-post-cat="<?php echo esc_attr( $feat_cat_slug ); ?>">
      <div class="col-lg-6">
        <div class="rounded-4 overflow-hidden bg-blush-mid" style="height:400px;">
          <?php if ( $feat_thumb ) : ?>
            <img src="<?php echo esc_url( $feat_thumb ); ?>" alt="<?php echo esc_attr( $featured_post->post_title ); ?>" style="width:100%;height:100%;object-fit:cover;display:block;" />
          <?php else : ?>
            <div style="width:100%;height:100%;background:var(--blush-mid);"></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <?php if ( $feat_cat_name ) : ?>
        <span class="d-inline-flex align-items-center gap-2 bg-pink-tint text-rose rounded-pill px-3 py-2 fw-semibold mb-4 eyebrow">
          <i class="bi bi-bookmark-fill"></i> Featured &middot; <?php echo esc_html( $feat_cat_name ); ?>
        </span>
        <?php endif; ?>
        <h2 class="text-plum ls-tight fw-bold mb-3" style="font-size:2rem;"><?php echo esc_html( $featured_post->post_title ); ?></h2>
        <p class="text-muted-pp body-md mb-4"><?php echo esc_html( wp_trim_words( $featured_post->post_excerpt ?: $featured_post->post_content, 30 ) ); ?></p>
        <div class="d-flex align-items-center gap-3 mb-4">
          <?php $feat_avatar = get_avatar_url( $featured_post->post_author, [ 'size' => 36 ] ); ?>
          <img src="<?php echo esc_url( $feat_avatar ); ?>" alt="<?php echo esc_attr( $feat_author ); ?>" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;" />
          <div>
            <span class="text-plum fw-semibold" style="font-size:13px;"><?php echo esc_html( $feat_author ); ?></span>
            <span class="text-muted-pp mx-2" style="font-size:13px;">&middot;</span>
            <span class="text-muted-pp" style="font-size:13px;"><?php echo esc_html( $feat_date ); ?> &middot; <?php echo esc_html( $read_time ); ?> min read</span>
          </div>
        </div>
        <a href="<?php echo esc_url( get_permalink( $featured_post->ID ) ); ?>" class="btn btn-plum rounded-3 px-4 py-3 fw-semibold" style="font-size:14px;">Read the Article <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>

    <?php wp_reset_postdata(); endif; ?>

    <!-- Grid header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <p class="text-muted-pp mb-0 body-sm">
        <span id="post-count"><?php echo esc_html( count( $remaining_posts ) ); ?></span> articles
      </p>
    </div>

    <!-- 4-column grid -->
    <div class="row g-4" id="post-grid">
      <?php foreach ( $remaining_posts as $post ) :
        setup_postdata( $post );
        $cats      = get_the_category( $post->ID );
        $cat_slug  = $cats ? $cats[0]->slug : '';
        $cat_name  = $cats ? $cats[0]->name : '';
        $thumb     = get_the_post_thumbnail_url( $post->ID, 'medium' );
        $date      = get_the_date( 'M j, Y', $post );
        $rt        = max( 1, (int) ( str_word_count( strip_tags( $post->post_content ) ) / 200 ) );
      ?>
      <div class="col-sm-6 col-lg-3" data-post-cat="<?php echo esc_attr( $cat_slug ); ?>">
        <div class="bg-blush rounded-4 p-4 h-100 d-flex flex-column card-lift">
          <div class="post-img-wrap">
            <?php if ( $thumb ) : ?>
              <img src="<?php echo esc_url( $thumb ); ?>" alt="" class="post-img" />
            <?php else : ?>
              <div class="post-img bg-blush-mid"></div>
            <?php endif; ?>
          </div>
          <?php if ( $cat_name ) : ?>
          <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 stage-tag"><?php echo esc_html( $cat_name ); ?></p>
          <?php endif; ?>
          <h4 class="text-plum mb-3 fw-bold post-title"><?php echo esc_html( $post->post_title ); ?></h4>
          <p class="text-muted-pp mb-4 body-xs flex-grow-1"><?php echo esc_html( wp_trim_words( $post->post_excerpt ?: $post->post_content, 20 ) ); ?></p>
          <div class="d-flex align-items-center justify-content-between mt-auto pt-2" style="border-top:1px solid var(--blush-mid);">
            <span class="post-meta"><?php echo esc_html( $date ); ?> &middot; <?php echo esc_html( $rt ); ?> min</span>
            <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="card-link d-inline-flex align-items-center gap-1">Read <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>

    <!-- Pagination -->
    <?php if ( $posts_query->max_num_pages > 1 ) : ?>
    <div class="d-flex justify-content-center align-items-center gap-1 mt-5 pt-3">
      <?php
      echo paginate_links( [
        'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'format'    => '?paged=%#%',
        'current'   => $paged,
        'total'     => $posts_query->max_num_pages,
        'prev_text' => '<i class="bi bi-chevron-left"></i>',
        'next_text' => '<i class="bi bi-chevron-right"></i>',
        'type'      => 'list',
        'before_page_number' => '',
      ] );
      ?>
    </div>
    <?php endif; ?>

  </div>
</section>


<!-- NEWSLETTER CTA -->
<section class="bg-plum section-pad">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-6">
        <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow">Stay in the Loop</p>
        <h2 class="text-white ls-tight fw-bold display-6 mb-4">New articles, every week.</h2>
        <p class="text-light-75 body-md mb-5">No noise. Just practical guidance for every stage of your legal journey — delivered to your inbox.</p>
        <div class="d-flex">
          <input type="email" class="flex-grow-1" placeholder="Your email address"
            style="background:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.15);border-right:none;border-radius:10px 0 0 10px;padding:14px 20px;font-size:15px;color:#fff;font-family:'DM Sans',sans-serif;" />
          <button style="background:var(--pink-deep);color:#fff;font-size:15px;font-weight:600;border-radius:0 10px 10px 0;padding:14px 24px;border:none;white-space:nowrap;font-family:'DM Sans',sans-serif;">Subscribe</button>
        </div>
        <p class="text-light-60 mt-3" style="font-size:12px;">No spam. Unsubscribe at any time.</p>
      </div>
    </div>
  </div>
</section>


<?php get_template_part( 'partials/ppl-footer' ); ?>

<script>
// Category filter
const pills  = document.querySelectorAll('.filter-pill');
const cards  = document.querySelectorAll('#post-grid [data-post-cat]');
const countEl = document.getElementById('post-count');

pills.forEach(pill => {
  pill.addEventListener('click', () => {
    pills.forEach(p => p.classList.remove('active'));
    pill.classList.add('active');
    const cat = pill.dataset.filter;
    let count = 0;
    cards.forEach(card => {
      const show = cat === 'all' || card.dataset.postCat === cat;
      card.style.display = show ? '' : 'none';
      if (show) count++;
    });
    countEl.textContent = count;
  });
});
</script>
