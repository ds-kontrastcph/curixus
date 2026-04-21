# 00-overview.md

## Project Overview

**curixus Project** is a custom WordPress theme built for the curixus marketing agency website. The theme is designed to showcase marketing services, campaigns, and agency expertise through a modern, interactive interface.

## Goal & Audience

- **Goal**: Provide a visually rich, content-driven marketing website with custom Gutenberg blocks for flexible page building
- **Audience**: Marketing professionals, potential clients, and businesses seeking digital marketing services
- **Workflow**: Content editors use WordPress Block Editor (Gutenberg) with ACF-powered custom blocks to build pages without touching code

## Tech Stack

### Frontend
- **HTML5** with WordPress PHP templates
- **SCSS** (compiled to CSS via Sass) for styling
- **Vanilla JavaScript** (ES5+) for interactive features
- **Google Fonts**: Montserrat (primary), Bricolage Grotesque (secondary)
- **Fancybox v6.1.7** for modal/lightbox functionality

### Backend/CMS
- **WordPress 6.2+** (tested up to 8.0)
- **PHP 8.0+** (minimum requirement)
- **Advanced Custom Fields (ACF)** for custom fields and block content management
- **ACF JSON** for version-controlled field definitions

### Build Tools
- **Sass** (Dart Sass 1.94.0) via npm for SCSS compilation
- **Node.js/npm** for dependency management

### Key Features
- Custom Gutenberg blocks (ACF-based)
- Scroll-driven animated background system
- Custom post types (Modals)
- Custom navigation walkers for complex menu structures
- SVG sprite system for icons
- Theme.json for Gutenberg editor configuration
- Responsive breakpoints system

## Runtime Environments

- **Development**: Local by Flywheel or similar WordPress local environment
- **Production**: Standard WordPress hosting (PHP 8.0+, MySQL/MariaDB)
- **Node environment**: For SCSS compilation during development only

## Main Entrypoints

### WordPress Template Hierarchy
1. **header.php** - Site header (logo, navigation, mobile menu)
2. **footer.php** - Site footer (contact info, menus, social links)
3. **page.php** - Default page template
4. **single.php** - Single post template
5. **archive.php** - Archive pages
6. **404.php** - Error page
7. **page-templates/structure.php** - Custom page template (structure demo)

### PHP Function Initialization
- **functions.php** - Main theme setup and module loading

### Custom Blocks (ACF-based Gutenberg Blocks)
Located in `/blocks/*/block-render.php`:
- **button-group** - Configurable button groups (supports modals)
- **card-tiles** - Grid of card items with images and descriptions

### Assets
- **CSS**: `css/style.css` (compiled from SCSS)
- **JavaScript**: 
  - `js/jquery.main.js` - Main theme scripts
  - `js/background-sections.js` - Scroll-driven background animation
  - `js/block-styles.js` - Gutenberg editor enhancements
  - `js/fancybox.umd.js` - Modal library

## High-Level Data Flow

### Request/Response Flow

```
Browser Request
    ↓
WordPress Core (Routing)
    ↓
Template Selection (template hierarchy)
    ↓
├── header.php (loads navigation, styles, scripts)
├── main template (page.php, single.php, etc.)
│   ↓
│   ACF Field Queries
│   ↓
│   Custom Block Rendering (block-render.php files)
│   ↓
│   Helper Functions (sprite_svg, get_excerpt_trim, etc.)
└── footer.php (loads modals, footer content)
    ↓
Browser Rendering
    ↓
JavaScript Initialization (DOMContentLoaded)
    ↓
Interactive Features Active (background animations, modals, navigation)
```

### Block Editor Flow (Gutenberg)

```
Admin: Add Block
    ↓
ACF Block (defined in blocks/*/block.json)
    ↓
ACF Fields (stored in acf-json/*.json)
    ↓
Preview Rendering (block-render.php with $is_preview = true)
    ↓
Save Post
    ↓
Frontend: Block Content Rendered (block-render.php with $is_preview = false)
```

### Asset Compilation Flow

```
Developer edits SCSS files in /scss/
    ↓
npm run compile-scss (watches files)
    ↓
Sass compiles SCSS → CSS
    ↓
Output to /css/*.css (with source maps)
    ↓
WordPress enqueues compiled CSS via inc/scripts.php
```

## Architecture Type

**Monolithic WordPress Theme** - Single theme package with:
- No child theme structure (standalone parent theme)
- No multi-package monorepo
- Modular organization via `/inc/` directory for feature separation
- Block-based content system via Gutenberg + ACF

## Key Integrations

- **ACF (Advanced Custom Fields)**: Core dependency for custom fields and blocks
- **WordPress Block Editor (Gutenberg)**: Native integration with theme.json
- **Google Fonts**: External CDN for typography
- **Fancybox**: Third-party library for modals/lightbox
- **SVG Sprites**: Custom SVG icon system (`images/icons.svg`, `images/socials.svg`)

## Version & Maintenance

- **Current Version**: 1.0.0
- **Theme Constant**: `_S_VERSION` (defined in functions.php)
- **Text Domain**: `curixus-project`
- **Authors**: Dmitry Shutko & Igor Drozdnyk @ Procoders
- **GitHub**: shoot56/curixus-project (main branch)

