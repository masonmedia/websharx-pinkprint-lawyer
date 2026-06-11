<footer class="bg-white border-top border-blush footer-pad">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <?php
        $logo_id  = get_theme_mod( 'custom_logo' );
        $logo_url = $logo_id
            ? wp_get_attachment_image_url( $logo_id, 'full' )
            : 'https://pinkprint.wpenginepowered.com/wp-content/uploads/2026/05/The-Pinkprint-Lawyer_Primary-1.png';
        echo '<img src="' . esc_url( $logo_url ) . '" class="custom-logo w-auto d-block" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" height="42" style="height: 42px;" />';
        ?>
        <p class="text-muted-pp mt-3 mb-3 footer-tagline">Your roadmap through law school<br />and into the profession.</p>
        <div class="d-flex gap-2">
          <a href="#" aria-label="Instagram" class="ppl-social bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="LinkedIn"  class="ppl-social bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="TikTok"    class="ppl-social bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-tiktok"></i></a>
          <a href="#" aria-label="YouTube"   class="ppl-social bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

      <div class="col-lg-8">
      <div class="row g-4">
      <?php
      $ppl_footer_data = (array) get_option( 'ppl_footer_nav', [] );
      $ppl_footer_cols = (array) ( $ppl_footer_data['cols'] ?? [] );
      $ppl_col_count   = max( 2, min( 4, intval( $ppl_footer_data['columns'] ?? 4 ) ) );

      // col class per column count (nested inside col-lg-8 wrapper, so 12-col grid within)
      $ppl_col_class = [ 2 => 'col-12 col-md-6 col-lg-6', 3 => 'col-12 col-md-6 col-lg-4', 4 => 'col-12 col-md-6 col-lg-3' ];
      $ppl_item_class = $ppl_col_class[ $ppl_col_count ] ?? 'col-6 col-lg-3';

      if ( $ppl_footer_cols ) :
        foreach ( $ppl_footer_cols as $ppl_col ) : ?>
          <div class="<?php echo esc_attr( $ppl_item_class ); ?>">
            <p class="text-plum fw-semibold text-uppercase ls-wide mb-3 footer-section-label"><?php echo esc_html( $ppl_col['heading'] ?? '' ); ?></p>
            <?php foreach ( (array) ( $ppl_col['links'] ?? [] ) as $ppl_link ) : ?>
              <a href="<?php echo esc_url( $ppl_link['url'] ); ?>" class="d-block text-muted-pp text-decoration-none mb-2 footer-link-sm"><?php echo esc_html( $ppl_link['label'] ); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endforeach;
      else :
        // Fallback until Footer Nav is configured
        $ppl_fallback = [
          'About'       => [ 'Shakierah Smith' => '#', 'Our Mission' => '#', 'The Book' => '#', 'Press' => '#' ],
          'Shop'        => [ 'Browse the Shop' => '#', 'Book a Session' => '#' ],
          'Membership'  => [ 'Join' => '#', 'Member Login' => '#' ],
          'Legal/Admin' => [ 'Contact' => '#', 'Privacy Policy' => '#', 'Terms of Use' => '#' ],
        ];
        foreach ( $ppl_fallback as $ppl_heading => $ppl_links ) : ?>
          <div class="col-12 col-md-6 col-lg-3">
            <p class="text-plum fw-semibold text-uppercase ls-wide mb-3 footer-section-label"><?php echo esc_html( $ppl_heading ); ?></p>
            <?php foreach ( $ppl_links as $ppl_text => $ppl_href ) : ?>
              <a href="<?php echo esc_url( $ppl_href ); ?>" class="d-block text-muted-pp text-decoration-none mb-2 footer-link-sm"><?php echo esc_html( $ppl_text ); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endforeach;
      endif;
      ?>
      </div><!-- /.row g-4 -->
      </div><!-- /.col-lg-8 -->
    </div><!-- /.row g-5 -->

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-top border-blush pt-4 mt-5">
      <p class="text-muted-pp mb-0 footer-meta">© <?php echo esc_html( gmdate( 'Y' ) ); ?> The Pinkprint Lawyer. All rights reserved.</p>
      <div class="d-flex gap-4">
        <a href="#" class="text-muted-pp text-decoration-none footer-meta">Privacy Policy</a>
        <a href="#" class="text-muted-pp text-decoration-none footer-meta">Terms of Use</a>
      </div>
    </div>
  </div>
</footer>

<script>
(function () {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in-view'); observer.unobserve(e.target); }
    });
  }, { threshold: 0.12 });

  const hero = document.querySelector('section.hero-pad');
  if (hero) {
    const targets = hero.querySelectorAll(':scope > .container > .row > [class*="col"]');
    targets.forEach((el, i) => { el.classList.add('fade-up'); el.style.setProperty('--stagger', i); });
    requestAnimationFrame(() => requestAnimationFrame(() => targets.forEach(el => el.classList.add('in-view'))));
  }

  document.querySelectorAll('section:not(.hero-pad)').forEach(section => {
    section.querySelectorAll(':scope > .container > *, :scope > .container > .row > [class*="col"]').forEach((el, i) => {
      el.classList.add('fade-up'); el.style.setProperty('--stagger', i); observer.observe(el);
    });
  });

  const credBar = document.querySelector('.border-top.border-bottom.border-blush');
  if (credBar) { credBar.classList.add('fade-up'); credBar.style.setProperty('--stagger', 0); observer.observe(credBar); }
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
