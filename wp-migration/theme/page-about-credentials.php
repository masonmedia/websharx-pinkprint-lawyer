<?php
/**
 * Template Name: About — Credentials
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-credentials">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<style>
  .hero-pad { padding-top: 72px; padding-bottom: 56px; }
  .mw-680 { max-width: 680px; }

  /* Accordion (Education) */
  .accordion-item { border: 1px solid var(--blush-mid); border-radius: 14px !important; overflow: hidden; }
  .accordion-item + .accordion-item { margin-top: 0.75rem; }

  /* Publications accordion — stronger contrast against the section background */
  #pubAccordion .accordion-item { background-color: var(--blush); border-color: var(--pink-tint-mid); box-shadow: 0 4px 16px rgba(196,54,112,0.08); }
  #pubAccordion .accordion-button { background-color: var(--blush); }
  #pubAccordion .accordion-body { background-color: #fff; }
  .accordion-button { font-family: 'Playfair Display', serif; color: var(--plum); font-weight: 600; font-size: 1.05rem; }
  .accordion-button:not(.collapsed) { background-color: var(--pink-tint); color: var(--pink-deep); box-shadow: none; }
  .accordion-button:focus { box-shadow: none; border-color: var(--blush-mid); }
  .accordion-button::after { filter: hue-rotate(290deg) saturate(2); }

  /* Timeline (Professional Experience) */
  .timeline { position: relative; padding-left: 0; }
  .timeline-item { position: relative; padding-left: 56px; }
  .timeline-item:not(:last-child) { padding-bottom: 2.25rem; }
  .timeline-item:not(:last-child)::before {
    content: ''; position: absolute; left: 19px; top: 40px; bottom: -2.25rem; width: 2px; background: var(--pink-tint-mid);
  }
  .timeline-dot {
    position: absolute; left: 0; top: 4px; width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: var(--pink-tint); color: var(--pink-deep); font-size: 18px; border: 3px solid #fff;
    box-shadow: 0 0 0 1px var(--pink-tint-mid);
  }
  .timeline-period { font-size: 12px; }

  /* Stat strip */
  .stat-num   { font-family: 'Playfair Display', serif; font-size: 2.2rem; line-height: 1; }
  .stat-label { font-size: 12px; }
</style>

<!-- PAGE HEADER -->
<section class="bg-blush hero-pad">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_crd_header_eyebrow', 'Credentials & Experience' ); ?></p>
        <h1 class="display-5 fw-bold text-plum ls-tight mb-3"><?php ppl_e( 'ppl_crd_header_heading', 'The record behind the guidance.' ); ?></h1>
        <p class="text-muted-pp mb-0 body-md mw-680 mx-auto"><?php ppl_e( 'ppl_crd_header_body', 'I value approachability, but I also value transparency: the guidance offered through this platform is grounded in rigorous training, professional responsibility, and demonstrated achievement. Browse the categories below for the full picture.' ); ?></p>
      </div>
    </div>
  </div>
</section>

<!-- FULL-BLEED IMAGE -->
<div class="w-100" style="height: 500px; overflow: hidden;">
  <img src="<?php echo esc_url( ppl_get( 'ppl_crd_fullbleed_image_url', get_stylesheet_directory_uri() . '/assets/images/pp-wallpaper.png' ) ); ?>" alt="The Pinkprint Lawyer" class="w-100 h-100" style="object-fit: cover;" />
</div>

<!-- AT A GLANCE STAT STRIP -->
<div class="border-top border-bottom border-blush py-4 bg-blush">
  <div class="container">
    <div class="row text-center g-3">
      <?php
      $stat_defaults = [
        1 => [ '3.9', 'J.D. GPA · Top 5%' ],
        2 => [ '2',   'Bar Admissions' ],
        3 => [ '6+',  'Legal Roles & Internships' ],
        4 => [ '10+', 'Publications & Features' ],
      ];
      for ( $i = 1; $i <= 4; $i++ ) :
        $num   = ppl_get( "ppl_crd_stat_{$i}_num",   $stat_defaults[ $i ][0] );
        $label = ppl_get( "ppl_crd_stat_{$i}_label", $stat_defaults[ $i ][1] );
      ?>
      <div class="col-6 col-md-3">
        <p class="stat-num text-rose mb-3 fw-bold"><?php echo esc_html( $num ); ?></p>
        <p class="text-muted-pp mb-0 stat-label text-uppercase ls-wide"><?php echo esc_html( $label ); ?></p>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</div>

<!-- EDUCATION & HONORS -->
<section class="bg-white section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-7">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_crd_education_eyebrow', 'Education & Honors' ); ?></p>
        <h2 class="text-plum ls-tight fw-bold display-6 mb-0"><?php ppl_e( 'ppl_crd_education_heading', 'Built on a foundation of academic excellence.' ); ?></h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion" id="educationAccordion">
          <?php
          $education_defaults = [
            [ 'title' => 'Juris Doctor — University at Buffalo School of Law (May 2022)',
              'body'  => '<p class="mb-3"><strong>Cumulative GPA:</strong> 3.9 &nbsp;|&nbsp; <strong>Class Rank:</strong> Top 5%</p><ul class="mb-0 ps-3"><li>Order of the Coif (2022)</li><li>Max Koren Award (2022)</li><li>Monique E. Emdin Award (2022) — recognizing commitment to community service</li><li>Promise Prize Scholar Award, Change Create Transform Foundation (2021)</li><li>John L. Hargrave Award, Minority Bar Foundation (2021)</li><li>Jessica Ortiz \'05 Federal Judicial Fellowship recipient</li></ul>' ],
            [ 'title' => 'M.S., Criminal Justice — Rochester Institute of Technology (May 2019)',
              'body'  => '<p class="mb-3"><strong>Cumulative GPA:</strong> 4.0</p><ul class="mb-0 ps-3"><li>Shaw &amp; McKay Award</li></ul>' ],
            [ 'title' => 'B.S., Criminal Justice & Communication (Double Major) — RIT (May 2018)',
              'body'  => '<p class="mb-3"><strong>Cumulative GPA:</strong> 4.0 &nbsp;|&nbsp; <strong>Class Rank:</strong> Top 1% of the entire university</p><ul class="mb-0 ps-3"><li>Center for Public Safety Initiatives\' Excellence in Research Award (2015)</li><li>RIT Outstanding Undergraduate Scholar Award (top 1%) — academic excellence, civic involvement, and research contributions</li><li>Thomas C. Castellano Award</li><li>Richard B. Lewis Award</li><li>Kearse Undergraduate Writing Award</li><li>College of Liberal Arts 2018 Undergraduate Commencement Speaker</li><li>Communication Honor Society — Lambda Pi Eta</li><li>McNair Scholars Program · RIT Honors Program</li><li>National Society of Leadership &amp; Success</li><li>Higher Education Opportunity Program (HEOP)</li></ul>' ],
          ];
          $education_raw     = get_post_meta( get_the_ID(), 'ppl_crd_education_items', true );
          $education_decoded = $education_raw ? json_decode( $education_raw, true ) : null;
          $education_items   = ( is_array( $education_decoded ) && count( $education_decoded ) ) ? $education_decoded : $education_defaults;
          foreach ( $education_items as $idx => $edu ) :
            $edu       = (array) $edu;
            $is_first  = ( $idx === 0 );
            $target_id = "eduItem{$idx}";
          ?>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button<?php echo $is_first ? '' : ' collapsed'; ?> bg-secondary bg-opacity-10" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $target_id ); ?>" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $target_id ); ?>">
                <?php echo esc_html( $edu['title'] ?? '' ); ?>
              </button>
            </h3>
            <div id="<?php echo esc_attr( $target_id ); ?>" class="accordion-collapse collapse<?php echo $is_first ? ' show' : ''; ?>" data-bs-parent="#educationAccordion">
              <div class="accordion-body body-sm text-plum">
                <?php echo wp_kses_post( $edu['body'] ?? '' ); ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BAR ADMISSIONS -->
<section class="bg-blush section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-7">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_crd_bar_eyebrow', 'Bar Admissions' ); ?></p>
        <h2 class="text-plum ls-tight fw-bold display-6 mb-0"><?php ppl_e( 'ppl_crd_bar_heading', 'Licensed to practice, ready to advocate.' ); ?></h2>
      </div>
    </div>
    <div class="row g-4 justify-content-center">
      <?php
      $bar_defaults = [
        [ 'icon' => 'bi-patch-check-fill', 'state' => 'New York',   'date' => 'Admitted January 2023' ],
        [ 'icon' => 'bi-patch-check-fill', 'state' => 'New Jersey', 'date' => 'Admitted June 2023' ],
      ];
      $bar_raw     = get_post_meta( get_the_ID(), 'ppl_crd_bar_items', true );
      $bar_decoded = $bar_raw ? json_decode( $bar_raw, true ) : null;
      $bar_items   = ( is_array( $bar_decoded ) && count( $bar_decoded ) ) ? $bar_decoded : $bar_defaults;
      foreach ( $bar_items as $bar ) :
        $bar = (array) $bar;
      ?>
      <div class="col-sm-6 col-lg-4">
        <div class="bg-white rounded-4 p-4 h-100 d-flex align-items-center gap-3">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 icon-44"><i class="bi <?php echo esc_attr( $bar['icon'] ?? 'bi-patch-check-fill' ); ?> fs-icon-md"></i></div>
          <div>
            <p class="text-plum fw-bold mb-1" style="font-size:1.15rem;"><?php echo esc_html( $bar['state'] ?? '' ); ?></p>
            <p class="text-muted-pp mb-0 body-xs"><?php echo esc_html( $bar['date'] ?? '' ); ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PROFESSIONAL EXPERIENCE (timeline) -->
<section class="bg-white section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-7">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_crd_experience_eyebrow', 'Professional Experience' ); ?></p>
        <h2 class="text-plum ls-tight fw-bold display-6 mb-0"><?php ppl_e( 'ppl_crd_experience_heading', 'A track record of practice and service.' ); ?></h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="timeline">
          <?php
          $experience_defaults = [
            [ 'icon' => 'bi-briefcase-fill', 'period' => 'Launched May 17, 2026 — Present', 'title' => 'New York City Partner & Co-Owner — Smith & Singleton Law', 'body' => 'A Black-owned law firm grounded in excellence, equity, and intentional advocacy, practicing real estate, immigration, business, and family law.' ],
            [ 'icon' => 'bi-building', 'period' => 'Sept. 2022 – Oct. 2025', 'title' => 'Real Estate Associate — Fried, Frank, Harris, Shriver & Jacobson LLP, New York, NY', 'body' => 'Supported complex, multimillion-dollar real estate financing matters. Founded the First-Generation Professionals Employee Resource Group. Provided pro bono assistance through NYLPI, Legal Services of the Hudson Valley, and Volunteers of Legal Services.' ],
            [ 'icon' => 'bi-bank', 'period' => 'Feb. – Apr. 2021', 'title' => "Extern — United States Attorney's Office, Rochester, NY", 'body' => 'Conducted legal research, drafted memoranda, and represented the United States in a federal court status hearing.' ],
            [ 'icon' => 'bi-columns-gap', 'period' => 'May – July 2020', 'title' => 'Judicial Intern — Hon. Julio Fuentes, U.S. Court of Appeals for the Third Circuit', 'body' => 'Interned at the second highest court in the United States. Drafted and revised opinions involving § 1983 claims, ERISA, and suppression of inculpatory evidence. Contributed to a precedential opinion concerning the Federal Tort Claims Act.' ],
            [ 'icon' => 'bi-folder2-open', 'period' => 'Jan. – Apr. 2017', 'title' => "Intern — Monroe County District Attorney's Office, Rochester, NY", 'body' => 'Filed and organized evidence and assisted Assistant District Attorneys with related casework tasks.' ],
            [ 'icon' => 'bi-search', 'period' => 'May 2018 – May 2019', 'title' => 'Research Assistant — Bureau of Justice Assistance, Rochester, NY', 'body' => 'Supported the National Evidentiary Value of Body-Worn Cameras Research Project, conducting qualitative research with prosecutors and defense attorneys.' ],
          ];
          $experience_raw     = get_post_meta( get_the_ID(), 'ppl_crd_experience_items', true );
          $experience_decoded = $experience_raw ? json_decode( $experience_raw, true ) : null;
          $experience_items   = ( is_array( $experience_decoded ) && count( $experience_decoded ) ) ? $experience_decoded : $experience_defaults;
          foreach ( $experience_items as $exp ) :
            $exp = (array) $exp;
          ?>
          <div class="timeline-item">
            <span class="timeline-dot"><i class="bi <?php echo esc_attr( $exp['icon'] ?? 'bi-briefcase-fill' ); ?>"></i></span>
            <p class="text-rose fw-semibold text-uppercase ls-wide mb-1 timeline-period"><?php echo esc_html( $exp['period'] ?? '' ); ?></p>
            <h4 class="text-plum fw-bold mb-1" style="font-size:1.15rem;"><?php echo esc_html( $exp['title'] ?? '' ); ?></h4>
            <p class="text-muted-pp mb-0 body-sm"><?php echo esc_html( $exp['body'] ?? '' ); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- LEADERSHIP & SERVICE -->
<section class="bg-blush section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-7">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_crd_leadership_eyebrow', 'Leadership & Service' ); ?></p>
        <h2 class="text-plum ls-tight fw-bold display-6 mb-2"><?php ppl_e( 'ppl_crd_leadership_heading', "Roles built to create structure and access where it didn't yet exist." ); ?></h2>
      </div>
    </div>
    <div class="row g-3 justify-content-center">
      <?php
      $leadership_defaults = [
        [ 'icon' => 'bi-flag-fill', 'title' => 'Founder & President, First-Generation Law Students Association', 'period' => '2021–2022' ],
        [ 'icon' => 'bi-journal-bookmark-fill', 'title' => 'Inaugural DEI Editor & Associate Editor, Buffalo Law Review', 'period' => '2021–2022 · 2020–2021' ],
        [ 'icon' => 'bi-mortarboard', 'title' => 'Faculty Research Scholar, Professor Guyora Binder', 'period' => '2020–2022' ],
        [ 'icon' => 'bi-pencil-square', 'title' => 'Writing Fellow, Professor Kate Rowan', 'period' => '2020–2021' ],
        [ 'icon' => 'bi-person-workspace', 'title' => 'Faculty Assistantships — Professor Matthew Steilen, Professor Rebecca French, Dean Gargano', 'period' => '' ],
        [ 'icon' => 'bi-mic-fill', 'title' => 'Panelist, Franklin H. Williams Judicial Commission Law Day Program', 'period' => 'May 2021' ],
        [ 'icon' => 'bi-calendar-event-fill', 'title' => 'Organizer, "Growing Up Marshall" event featuring John W. Marshall', 'period' => '' ],
        [ 'icon' => 'bi-people-fill', 'title' => 'Founder, First-Generation Professionals ERG at Fried Frank', 'period' => '' ],
      ];
      $leadership_raw     = get_post_meta( get_the_ID(), 'ppl_crd_leadership_items', true );
      $leadership_decoded = $leadership_raw ? json_decode( $leadership_raw, true ) : null;
      $leadership_items   = ( is_array( $leadership_decoded ) && count( $leadership_decoded ) ) ? $leadership_decoded : $leadership_defaults;
      foreach ( $leadership_items as $lead ) :
        $lead = (array) $lead;
      ?>
      <div class="col-6 col-lg-3">
        <div class="bg-white rounded-4 p-4 h-100 d-flex flex-column gap-2">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 icon-40"><i class="bi <?php echo esc_attr( $lead['icon'] ?? 'bi-flag-fill' ); ?> fs-icon-sm"></i></div>
          <p class="text-plum fw-semibold mb-0 body-sm"><?php echo esc_html( $lead['title'] ?? '' ); ?></p>
          <?php if ( ! empty( $lead['period'] ) ) : ?>
          <p class="text-muted-pp fw-normal mb-0 body-xs mt-auto text-muted-pp fw-normal mb-0 body-xs mt-auto border-top pt-2"><?php echo esc_html( $lead['period'] ); ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PUBLICATIONS & RECOGNITION (grouped accordions) -->
<section class="bg-white section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-7">
        <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_crd_publications_eyebrow', 'Publications & Recognition' ); ?></p>
        <h2 class="text-plum ls-tight fw-bold display-6 mb-2"><?php ppl_e( 'ppl_crd_publications_heading', 'My commitment to legal scholarship and research is central to who I am as an attorney and educator.' ); ?></h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion" id="pubAccordion">
          <?php
          $publications_defaults = [
            [ 'icon' => 'bi-journal-check', 'title' => 'Peer-Reviewed Publication',
              'body' => 'Robertson, O. N., McCluskey, J. D., Smith, S. S., &amp; Uchida, C. D. (2022). <em>Body Cameras and Adjudication: Views of Prosecutors and Public Defenders.</em> Criminal Justice Review, 49(1), 15–29.' ],
            [ 'icon' => 'bi-bank2', 'title' => 'Research Contributions Acknowledged in Leading Journals',
              'body' => '<ul class="mb-0 ps-3"><li>Police Killings as Felony Murder (with Guyora Binder &amp; Ekow Yankah), 17 Harvard Law &amp; Policy Review (2022)</li><li>Defunding Police Agencies (with Guyora Binder, Rick Su &amp; Anthony O\'Rourke), 71 Emory Law Journal (2022)</li><li>Disbanding Police Agencies (with Guyora Binder, Anthony O\'Rourke &amp; Rick Su), 121 Columbia Law Review 1327 (2021)</li><li>Criminal Law: Cases and Materials (with Guyora Binder), Wolters-Kluwer, 8th ed. (2017) / 9th ed. (2021)</li></ul>' ],
            [ 'icon' => 'bi-camera-video-fill', 'title' => 'Undergraduate Research',
              'body' => 'Smith, Shakierah &amp; McCluskey, John. (2017). <em>Body-Worn Cameras (BWCs): How Prosecutors, Public Defenders, and Judges Perceive the Implementation and Utilization of BWCs in Monroe County.</em> RIT Department of Criminal Justice/CPSI, Rochester, NY.' ],
            [ 'icon' => 'bi-star-fill', 'title' => 'University & Alumni Features',
              'body' => '<ul class="mb-0 ps-3"><li><strong>RIT Spotlights</strong> — Profile detailing my journey from first-generation RIT student to practicing attorney</li><li><strong>"Leaving a Mark at UB Law"</strong> — UB Law profile on community impact and institutional leadership</li><li><strong>"Attorney Finds Her Home in Real Estate Law"</strong> — RIT News feature on my career in commercial real estate</li><li><strong>"From Humble Beginnings to Planning for Law School"</strong> — RIT Diversity Newsletter profile</li><li><strong>"Lawyer, Entrepreneur &amp; More"</strong> — Rochester Woman Online magazine feature (May 2024)</li></ul>' ],
            [ 'icon' => 'bi-newspaper', 'title' => 'Additional Coverage',
              'body' => '<ul class="mb-0 ps-3"><li><strong>"Committing to a More Diverse Law Review"</strong> — UB Law feature on my election as inaugural DEI Editor</li><li><strong>Franklin H. Williams Judicial Commission</strong> — Featured as panelist in Law Day Program (May 2021)</li><li><strong>"Third Circuit x COVID-19: What I Learned During My Internship"</strong> — Authored blog post reflecting on lessons from the Third Circuit</li></ul>' ],
          ];
          $publications_raw     = get_post_meta( get_the_ID(), 'ppl_crd_publications_items', true );
          $publications_decoded = $publications_raw ? json_decode( $publications_raw, true ) : null;
          $publications_items   = ( is_array( $publications_decoded ) && count( $publications_decoded ) ) ? $publications_decoded : $publications_defaults;
          foreach ( $publications_items as $idx => $pub ) :
            $pub       = (array) $pub;
            $is_first  = ( $idx === 0 );
            $target_id = "pubItem{$idx}";
          ?>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button<?php echo $is_first ? '' : ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $target_id ); ?>" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $target_id ); ?>">
                <i class="bi <?php echo esc_attr( $pub['icon'] ?? 'bi-journal-check' ); ?> text-rose me-2"></i> <?php echo esc_html( $pub['title'] ?? '' ); ?>
              </button>
            </h3>
            <div id="<?php echo esc_attr( $target_id ); ?>" class="accordion-collapse collapse<?php echo $is_first ? ' show' : ''; ?>" data-bs-parent="#pubAccordion">
              <div class="accordion-body body-sm text-plum">
                <?php echo wp_kses_post( $pub['body'] ?? '' ); ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DISCLAIMER CTA -->
<section class=" py-5" style="background: var(--pink-tint-mid)">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="bg-pink-tint rounded-4 p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center gap-4 text-start">
          <div class="icon-wrap-tint rounded-circle d-flex align-items-center justify-content-start justify-content-lg-center flex-shrink-0"><i class="bi bi-shield-check fs-1 bg-secondary bg-opacity-10 p-3 rounded-4"></i></div>
          <div>
            <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow"><?php ppl_e( 'ppl_crd_disclaimer_eyebrow', 'Quick Disclaimer' ); ?></p>
            <h2 class="text-plum ls-tight fw-bold h4 mb-2"><?php ppl_e( 'ppl_crd_disclaimer_heading', 'Education first, always.' ); ?></h2>
            <p class="text-muted-pp mb-0 body-md"><?php ppl_e( 'ppl_crd_disclaimer_body', 'The Pinkprint Lawyer is an educational platform. Nothing here constitutes legal advice, nor does it create an attorney–client relationship.' ); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT CTA -->
<section class="bg-plum-mid section-pad">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-7">
        <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow"><?php ppl_e( 'ppl_crd_contact_eyebrow', 'Contact' ); ?></p>
        <h2 class="text-white ls-tight mb-4 display-6 fw-bold"><?php ppl_e( 'ppl_crd_contact_heading', 'Get in Touch' ); ?></h2>
        <p class="text-light-75 mb-4 body-lead"><?php ppl_e( 'ppl_crd_contact_body', 'I am always open to thoughtful conversation and meaningful opportunities — questions, collaborations, speaking engagements, or media inquiries. Reach out and I will do my best to respond with clarity and intention.' ); ?></p>
        <a href="<?php echo esc_url( ppl_get( 'ppl_crd_contact_cta_url', '/contact' ) ); ?>" class="btn btn-outline-light rounded-3 px-4 py-3 fw-semibold"><?php ppl_e( 'ppl_crd_contact_cta_label', 'Contact Me' ); ?> <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>
  </div>
</section>

<?php get_template_part( 'partials/ppl-footer' ); ?>
