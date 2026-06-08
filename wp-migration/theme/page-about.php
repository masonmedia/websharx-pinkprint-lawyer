<?php
/**
 * Template Name: About
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-about">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<style>
  .hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo esc_url( ppl_get( 'ppl_abt_hero_bg_image_url', get_stylesheet_directory_uri() . '/assets/images/pp-wallpaper.webp' ) ); ?>');
    background-size: cover;
    background-position: top center;
    opacity: 0.06;
    z-index: 0;
  }
  .hero-pad > .container { position: relative; z-index: 1; }
  .hero-portrait { aspect-ratio: 16 / 9; }
  .hero-portrait img {
    width: 100%;
    height: auto;
    min-height: 500px;
    object-fit: cover;
    object-position: center;
    display: block;
  }
  .scroll-cue { width: 52px; height: 52px; animation: ppl-scroll-cue-bounce 2s ease-in-out infinite; }
  @keyframes ppl-scroll-cue-bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(8px); }
  }
  .mw-640 { max-width: 640px; }
  .mw-720 { max-width: 720px; }
  .kpi-card { border: 1px solid var(--blush-mid); }
  .kpi-num { font-family: 'Playfair Display', serif; font-size: 2.2rem; line-height: 1; }
  .kpi-label { font-size: 12px; line-height: 1.4; }
  .story-accordion .accordion-item { background-color: transparent; border: 1px solid rgba(255,255,255,0.12); border-radius: 16px !important; overflow: hidden; }
  .story-accordion .accordion-item + .accordion-item { margin-top: 16px; }
  .story-accordion .accordion-button { background-color: var(--plum-mid); color: #fff; font-family: 'Playfair Display', serif; font-weight: 600; font-size: 1.15rem; box-shadow: none; padding: 1.5rem; }
  .story-accordion .accordion-button:not(.collapsed) { background-color: var(--plum-mid); color: var(--pink-light); box-shadow: none; }
  .story-accordion .accordion-button::after { filter: invert(1) brightness(1.6); }
  .story-accordion .accordion-body { background-color: var(--plum-mid); padding: 0 1.5rem 1.75rem; }
  .story-num { font-family: 'Playfair Display', serif; color: var(--pink-light); border: 1.5px solid rgba(255,137,197,0.35); flex-shrink: 0; }
  .mission-quote { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.5rem; line-height: 1.5; }
</style>

<!-- HERO -->
<section class="bg-white hero-pad position-relative overflow-hidden">
  <span class="hero-bg"></span>
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="d-inline-flex align-items-center gap-2 bg-pink-tint text-rose rounded-pill px-3 py-2 fw-semibold mb-4 eyebrow">
          <i class="bi bi-quote"></i> <?php ppl_e( 'ppl_abt_hero_eyebrow', 'About Shakierah Smith' ); ?>
        </span>
        <h1 class="display-4 fw-bold text-plum ls-tight mb-4"><?php echo wp_kses_post( ppl_get( 'ppl_abt_hero_heading', 'I learned the hard way <span class="text-rose d-table"> so you don\'t have to.</span>' ) ); ?></h1>
        <p class="text-muted-pp mb-5 body-lead mw-480"><?php ppl_e( 'ppl_abt_hero_body', 'I did not enter law school with a built-in roadmap or a family of attorneys. I came in as a first-generation student, learning the language, the expectations, and the unspoken rules of the profession in real time; often, through trial and error.' ); ?></p>
        <a href="#my-story" class="btn btn-outline-plum rounded-circle d-inline-flex align-items-center justify-content-center scroll-cue" aria-label="Scroll to my story">
          <i class="bi bi-arrow-down fs-icon-lg"></i>
        </a>
      </div>
      <div class="col-lg-6">
        <div class="rounded-5 hero-portrait">
          <img src="<?php echo esc_url( ppl_get( 'ppl_abt_hero_image_url', get_stylesheet_directory_uri() . '/assets/images/pp-about-hero.png' ) ); ?>" alt="Shakierah Smith, Founder of The Pinkprint Lawyer" class="rounded-4" />
        </div>
      </div>
    </div>
  </div>
</section>


<!-- KPI STRIP -->
<div class="border-top border-bottom border-blush py-5">
  <div class="container">
    <div class="row g-3 text-center">
      <?php
      $kpi_defaults = [
        1 => [ '3.9',    "Cumulative GPA\nUB School of Law" ],
        2 => [ 'Top 5%', "Class Rank\nUB School of Law" ],
        3 => [ '4.0',    "Cumulative GPA\nRochester Institute of Technology" ],
        4 => [ 'Top 1%', "University Ranking\nRochester Institute of Technology" ],
      ];
      for ( $i = 1; $i <= 4; $i++ ) :
        $num   = ppl_get( "ppl_abt_kpi_{$i}_num",   $kpi_defaults[ $i ][0] );
        $label = ppl_get( "ppl_abt_kpi_{$i}_label", $kpi_defaults[ $i ][1] );
      ?>
      <div class="col-6 col-md-3">
        <div class="kpi-card rounded-4 py-4 px-2 h-100 bg-blush">
          <p class="kpi-num text-rose pb-2 fw-bold"><?php echo esc_html( $num ); ?></p>
          <p class="kpi-label text-plum fw-semibold text-uppercase ls-wide mb-0"><?php echo nl2br( esc_html( $label ) ); ?></p>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</div>


<!-- MY STORY — ACCORDION -->
<section id="my-story" class="bg-plum section-pad">
  <div class="container">
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8 text-center">
        <p class="text-pink fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_abt_story_eyebrow', 'About The Pinkprint Lawyer' ); ?></p>
        <h2 class="text-white ls-tight fw-bold display-5 mb-0"><?php ppl_e( 'ppl_abt_story_heading', 'My story, in chapters.' ); ?></h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion story-accordion" id="storyAccordion">
          <?php
          $story_defaults = [
            [ 'title' => 'Where It Began',
              'body'  => "The Pinkprint Lawyer began with a straightforward realization: too many law students are expected to simply \u{201C}figure it out.\u{201D} I did not enter law school with a built-in roadmap or a family of attorneys. I came in as a first-generation student, learning the language, the expectations, and the unspoken rules of the profession in real time; often, through trial and error. What I quickly recognized was that intelligence and work ethic were not the issue. Access to clear, honest guidance was." ],
            [ 'title' => 'Through Law School',
              'body'  => 'My path through law school was defined by preparation, discipline, and a deep commitment to excellence. I graduated in the top five percent of my class at the University at Buffalo School of Law with a cumulative 3.9 GPA, earning induction into the Order of the Coif and receiving multiple academic and service-based awards, including the Max Koren Award, the Monique E. Emdin Award, the Promise Prize Scholar Award from the Change Create Transform Foundation, and the John L. Hargrave Award from the Minority Bar Foundation.' ],
            [ 'title' => 'Building Community & Research',
              'body'  => "I served as the inaugural Diversity, Equity, and Inclusion Editor of the Buffalo Law Review, a role I helped create to promote inclusivity within the journal, and founded the First-Generation Law Students Association, providing structure and community for students navigating law school without a traditional roadmap.\n\nAlongside my legal training, I spent years immersed in research, writing, and teaching-oriented roles. I served as a Faculty Research Scholar under Professor Guyora Binder, a Writing Fellow under Professor Kate Rowan, and held faculty assistantships with Professor Matthew Steilen, Professor Rebecca French, and Dean Gargano. My research contributions have been recognized in publications at the Columbia Law Review, Harvard Law & Policy Review, Emory Law Journal, and in a leading criminal law casebook. I also co-authored a peer-reviewed article in the Criminal Justice Review, and my scholarly work has been featured through RIT and UB Law.\n\nI also participated in the Criminal Justice Advocacy Clinic at the University at Buffalo School of Law, which allowed me to connect with real clients in a meaningful way and deepened my understanding of the human impact of legal work." ],
            [ 'title' => 'Before Law School',
              'body'  => "Before law school, I completed a Master of Science in Criminal Justice and a Bachelor of Science in Criminal Justice and Communication at the Rochester Institute of Technology, graduating with a cumulative 4.0 GPA and ranking in the top 1% of the entire university. During my studies, I received multiple academic honors, including the RIT Outstanding Undergraduate Scholar Award, the Thomas C. Castellano Award, the Richard B. Lewis Award, the Kearse Undergraduate Writing Award, the Shaw & McKay Award, and the Center for Public Safety Initiatives\u{2019} Excellence in Research Award. I was selected as the College of Liberal Arts 2018 Undergraduate Commencement Speaker and participated in the McNair Scholars Program, the RIT Honors Program, and the Higher Education Opportunity Program (HEOP). I also contributed to a nationally funded body-worn camera research project through the Bureau of Justice Assistance." ],
            [ 'title' => 'Practicing Today',
              'body'  => "Today, I practice law in real estate, immigration law, business law, and family law, and serve as the New York City Partner and Co-Owner of Smith & Singleton Law, a Black-owned law firm grounded in excellence, equity, and intentional advocacy. The firm officially launched on May 17, 2026, intentionally marking the 72nd anniversary of the Supreme Court\u{2019}s decision in Brown v. Board of Education; a moment that continues to shape how we think about access, opportunity, and the law\u{2019}s role in social change.\n\nMy experience across private practice at Fried, Frank, Harris, Shriver & Jacobson LLP, the United States Attorney\u{2019}s Office, the United States Court of Appeals for the Third Circuit—the second highest court in the United States—and academic institutions has given me a clear understanding of how legal careers are built. Not just on paper, but in real life." ],
            [ 'title' => 'Why The Pinkprint Lawyer Exists',
              'body'  => "I have seen what works, what is often left unsaid, and where students are most likely to feel unsure or unsupported.\n\nThe Pinkprint Lawyer exists for students who want to approach this journey with confidence rather than confusion. It is for those who want to understand the why behind each step, not just the steps themselves. And it is for anyone who has ever thought, \u{201C}I wish someone had explained this sooner.\u{201D}\n\nHere, I share the guidance I once needed: clearly, honestly, and without gatekeeping." ],
          ];
          $story_raw     = get_post_meta( get_the_ID(), 'ppl_abt_story_items', true );
          $story_decoded = $story_raw ? json_decode( $story_raw, true ) : null;
          $story_items   = ( is_array( $story_decoded ) && count( $story_decoded ) ) ? $story_decoded : $story_defaults;
          foreach ( $story_items as $idx => $chapter ) :
            $chapter   = (array) $chapter;
            $num       = $idx + 1;
            $is_first  = ( $idx === 0 );
            $target_id = "story{$num}";
            $paras     = preg_split( '/\n\s*\n/', trim( $chapter['body'] ?? '' ) );
            $paras     = array_values( array_filter( array_map( 'trim', $paras ), 'strlen' ) );
          ?>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button<?php echo $is_first ? '' : ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $target_id ); ?>">
                <span class="rounded-3 d-inline-flex align-items-center justify-content-center story-num me-3 p-4 fs-5 fw-bold card-glass"><?php echo esc_html( str_pad( (string) $num, 2, '0', STR_PAD_LEFT ) ); ?></span>
                <?php echo esc_html( $chapter['title'] ?? '' ); ?>
              </button>
            </h3>
            <div id="<?php echo esc_attr( $target_id ); ?>" class="accordion-collapse collapse<?php echo $is_first ? ' show' : ''; ?>" data-bs-parent="#storyAccordion">
              <div class="accordion-body">
                <?php foreach ( $paras as $p_idx => $para ) : ?>
                <p class="text-light-75 body-lead <?php echo ( $p_idx === count( $paras ) - 1 ) ? 'mb-0' : 'mb-3'; ?>"><?php echo esc_html( $para ); ?></p>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- MY MISSION -->
<section class="bg-white section-pad">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center mb-5">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_abt_mission_eyebrow', 'My Mission' ); ?></p>
        <h2 class="text-plum ls-tight fw-bold display-5 mb-4"><?php ppl_e( 'ppl_abt_mission_heading', 'My mission is to help law students move through this profession with clarity, confidence, and intention.' ); ?></h2>
        <p class="text-muted-pp fst-italic mb-3 body-lead"><?php ppl_e( 'ppl_abt_mission_subtext', 'Not fear, confusion, or unnecessary pressure.' ); ?></p>
        <p class="text-plum mb-3 body-lead mx-auto mw-720"><?php ppl_e( 'ppl_abt_mission_body_1', 'Too often, legal education operates on silence and assumption. Students are expected to already know the rules, the language, and the strategy, even when no one has taken the time to explain them. That gap does not reflect a lack of ability; it reflects a lack of access.' ); ?></p>
        <p class="text-plum fw-semibold mb-0 body-lead"><?php ppl_e( 'ppl_abt_mission_body_2', 'The Pinkprint Lawyer exists to close that gap.' ); ?></p>
      </div>
    </div>

    <div class="row g-4">
      <?php
      $mission_icons    = [ 'bi-lightbulb-fill', 'bi-unlock-fill', 'bi-compass-fill' ];
      $mission_defaults = [
        1 => [ 'Preparation', 'At the core of everything I create is a belief that preparation builds confidence. When students understand how the system works (academically, professionally, and culturally), they show up differently. They ask better questions. They make more informed decisions. They stop second-guessing whether they belong.' ],
        2 => [ 'Empowerment', "I am deeply committed to empowerment without gatekeeping. That means sharing information clearly, explaining the \u{201C}why\u{201D} behind the advice, and respecting the fact that every student\u{2019}s path looks different. There is no single way to succeed in law, but there are ways to move through it more intentionally." ],
        3 => [ 'Guidance',    'The Pinkprint Lawyer is here to offer structure where there is overwhelm, reassurance where there is doubt, and guidance that is both honest and practical. My goal is not only to help students succeed in law school, but to help them build careers they feel confident standing behind.' ],
      ];
      for ( $i = 1; $i <= 3; $i++ ) :
        $badge = ppl_get( "ppl_abt_mission_card_{$i}_badge", $mission_defaults[ $i ][0] );
        $body  = ppl_get( "ppl_abt_mission_card_{$i}_body",  $mission_defaults[ $i ][1] );
      ?>
      <div class="col-md-4">
        <div class="bg-blush rounded-4 p-4 h-100 d-flex flex-column">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center mb-3 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $mission_icons[ $i - 1 ] ); ?> fs-icon-lg"></i>
          </div>
          <span class="d-inline-flex align-items-center rounded-pill px-3 py-2 fw-semibold mb-3 bg-pink-tint text-rose text-uppercase ls-wide badge-start badge-sm"><?php echo esc_html( $badge ); ?></span>
          <p class="text-plum mb-0 body-md"><?php echo esc_html( $body ); ?></p>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    <div class="row justify-content-center mt-5">
      <div class="col-lg-8 text-center">
        <p class="mission-quote text-rose mb-0">&ldquo;<?php ppl_e( 'ppl_abt_mission_quote', "This work isn\u{2019}t just about surviving the process, but rather, about understanding it and moving through it with purpose." ); ?>&rdquo;</p>
      </div>
    </div>
  </div>
</section>


<!-- START HERE -->
<section class="bg-rose section-pad">
  <div class="container">
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8 text-center">
        <p class="fw-semibold text-uppercase ls-wide mb-2 text-light-60 eyebrow"><?php ppl_e( 'ppl_abt_start_eyebrow', 'Start Here' ); ?></p>
        <h2 class="text-white ls-tight mb-3 display-5 fw-bold"><?php ppl_e( 'ppl_abt_start_heading', 'If you are new here, welcome. You do not have to sort everything out on your own.' ); ?></h2>
        <p class="mx-auto text-light-75 mw-640 body-md mb-0"><?php ppl_e( 'ppl_abt_start_body', 'This page is designed to help you orient quickly, based on where you are right now. No scrolling for hours, no guessing which pinkprint fits your situation. Just a clear path forward.' ); ?></p>
      </div>
    </div>

    <div class="row g-4 mb-5">
      <?php
      $start_icons    = [ 'bi-mortarboard-fill', 'bi-book-fill', 'bi-clipboard2-check' ];
      $start_defaults = [
        1 => [ 'New to Law School?', 'If you are still planning, applying, or preparing for your first semester, this path is for you. You will find resources that help you understand what matters early, so you can start strong and avoid common missteps that cost students time, confidence, and opportunities.' ],
        2 => [ 'Already in Law School?', 'If you are in the middle of the experience (juggling classes, outlining, exams, internships, and everything that comes with being a law student), this path is for you. The goal here is simple: help you build systems that reduce overwhelm and strengthen performance.' ],
        3 => [ 'Just Graduated | First Position?', "If you are stepping into the next chapter (bar prep, job searching, or your first role in the profession), this path is for you. Transition seasons can feel unclear, even when you have done everything \u{201C}right.\u{201D} This section helps you approach what comes next with direction and confidence." ],
      ];
      for ( $i = 1; $i <= 3; $i++ ) :
        $badge = ppl_get( "ppl_abt_start_card_{$i}_badge", $start_defaults[ $i ][0] );
        $body  = ppl_get( "ppl_abt_start_card_{$i}_body",  $start_defaults[ $i ][1] );
      ?>
      <div class="col-md-4">
        <div class="rounded-4 p-4 h-100 d-flex flex-column card-glass card-lift">
          <div class="icon-wrap-ghost rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $start_icons[ $i - 1 ] ); ?> text-white fs-icon-lg"></i>
          </div>
          <span class="d-inline-flex align-items-center rounded-pill px-3 py-2 fw-semibold mb-3 text-white text-uppercase ls-wide badge-start badge-glass"><?php echo esc_html( $badge ); ?></span>
          <p class="text-light-75 mb-0 body-sm"><?php echo esc_html( $body ); ?></p>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    <div class="text-center">
      <p class="text-white fw-semibold mb-1 body-lead"><?php ppl_e( 'ppl_abt_start_closing_1', 'Wherever you are in the journey, the goal is the same:' ); ?></p>
      <p class="text-light-75 mb-4 body-md"><?php ppl_e( 'ppl_abt_start_closing_2', 'Less confusion, and more clarity. A plan you can actually follow!' ); ?></p>
      <a href="<?php echo esc_url( ppl_get( 'ppl_abt_start_cta_url', '#' ) ); ?>" class="btn btn-white rounded-3 px-4 py-3 fw-semibold">
        <?php ppl_e( 'ppl_abt_start_cta_label', 'Start Here' ); ?> <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>


<!-- CONTACT CTA -->
<section class="bg-plum section-pad">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-7">
        <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow"><?php ppl_e( 'ppl_abt_contact_eyebrow', 'Contact' ); ?></p>
        <h2 class="text-white ls-tight mb-4 display-6 fw-bold"><?php ppl_e( 'ppl_abt_contact_heading', 'Get in Touch' ); ?></h2>
        <p class="text-light-75 mb-4 body-lead"><?php ppl_e( 'ppl_abt_contact_body', 'I am always open to thoughtful conversation and meaningful opportunities — questions, collaborations, speaking engagements, or media inquiries. Reach out and I will do my best to respond with clarity and intention.' ); ?></p>
        <a href="<?php echo esc_url( ppl_get( 'ppl_abt_contact_cta_url', '/contact' ) ); ?>" class="btn btn-outline-light rounded-3 px-4 py-3 fw-semibold">
          <?php ppl_e( 'ppl_abt_contact_cta_label', 'Contact Me' ); ?> <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>


<!-- DISCLAIMER -->
<section class="bg-blush py-4">
  <div class="container">
    <p class="text-muted-pp text-center mb-0 body-xs">
      <i class="bi bi-info-circle me-1"></i> <?php ppl_e( 'ppl_abt_disclaimer', 'The Pinkprint Lawyer is an educational platform. Nothing on this site constitutes legal advice, nor does any content create an attorney–client relationship.' ); ?>
    </p>
  </div>
</section>

<?php get_template_part( 'partials/ppl-footer' ); ?>
