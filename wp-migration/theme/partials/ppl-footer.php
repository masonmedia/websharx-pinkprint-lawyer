<footer class="bg-white border-top border-blush footer-pad">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <?php
        $logo_id  = get_theme_mod( 'custom_logo' );
        $logo_url = $logo_id
            ? wp_get_attachment_image_url( $logo_id, 'full' )
            : 'https://pinkprint.wpenginepowered.com/wp-content/uploads/2026/05/The-Pinkprint-Lawyer_Primary-1.png';
        echo '<img src="' . esc_url( $logo_url ) . '" class="custom-logo" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" height="42" style="width:auto;display:block;" />';
        ?>
        <p class="text-muted-pp mt-3 mb-3 footer-tagline">Your roadmap through law school<br />and into the profession.</p>
        <div class="d-flex gap-2">
          <a href="#" aria-label="Instagram" class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="LinkedIn"  class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="TikTok"    class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-tiktok"></i></a>
          <a href="#" aria-label="YouTube"   class="bg-blush rounded-3 d-flex align-items-center justify-content-center text-plum text-decoration-none icon-36"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

      <?php
      $footer_cols = [
          [ 'location' => 'footer_about',  'label' => 'About',        'fallback' => [ 'Shakierah Smith' => '#', 'Our Mission' => '#', 'The Book' => '#', 'Press' => '#' ] ],
          [ 'location' => 'footer_shop',   'label' => 'Shop',         'fallback' => [ 'Browse the Shop' => '#', 'Book a Session' => '#' ] ],
          [ 'location' => 'footer_member', 'label' => 'Membership',   'fallback' => [ 'Join' => '#', 'Member Login' => '#' ] ],
          [ 'location' => 'footer_legal',  'label' => 'Legal/Admin',  'fallback' => [ 'Contact' => '#', 'Privacy Policy' => '#', 'Terms of Use' => '#' ] ],
      ];
      foreach ( $footer_cols as $col ) :
      ?>
      <div class="col-6 col-lg-2">
        <p class="text-plum fw-semibold text-uppercase ls-wide mb-3 footer-section-label"><?php echo esc_html( $col['label'] ); ?></p>
        <?php if ( has_nav_menu( $col['location'] ) ) : ?>
          <?php wp_nav_menu( [
            'theme_location' => $col['location'],
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => new PPL_Footer_Link_Walker(),
          ] ); ?>
        <?php else : ?>
          <?php foreach ( $col['fallback'] as $text => $href ) : ?>
            <a href="<?php echo esc_url( $href ); ?>" class="d-block text-muted-pp text-decoration-none mb-2 footer-link-sm"><?php echo esc_html( $text ); ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 border-top border-blush pt-4 mt-5">
      <p class="text-muted-pp mb-0 footer-meta">© <?php echo esc_html( gmdate( 'Y' ) ); ?> The Pinkprint Lawyer. All rights reserved.</p>
      <div class="d-flex gap-4">
        <a href="#" class="text-muted-pp text-decoration-none footer-meta">Privacy Policy</a>
        <a href="#" class="text-muted-pp text-decoration-none footer-meta">Terms of Use</a>
      </div>
    </div>
  </div>
</footer>

<?php
// Minimal walker: renders each menu item as a plain <a> with footer-link-sm class.
if ( ! class_exists( 'PPL_Footer_Link_Walker' ) ) :
class PPL_Footer_Link_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $output .= '<a href="' . esc_url( $item->url ) . '" class="d-block text-muted-pp text-decoration-none mb-2 footer-link-sm">' . esc_html( $item->title ) . '</a>';
    }
    public function end_el( &$output, $item, $depth = 0, $args = null ) {}
    public function start_lvl( &$output, $depth = 0, $args = null ) {}
    public function end_lvl( &$output, $depth = 0, $args = null ) {}
}
endif;
?>

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
