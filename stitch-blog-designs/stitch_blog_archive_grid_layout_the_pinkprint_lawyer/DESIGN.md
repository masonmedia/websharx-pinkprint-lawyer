---
name: The Pinkprint Lawyer Editorial System
colors:
  surface: '#fff8f8'
  surface-dim: '#f7cfe2'
  surface-bright: '#fff8f8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#fff0f5'
  surface-container: '#ffe8f1'
  surface-container-high: '#ffe0ed'
  surface-container-highest: '#ffd8ea'
  on-surface: '#2b1421'
  on-surface-variant: '#574147'
  inverse-surface: '#422936'
  inverse-on-surface: '#ffecf3'
  outline: '#8a7077'
  outline-variant: '#debfc6'
  surface-tint: '#af2460'
  primary: '#a31958'
  on-primary: '#ffffff'
  primary-container: '#c43670'
  on-primary-container: '#ffedf0'
  inverse-primary: '#ffb1c7'
  secondary: '#9d3971'
  on-secondary: '#ffffff'
  secondary-container: '#fd87c3'
  on-secondary-container: '#781952'
  tertiary: '#6b4d5a'
  on-tertiary: '#ffffff'
  tertiary-container: '#856572'
  on-tertiary-container: '#ffedf2'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffd9e2'
  primary-fixed-dim: '#ffb1c7'
  on-primary-fixed: '#3f001c'
  on-primary-fixed-variant: '#8e0048'
  secondary-fixed: '#ffd8e7'
  secondary-fixed-dim: '#ffafd4'
  on-secondary-fixed: '#3d0026'
  on-secondary-fixed-variant: '#7f1f58'
  tertiary-fixed: '#ffd8e7'
  tertiary-fixed-dim: '#e3bccb'
  on-tertiary-fixed: '#2b141f'
  on-tertiary-fixed-variant: '#5b3e4b'
  background: '#fff8f8'
  on-background: '#2b1421'
  surface-variant: '#ffd8ea'
typography:
  display-lg:
    fontFamily: Playfair Display
    fontSize: 64px
    fontWeight: '700'
    lineHeight: 72px
    letterSpacing: -0.02em
  display-lg-mobile:
    fontFamily: Playfair Display
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
  headline-lg:
    fontFamily: Playfair Display
    fontSize: 48px
    fontWeight: '600'
    lineHeight: 56px
  headline-lg-mobile:
    fontFamily: Playfair Display
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
  headline-md:
    fontFamily: Playfair Display
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
  body-lg:
    fontFamily: Literata
    fontSize: 20px
    fontWeight: '400'
    lineHeight: 32px
  body-md:
    fontFamily: Literata
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  label-lg:
    fontFamily: Hanken Grotesk
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
    letterSpacing: 0.05em
  label-md:
    fontFamily: Hanken Grotesk
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
  caption:
    fontFamily: Literata
    fontSize: 14px
    lineHeight: 20px
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  container-max: 1200px
  gutter: 32px
  margin-mobile: 20px
  margin-desktop: 64px
  section-gap: 120px
---

## Brand & Style

The design system is anchored in a **Modern Editorial** aesthetic that bridges the gap between traditional legal authority and contemporary professional branding. It evokes a sense of "intellectual luxury"—where rigorous expertise meets a polished, feminine-forward perspective.

The visual narrative is driven by high-contrast typography, a sophisticated "warm-neutral" palette, and an uncompromising commitment to whitespace. It avoids the clutter of traditional law firm sites in favor of a clean, structured layout reminiscent of high-end digital broadsheets or fashion journals. The emotional response is one of trust, clarity, and deliberate intent.

## Colors

The color strategy for the design system replaces standard greys with a spectrum of **Plums and Blushes**. This creates a warmer, more inviting professional environment without sacrificing authority.

- **Primary & Secondary:** Used for high-impact actions, link states, and key brand accents. The deep pink (#c43670) provides the "Lawyerly" weight, while the light pink (#ff89c5) serves as a softer highlight.
- **Backgrounds:** The primary surface is a soft blush (#faf4f7). Section differentiation should be handled through the "mid-blush" (#f2e4ec) rather than harsh borders.
- **Typography & Accents:** The deepest plum (#230d18) is used for maximum legibility in body text. Muted tones (#8a6a7a) are reserved for metadata and placeholder text.

## Typography

Typography is the cornerstone of this design system. It uses a **triple-font strategy** to organize information:

1.  **Display & Headlines:** *Playfair Display* is used for its high-contrast strokes, giving article titles and section headers a classic, authoritative editorial feel.
2.  **Long-form Body:** *Literata* is selected for its exceptional readability in long-form blog posts. Its scholarly, "bookish" nature reinforces the legal expertise of the content.
3.  **UI & Metadata:** *Hanken Grotesk* provides a clean, modern contrast for navigation, buttons, and labels, ensuring the interface feels functional and current.

Large type scales are used to create a hierarchy that guides the reader through complex legal narratives with ease.

## Layout & Spacing

The layout follows a **Fixed Grid** philosophy for desktop to maintain the integrity of the editorial "column." 

- **Grid System:** A 12-column grid is used for the homepage and category layouts. For single article pages, a centered 8-column "reading lane" (approx. 800px) is utilized to optimize line length for the Literata body font.
- **Rhythm:** A vertical rhythm based on an 8px baseline ensures consistent flow. Generous section gaps (120px) allow the professional content to "breathe," preventing the reader from feeling overwhelmed by technical legal information.
- **Adaptation:** On mobile, margins shrink to 20px, and the 12-column grid collapses into a single-column stack. Typography scales down specifically for display and headline levels to maintain legibility.

## Elevation & Depth

The design system utilizes **Tonal Layering** and **Low-Contrast Outlines** rather than aggressive shadows to define depth. This approach maintains the "flatness" of a high-end printed journal.

- **Surfaces:** Depth is achieved by placing elements (like cards) on top of the `background_alt` (#f2e4ec) surface using the main `background_main` (#faf4f7) color.
- **Borders:** Thin, 1px solid borders in `pink-tint-mid` (#fbd6e9) are the primary way to define container boundaries. 
- **Shadows:** If elevation is required for interactivity (e.g., a hover state on a featured article), use a very soft, highly diffused plum-tinted shadow (0px 12px 24px rgba(35, 13, 24, 0.04)).

## Shapes

The shape language of the design system is **Soft**, utilizing subtle corner radii to temper the "sharpness" of legal discourse with approachable professionalism.

- **Standard Elements:** Buttons, input fields, and small cards use a 0.25rem (4px) radius.
- **Large Containers:** Content blocks and featured image containers use a 0.5rem (8px) radius.
- **Interactive UI:** Small utility chips and tags may use a pill-shape to distinguish them from functional buttons.

## Components

### Buttons
Primary buttons use a solid `primary_color_hex` (#c43670) with white text. Secondary buttons use a `plum` outline with `hankenGrotesk` in all-caps. Hover states should involve a subtle shift to a deeper plum or a slight background tint.

### Editorial Cards
Cards for blog posts should be minimal. They feature a large image with a 1px border, followed by a `label-lg` category tag, a `headline-md` title, and a short `body-md` excerpt. Avoid heavy drop shadows; use white space to separate cards.

### Input Fields
Forms should feel "document-like." Use a `background_main` fill with a bottom-only border or a very light all-around border in `pink-tint-mid`. Use `hankenGrotesk` for labels to ensure they are distinct from the editorial content.

### Lists & Blockquotes
- **Lists:** Use custom plum-colored bullets or numerals in `playfairDisplay`.
- **Blockquotes:** Feature a thick 4px left-accent border in `primary_color_hex`. The text should be `headline-md` in `playfairDisplay`, italicized, to highlight key legal takeaways or quotes.

### Navigation
The header should be clean and centered, featuring the wordmark in large `playfairDisplay`. Navigation links use `label-md` with generous tracking to emphasize the professional, organized nature of the site.