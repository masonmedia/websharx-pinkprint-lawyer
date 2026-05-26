# Pinkprint Lawyer 

Goal: create an elegant, modern, functional and working homepage for a new website called The Pinkprint Lawyer.

Create 3 page mockups with contrasting design styles and page layouts using the content and design references below.

For the mockups use simple HTML/CSS/JS/Bootstrap 5 CDN. Once approved, move on to building the working prototype with Nuxt under the `## Stack` section.

Consider all angles of a production home page and try to make it viable in the first try:
- Great UI: sizing, spacing, design, color choice, symmetry, balance, and variation
- Great UX: ease of use, clear navigation
- High converting: design with conversion in mind.


## Design

1. Read all files in /assets for guidelines and references to:
> Brand guide, color palette, typography in /design/
> Logos in /logos/
> design potential references in /book_covers/

2. Use /DESIGN-clay.md for the site design system (spacing, sizing, etc) incorporating the above Pink Lawyer colors, fonts, etc.

3. Generate light and dark page variations loosely based on the existing light and dark logo and /book_cover/Book (2) specifically for dark reference in general.


## Content and site core outline

- Read from `/pinkprint-home-outline.md` for content and use it to extrapolate best-case foundation to generate page layout.

- Use it to create 3 mockup variations based on the content and;

- incorporate the law website links below for design reference:
> https://mcmillan.ca/
> https://ingenuitylegal.com/
> https://www.foglers.com/
> https://miro.com/
> https://apple.com

- Source law/legal related subject images from unsplash.com

### How to use and parse content

- Explicitly use the content in the `/pinkprint-home-outline.md` document; 
- if you want to generate or synthsize text, tell me first to approve or not.
- you may parse content for better web layout
- do not omit or delete content
- focus on the content from the homepage, but cherry pick elements from different sections to flesh out a standard home page



## Stack

1. Nuxt 4, Bootstrap 5 CSS, Wordpress API, Netlify deployment.

> use Bootstrap utility classes exclusively
> Use Bootstrap components i.e. Nav (use an offcanvas right for mobile), and accordions, any modals, buttons, etc. 
> Do not use inline styles of any kind
> For necessary custom styles (colors etc) use /assets/css/main.css OR if scoped, keep inside SFC style tag.

2. Wordpress monolith using Divi or Elementor builder plugins (I don't know how to use these so a simple guide or code to execute the design is needed).

> inform as to whether there is a way for you to generate the Divi layout/code, or if not, if there is a Claude plugin for wordpress that might do that task.

## Coding and syntax

1. Be simple. Be DRY. Do not over-complicate or over-componentize.
2. Be efficient.
3. Think ahead - make code clean, easy to read, easy to maintain, easy to scale.
4. The page has to be RESPONSIVE out of the box. Every component and line of code needs to be considered in all screen sizes and environments.
5. a11y: Maimize and optimize the page for accessibility.

---

# CLAUDE.md general project guide

## 1. Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

Before implementing:
- State your assumptions explicitly. If uncertain, ask.
- If multiple interpretations exist, present them - don't pick silently.
- If a simpler approach exists, say so. Push back when warranted.
- If something is unclear, stop. Name what's confusing. Ask.

## 2. Simplicity First

**Minimum code that solves the problem. Nothing speculative.**

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.
- If you write 200 lines and it could be 50, rewrite it.

Ask yourself: "Would a senior engineer say this is overcomplicated?" If yes, simplify.

## 3. Surgical Changes

**Touch only what you must. Clean up only your own mess.**

When editing existing code:
- Don't "improve" adjacent code, comments, or formatting.
- Don't refactor things that aren't broken.
- Match existing style, even if you'd do it differently.
- If you notice unrelated dead code, mention it - don't delete it.

When your changes create orphans:
- Remove imports/variables/functions that YOUR changes made unused.
- Don't remove pre-existing dead code unless asked.

The test: Every changed line should trace directly to the user's request.

## 4. Goal-Driven Execution

**Define success criteria. Loop until verified.**

Transform tasks into verifiable goals:
- "Add validation" → "Write tests for invalid inputs, then make them pass"
- "Fix the bug" → "Write a test that reproduces it, then make it pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:
```
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
```

Strong success criteria let you loop independently. Weak criteria ("make it work") require constant clarification.


Add these to my skills.

# General search, thinking, and answering

- If you take longer than 30 seconds on any answer, stop and give me an update.
- If you are encountering the same problem, the same error, and I prompt you multiple times about the same thing: Stop and reset. Do not keep trying the same thing. Think differently. Approach the problem differently. Try something else. Update me on what you are encountering with the problem, what you have tried, what isn't working, and what you are doing to change the tactic.

# Debug

- Follow my directions on which file to look at
- Follow my directions on which text content or title or UI element to target
- Assume Bootstrap and other scaffolding is working and imported properly
- Assume it isn't a global problem or something in the store
- Do not spend more than 1-2 minutes debugging: If you do stop and give me an update