# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

**Compile SCSS (watch mode):**
```bash
npm run compile-scss
```
This compiles both `scss/` → `css/` and `blocks/*/style.scss` → `blocks/*/style.css` simultaneously, in watch mode.

**One-shot compile (no watch):**
```bash
npx sass blocks:blocks scss:css
```

There is no test suite or linter configured at the npm level. PHP linting is available via Composer if dependencies are installed:
```bash
composer run lint:php
composer run lint:wpcs
```

## Architecture

### Theme Foundation
Built on the [Underscores (_s)](https://underscores.me/) starter theme. `functions.php` is the entry point — it requires all files from `inc/`:

| File | Purpose |
|---|---|
| `inc/scripts.php` | Enqueues `css/style.css` and `js/navigation.js` |
| `inc/acf.php` | Block registration, ACF JSON save/load paths, Gutenberg block categories, ACF options page |
| `inc/helpers.php` | SVG sprite helpers, SVG/JSON upload permissions |
| `inc/template-tags.php` / `template-functions.php` | Standard _s template utilities |

### ACF Gutenberg Block System
Custom blocks live in `blocks/<block-name>/` and are **auto-registered** via `glob()` in `inc/acf.php`. Each block directory must contain:
- `block.json` — block metadata (`"acf": { "blockVersion": 3, "renderTemplate": "block-render.php" }`)
- `block-render.php` — PHP render template with access to ACF fields
- `style.scss` / `style.css` — block-scoped styles

To add a new block: create a directory under `blocks/`, add `block.json` and `block-render.php`, and it will be picked up automatically. Two custom categories are available: `"curixus"` (Custom Blocks) and `"curixus_blocks"` (Landing Page).

ACF field group JSON is stored in and loaded from `acf-json/`.

### SCSS Structure
Global styles:
```
scss/style.scss          → css/style.css
  ├── _variable.scss     (breakpoint mixins, color vars, font vars, px-to-rem util)
  ├── _reset.scss
  ├── _components.scss   (12-col grid: .grid-row / .grid-col--{1-12})
  ├── _header.scss
  └── _footer.scss
```
Block styles compile separately: `blocks/*/style.scss` → `blocks/*/style.css`.

### Design Tokens (`scss/_variable.scss`)
**Colors:**
- `$clr-primary: #FFDD0C` — yellow (brand primary)
- `$clr-base: #10233A` — dark blue (default text/bg)
- `$clr-navy: #0A1428`
- `$clr-white: #FAFAFA`, `$white: #FFFFFF`
- `$clr-coral`, `$clr-violet`, `$clr-blue`, `$clr-green` — accent palette

**Breakpoint mixins (max-width, mobile-first pattern):**
- `@include desktop` — 1350px
- `@include desktop-sm` — 1200px
- `@include desktop-xs` / `desktop-1024` — 1024px
- `@include tablet` — 991px
- `@include mobile` — 767px
- `@include mobile-sm` — 575px
- `@include mf-desktop` / `mf-tablet` / `min-tablet` — min-width variants

**Layout:** Content width 1320px, wide width 1440px (set in `theme.json`). Font: Manrope (both primary and secondary families).

### SVG Icon System
Two sprite files in `images/`: `icons.svg` and `socials.svg`. Use the helpers from `inc/helpers.php`:
```php
sprite_svg($attachment_id, '24', '24');         // echoes icon from icons.svg
sprite_svg_social($attachment_id, '22', '22');  // echoes icon from socials.svg
// Pass a truthy 4th arg to return string instead of echo
```

### Modal Pattern
Blocks that open modals use Fancybox (`data-fancybox`, `data-src="#modal-{id}"`). A global `$modal_storage` array (initialized in `button-group` block) deduplicates modal markup across multiple block instances on the same page.

### `theme.json`
Defines the WordPress editor's color palette, spacing scale (1rem–17rem slugs), typography scale (H1–H5, B1–B2), and disables default WordPress palettes/gradients to enforce the custom design system.
