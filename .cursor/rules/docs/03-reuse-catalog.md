# 03-reuse-catalog.md

## Reusable Components Catalog

This document catalogs all reusable building blocks in the curixus Project theme. **Use these before creating new implementations.**

## UI Components (SCSS/CSS)

### Buttons

**Location**: `scss/_components.scss`

**Class**: `.btn`

**Variants**:
- `.btn--primary` - Yellow primary button
- `.btn--secondary` - Secondary style button
- `.btn--{size}` - Size variants (if defined)

**Structure**:
```html
<a href="#" class="btn btn--primary">
    <span class="btn__text">Button Text</span>
    <span class="btn__icon btn__icon--right">
        <!-- SVG icon via sprite_svg() -->
    </span>
</a>
```

**When to use**:
- Any call-to-action link
- Form submissions
- Modal triggers
- Navigation actions

---

### Button Group

**Component**: ACF Block

**Location**: `blocks/button-group/`

**Features**:
- Multiple buttons in horizontal/vertical layout
- Configurable gap spacing
- Alignment options (left, center, right)
- Modal trigger support (integrates with Modals CPT)
- Icon support (left/right positioning)

**When to use**:
- Multiple CTAs in a section
- Modal-based interactions
- Hero sections with multiple actions
- Forms with multiple submit options

**ACF Fields**:
- `direction` - horizontal/vertical
- `alignment` - left/center/right
- `gap` - spacing between buttons (px)
- `buttons` (repeater):
  - `button_text`
  - `button_style` (primary/secondary)
  - `button_size`
  - `button_icon` (SVG icon field)
  - `icon_position` (left/right)
  - `has_modal` (true/false)
  - `select_modal` (post object - Modals CPT)
  - `button` (link field - if not modal)

---

### Card Tiles

**Component**: ACF Block

**Location**: `blocks/card-tiles/`

**Features**:
- Grid layout of cards
- Image + title + description per card
- Glass effect styling (`.glass-effect`)

**When to use**:
- Feature showcases
- Service listings
- Team member grids
- Portfolio items
- Icon + text combinations

**ACF Fields**:
- `items` (repeater):
  - `item_image` (image field)
  - `item_title` (text)
  - `item_description` (textarea/wysiwyg)

---

### Glass Effect

**Class**: `.glass-effect`

**Location**: `scss/_components.scss`

**When to use**:
- Cards that need frosted glass appearance
- Overlays on colorful backgrounds
- Modern, semi-transparent UI elements

**Example**:
```html
<div class="card-tiles__item glass-effect">
    <!-- content -->
</div>
```

---

### Container

**Class**: `.container`

**Location**: `scss/_structure.scss`

**Purpose**: Max-width content wrapper with responsive padding

**When to use**:
- Wrapping section content
- Ensuring consistent max-width (matches theme.json: 1700px content, 1920px wide)

---

### Background Sections

**System**: Scroll-driven animated backgrounds

**Location**: `inc/background-sections.php`, `js/background-sections.js`, `scss/_bg.scss`

**How to use**:
1. Apply "Background section" block style to `core/group` in Gutenberg editor
2. Select background variant from inspector panel
3. Background automatically transitions when section scrolls into view

**Available variants** (from `js/block-styles.js`):
- `intense-blue-and-purple-middle`
- `blue-and-purple-corners`
- `yellow`
- `green`
- `purple`
- `red`
- `purple-top-soft-light`
- `blue-navy`
- `top-light`
- `soft-middle-light`
- `soft-side-light`
- `bottom-left-light`
- `intense-purple-light`
- `yellow-gradient`

**When to use**:
- Hero sections with distinct visual identity
- Section transitions requiring background color shifts
- Creating visual hierarchy through color

**Technical**: Uses Intersection Observer, two-layer crossfade system for smooth transitions.

---

## Layout Components

### Header

**Location**: `header.php`

**Features**:
- Logo (normal + dark variant via Customizer)
- Header menu (custom walker with multi-level support)
- Mobile navigation toggle
- CTA buttons
- Header style variants (light/dark) via ACF field

**When to use**: Automatically loaded on all pages via WordPress template hierarchy.

**Customization**: Set `header_style` ACF field on page to change header appearance.

---

### Footer

**Location**: `footer.php`

**Features**:
- Footer logo (via Customizer)
- Contact information
- Footer menu (custom walker)
- Social links (SVG sprites from `images/socials.svg`)
- Footer info text (ACF options)
- SVG background graphic

**When to use**: Automatically loaded on all pages via WordPress template hierarchy.

**Customization**: Edit footer content via "Site Settings" options page (ACF).

---

### Navigation Menus

**Walkers**: `Header_Menu_Walker`, `Footer_Menu_Walker`

**Location**: `inc/walkers.php`

**Features**:
- Multi-level menus (header)
- ACF custom fields on menu items:
  - `menu_item_type` = 'column' (for mega menu columns)
  - `menu_item_type` = 'icons' (for icon groups)
  - `menu_item_type` = 'label' (footer labels)
- Custom arrows/openers for submenus
- Counter numbers for submenu items

**When to use**:
- Creating header navigation (location: `header-menu`)
- Creating footer navigation (location: `footer-menu`)

---

## Shared Helpers/Utilities

### SVG Sprite System

**Function**: `sprite_svg( $spriteName, $svgWidth, $svgHeight, $return = '', $file = '' )`

**Location**: `inc/helpers.php`

**Purpose**: Output SVG icons from sprite files

**When to use**:
- Any icon rendering (UI icons, social icons)
- Ensures consistent icon system
- Better than individual SVG files (reduces HTTP requests)

**Example**:
```php
sprite_svg('icon-arrow', '24', '24');  // Echoes icon
$icon = sprite_svg('icon-close', '16', '16', true);  // Returns icon HTML
```

**Sprite files**:
- `images/icons.svg` - UI icons (default)
- `images/socials.svg` - Social media icons

---

### ACF SVG Icon Field Integration

**Filter**: `acf/fields/svg_icon/file_path`

**Location**: `inc/helpers.php`

**Purpose**: ACF SVG Icon field plugin integration

**When to use**: ACF provides SVG icon picker in block/field configurations.

---

### Excerpt Trimmer

**Function**: `get_excerpt_trim( $num_words='20', $more='...', $post_id = '' )`

**Location**: `inc/helpers.php`

**When to use**:
- Blog cards/listings
- Preview text for posts
- Custom excerpt lengths

**Example**:
```php
echo get_excerpt_trim(30, '...', get_the_ID());
```

---

### YouTube ID Extractor

**Function**: `extract_youtube_id( $url )`

**Location**: `inc/helpers.php`

**Purpose**: Extract video ID from various YouTube URL formats

**When to use**:
- Video embed ACF fields
- Creating YouTube embed URLs
- Video lightbox integrations

**Example**:
```php
$video_id = extract_youtube_id('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
// Returns: dQw4w9WgXcQ
```

---

### Modal System

**Global Variable**: `$modal_storage`

**Location**: Initialized in block-render.php files, rendered in `inc/helpers.php` via `wp_footer` action

**How it works**:
1. Blocks add modals to global `$modal_storage` array during rendering
2. Footer action renders unique modals (deduplicates by modal_id)
3. Fancybox opens modals via `data-fancybox` + `data-src="#modal-{id}"`

**When to use**:
- Any button/link that triggers a modal
- Forms in modals
- Video/image galleries in modals
- Any popup content

**Usage in blocks**:
```php
global $modal_storage;
$modal_storage = $modal_storage ?? [];

// Add modal to storage
if ( $select_modal ) {
    $modal_storage[] = [
        'id'       => 'modal-' . $select_modal,
        'modal_id' => $select_modal 
    ];
}

// Trigger button
<button data-fancybox data-src="#modal-<?php echo esc_attr($select_modal); ?>">
    Open Modal
</button>
```

---

### SVG Upload Support

**Functions**: `allow_mimes()`, `fix_svg_mime_type()`

**Location**: `inc/helpers.php`

**Purpose**: Allow SVG and JSON file uploads in WordPress media library (admin only)

**When to use**: Already active - no action needed. Admins can upload SVG files.

---

### Contact Form 7 Customization

**Filters**: 
- `wpcf7_autop_or_not` - Removes auto `<br>` and `<p>` tags
- `wpcf7_form_elements` - Custom checkbox/radio markup

**Location**: `inc/helpers.php`

**Purpose**: Style CF7 forms with custom markup structure

**When to use**: Automatically applied to all CF7 forms.

---

## SCSS Mixins & Functions

### Responsive Breakpoint Mixins

**Location**: `scss/_variable.scss`

**Available mixins**:
```scss
@include desktop    { /* max-width: 1350px */ }
@include desktop-sm { /* max-width: 1200px */ }
@include tablet     { /* max-width: 991px  */ }
@include mobile     { /* max-width: 767px  */ }
@include mobile-sm  { /* max-width: 575px  */ }

// Min-width variants
@include mf-desktop { /* min-width: 992px  */ }
@include mf-tablet  { /* min-width: 768px  */ }
```

**When to use**: All responsive styling. Do NOT write raw media queries.

---

### Font Size Helper

**Function**: `px-to-rem($size)`

**Mixin**: `@mixin font-rem-min($size, $min-size)`

**Location**: `scss/_variable.scss`

**Purpose**: 
- Convert px to rem units
- Set font size with minimum pixel fallback (using `clamp()`)

**When to use**:
- Responsive typography
- Preventing text from shrinking too small on mobile

**Example**:
```scss
.title {
    @include font-rem-min(24, 18); // 24px ideal, min 18px
}
```

---

## Template Tags

**Location**: `inc/template-tags.php`

**Functions**:

### `curixus_project_posted_on()`
- Outputs post date with structured data
- Use in: Blog listings, single post templates

### `curixus_project_posted_by()`
- Outputs post author with link
- Use in: Blog listings, single post templates

### `curixus_project_entry_footer()`
- Outputs categories, tags, comments link, edit link
- Use in: Single post footer

### `curixus_project_post_thumbnail()`
- Outputs responsive post thumbnail
- Use in: Blog listings, archive pages

---

## Custom Post Types

### Modals CPT

**Slug**: `modals`

**Location**: `inc/post-types/modals.php`

**Purpose**: Store popup/modal content separately from pages

**Features**:
- Full Gutenberg editor support
- Not publicly queryable
- Used with Button Group block modal trigger

**When to use**:
- Any popup content (forms, announcements, videos, etc.)
- Content that appears on multiple pages
- Campaign-specific popups

**Usage**:
1. Create new Modal post in admin
2. Add content using Gutenberg blocks
3. Select modal in Button Group block or custom buttons
4. Modal renders automatically in footer via global storage system

---

## Theme.json Design Tokens

**Location**: `theme.json`

**Reusable via Gutenberg editor** - Use these instead of hardcoding:

### Colors
- Warm Coral (`#F88673`)
- Vivid Violet (`#926FDE`)
- Light Blue (`#6284C8`)
- Mint Green (`#54F0A3`)
- Yellow (`#FFDD0C`)
- Cloud White (`#FAFAFA`)
- Grey (`#B2B8BE`)
- Navy Blue (`#0A1428`)
- + Additional variants

### Gradients
- Gradient yellow
- Gradient yellow-orange
- Gradient blue
- Light Stroke effect
- Dark Gradient
- + More variants

### Typography
- **Families**: Montserrat (primary), Bricolage Grotesque (secondary)
- **Sizes**: H1-H6, B1-B2 (defined in theme.json)
- **Weights**: 300-900 (defined in theme.json custom settings)

### Spacing
- Predefined scale from 1rem (16px) to 17rem (272px)
- Use in block settings for consistent spacing

---

## Anti-Patterns to Avoid

❌ **DON'T** create inline styles when components exist:
```html
<!-- Bad -->
<a href="#" style="background: yellow; padding: 10px;">Click</a>

<!-- Good -->
<a href="#" class="btn btn--primary"><span class="btn__text">Click</span></a>
```

❌ **DON'T** duplicate modal rendering logic:
```php
<!-- Bad -->
<div class="modal" id="my-modal">...</div>

<!-- Good -->
Use Button Group block with Modal CPT integration
```

❌ **DON'T** write custom navigation walkers for standard menus:
```php
<!-- Bad -->
Custom walker for simple menu

<!-- Good -->
Use existing Header_Menu_Walker or Footer_Menu_Walker
```

❌ **DON'T** hardcode breakpoints:
```scss
/* Bad */
@media (max-width: 768px) { }

/* Good */
@include mobile { }
```

❌ **DON'T** create new SVG handling when sprite system exists:
```php
<!-- Bad -->
<img src="icon.svg" />

<!-- Good -->
<?php sprite_svg('icon-name', '24', '24'); ?>
```

---

## DRY Principle Enforcement

Before creating new code, check if these exist:
1. ✅ UI component in `_components.scss`
2. ✅ Helper function in `inc/helpers.php`
3. ✅ Similar block in `/blocks/`
4. ✅ Template tag in `inc/template-tags.php`
5. ✅ Custom walker in `inc/walkers.php`
6. ✅ SCSS mixin in `_variable.scss`

**If found**: Reuse and extend if needed.  
**If not found**: Create following documented patterns in `02-conventions.md`.

