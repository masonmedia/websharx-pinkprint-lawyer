<?php
/**
 * Template Name: Home
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-home">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<?php $contact_status = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : ''; ?>


<!-- HERO -->
<section class="bg-white hero-pad">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="d-inline-flex align-items-center gap-2 bg-pink-tint text-rose rounded-pill px-3 py-2 fw-semibold mb-4 eyebrow">
          <i class="bi bi-patch-check-fill"></i> <?php ppl_e( 'ppl_hero_eyebrow', 'Practicing Attorney &amp; Mentor' ); ?>
        </span>
        <h1 class="display-4 fw-bold text-plum ls-tight mb-4"><?php echo wp_kses_post( ppl_get( 'ppl_hero_heading', 'Your <em class="text-rose">Pinkprint</em> for Law School, the Bar, and Beyond.' ) ); ?></h1>
        <p class="lead text-plum mb-3 body-lead"><?php ppl_e( 'ppl_hero_lead', 'I am Shakierah Smith — a first-generation law graduate, practicing attorney, published researcher, and dedicated mentor for students navigating law school and the legal profession.' ); ?></p>
        <p class="text-muted-pp fst-italic mb-5 body-sm"><?php ppl_e( 'ppl_hero_tagline', 'This is not about perfection. It is about preparation.' ); ?></p>
        <div class="d-flex flex-wrap gap-3">
          <a href="<?php echo esc_url( ppl_get( 'ppl_hero_cta_primary_url', '#' ) ); ?>" class="btn btn-plum rounded-3 px-4 py-3 fw-semibold">
            <?php ppl_e( 'ppl_hero_cta_primary_label', 'Start Here' ); ?> <i class="bi bi-arrow-right ms-1"></i>
          </a>
          <a href="<?php echo esc_url( ppl_get( 'ppl_hero_cta_secondary_url', '#' ) ); ?>" class="btn btn-outline-plum rounded-3 px-4 py-3 fw-semibold">
            <?php ppl_e( 'ppl_hero_cta_secondary_label', 'Explore the Pinkprints' ); ?>
          </a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="rounded-5 overflow-hidden bg-blush-mid">
          <img src="<?php echo esc_url( ppl_get( 'ppl_hero_image_url', 'https://images.unsplash.com/vector-1775025870074-892399cbf787?q=80&w=1172&auto=format&fit=crop' ) ); ?>" alt="Legal illustration" class="hero-img" />
        </div>
      </div>
    </div>
  </div>
</section>


<!-- CREDENTIAL BAR -->
<div class="border-top border-bottom border-blush py-5">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between gap-4">
      <?php
      $cred_icons    = [ 'bi-award-fill', 'bi-briefcase-fill', 'bi-journal-richtext', 'bi-people-fill', 'bi-book-fill' ];
      $cred_defaults = [ 'First-Generation Graduate', 'Practicing Attorney', 'Published Researcher', 'Dedicated Mentor', 'Author' ];
      for ( $i = 1; $i <= 5; $i++ ) :
        $label = ppl_get( "ppl_cred_{$i}", $cred_defaults[ $i - 1 ] );
        $icon  = $cred_icons[ $i - 1 ];
        if ( $i > 1 ) echo '<span class="cred-divider d-none d-md-block"></span>';
      ?>
        <span class="d-flex align-items-center cred-bar-item">
          <i class="bi <?php echo esc_attr( $icon ); ?> cred-bar-icon"></i>&nbsp;<?php echo esc_html( $label ); ?>
        </span>
      <?php endfor; ?>
    </div>
  </div>
</div>


<!-- MISSION -->
<section class="p-5">
  <div class="container py-5">
    <div class="row g-0">
      <div class="col-xl-6" style="min-height:480px;">
        <img class="rounded-4" src="<?php echo esc_url( ppl_get( 'ppl_mission_image_url', 'https://images.unsplash.com/photo-1591692400544-1ad3f63a911d?q=80&w=1732&auto=format&fit=crop' ) ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" />
      </div>
      <div class="col-xl-6 d-flex align-items-center">
        <div class="px-5 py-5 py-xl-0" style="max-width:600px;margin:0 auto;padding-top:80px!important;padding-bottom:80px!important;">
          <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow"><?php ppl_e( 'ppl_mission_eyebrow', 'My Mission' ); ?></p>
          <h2 class="ls-tight fw-bold display-6 mb-4"><?php ppl_e( 'ppl_mission_heading', 'Law school does not come with a clear set of instructions.' ); ?></h2>
          <p class="text-secondary text-opacity-75 body-lead mb-0"><?php ppl_e( 'ppl_mission_body', 'I created The Pinkprint Lawyer to help students navigate complex systems with guidance. Here, you will find resources that are practical, evidence-informed, and designed to help you move through each stage of this journey with clarity, confidence, and intention.' ); ?></p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- WHO IT'S FOR -->
<section class="bg-blush section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_audience_eyebrow', 'Who This Platform Serves' ); ?></p>
      <h2 class="text-plum ls-tight mb-3 display-5 fw-bold"><?php ppl_e( 'ppl_audience_heading', 'Built for every stage of the legal journey.' ); ?></h2>
      <p class="mx-auto text-muted-pp mw-520 body-md"><?php ppl_e( 'ppl_audience_subtext', 'Built for students who want direction without intimidation.' ); ?></p>
    </div>
    <div class="row g-4">
      <?php
      $aud_icons    = [ 'bi-mortarboard-fill', 'bi-book-fill', 'bi-briefcase-fill' ];
      $aud_defaults = [
        [ 'stage' => '01 — Aspiring',  'title' => 'Pre-Law Students',                   'body' => 'You are preparing for law school and want to make well-informed decisions before day one.', 'badge' => 'Pre-Law Pinkprint' ],
        [ 'stage' => '02 — Current',   'title' => 'Law Students',                       'body' => 'You are managing coursework, examinations, and professional opportunities. You want structure, not overwhelm.', 'badge' => 'Study System' ],
        [ 'stage' => '03 — Graduates', 'title' => 'Recent Graduates & Bar Candidates',  'body' => 'You are transitioning into the profession and want to approach what comes next with confidence and clarity.', 'badge' => 'Bar Prep Guide' ],
      ];
      $aud_raw      = get_post_meta( get_the_ID(), 'ppl_audience_items', true );
      $aud_decoded  = $aud_raw ? json_decode( $aud_raw, true ) : null;
      $aud_items    = ( is_array( $aud_decoded ) && count( $aud_decoded ) ) ? $aud_decoded : $aud_defaults;
      foreach ( $aud_items as $idx => $card ) :
        $card = (array) $card;
        $icon = $aud_icons[ $idx ] ?? 'bi-star-fill';
      ?>
      <div class="col-md-4">
        <div class="bg-white rounded-4 p-4 h-100 d-flex flex-column">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-56">
            <i class="bi <?php echo esc_attr( $icon ); ?> fs-4"></i>
          </div>
          <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 stage-tag"><?php echo esc_html( $card['stage'] ?? '' ); ?></p>
          <h3 class="text-plum mb-3 fw-bold card-h"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
          <p class="text-plum mb-4 body-sm"><?php echo esc_html( $card['body'] ?? '' ); ?></p>
          <div class="mt-auto">
            <span class="d-inline-flex align-items-center gap-2 bg-blush text-muted-pp rounded-pill px-3 py-2 fw-semibold badge-sm">
              <i class="bi bi-arrow-right-circle"></i> <?php echo esc_html( $card['badge'] ?? '' ); ?>
            </span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- FULL BLEED IMAGE -->
<div style="min-height:500px;background-image:url('<?php echo esc_url( ppl_get( 'ppl_fullbleed_image_url', get_stylesheet_directory_uri() . '/assets/pink-gavel.jpg' ) ); ?>');background-size:cover;background-position:center;"></div>


<!-- ABOUT SHAKIERAH -->
<section class="bg-plum section-pad">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5">
        <div class="rounded-4 overflow-hidden">
          <img src="<?php echo esc_url( ppl_get( 'ppl_about_image_url', 'https://cdn.rit.edu/images/news/2024-04/WEB-20240210_shakira-smith_nycalumniupdate_0011_jamod.jpg' ) ); ?>" alt="Shakierah Smith" class="about-img" />
        </div>
      </div>
      <div class="col-lg-7">
        <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow"><?php ppl_e( 'ppl_about_eyebrow', 'About The Pinkprint' ); ?></p>
        <h2 class="text-white ls-tight mb-4 display-5 fw-bold"><?php ppl_e( 'ppl_about_heading', 'Too many law students are expected to simply "figure it out".' ); ?></h2>
        <p class="text-light-75 mb-3 body-lead"><?php ppl_e( 'ppl_about_body_1', 'I did not enter law school with a built-in roadmap or a family of attorneys. I came in as a first-generation student, learning the language, the expectations, and the unspoken rules of the profession in real time — often through trial and error.' ); ?></p>
        <p class="text-light-75 mb-5 body-lead"><?php ppl_e( 'ppl_about_body_2', 'My path was defined by preparation, discipline, and a deep commitment to excellence. I graduated in the top five percent of my class at the University at Buffalo School of Law.' ); ?></p>
        <a href="<?php echo esc_url( ppl_get( 'ppl_about_cta_url', '#' ) ); ?>" class="btn btn-outline-light rounded-3 px-4 py-3 fw-semibold">
          <?php ppl_e( 'ppl_about_cta_label', 'Read the Full Story' ); ?> <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>


<!-- FEATURED PRODUCTS -->
<section class="bg-white section-pad">
  <div class="container">
    <div class="row align-items-end mb-4">
      <div class="col-md-8">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_products_eyebrow', 'Featured Digital Products' ); ?></p>
        <h2 class="text-plum ls-tight fw-bold display-6"><?php ppl_e( 'ppl_products_heading', 'Each pinkprint addresses a specific stage of your journey.' ); ?></h2>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="#" class="btn btn-outline-plum rounded-3 px-4 py-3 fw-semibold">View All Resources</a>
      </div>
    </div>
    <p class="text-muted-pp mb-5 body-md"><?php ppl_e( 'ppl_products_subtext', 'Each pinkprint is designed with clear takeaways and realistic strategies you can apply immediately.' ); ?></p>
    <div class="row g-4">
      <?php
      $prod_icons    = [ 'bi-journal-bookmark', 'bi-book', 'bi-clipboard2-check', 'bi-briefcase' ];
      $prod_defaults = [
        [ 'stage' => 'Stage 01 · Aspiring',  'title' => 'Pre-Law Preparation',     'body' => 'Pre-law preparation and planning tools to help you make well-informed decisions before day one.', 'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
        [ 'stage' => 'Stage 02 · Current',   'title' => 'Law School Study System', 'body' => 'Law school study systems and academic strategy to help you manage coursework and examinations.',  'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
        [ 'stage' => 'Stage 03 · Graduates', 'title' => 'Bar Exam & Career Prep',  'body' => 'Examination preparation, internship positioning, and career development.',                         'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
        [ 'stage' => 'Stage 04 · Attorneys', 'title' => 'Early Career Guidance',   'body' => 'Post-graduate and early-career resources for attorneys entering the profession with confidence.',   'cta' => 'Get This Pinkprint', 'cta_url' => '#' ],
      ];
      $prod_raw     = get_post_meta( get_the_ID(), 'ppl_products_items', true );
      $prod_decoded = $prod_raw ? json_decode( $prod_raw, true ) : null;
      $prod_items   = ( is_array( $prod_decoded ) && count( $prod_decoded ) ) ? $prod_decoded : $prod_defaults;
      foreach ( $prod_items as $idx => $card ) :
        $card = (array) $card;
        $icon = $prod_icons[ $idx ] ?? 'bi-star-fill';
      ?>
      <div class="col-sm-6 col-lg-3">
        <div class="bg-blush rounded-4 p-4 h-100 d-flex flex-column card-lift">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $icon ); ?> fs-icon-lg"></i>
          </div>
          <p class="text-muted-pp fw-semibold text-uppercase ls-wide mb-2 stage-tag"><?php echo esc_html( $card['stage'] ?? '' ); ?></p>
          <h4 class="text-plum mb-3 fw-bold card-h-sm"><?php echo esc_html( $card['title'] ?? '' ); ?></h4>
          <p class="text-plum mb-4 body-xs"><?php echo esc_html( $card['body'] ?? '' ); ?></p>
          <div class="mt-auto">
            <a href="<?php echo esc_url( $card['cta_url'] ?? '#' ); ?>" class="card-link d-inline-flex align-items-center gap-2">
              <?php echo esc_html( $card['cta'] ?? 'Get This Pinkprint' ); ?> <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Session card -->
      <div class="col-lg-12">
        <div class="bg-blush-mid rounded-4 p-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 card-lift">
          <div class="d-flex align-items-center gap-4">
            <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:96px;height:96px;">
              <i class="bi bi-calendar2-check-fill" style="font-size:36px;"></i>
            </div>
            <div>
              <p class="text-rose fw-semibold text-uppercase ls-wide mb-1 stage-tag"><?php ppl_e( 'ppl_session_eyebrow', '1-on-1 Strategy Session' ); ?></p>
              <h4 class="text-plum fw-bold mb-1" style="font-size:1.25rem;"><?php ppl_e( 'ppl_session_title', 'Pre-Law, Law School &amp; Post-Law Strategy Meeting' ); ?></h4>
              <p class="text-muted-pp mb-0 body-sm" style="max-width:540px;"><?php ppl_e( 'ppl_session_body', 'A focused, one-hour session tailored to where you are in your journey.' ); ?></p>
            </div>
          </div>
          <div class="flex-shrink-0">
            <a href="<?php echo esc_url( ppl_get( 'ppl_session_cta_url', '#' ) ); ?>" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold">
              <?php ppl_e( 'ppl_session_cta_label', 'Book a Session' ); ?> <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- HOW IT WORKS -->
<section class="bg-pink-tint section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_hiw_eyebrow', 'How It Works' ); ?></p>
      <h2 class="text-plum ls-tight mb-3 display-5 fw-bold"><?php ppl_e( 'ppl_hiw_heading', 'Three steps to moving forward with clarity.' ); ?></h2>
      <p class="mx-auto text-muted-pp mw-480 body-md"><?php ppl_e( 'ppl_hiw_subtext', 'One intentional step at a time.' ); ?></p>
    </div>
    <div class="row g-4">
      <?php
      $step_icons    = [ 'bi-compass-fill', 'bi-map-fill', 'bi-rocket-takeoff-fill' ];
      $step_defaults = [
        [ 'Find Your Stage',       'Identify where you are in your legal journey — pre-law, enrolled, or post-graduate. This determines everything that follows.' ],
        [ 'Choose Your Pinkprint', 'Select the resource that fits your current season. Each pinkprint is purpose-built with clear takeaways you can apply immediately.' ],
        [ 'Move with Clarity',     'Apply the strategies, follow the frameworks, and advance through your legal education with intention — not guesswork.' ],
      ];
      for ( $i = 1; $i <= 3; $i++ ) :
        $d = $step_defaults[ $i - 1 ];
      ?>
      <div class="col-md-4">
        <div class="bg-white rounded-4 p-4 h-100 d-flex flex-column">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $step_icons[ $i - 1 ] ); ?> fs-icon-lg"></i>
          </div>
          <h4 class="text-plum mb-3 fw-bold card-h-md"><?php ppl_e( "ppl_step_{$i}_title", $d[0] ); ?></h4>
          <p class="text-plum mb-0 body-sm"><?php ppl_e( "ppl_step_{$i}_body", $d[1] ); ?></p>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>


<!-- TESTIMONIALS -->
<section class="bg-blush section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_testimonials_eyebrow', 'Student Stories' ); ?></p>
      <h2 class="text-plum ls-tight fw-bold display-5"><?php ppl_e( 'ppl_testimonials_heading', 'Real students. Real results.' ); ?></h2>
    </div>
    <div class="row g-4">
      <?php
      $test_defaults = [
        [ 'quote' => '"The Pinkprint gave me a framework I could actually use. I went into my 1L year knowing exactly what to expect and how to handle it."', 'name' => 'Alicia M.',   'role' => '1L · Howard University School of Law' ],
        [ 'quote' => '"As a first-generation student, I had no idea what I didn\'t know. This platform filled every gap — from study strategy to understanding the bar process."', 'name' => 'Jordan T.',   'role' => '2L · Temple University Beasley School of Law' ],
        [ 'quote' => '"The bar prep pinkprint helped me structure my schedule and approach the exam with confidence instead of panic. Genuinely life-changing."', 'name' => 'Danielle R.', 'role' => 'Recent Graduate · Bar Candidate' ],
      ];
      $test_raw    = get_post_meta( get_the_ID(), 'ppl_testimonials_items', true );
      $test_decoded = $test_raw ? json_decode( $test_raw, true ) : null;
      $test_items  = ( is_array( $test_decoded ) && count( $test_decoded ) ) ? $test_decoded : $test_defaults;
      foreach ( $test_items as $card ) :
        $card = (array) $card;
      ?>
      <div class="col-md-4">
        <div class="bg-white rounded-4 p-4 h-100 d-flex flex-column">
          <div class="text-rose mb-3 stars">★★★★★</div>
          <i class="bi bi-quote text-pink mb-3 quote-icon"></i>
          <blockquote class="fst-italic text-plum mb-4 flex-grow-1 testimonial-text"><?php echo esc_html( $card['quote'] ?? '' ); ?></blockquote>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <div class="icon-wrap-tint rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 icon-44">
              <i class="bi bi-person-fill fs-icon-md"></i>
            </div>
            <div>
              <div class="text-plum fw-semibold author-name-sm"><?php echo esc_html( $card['name'] ?? '' ); ?></div>
              <div class="text-muted-pp author-role"><?php echo esc_html( $card['role'] ?? '' ); ?></div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- BOOK SPOTLIGHT -->
<section class="bg-secondary bg-opacity-10 section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-3 eyebrow"><?php ppl_e( 'ppl_book_eyebrow', 'Now Available' ); ?></p>
      <h2 class="text-plum ls-tight fw-bold display-6 mb-4"><?php ppl_e( 'ppl_book_heading', 'The Pinkprint Guides' ); ?></h2>
      <p class="mx-auto text-plum mb-4 body-lead" style="max-width:620px;"><?php ppl_e( 'ppl_book_body', 'Three guides. One through-line. Each Pinkprint meets you at a different stage of the legal journey.' ); ?></p>
      <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <span class="bg-pink-tint text-rose fw-semibold rounded-pill px-3 py-2 badge-sm">Pre-Law</span>
        <span class="bg-pink-tint text-rose fw-semibold rounded-pill px-3 py-2 badge-sm">Law School</span>
        <span class="bg-pink-tint text-rose fw-semibold rounded-pill px-3 py-2 badge-sm">Bar &amp; Beyond</span>
        <span class="bg-pink-tint text-rose fw-semibold rounded-pill px-3 py-2 badge-sm">First-Gen Perspective</span>
        <span class="bg-pink-tint text-rose fw-semibold rounded-pill px-3 py-2 badge-sm">Digital &amp; Print</span>
      </div>
      <a href="<?php echo esc_url( ppl_get( 'ppl_book_cta_url', '#' ) ); ?>" class="btn btn-rose rounded-3 px-4 py-3 fw-semibold">
        <?php ppl_e( 'ppl_book_cta_label', 'Shop the Guides' ); ?> <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="row g-4 justify-content-center mt-2">
      <?php
      $covers_raw = get_post_meta( get_the_ID(), 'ppl_book_covers', true );
      $covers     = $covers_raw ? (array) json_decode( $covers_raw, true ) : [];
      if ( empty( $covers ) ) {
          for ( $i = 1; $i <= 3; $i++ ) {
              $covers[] = [ 'url' => get_stylesheet_directory_uri() . "/assets/book_covers/Book ({$i})/The Pinkprint Lawyer_Book ({$i}) - Front Cover.png" ];
          }
      }
      foreach ( $covers as $i => $cover ) :
        $cover = (array) $cover;
        if ( empty( $cover['url'] ) ) continue;
      ?>
      <div class="col-sm-4 col-lg-3">
        <img src="<?php echo esc_url( $cover['url'] ); ?>" alt="The Pinkprint Lawyer Book <?php echo esc_attr( $i + 1 ); ?>" class="w-100 rounded-4" style="box-shadow:0 24px 56px rgba(35,13,24,0.14);" />
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- START HERE -->
<section class="bg-rose section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="fw-semibold text-uppercase ls-wide mb-2 text-light-60 eyebrow"><?php ppl_e( 'ppl_start_eyebrow', 'Find Your Path' ); ?></p>
      <h2 class="text-white ls-tight mb-3 display-5 fw-bold"><?php ppl_e( 'ppl_start_heading', 'Not sure where to start?' ); ?></h2>
      <p class="mx-auto text-light-75 mw-560 body-md"><?php ppl_e( 'ppl_start_body', 'If you are unsure which resource is right for you, start here. Choose your current stage and we will point you to exactly what you need.' ); ?></p>
    </div>
    <div class="row g-4 mb-5">
      <?php
      $path_icons    = [ 'bi-mortarboard-fill', 'bi-book-fill', 'bi-clipboard2-check' ];
      $path_defaults = [
        [ 'badge' => 'New to Law School',  'title' => 'Just Getting Started',  'body' => 'Explore the Pre-Law Pinkprint and foundation resources designed to set you up before day one.', 'cta' => 'Explore Pre-Law Resources',  'cta_url' => '#' ],
        [ 'badge' => 'Currently Enrolled', 'title' => 'In the Middle of It',   'body' => 'Find study systems, academic strategy guides, and exam prep frameworks built for active students.', 'cta' => 'Explore Study Resources',    'cta_url' => '#' ],
        [ 'badge' => 'Post-Graduate',      'title' => 'Preparing for the Bar', 'body' => 'Access bar prep frameworks and early-career transition tools for graduates entering the profession.', 'cta' => 'Explore Bar & Career Prep', 'cta_url' => '#' ],
      ];
      $paths_raw     = get_post_meta( get_the_ID(), 'ppl_start_paths', true );
      $paths_decoded = $paths_raw ? json_decode( $paths_raw, true ) : null;
      $path_items    = ( is_array( $paths_decoded ) && count( $paths_decoded ) ) ? $paths_decoded : $path_defaults;
      foreach ( $path_items as $idx => $card ) :
        $card = (array) $card;
        $icon = $path_icons[ $idx ] ?? 'bi-star-fill';
      ?>
      <div class="col-md-4">
        <div class="rounded-4 p-4 h-100 d-flex flex-column card-glass">
          <div class="icon-wrap-ghost rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $icon ); ?> text-white fs-icon-lg"></i>
          </div>
          <span class="d-inline-flex align-items-center rounded-pill px-3 py-2 fw-semibold mb-3 text-white text-uppercase ls-wide badge-start badge-glass">
            <?php echo esc_html( $card['badge'] ?? '' ); ?>
          </span>
          <h4 class="text-white mb-3 fw-bold card-h-md"><?php echo esc_html( $card['title'] ?? '' ); ?></h4>
          <p class="text-light-75 mb-4 body-xs"><?php echo esc_html( $card['body'] ?? '' ); ?></p>
          <a href="<?php echo esc_url( $card['cta_url'] ?? '#' ); ?>" class="card-link-light d-inline-flex align-items-center gap-2 mt-auto">
            <?php echo esc_html( $card['cta'] ?? 'Explore Resources' ); ?> <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center">
      <a href="<?php echo esc_url( ppl_get( 'ppl_start_cta_url', '#' ) ); ?>" class="btn btn-white rounded-3 px-4 py-3 fw-semibold">
        <?php ppl_e( 'ppl_start_cta_label', 'Start Here' ); ?> <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>


<!-- CONTACT -->
<section class="bg-plum section-pad">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow"><?php ppl_e( 'ppl_contact_eyebrow', 'Contact' ); ?></p>
        <h2 class="text-white ls-tight mb-4 display-6 fw-bold"><?php ppl_e( 'ppl_contact_heading', 'Get in Touch' ); ?></h2>
        <p class="text-light-75 mb-4 body-lead"><?php ppl_e( 'ppl_contact_body_1', 'I am always open to thoughtful conversation and meaningful opportunities.' ); ?></p>
        <p class="text-light-60 mb-5 body-sm"><?php ppl_e( 'ppl_contact_body_2', 'Whether you are reaching out with a question or exploring a potential partnership, I appreciate clarity and intention.' ); ?></p>
        <div class="d-flex flex-column gap-3">
          <?php
          $contact_items = [
            [ 'bi-envelope-fill', 'General Inquiries',                'Questions about The Pinkprint Lawyer, resources, or educational content.' ],
            [ 'bi-people-fill',   'Collaborations &amp; Partnerships', 'Brands, organizations, and institutions aligned with access, education, and professional development.' ],
            [ 'bi-mic-fill',      'Speaking Engagements',             'Panels, workshops, guest lectures, or speaking opportunities.' ],
            [ 'bi-newspaper',     'Media &amp; Press',                'Interviews, features, or press-related inquiries.' ],
          ];
          foreach ( $contact_items as $item ) :
          ?>
          <div class="d-flex align-items-start gap-3">
            <div class="icon-wrap-dim rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 icon-40">
              <i class="bi <?php echo esc_attr( $item[0] ); ?> fs-icon-sm"></i>
            </div>
            <div>
              <p class="text-white fw-semibold mb-1 body-sm"><?php echo wp_kses_post( $item[1] ); ?></p>
              <p class="text-light-60 mb-0 body-xs"><?php echo wp_kses_post( $item[2] ); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="bg-plum-mid rounded-4 p-4 p-md-5">

          <p class="text-light-60 mb-4 body-sm">If you are unsure which category your message falls under, that is okay — just share the details, and we will take it from there.</p>

          <form id="ppl-contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="d-flex flex-column gap-3">
            <?php wp_nonce_field( 'ppl_contact_submit', 'ppl_contact_nonce' ); ?>
            <input type="hidden" name="action" value="ppl_contact" />
            <input type="hidden" name="ppl_ts" value="<?php echo esc_attr( time() ); ?>" />
            <div style="display:none;" aria-hidden="true"><input type="text" name="ppl_website" tabindex="-1" autocomplete="off" /></div>

            <div>
              <label class="ppl-form-label" for="ppl_name">Name <span style="color:var(--pink-light);">*</span></label>
              <input type="text" id="ppl_name" name="ppl_name" class="ppl-form-input" placeholder="Your full name" required style="background-color:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.15);color:#fff;padding:14px 20px;border-radius:10px;" />
            </div>
            <div>
              <label class="ppl-form-label" for="ppl_email">Email <span style="color:var(--pink-light);">*</span></label>
              <input type="email" id="ppl_email" name="ppl_email" class="ppl-form-input" placeholder="Your email address" required style="background-color:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.15);color:#fff;padding:14px 20px;border-radius:10px;" />
            </div>
            <div>
              <label class="ppl-form-label" for="ppl_type">Inquiry Type</label>
              <select id="ppl_type" name="ppl_type" class="ppl-form-input" style="appearance:auto;">
                <option value="" disabled selected>Select a category</option>
                <option value="General Inquiry">General Inquiry</option>
                <option value="Collaboration &amp; Partnership">Collaboration &amp; Partnership</option>
                <option value="Speaking Engagement">Speaking Engagement</option>
                <option value="Media &amp; Press">Media &amp; Press</option>
              </select>
            </div>
            <div>
              <label class="ppl-form-label" for="ppl_message">Message <span style="color:var(--pink-light);">*</span></label>
              <textarea id="ppl_message" name="ppl_message" class="ppl-form-input" rows="4" style="resize:none;" placeholder="Share the details of your inquiry" required></textarea>
            </div>
            <button type="submit" class="ppl-form-submit mt-1">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact success modal -->
<div class="modal fade" id="pplContactModal" tabindex="-1" aria-labelledby="pplContactModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-plum-mid border-0 rounded-4 p-2">
      <div class="modal-body text-center py-5 px-4">
        <div class="icon-wrap-dim rounded-3 d-inline-flex align-items-center justify-content-center icon-56 mb-4">
          <i class="bi bi-check2 fs-icon-lg text-pink"></i>
        </div>
        <p class="text-pink fw-semibold text-uppercase ls-wide eyebrow mb-3">Message Sent</p>
        <h3 id="pplContactModalLabel" class="text-white fw-bold mb-3" style="font-size:1.5rem;font-family:'Playfair Display',serif;">Thank you for reaching out.</h3>
        <p class="text-light-60 body-sm mb-5">Your message has been received. I try to respond as quickly as possible, generally within 1&ndash;2 business days.</p>
        <button type="button" class="ppl-form-submit" style="max-width:200px;margin:0 auto;display:block;" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  var form = document.getElementById('ppl-contact-form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var btn = form.querySelector('button[type="submit"]');
    var originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Sending…';

    var data = new FormData(form);
    data.set('action', 'ppl_contact_json');

    fetch(pplData.ajaxurl, { method: 'POST', body: data })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.success) {
          form.reset();
          new bootstrap.Modal(document.getElementById('pplContactModal')).show();
        } else {
          showError(res.data || 'Something went wrong. Please try again.');
        }
      })
      .catch(function () {
        showError('Something went wrong. Please try again.');
      })
      .finally(function () {
        btn.disabled = false;
        btn.textContent = originalText;
      });
  });

  function showError(msg) {
    var existing = form.querySelector('.ppl-alert-error');
    if (existing) existing.remove();
    var el = document.createElement('div');
    el.className = 'ppl-alert ppl-alert-error';
    el.textContent = msg;
    form.insertBefore(el, form.firstChild);
  }
})();
</script>

<?php get_template_part( 'partials/ppl-footer' ); ?>
