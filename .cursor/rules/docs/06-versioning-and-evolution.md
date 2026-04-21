# 06-versioning-and-evolution.md

## Versioning & System Evolution

This document describes how versioning is handled and how to safely extend the curixus Project theme without breaking existing functionality.

---

## Current Versioning Approach

### Theme Version

**Location**: `functions.php` and `style.css`

**Current Version**: `1.0.0`

**Constant**:
```php
define( '_S_VERSION', '1.0.0' );
```

**CSS Header**:
```css
/*!
Theme Name: curixus Project
Version: 1.0.0
*/
```

**Purpose**: 
- Asset cache busting
- Theme identification
- Compatibility tracking

### Versioning Strategy: Semantic Versioning (Implied)

While not formally documented, follow **Semantic Versioning 2.0.0** principles:

**Format**: `MAJOR.MINOR.PATCH`

- **MAJOR** (1.x.x) - Breaking changes (incompatible with previous version)
- **MINOR** (x.1.x) - New features (backward compatible)
- **PATCH** (x.x.1) - Bug fixes (backward compatible)

### When to Bump Version

**PATCH version** (1.0.0 → 1.0.1):
- Bug fixes
- Security patches
- Minor CSS/JS adjustments
- Performance improvements
- Documentation updates

**MINOR version** (1.0.0 → 1.1.0):
- New blocks added
- New helper functions
- New template files
- New ACF field groups (non-breaking)
- New features that don't affect existing pages

**MAJOR version** (1.0.0 → 2.0.0):
- Removed blocks or functions
- Changed function signatures
- Changed block field structures (breaking existing content)
- Changed CSS class naming (breaking custom CSS)
- Changed template hierarchy
- WordPress minimum version change
- PHP minimum version change

### Where to Update Version

1. ✅ `functions.php` - `_S_VERSION` constant
2. ✅ `style.css` - Theme header comment
3. ✅ `package.json` - npm version field (for consistency)
4. ✅ Git tag - Create release tag matching version

**Process**:
```bash
# 1. Update version in files
# Edit functions.php, style.css, package.json

# 2. Commit changes
git add functions.php style.css package.json
git commit -m "Bump version to 1.1.0"

# 3. Create tag
git tag -a v1.1.0 -m "Release version 1.1.0"

# 4. Push with tags
git push origin main --tags
```

---

## Asset Versioning (Cache Busting)

### Automatic File-Based Versioning

**Method**: Using `filemtime()` for asset enqueuing

**Implementation** (from `inc/scripts.php`):
```php
wp_enqueue_style( 
    'style', 
    get_template_directory_uri() . '/css/style.css', 
    array(), 
    filemtime(get_template_directory() . '/css/style.css'),  // ← Cache busting
    false 
);
```

**Benefits**:
- Automatic cache invalidation when file changes
- No manual version management needed
- Works independently of theme version

**When to use**:
- CSS files (main theme and blocks)
- JavaScript files
- Any frequently updated assets

**Exception - External libraries**:
```php
// Use library version for external/vendor assets
wp_enqueue_script('fancybox', ..., '', '6.1.7', true);
```

---

## ACF Field Group Versioning

### ACF JSON as Version Control

**Location**: `/acf-json/*.json`

**How it works**:
1. Edit field groups in WordPress admin
2. ACF auto-saves to `/acf-json/group_{hash}.json`
3. Commit JSON files to git
4. Other environments auto-sync from JSON

**Version strategy**:
- **Additive changes** (new fields): Safe, backward compatible
- **Field deletion**: Breaking change - requires MAJOR version bump
- **Field rename**: Breaking change
- **Field type change**: Breaking change
- **Field key change**: Breaking change

### Safe ACF Evolution

✅ **Safe changes** (no version bump required):
```
- Add new field to existing group
- Add new field group
- Change field label (UI only)
- Change field instructions
- Change field conditional logic
- Change field default value
```

⚠️ **Breaking changes** (MAJOR version bump):
```
- Delete field (content loss)
- Rename field name (field key preserved but name changed)
- Change field type (e.g., text → wysiwyg)
- Delete field group
- Change field return format in breaking way
```

### Migration Pattern for Breaking ACF Changes

**Scenario**: Need to rename field `old_name` → `new_name`

**Safe migration**:
```php
// 1. Add new field 'new_name' to ACF
// 2. Add migration code in functions.php

add_action('acf/save_post', 'migrate_old_field_to_new', 20);
function migrate_old_field_to_new( $post_id ) {
    // Only for specific post type
    if ( get_post_type($post_id) !== 'page' ) return;
    
    $old_value = get_field('old_name', $post_id);
    $new_value = get_field('new_name', $post_id);
    
    // Migrate if new field empty but old has value
    if ( empty($new_value) && !empty($old_value) ) {
        update_field('new_name', $old_value, $post_id);
    }
}

// 3. After all content migrated, remove old field
// 4. Remove migration code
```

---

## Block Versioning

### ACF Block Version API

**Location**: `blocks/*/block.json`

**Current usage**:
```json
{
  "acf": {
    "mode": "preview",
    "blockVersion": 3
  }
}
```

**`blockVersion`**: Indicates ACF Blocks API version (not theme version)

### Evolving Blocks Safely

#### Adding Fields to Existing Block

✅ **Safe** (backward compatible):
```json
// In ACF admin, add field 'new_option' to block field group
// Existing content unaffected (field will be empty)
```

**In block-render.php**:
```php
// Check if field exists before using
if ( get_field('new_option') ) {
    // Render new feature
}
// Old blocks work fine (field just empty)
```

#### Removing Fields from Block

⚠️ **Breaking change** - requires:
1. Audit all pages using the block
2. Remove or migrate field data
3. Remove field from ACF and block-render.php
4. Bump MAJOR version

#### Changing Field Type

⚠️ **Breaking change** - requires migration:

**Example**: Change `image` field to `gallery`

```php
// Migration function
function migrate_block_field_image_to_gallery() {
    // Find all posts with block
    $posts = get_posts(['post_type' => 'page', 'posts_per_page' => -1]);
    
    foreach ($posts as $post) {
        $content = $post->post_content;
        
        // Parse blocks
        $blocks = parse_blocks($content);
        
        foreach ($blocks as &$block) {
            if ($block['blockName'] === 'yh/card-tiles') {
                // Migrate data structure
                // ... complex migration logic
            }
        }
        
        // Save updated content
        wp_update_post([
            'ID' => $post->ID,
            'post_content' => serialize_blocks($blocks)
        ]);
    }
}

// Run once via admin interface or WP-CLI
```

---

## Template Versioning

### WordPress Template Hierarchy

**No explicit versioning** - follows WordPress standards

**Safe changes**:
- Add new template files (e.g., `page-services.php`)
- Modify template HTML (test thoroughly)
- Add new template parts

**Breaking changes**:
- Remove template files used by content
- Change template file names
- Remove required markup (affects CSS/JS)

### Template Change Strategy

**Process**:
1. ✅ Test template changes in staging environment
2. ✅ Verify with real content (not dummy data)
3. ✅ Check responsive behavior
4. ✅ Verify with multiple post types
5. ✅ Document changes in commit message

**Example commit**:
```
feat: Add custom template for case studies

- New template: page-templates/case-study.php
- Includes featured image gallery
- Custom ACF fields: client_name, project_date
- Backward compatible (doesn't affect existing pages)

Version: 1.1.0
```

---

## CSS/SCSS Versioning

### BEM for Stability

**Why BEM helps**:
- Clear, predictable class names
- Avoids cascade conflicts
- Safe to add new components
- Modifications isolated to components

**Safe CSS changes**:
```scss
// ✅ Add new component
.new-component {
    // ...
}

// ✅ Add new modifier
.btn--new-style {
    // ...
}

// ✅ Add new element
.card__new-section {
    // ...
}
```

**Breaking CSS changes**:
```scss
// ❌ Rename existing class (breaks templates)
.btn { } → .button { }

// ❌ Remove class (breaks templates)
// .btn--primary { } // deleted

// ❌ Change class structure (breaks templates)
.btn { .btn__text { } } → .button { .button-text { } }
```

### CSS Migration Strategy

**If class rename necessary**:

1. **Phase 1**: Add new class, keep old (deprecation)
```scss
.btn,
.button {  // Both work
    // styles
}
```

2. **Phase 2**: Update all templates to new class

3. **Phase 3**: Remove old class in next MAJOR version

**Document deprecation**:
```scss
// @deprecated Use .button instead. Will be removed in v2.0.0
.btn {
    @extend .button;
}
```

---

## JavaScript API Stability

### Public Functions

**"Public" functions** (used across files):

Current examples:
```javascript
// In background-sections.js
// These are encapsulated in IIFE - not public

// If making public API, define clearly:
window.curixusTheme = window.curixusTheme || {};

window.curixusTheme.bgSections = {
    getVariantFromEl: getVariantFromEl,
    setVariant: function(variant) { /* ... */ }
};
```

**Versioning public JS API**:
- Document all public functions
- Never remove public functions (deprecate first)
- Maintain backward compatibility
- Use console warnings for deprecated functions

**Example deprecation**:
```javascript
window.curixusTheme.oldFunction = function() {
    console.warn('oldFunction is deprecated. Use newFunction instead. Will be removed in v2.0.0');
    return window.curixusTheme.newFunction.apply(this, arguments);
};
```

---

## Database Schema (Custom Tables)

**Current state**: No custom tables (uses WordPress core tables only)

**If adding custom tables in future**:

### Migration Pattern

```php
// In functions.php or inc/database.php

define( 'curixus_DB_VERSION', '1.0' );

function curixus_install_db() {
    global $wpdb;
    
    $installed_ver = get_option( 'curixus_db_version' );
    
    if ( $installed_ver != curixus_DB_VERSION ) {
        // Create or update schema
        $table_name = $wpdb->prefix . 'curixus_data';
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        update_option( 'curixus_db_version', curixus_DB_VERSION );
    }
}
register_activation_hook( __FILE__, 'curixus_install_db' );

// Check version on theme updates
add_action( 'after_switch_theme', 'curixus_install_db' );
```

---

## Extending Without Breaking

### Adding New Features Safely

#### ✅ Safe Extensions

**1. New Blocks**
```
Create /blocks/new-block/
- block.json
- block-render.php
- style.scss
```
**Impact**: None on existing content

**2. New Helper Functions**
```php
// Add to inc/helpers.php
function new_helper_function( $param ) {
    // Implementation
}
```
**Impact**: None on existing code (only if called)

**3. New Post Types**
```php
// Add inc/post-types/new-cpt.php
// Register in init-cpt.php
```
**Impact**: None on existing content

**4. New Template Parts**
```
Create template-parts/new-component.php
```
**Impact**: None (only if explicitly included)

**5. New SCSS Partials**
```scss
// Create scss/_new-component.scss
// Import in style.scss
@import "_new-component";
```
**Impact**: Additive only (no conflicts if BEM used)

---

#### ⚠️ Changes Requiring Care

**1. Modifying Core Files**

**Changes to**:
- `functions.php`
- `header.php`
- `footer.php`
- Core SCSS files (`_variable.scss`, `_reset.scss`)

**Process**:
- ✅ Test thoroughly on staging
- ✅ Check all pages/post types
- ✅ Verify responsive design
- ✅ Check browser compatibility
- ✅ Review with team lead

**2. Changing ACF Field Groups**

**Process**:
- ✅ Export JSON backup before changes
- ✅ Test with existing content
- ✅ Verify blocks still render correctly
- ✅ Document field changes in commit

**3. Modifying Existing Blocks**

**Safe modifications**:
- Change HTML wrapper (test styling)
- Add conditional features (backward compatible)
- Add new ACF fields (existing blocks unaffected)

**Unsafe modifications**:
- Remove ACF fields
- Change field names/keys
- Change block slug (breaks content)

---

### Backward Compatibility Checklist

Before deploying changes, verify:

- [ ] Existing pages render correctly
- [ ] Existing blocks display properly
- [ ] No console errors (JS)
- [ ] No PHP warnings/errors
- [ ] ACF fields accessible in editor
- [ ] Modals still open correctly
- [ ] Navigation menus work
- [ ] Background animations functional
- [ ] Mobile responsive (all breakpoints)
- [ ] Forms submit correctly
- [ ] SVG sprites render
- [ ] Fancybox opens properly

---

## Deprecation Strategy

### Marking Code as Deprecated

**PHP**:
```php
/**
 * Old helper function.
 *
 * @deprecated 1.5.0 Use new_helper_function() instead.
 * @see new_helper_function()
 *
 * @param string $param Parameter description.
 * @return string
 */
function old_helper_function( $param ) {
    _deprecated_function( __FUNCTION__, '1.5.0', 'new_helper_function' );
    return new_helper_function( $param );
}
```

**SCSS**:
```scss
// @deprecated 1.5.0 Use .new-class instead. Will be removed in 2.0.0
.old-class {
    @extend .new-class;
}
```

**JavaScript**:
```javascript
/**
 * @deprecated Since version 1.5.0. Use newFunction instead.
 */
function oldFunction() {
    console.warn('oldFunction is deprecated since 1.5.0. Use newFunction. Will be removed in 2.0.0');
    return newFunction.apply(this, arguments);
}
```

### Deprecation Timeline

**Phase 1** (Minor version, e.g., 1.5.0):
- Mark as deprecated
- Add warnings
- Document replacement
- Keep functionality working

**Phase 2** (Next minor, e.g., 1.6.0):
- Continue warnings
- Update documentation

**Phase 3** (Major version, e.g., 2.0.0):
- Remove deprecated code
- Document breaking changes in release notes

---

## Changelog Management

### TODO: Implement CHANGELOG.md

**Recommended format** (Keep a Changelog):

```markdown
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- New feature X

### Changed
- Updated component Y

### Deprecated
- Function Z (use new_function_z instead)

### Removed
- Legacy template A

### Fixed
- Bug in modal rendering

### Security
- Fixed XSS vulnerability in helper function

## [1.1.0] - 2026-01-15

### Added
- Background animation system
- Card tiles block
- Button group block with modal support

### Changed
- Updated Fancybox to 6.1.7

## [1.0.0] - 2025-12-01

### Added
- Initial release
- Custom theme structure
- ACF integration
```

**Update changelog**:
- On every commit (Unreleased section)
- On version bump (move Unreleased to versioned section)

---

## Release Process

### Recommended Release Workflow

**1. Pre-release**:
```bash
# Ensure on main branch
git checkout main
git pull origin main

# Run tests (if implemented)
npm run test

# Verify no uncommitted changes
git status
```

**2. Version bump**:
```bash
# Edit version in:
# - functions.php
# - style.css
# - package.json

# Update CHANGELOG.md (move Unreleased to version)
```

**3. Commit and tag**:
```bash
git add functions.php style.css package.json CHANGELOG.md
git commit -m "Release version 1.2.0"
git tag -a v1.2.0 -m "Release version 1.2.0

Release notes:
- Feature A
- Feature B
- Bug fix C
"
```

**4. Push**:
```bash
git push origin main
git push origin v1.2.0
```

**5. GitHub Release** (if using GitHub):
- Create release from tag
- Copy changelog for this version
- Attach theme ZIP (if distributing)

---

## Rollback Strategy

### If Update Breaks Production

**Immediate rollback**:
```bash
# Option 1: Revert to previous commit
git revert HEAD
git push origin main

# Option 2: Checkout previous version tag
git checkout v1.0.0
# Then deploy previous version

# Option 3: Restore from backup
# Restore theme files from server backup
```

**Database rollback** (if schema changed):
```php
// If custom tables were modified, include rollback in migration code
function curixus_rollback_db_to_version( $target_version ) {
    // Rollback schema changes
    // Restore data from backups
}
```

**Always maintain**:
- Database backups before updates
- Theme file backups
- Content exports (XML)
- ACF JSON files committed to git

---

## Future Versioning Improvements

### TODO: Implement

1. **Automated versioning**
   - Use tool like `semantic-release`
   - Auto-generate changelog from commits

2. **Migration system**
   - Version-aware migrations
   - Rollback support
   - Migration logs

3. **Feature flags**
   - Enable new features gradually
   - A/B test changes
   - Safe rollback without redeployment

4. **Automated testing**
   - Unit tests (PHPUnit)
   - Integration tests
   - Visual regression tests (Percy, BackstopJS)

5. **CI/CD pipeline**
   - Automated deployment
   - Automated testing
   - Staging environment testing

---

## Summary: Safe Evolution Principles

1. ✅ **Add, don't remove** - Additive changes are safe
2. ✅ **Deprecate before removing** - Give time for migration
3. ✅ **Version semantically** - MAJOR.MINOR.PATCH
4. ✅ **Test thoroughly** - Staging before production
5. ✅ **Document changes** - Changelog and commit messages
6. ✅ **Maintain backward compatibility** - Within major version
7. ✅ **Use migrations** - For breaking data structure changes
8. ✅ **Keep backups** - Database and files before updates

**Golden rule**: If existing content might break, it's a MAJOR version change.

