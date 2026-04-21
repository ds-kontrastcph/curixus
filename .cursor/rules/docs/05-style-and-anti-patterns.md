# 05-style-and-anti-patterns.md

## Style Rules & Anti-Patterns

This document defines coding style requirements and explicitly prohibited patterns for the curixus Project theme.

---

## Mandatory Style Rules

### 1. Comments and Documentation MUST Be in English

**Rule**: All code comments, PHPDoc blocks, inline documentation, and commit messages MUST be in English.

**Rationale**: International team collaboration, maintainability, searchability.

**Examples**:

✅ **Good**:
```php
/**
 * Render SVG sprite icon.
 *
 * @param string $spriteName Icon ID from sprite file.
 * @param string $svgWidth Width in pixels.
 * @param string $svgHeight Height in pixels.
 * @param string $return Return HTML instead of echo if true.
 * @return string|void
 */
function sprite_svg( $spriteName, $svgWidth = '24', $svgHeight = '24', $return = '' ) {
    // Generate SVG markup with cache busting
    $svg = get_stylesheet_directory_uri() . '/images/icons.svg?ver='. filemtime(...);
    // ...
}
```

❌ **Bad**:
```php
// Виводимо іконку
function sprite_svg( $spriteName, $svgWidth = '24', $svgHeight = '24', $return = '' ) {
    // генеруємо SVG
    // ...
}
```

---

### 2. No Magic Numbers - Use Constants or Variables

**Rule**: Avoid hardcoded numeric values. Define as constants, variables, or configuration.

**Exceptions**: Obvious values like `0`, `1`, `100%` in specific contexts.

**Examples**:

✅ **Good**:
```php
// In functions.php or constants file
define( 'EXCERPT_LENGTH_DEFAULT', 20 );
define( 'EXCERPT_LENGTH_EXTENDED', 50 );

// In usage
$excerpt = get_excerpt_trim( EXCERPT_LENGTH_DEFAULT, '...' );
```

```scss
// In _variable.scss
$header-height: 80px;
$mobile-header-height: 60px;

// In _header.scss
.site-header {
    height: $header-height;
    
    @include mobile {
        height: $mobile-header-height;
    }
}
```

❌ **Bad**:
```php
$excerpt = get_excerpt_trim( 20, '...' );  // What is 20? Why 20?
```

```scss
.site-header {
    height: 80px;  // Why 80? Where else is this used?
}
```

**Existing usage to maintain**:
```javascript
const TRANSITION_MS = 900;  // ✅ Good - named constant
```

---

### 3. No Duplicate Code - Use Helpers and Components

**Rule**: Extract repeated code into functions, mixins, or components.

**Threshold**: If code appears 2+ times, extract it.

**Examples**:

✅ **Good**:
```php
// Helper function in inc/helpers.php
function render_social_icon( $icon_name, $url, $label ) {
    ?>
    <a href="<?php echo esc_url( $url ); ?>" 
       class="social-list__link" 
       aria-label="<?php echo esc_attr( $label ); ?>">
        <?php sprite_svg( $icon_name, '22', '22' ); ?>
    </a>
    <?php
}

// Usage in footer.php
render_social_icon( 'icon-tiktok', 'https://tiktok.com/@curixus', 'TikTok' );
render_social_icon( 'icon-instagram', 'https://instagram.com/curixus', 'Instagram' );
```

❌ **Bad**:
```php
// Repeated in footer.php, header.php, block templates
<a href="https://tiktok.com/@curixus" class="social-list__link">
    <?php sprite_svg('icon-tiktok', '22', '22'); ?>
</a>
<a href="https://instagram.com/curixus" class="social-list__link">
    <?php sprite_svg('icon-instagram', '22', '22'); ?>
</a>
// ... repeated multiple times
```

**SCSS Example**:

✅ **Good**:
```scss
// Mixin in _variable.scss
@mixin card-shadow {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
    
    &:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }
}

// Usage
.card-tiles__item {
    @include card-shadow;
}

.blog-list__item {
    @include card-shadow;
}
```

❌ **Bad**:
```scss
.card-tiles__item {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
    &:hover { box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); }
}

.blog-list__item {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
    &:hover { box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15); }
}
```

---

### 4. No Cryptic or Misleading Names

**Rule**: Use descriptive, semantic names that reveal intent.

**Naming principles**:
- **Variables**: Describe the data (`$post_thumbnail_url`, not `$img`)
- **Functions**: Describe the action (`get_excerpt_trim`, not `trim`)
- **Classes**: Describe the component (`.site-header`, not `.top`)

**Examples**:

✅ **Good**:
```php
$dark_header_logo_id = get_theme_mod( 'dark_header_logo' );
$header_style = get_field( 'header_style' );
$modal_storage = $modal_storage ?? [];

function extract_youtube_id( $url ) { /* ... */ }
function get_excerpt_trim( $num_words, $more, $post_id ) { /* ... */ }
```

```scss
.site-header__wrap { }
.btn-group__item { }
.card-tiles__description { }
```

```javascript
function getVariantFromEl(el) { }
function beginVariantTransition(bgEl, layers, nextVariant) { }
```

❌ **Bad**:
```php
$x = get_theme_mod( 'dark_header_logo' );  // What is x?
$s = get_field( 'header_style' );          // What is s?
$arr = $arr ?? [];                         // What kind of array?

function get_id( $u ) { /* ... */ }        // Get what ID? From what?
function trim( $n, $m, $p ) { /* ... */ }  // Trim what? What are n, m, p?
```

```scss
.hdr { }      // Header? Harder? Header wrapper?
.btn-gr { }   // Button group? Button gradient?
.item { }     // Too generic - item of what?
```

```javascript
function get(e) { }              // Get what? What is e?
function start(a, b, c) { }      // Start what? Unknown parameters
```

---

### 5. No Ad-Hoc Solutions Bypassing Architecture

**Rule**: Follow established patterns. Don't create workarounds that bypass the architecture.

**Anti-patterns to avoid**:

❌ **Bypassing the modal system**:
```php
// Bad - directly outputting modal in template
<div class="modal" id="my-modal">
    <div class="modal-content">
        <?php echo get_field('modal_content'); ?>
    </div>
</div>
```

✅ **Good - using the modal storage system**:
```php
global $modal_storage;
$modal_storage[] = [
    'id' => 'modal-' . $modal_id,
    'modal_id' => $modal_id
];
// Modal renders automatically in footer
```

❌ **Bypassing block registration system**:
```php
// Bad - manually registering block outside register_my_blocks()
add_action('acf/init', function() {
    register_block_type( __DIR__ . '/my-special-block' );
});
```

✅ **Good - using automatic registration**:
```php
// Just create block in /blocks/my-block/ with block.json
// Auto-registers via inc/acf.php
```

❌ **Bypassing SVG sprite system**:
```php
// Bad - inline SVG or direct file
<svg><path d="..."></path></svg>
<img src="<?php echo get_template_directory_uri(); ?>/images/icon.svg" />
```

✅ **Good - using sprite system**:
```php
<?php sprite_svg('icon-name', '24', '24'); ?>
```

❌ **Bypassing helper functions**:
```php
// Bad - reimplementing existing functionality
$excerpt = wp_trim_words( get_the_excerpt($post_id), 30, '...' );
```

✅ **Good - using existing helper**:
```php
$excerpt = get_excerpt_trim(30, '...', $post_id);
```

---

### 6. Consistent Escaping and Sanitization

**Rule**: ALWAYS escape output, sanitize input.

**Escaping functions to use**:
- `esc_html()` - Plain text content
- `esc_attr()` - HTML attributes
- `esc_url()` - URLs
- `esc_js()` - JavaScript strings
- `wp_kses_post()` - HTML content (allows safe tags)

**Examples**:

✅ **Good**:
```php
<h1><?php echo esc_html( $title ); ?></h1>
<a href="<?php echo esc_url( $button['url'] ); ?>" 
   class="<?php echo esc_attr( $class_names ); ?>">
    <?php echo esc_html( $button['title'] ); ?>
</a>

<div data-variant="<?php echo esc_attr( $variant ); ?>">
    <?php echo wp_kses_post( $content ); ?>
</div>
```

❌ **Bad**:
```php
<h1><?php echo $title; ?></h1>  // XSS vulnerable
<a href="<?php echo $button['url']; ?>">  // Not escaped
    <?php echo $button['title']; ?>
</a>
```

**ACF output** (safe by default, but still escape):
```php
// ACF already sanitizes, but best practice to escape output
<?php echo esc_html( get_field('title') ); ?>
```

---

### 7. Use WordPress Coding Standards

**Standards**: Follow WordPress PHP, HTML, CSS, and JavaScript coding standards.

**Key points**:

**PHP**:
- Yoda conditions: `if ( true === $var )`
- Braces on same line: `function name() {`
- Spaces around operators: `$a = $b + $c`
- Single quotes for strings (unless interpolation needed)

**SCSS**:
- BEM naming (`.block__element--modifier`)
- 2-space or 4-space indentation (consistent)
- Properties alphabetical or grouped logically
- One selector per line in multi-selector rules

**JavaScript**:
- camelCase for variables/functions
- SCREAMING_SNAKE_CASE for constants
- Use strict mode: `'use strict';`
- IIFE to avoid global scope pollution

**Example**:
```php
// ✅ WordPress style
if ( true === $is_preview ) {
    $classes = 'block-class';
} else {
    $classes = 'block-class block-class--variant';
}

// ❌ Non-WordPress style
if($is_preview==true){
    $classes="block-class";
}else{
    $classes="block-class block-class--variant";
}
```

---

### 8. Consistent Indentation and Formatting

**Rule**: Use consistent indentation throughout the file.

**Standards**:
- **PHP**: Tabs (WordPress standard)
- **HTML in PHP**: Tabs
- **SCSS**: 2 spaces or tabs (be consistent)
- **JavaScript**: 2 spaces

**Bad mixing example**:
```php
<?php
function example() {
    if ( $condition ) {  // Tab
      echo 'test';      // 2 spaces - inconsistent
            $var = 1;   // 6 spaces - inconsistent
    }
}
```

**Good example**:
```php
<?php
function example() {
	if ( $condition ) {
		echo 'test';
		$var = 1;
	}
}
```

---

## Prohibited Patterns

### ❌ 1. Hardcoded URLs

**Never hardcode**:
```php
// Bad
<link href="https://example.com/wp-content/themes/curixus-project/css/style.css">
<img src="/wp-content/themes/curixus-project/images/logo.png">
```

**Always use**:
```php
// Good
get_template_directory_uri() . '/css/style.css'
get_stylesheet_directory_uri() . '/images/logo.png'
```

---

### ❌ 2. Direct Database Queries

**Don't bypass WordPress API**:
```php
// Bad
global $wpdb;
$results = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_type = 'page'");
```

**Use WordPress functions**:
```php
// Good
$results = get_posts([
    'post_type' => 'page',
    'posts_per_page' => -1
]);
```

---

### ❌ 3. Inline Styles and Scripts

**Don't embed in templates**:
```php
// Bad
<style>
    .custom-class { color: red; }
</style>

<script>
    jQuery(function($) { /* ... */ });
</script>
```

**Enqueue properly**:
```php
// Good - in inc/scripts.php
wp_enqueue_style('custom-styles', get_template_directory_uri() . '/css/custom.css');
wp_enqueue_script('custom-scripts', get_template_directory_uri() . '/js/custom.js', ['jquery']);
```

**Exception**: Block preview styles (only in `$is_preview` conditional in block-render.php)

---

### ❌ 4. Global Variables Without Namespace

**Don't pollute global scope**:
```php
// Bad
$storage = [];  // Could conflict with plugins
```

**Use namespaced globals**:
```php
// Good
global $modal_storage;
$modal_storage = [];
```

Or better, use static class properties or theme options.

---

### ❌ 5. Mixing Concerns in Templates

**Don't put business logic in templates**:
```php
// Bad - in template file
<?php
function calculate_discount( $price ) {
    return $price * 0.9;
}
$discounted = calculate_discount( get_field('price') );
?>
```

**Put logic in inc/helpers.php or inc/template-functions.php**:
```php
// Good - in inc/helpers.php
function calculate_discount( $price ) {
    return $price * 0.9;
}

// In template
<?php
$discounted = calculate_discount( get_field('price') );
?>
```

---

### ❌ 6. Not Using WordPress Template Hierarchy

**Don't create custom routing**:
```php
// Bad - custom routing in functions.php
add_action('template_redirect', function() {
    if ( $_GET['custom_page'] ) {
        include get_template_directory() . '/my-custom-page.php';
        exit;
    }
});
```

**Use WordPress template hierarchy**:
```php
// Good - create page-{slug}.php or use page templates
// Template Name: Custom Page
```

---

### ❌ 7. jQuery Document Ready Soup

**Don't pile everything into one ready handler**:
```javascript
// Bad
jQuery(document).ready(function($) {
    // 500 lines of unrelated code
    $('.menu').click(function() { /* ... */ });
    $('.modal').fadeIn();
    // ... everything mixed together
});
```

**Use modular, organized code**:
```javascript
// Good - separate files for separate features
// jquery.main.js
Fancybox.bind("[data-fancybox]", {});

// background-sections.js
(function() {
    function init() { /* ... */ }
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
```

---

## Legacy Exceptions

**The following patterns exist in legacy code but SHOULD NOT be copied**:

### 1. Hardcoded Content in footer.php

**Current state** (lines 21-37 in footer.php):
```php
<p>Let curixus Light</p>
<p>Your Way</p>
<!-- ... hardcoded email, address, phone -->
```

**Should be**: ACF fields or Customizer settings

**Exception reason**: Initial development phase. Will be migrated to ACF options.

**Do NOT copy**: Always use ACF fields for editable content.

---

### 2. Some Color Values in CSS

**Current state**: Some hex values hardcoded in compiled CSS

**Should be**: All colors from `_variable.scss` or `theme.json`

**Exception reason**: Compiled legacy CSS

**Do NOT copy**: Always use SCSS variables or CSS custom properties from theme.json.

---

### 3. Structure Template (`page-templates/structure.php`)

**Current state**: Demo/style guide template with placeholder content

**Purpose**: Development reference only

**Do NOT use** in production pages.

---

## Code Review Checklist

Before committing code, verify:

- [ ] All comments in English
- [ ] No magic numbers (constants/variables used)
- [ ] No duplicate code (helpers/components used)
- [ ] Descriptive, semantic names
- [ ] Follows established architecture patterns
- [ ] Output properly escaped
- [ ] WordPress coding standards followed
- [ ] Consistent indentation
- [ ] No hardcoded URLs
- [ ] No inline styles/scripts (except preview styles)
- [ ] No direct database queries
- [ ] Business logic in inc/, not templates
- [ ] Assets properly enqueued
- [ ] Responsive (uses SCSS mixins)
- [ ] Tested in supported browsers
- [ ] No PHP/JS errors in console
- [ ] ACF JSON committed (if fields changed)

---

## Enforcing Standards

### Manual Review
- Pull request reviews by team leads
- Reference this document in code review comments

### Automated Tools (Recommended)
- **PHP_CodeSniffer** with WordPress standards
- **ESLint** for JavaScript
- **Stylelint** for SCSS/CSS

### TODO: Set up automated linting
- Add `.phpcs.xml` for PHP_CodeSniffer
- Add `.eslintrc.json` for ESLint
- Add `.stylelintrc.json` for Stylelint
- Integrate into CI/CD pipeline

---

## When Rules Can Be Broken

**Very rarely**, and only with:
1. Explicit comment explaining WHY
2. Team lead approval
3. Documentation of the exception
4. Plan to refactor later (if temporary)

**Example of acceptable exception**:
```php
// TODO: Temporary hardcode until API integration complete
// Ticket: PROJ-123
// @deprecated Remove after API v2 launch (Q2 2026)
$api_endpoint = 'https://api.example.com/v1';
```

**Most rules should NEVER be broken**:
- English comments (NEVER break)
- Output escaping (NEVER break)
- No direct SQL (NEVER break)

