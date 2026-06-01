<?php
$logo_id = get_theme_mod( 'custom_logo' );
$fallback = 'https://pinkprint.wpenginepowered.com/wp-content/uploads/2026/05/The-Pinkprint-Lawyer_Primary-1.png';

$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : $fallback;
$alt      = esc_attr( get_bloginfo( 'name' ) );
$logo_nav    = '<img src="' . esc_url( $logo_url ) . '" class="custom-logo" alt="' . $alt . '" height="46" style="width:auto;display:block;" />';
$logo_mobile = '<img src="' . esc_url( $logo_url ) . '" class="custom-logo" alt="' . $alt . '" height="40" style="width:auto;display:block;" />';
?>
<nav class="navbar navbar-expand-lg sticky-top py-3 bg-white border-bottom border-blush">
  <div class="container">
    <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <?php echo $logo_nav; ?>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-label="Open navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center">
      <?php if ( has_nav_menu( 'primary' ) ) : ?>
        <?php ppl_nav_menu( 'primary', [ 'menu_class' => 'navbar-nav gap-4', 'link_class' => 'nav-link fw-medium text-plum nav-link-sm' ] ); ?>
      <?php else : ?>
        <ul class="navbar-nav gap-4">
          <li class="nav-item"><a class="nav-link fw-medium text-plum nav-link-sm" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
          <li class="nav-item"><a class="nav-link fw-medium text-plum nav-link-sm" href="#">About</a></li>
          <li class="nav-item"><a class="nav-link fw-medium text-plum nav-link-sm" href="#">Shop</a></li>
          <li class="nav-item"><a class="nav-link fw-medium text-plum nav-link-sm" href="#">Membership</a></li>
          <li class="nav-item"><a class="nav-link fw-medium text-plum nav-link-sm" href="#">Legal/Admin</a></li>
          <li class="nav-item"><a class="nav-link fw-medium text-plum nav-link-sm" href="#">Contact</a></li>
        </ul>
      <?php endif; ?>
    </div>
    <div class="d-none d-lg-flex align-items-center gap-2">
      <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn-rose rounded-3 px-4 py-2 fw-semibold nav-link-sm">Log In</a>
    </div>
  </div>
</nav>

<div class="offcanvas offcanvas-end bg-blush" tabindex="-1" id="mobileNav">
  <div class="offcanvas-header bg-blush" style="border-bottom:1px solid var(--pink-tint-mid);">
    <?php echo $logo_mobile; ?>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column gap-3 pt-4 justify-content-end">
    <?php if ( has_nav_menu( 'primary' ) ) : ?>
      <?php ppl_nav_menu( 'primary', [ 'menu_class' => 'd-flex flex-column gap-3', 'link_class' => 'nav-link fs-5 text-plum fw-medium' ] ); ?>
    <?php else : ?>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-link fs-5 text-plum fw-medium">Home</a>
      <a href="#" class="nav-link fs-5 text-plum fw-medium">About</a>
      <a href="#" class="nav-link fs-5 text-plum fw-medium">Shop</a>
      <a href="#" class="nav-link fs-5 text-plum fw-medium">Membership</a>
      <a href="#" class="nav-link fs-5 text-plum fw-medium">Legal/Admin</a>
      <a href="#" class="nav-link fs-5 text-plum fw-medium">Contact</a>
    <?php endif; ?>
    <div class="d-flex flex-column gap-2 mt-3">
      <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn-rose rounded-3 py-2 fw-semibold">Log In</a>
    </div>
  </div>
</div>
