<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
  <style>
    :root {
      --pink-light:    #ff89c5;
      --pink-tint:     #ffeaf4;
      --pink-tint-mid: #fbd6e9;
      --pink-deep:     #c43670;
      --blush:         #faf4f7;
      --blush-mid:     #f2e4ec;
      --plum:          #230d18;
      --plum-mid:      #3d1a2e;
      --plum-soft:     #4f2240;
      --muted-pp:      #8a6a7a;
    }
    .bg-plum       { background-color: var(--plum) !important; }
    .bg-plum-mid   { background-color: var(--plum-mid) !important; }
    .bg-rose       { background-color: var(--pink-deep) !important; }
    .bg-pink-tint  { background-color: var(--pink-tint) !important; }
    .bg-blush      { background-color: var(--blush) !important; }
    .bg-blush-mid  { background-color: var(--blush-mid) !important; }
    .text-plum     { color: var(--plum) !important; }
    .text-rose     { color: var(--pink-deep) !important; }
    .text-pink     { color: var(--pink-light) !important; }
    .text-muted-pp { color: var(--muted-pp) !important; }
    .text-light-75 { color: rgba(255,255,255,0.75) !important; }
    .text-light-60 { color: rgba(255,255,255,0.6) !important; }
    .text-light-50 { color: rgba(255,255,255,0.5) !important; }
    .border-blush  { border-color: var(--blush-mid) !important; }
    body { font-family: 'DM Sans', sans-serif; }
    h1,h2,h3,h4,h5,h6,blockquote { font-family: 'Playfair Display', serif; }
    .ls-wide  { letter-spacing: 2px; }
    .ls-tight { letter-spacing: -0.025em; }
    .section-pad { padding: 96px 0; }
    .hero-pad    { padding-top: 96px; padding-bottom: 96px; }
    .mw-480 { max-width: 480px; }
    .mw-520 { max-width: 520px; }
    .mw-560 { max-width: 560px; }
    .eyebrow  { font-size: 11px; font-family: 'DM Sans', sans-serif; }
    .body-lead { font-size: 16px; line-height: 1.75; }
    .body-md   { font-size: 16px; line-height: 1.7; }
    .body-sm   { font-size: 15px; line-height: 1.65; }
    .body-xs   { font-size: 14px; line-height: 1.65; }
    .btn-plum        { background-color: var(--plum); color: #fff; border: none; }
    .btn-plum:hover  { background-color: var(--pink-deep); color: #fff; }
    .btn-outline-plum       { background: transparent; color: var(--plum); border: 2px solid var(--plum); }
    .btn-outline-plum:hover { background-color: var(--plum); color: #fff; }
    .btn-rose        { background-color: var(--pink-deep); color: #fff; border: none; }
    .btn-rose:hover  { background-color: var(--plum); color: #fff; }
    .btn-ghost-light       { background: transparent; color: var(--plum); border: 1.5px solid var(--blush-mid); }
    .btn-ghost-light:hover { border-color: var(--pink-deep); color: var(--pink-deep); }
    .btn-outline-light       { background: transparent; color: var(--pink-light); border: 1.5px solid rgba(255,137,197,0.4); }
    .btn-outline-light:hover { background-color: var(--pink-deep); color: #fff; border-color: var(--pink-deep); }
    .btn-white       { background-color: #fff; color: var(--pink-deep); border: none; }
    .btn-white:hover { background-color: var(--blush); color: var(--plum); }
    .icon-wrap-tint  { background-color: var(--pink-tint); color: var(--pink-deep); }
    .icon-wrap-dim   { background-color: rgba(255,137,197,0.15); color: var(--pink-light); }
    .icon-wrap-ghost { background-color: rgba(255,255,255,0.2); color: #fff; }
    .icon-56 { width: 56px; height: 56px; }
    .icon-52 { width: 52px; height: 52px; }
    .icon-44 { width: 44px; height: 44px; }
    .icon-40 { width: 40px; height: 40px; }
    .icon-36 { width: 36px; height: 36px; }
    .fs-icon-xl { font-size: 28px; }
    .fs-icon-lg { font-size: 22px; }
    .fs-icon-md { font-size: 20px; }
    .fs-icon-sm { font-size: 18px; }
    .hero-img  { width: 100%; height: 540px; object-fit: cover; object-position: top;
                 filter: sepia(0.8) saturate(3) hue-rotate(280deg) brightness(1.05);
                 mix-blend-mode: multiply; display: block; }
    .about-img { width: 100%; height: 520px; object-fit: cover; object-position: top center; display: block; }
    .card-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card-lift:hover { transform: translateY(-5px); box-shadow: 0 16px 48px rgba(35,13,24,0.1); }
    .card-link       { font-size: 14px; font-weight: 600; color: var(--pink-deep); text-decoration: none; }
    .card-link:hover { color: var(--plum); }
    .card-link-light       { font-size: 14px; font-weight: 600; color: #fff; text-decoration: none; }
    .card-link-light:hover { color: var(--pink-light); }
    .card-h    { font-size: 1.3rem; }
    .card-h-md { font-size: 1.2rem; }
    .card-h-sm { font-size: 1.15rem; }
    .stage-tag { font-size: 11px; font-family: 'DM Sans', sans-serif; }
    .cred-bar-item { font-size: 15px; font-weight: 600; color: var(--pink-deep); gap: 0.6rem; }
    .cred-bar-icon { font-size: 22px; }
    .cred-divider  { width: 1px; height: 32px; background: var(--pink-tint-mid); }
    .stars          { font-size: 13px; letter-spacing: 2px; }
    .quote-icon     { font-size: 28px; }
    .testimonial-text { font-size: 1.05rem; line-height: 1.65; }
    .author-name-sm { font-size: 14px; }
    .author-role    { font-size: 12px; }
    .badge-sm    { font-size: 12px; }
    .badge-glass { background: rgba(255,255,255,0.2); font-size: 11px; font-family: 'DM Sans', sans-serif; }
    .badge-start { font-size: 11px; align-self: flex-start; font-family: 'DM Sans', sans-serif; }
    .card-glass  { background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); }
    .navbar-collapse .nav-link:hover { color: var(--pink-deep) !important; }
    .offcanvas-body .nav-link:hover  { color: var(--pink-deep) !important; }
    .footer-pad           { padding: 64px 0 32px; }
    .footer-tagline       { font-size: 14px; line-height: 1.6; }
    .footer-section-label { font-size: 11px; }
    .footer-link-sm       { font-size: 14px; }
    .footer-link-sm:hover { color: var(--pink-deep) !important; }
    .footer-meta          { font-size: 13px; }
    .footer-meta:hover    { color: var(--pink-deep) !important; }
    .ppl-social:hover { background-color: var(--pink-tint-mid) !important; color: var(--pink-deep) !important; }
    /* Contact form */
    .ppl-form-input,
    input[type=text].ppl-form-input,
    input[type=email].ppl-form-input { background-color: rgba(255,255,255,0.08) !important; border: 1.5px solid rgba(255,255,255,0.15) !important; border-radius: 10px; padding: 14px 20px !important; font-size: 15px; color: #fff !important; width: 100%; box-sizing: border-box; font-family: 'DM Sans', sans-serif; }
    .ppl-form-input::placeholder { color: rgba(255,255,255,0.35); }
    .ppl-form-input:focus { outline: none; border-color: var(--pink-light); }
    .ppl-form-label  { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.6); display: block; margin-bottom: 8px; }
    .ppl-form-submit { background-color: var(--pink-deep); color: #fff; font-size: 15px; font-weight: 600; border-radius: 10px; padding: 14px 24px; border: none; width: 100%; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .ppl-form-submit:hover { background-color: var(--pink-light); color: var(--plum); }
    .ppl-alert         { padding: 14px 20px; border-radius: 10px; font-size: 15px; margin-bottom: 20px; }
    .ppl-alert-success { background: rgba(255,137,197,0.15); color: var(--pink-light); }
    .ppl-alert-error   { background: rgba(255,80,80,0.15); color: #ff8080; }
    /* Scroll animations */
    .fade-up { opacity: 0; transform: translateY(24px); transition: opacity 0.55s ease calc(var(--stagger,0)*80ms), transform 0.55s ease calc(var(--stagger,0)*80ms); }
    .fade-up.in-view { opacity: 1; transform: none; }
  </style>
</head>
