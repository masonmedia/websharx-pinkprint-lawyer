<?php
/**
 * Template Name: Contact
 */
?>
<?php get_template_part( 'partials/ppl-head' ); ?>
<body class="bg-white ppl-contact">
<?php get_template_part( 'partials/ppl-nav' ); ?>

<?php $contact_status = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : ''; ?>


<!-- HERO -->
<section class="hero-pad" style="background-image:url('<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/contact-hero.jpg' ); ?>');background-size:cover;background-position:center;position:relative;">
<div style="position:absolute;inset:0;background:rgba(30,10,40,0.72);z-index:0;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="d-inline-flex align-items-center gap-2 bg-pink-tint text-rose rounded-pill px-3 py-2 fw-semibold mb-4 eyebrow">
          <i class="bi bi-envelope-fill"></i> Get in Touch
        </span>
        <h1 class="display-4 fw-bold text-white ls-tight mb-4"><?php echo wp_kses_post( ppl_get( 'ppl_contact_page_heading', 'Open to thoughtful conversation and meaningful opportunities.' ) ); ?></h1>
        <p class="body-lead text-light-75 mb-0"><?php ppl_e( 'ppl_contact_page_lead', 'This page is the best way to get in touch for professional inquiries, collaborations, or invitations. Whether you are reaching out with a question or exploring a potential partnership, I appreciate clarity and intention — and I do my best to respond with the same.' ); ?></p>
      </div>
      <div class="col-lg-5 d-none d-lg-flex justify-content-end">
        <div class="d-flex flex-column gap-3 w-100" style="max-width:340px;">
          <?php
          $hero_items = [
            [ 'bi-clock-fill',    'Response Time',   '1–2 business days' ],
            [ 'bi-shield-check',  'Privacy',         'Your details are never shared' ],
            [ 'bi-chat-dots-fill','Preferred Method', 'Form below is best for all inquiries' ],
          ];
          foreach ( $hero_items as $item ) :
          ?>
          <div class="d-flex align-items-center gap-3 fade-up">
            <div class="icon-wrap-dim rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 icon-44">
              <i class="bi <?php echo esc_attr( $item[0] ); ?> fs-icon-sm"></i>
            </div>
            <div>
              <p class="text-pink fw-semibold mb-0 eyebrow text-uppercase ls-wide"><?php echo esc_html( $item[1] ); ?></p>
              <p class="text-light-75 mb-0 body-sm"><?php echo esc_html( $item[2] ); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- INQUIRY TYPES -->
<section class="bg-blush section-pad">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-rose fw-semibold text-uppercase ls-wide mb-2 eyebrow">How I Can Help</p>
      <h2 class="text-plum ls-tight fw-bold display-5 mb-3"><?php ppl_e( 'ppl_contact_types_heading', 'What are you reaching out about?' ); ?></h2>
      <p class="mx-auto text-muted-pp mw-520 body-md">If you are unsure which category your message falls under, that is okay — just share the details and I will take it from there.</p>
    </div>
    <div class="row g-4">
      <?php
      $inquiry_types = [
        [
          'icon'  => 'bi-envelope-fill',
          'title' => 'General Inquiries',
          'body'  => 'Questions about The Pinkprint Lawyer, resources, or educational content can be sent here.',
          'value' => 'General Inquiry',
        ],
        [
          'icon'  => 'bi-people-fill',
          'title' => 'Collaborations &amp; Partnerships',
          'body'  => 'I am open to working with brands, organizations, and institutions that align with my values around access, education, and professional development.',
          'value' => 'Collaboration &amp; Partnership',
        ],
        [
          'icon'  => 'bi-mic-fill',
          'title' => 'Speaking Engagements',
          'body'  => 'Requests for panels, workshops, guest lectures, or speaking opportunities can be submitted here.',
          'value' => 'Speaking Engagement',
        ],
        [
          'icon'  => 'bi-newspaper',
          'title' => 'Media &amp; Press',
          'body'  => 'For interviews, features, or press-related inquiries, please use the form so your request can be routed appropriately.',
          'value' => 'Media &amp; Press',
        ],
      ];
      foreach ( $inquiry_types as $card ) :
      ?>
      <div class="col-sm-6 col-lg-3 fade-up">
        <div class="bg-white rounded-4 p-4 h-100 d-flex flex-column card-lift">
          <div class="icon-wrap-tint rounded-3 d-flex align-items-center justify-content-center mb-4 flex-shrink-0 icon-52">
            <i class="bi <?php echo esc_attr( $card['icon'] ); ?> fs-icon-lg"></i>
          </div>
          <h3 class="text-plum mb-3 fw-bold card-h-sm"><?php echo wp_kses_post( $card['title'] ); ?></h3>
          <p class="text-muted-pp mb-0 body-sm"><?php echo wp_kses_post( $card['body'] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- CONTACT FORM -->
<section class="bg-plum section-pad">
  <div class="container">
    <div class="row g-5 align-items-start">

      <div class="col-lg-5">
        <p class="text-pink fw-semibold text-uppercase ls-wide mb-3 eyebrow">Send a Message</p>
        <h2 class="text-white ls-tight mb-4 display-6 fw-bold"><?php ppl_e( 'ppl_contact_form_heading', 'Let\'s start the conversation.' ); ?></h2>
        <p class="text-light-75 mb-5 body-lead"><?php ppl_e( 'ppl_contact_form_body', 'I am always open to thoughtful conversation and meaningful opportunities. Fill out the form and I will get back to you within 1–2 business days.' ); ?></p>

        <div class="d-flex flex-column gap-4">
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
              <input type="text" id="ppl_name" name="ppl_name" class="ppl-form-input" placeholder="Your full name" required />
            </div>
            <div>
              <label class="ppl-form-label" for="ppl_email">Email <span style="color:var(--pink-light);">*</span></label>
              <input type="email" id="ppl_email" name="ppl_email" class="ppl-form-input" placeholder="Your email address" required />
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
              <textarea id="ppl_message" name="ppl_message" class="ppl-form-input" rows="5" style="resize:none;" placeholder="Share the details of your inquiry" required></textarea>
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
