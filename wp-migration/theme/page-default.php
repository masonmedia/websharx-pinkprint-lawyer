<?php
/**
 * Template Name: PPL Default
 *
 * General-purpose on-brand page. Client writes content in the block editor.
 * Nav and footer come from partials — no custom meta fields needed.
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-default">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<section class="section-pad">
  <div class="container" style="max-width: 780px;">
    <?php
    while ( have_posts() ) :
      the_post();
      the_content();
    endwhile;
    ?>
  </div>
</section>

<?php get_template_part( 'partials/ppl-footer' ); ?>
