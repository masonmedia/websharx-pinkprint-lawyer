<?php
/**
 * Template Name: PPL Default
 *
 * General-purpose on-brand page for terms, privacy, accessibility, etc.
 * Hero text driven by meta fields; content from the block editor.
 *
 * Per-page meta fields (editable in the Page editor sidebar):
 *   ppl_pg_eyebrow  — small label above the title (e.g. "Legal")
 *   ppl_pg_subtext  — lead sentence below the title
 *
 * The featured image is used as the full-bleed banner beneath the hero header.
 */

$post_id  = get_the_ID();
$eyebrow  = get_post_meta( $post_id, 'ppl_pg_eyebrow', true );
$heading  = get_post_meta( $post_id, 'ppl_pg_heading', true );
$subtext  = get_post_meta( $post_id, 'ppl_pg_subtext', true );

get_template_part( 'partials/ppl-head' );
?>
<style>
  .ppl-default-content { font-size: 17px; line-height: 1.8; font-family: 'Literata', Georgia, serif; color: var(--plum); }
  .ppl-default-content p { margin-bottom: 1.5rem; }
  .ppl-default-content h2 { font-family: 'Playfair Display', serif; font-size: clamp(1.4rem, 2.5vw, 1.85rem); font-weight: 600; margin-top: 2.75rem; margin-bottom: 1rem; color: var(--plum); }
  .ppl-default-content h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 600; margin-top: 2rem; margin-bottom: 0.75rem; color: var(--plum); }
  .ppl-default-content ul, .ppl-default-content ol { margin-bottom: 1.5rem; padding-left: 1.4rem; }
  .ppl-default-content li { margin-bottom: 0.4rem; }
  .ppl-default-content ul li::marker { color: var(--pink-deep); }
  .ppl-default-content a { color: var(--pink-deep); text-decoration: underline; text-underline-offset: 3px; }
  .ppl-default-content a:hover { color: var(--plum); }
  .ppl-default-content blockquote { border-left: 4px solid var(--pink-deep); margin: 2rem 0; padding: 0.85rem 1.5rem; font-style: italic; color: var(--plum-soft); }
  html[data-theme="dark"] .ppl-default-content { color: rgba(255,255,255,0.88); }
  html[data-theme="dark"] .ppl-default-content h2, html[data-theme="dark"] .ppl-default-content h3 { color: #fff; }
</style>
</head>
<body class="bg-white ppl-default">

<?php get_template_part( 'partials/ppl-nav' ); ?>

<main>

  <!-- ── Hero header ─────────────────────────────────────────────────────── -->
  <header class="bg-blush" style="padding: 80px 0 60px;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-7 text-center">

          <?php if ( $eyebrow ) : ?>
          <p class="text-rose fw-semibold text-uppercase ls-wide mb-1 eyebrow">
            <?php echo esc_html( $eyebrow ); ?>
          </p>
          <?php endif; ?>

          <h1 class="text-plum fw-bold ls-tight mb-4"
              style="font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5vw, 3.25rem); line-height: 1.1; letter-spacing: -0.02em;">
            <?php echo $heading ? esc_html( $heading ) : get_the_title(); ?>
          </h1>

          <?php if ( $subtext ) : ?>
          <p class="text-muted-pp mx-auto" style="font-size: 18px; line-height: 1.7; max-width: 580px; font-family: 'Literata', serif;">
            <?php echo esc_html( $subtext ); ?>
          </p>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </header>


  <!-- ── Full-bleed featured image ──────────────────────────────────────── -->
  <?php if ( has_post_thumbnail() ) : ?>
  <div style="overflow: hidden;">
    <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'full' ) ); ?>"
         alt=""
         style="width: 100%; min-height: 320px; max-height: 420px; object-fit: cover; display: block;" />
  </div>
  <?php endif; ?>


  <!-- ── Content ────────────────────────────────────────────────────────── -->
  <section class="bg-white" style="padding: 72px 0 96px;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="ppl-default-content">
            <?php
            while ( have_posts() ) :
              the_post();
              the_content();
            endwhile;
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php get_template_part( 'partials/ppl-footer' ); ?>
