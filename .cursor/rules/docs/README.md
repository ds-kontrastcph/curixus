# curixus Project Theme Documentation

**Version**: 1.0.0  
**Last Updated**: April 21, 2026  
**Authors**: Procoders

---

## About This Documentation

This documentation set serves as the **single source of truth** for the curixus Project WordPress theme. It describes the actual, implemented architecture and conventions—not theoretical or aspirational patterns.

All information is extracted from and validated against the codebase as of version 1.0.0.

---

## Documentation Structure

### [00-overview.md](./00-overview.md)
**Start here** if you're new to the project.

Covers:
- Project goals and audience
- Tech stack (WordPress, ACF, SCSS, JavaScript)
- Runtime environments
- Main entrypoints (templates, blocks, assets)
- High-level data flow
- Architecture type (monolithic WordPress theme)

**Read this first** to understand what the project is and how it works at a high level.

---

### [01-architecture.md](./01-architecture.md)
**Deep dive** into system architecture.

Covers:
- Layered architecture (Presentation, Block, Application, Data, Asset layers)
- Modules and domains (theme setup, blocks, CPTs, navigation, effects)
- Dependency rules (what can depend on what)
- Mermaid diagrams:
  - Module dependency graph
  - Request/response flow
  - Block rendering flow
  - Background animation state machine
- File organization patterns
- Scaling considerations

**Read this** to understand how the codebase is structured and why.

---

### [02-conventions.md](./02-conventions.md)
**Coding standards and naming conventions.**

Covers:
- File and directory naming (templates, blocks, SCSS, JS)
- PHP naming (functions, classes, variables, constants)
- CSS/SCSS naming (BEM methodology)
- JavaScript naming (camelCase, constants)
- ACF field naming
- Code organization patterns (blocks, CPTs, helpers, walkers)
- SCSS organization (import order, breakpoints, variables)
- JavaScript patterns (IIFE, DOM ready, Gutenberg)
- WordPress integration patterns (enqueuing, ACF, SVG sprites)
- Rule of thumb for adding new code

**Read this** before writing any code. Follow these conventions rigorously.

---

### [03-reuse-catalog.md](./03-reuse-catalog.md)
**Catalog of reusable components and helpers.**

Covers:
- UI components (buttons, button groups, card tiles, glass effect)
- Layout components (header, footer, navigation menus)
- Background section system
- Shared helpers (SVG sprites, excerpt trimmer, YouTube extractor, modal system)
- SCSS mixins and functions (responsive breakpoints, font helpers)
- Template tags
- Custom post types (Modals)
- Theme.json design tokens
- Anti-patterns to avoid (inline styles, duplicate modals, etc.)
- DRY enforcement checklist

**Read this** before creating new components. Reuse existing code whenever possible.

---

### [04-integrations-and-infra.md](./04-integrations-and-infra.md)
**External services and infrastructure.**

Covers:
- External services (Google Fonts, Fancybox, ACF, CF7, Gutenberg)
- Configuration patterns (constants, customizer, ACF options, SVG sprites)
- Logging and monitoring (WordPress debug mode)
- Performance considerations (cache busting, font optimization, SVG sprites)
- Build process (SCSS compilation via Sass)
- Deployment checklist
- Security considerations (escaping, sanitization, SVG upload restrictions)
- Infrastructure diagram

**Read this** to understand third-party dependencies and how to configure the theme.

---

### [05-style-and-anti-patterns.md](./05-style-and-anti-patterns.md)
**Mandatory style rules and prohibited patterns.**

Covers:
- Mandatory rules:
  1. Comments in English (ALWAYS)
  2. No magic numbers (use constants)
  3. No duplicate code (use helpers)
  4. No cryptic names (descriptive, semantic)
  5. No ad-hoc solutions (follow architecture)
  6. Consistent escaping/sanitization
  7. WordPress coding standards
  8. Consistent indentation
- Prohibited patterns (hardcoded URLs, direct DB queries, inline styles, etc.)
- Legacy exceptions (what NOT to copy)
- Code review checklist
- When rules can be broken (rarely, with approval)

**Read this** to understand quality standards. This document is used in code reviews.

---

### [06-versioning-and-evolution.md](./06-versioning-and-evolution.md)
**How to version and safely extend the system.**

Covers:
- Theme versioning (semantic versioning: MAJOR.MINOR.PATCH)
- When to bump versions
- Asset versioning (cache busting via `filemtime()`)
- ACF field group versioning (safe vs breaking changes)
- Block versioning (adding/removing fields safely)
- Template versioning
- CSS/SCSS versioning (BEM stability)
- JavaScript API stability
- Database schema migrations (if needed in future)
- Extending without breaking (safe additions vs risky changes)
- Backward compatibility checklist
- Deprecation strategy
- Changelog management (TODO: implement)
- Release process
- Rollback strategy

**Read this** before making significant changes or releasing new versions.

---

## Documentation Goals

This documentation is designed to:

1. **Onboard new developers** quickly and accurately
2. **Maintain consistency** across all code contributions
3. **Prevent architectural drift** by documenting actual patterns
4. **Enable safe evolution** without breaking existing functionality
5. **Support .cursor/rules generation** (compact, factual reference)
6. **Serve as code review reference** (standards enforcement)

---

## How to Use This Documentation

### For New Team Members
1. Read [00-overview.md](./00-overview.md) - Understand the project
2. Read [01-architecture.md](./01-architecture.md) - Understand the structure
3. Skim [03-reuse-catalog.md](./03-reuse-catalog.md) - Know what's available
4. Keep [02-conventions.md](./02-conventions.md) open while coding

### For Code Reviews
1. Reference [02-conventions.md](./02-conventions.md) - Naming and patterns
2. Reference [05-style-and-anti-patterns.md](./05-style-and-anti-patterns.md) - Quality standards
3. Check [03-reuse-catalog.md](./03-reuse-catalog.md) - Is existing code being reused?

### For Feature Development
1. Check [03-reuse-catalog.md](./03-reuse-catalog.md) - Can I reuse something?
2. Check [02-conventions.md](./02-conventions.md) - How should I structure this?
3. Check [01-architecture.md](./01-architecture.md) - Where does this fit?
4. Check [06-versioning-and-evolution.md](./06-versioning-and-evolution.md) - Is this a breaking change?

### For Debugging Integrations
1. Check [04-integrations-and-infra.md](./04-integrations-and-infra.md) - How is X configured?
2. Check [00-overview.md](./00-overview.md) - What's the data flow?

---

## Keeping Documentation Updated

**This documentation MUST stay synchronized with the code.**

### When to Update
- ✅ **New feature added** → Update relevant sections
- ✅ **Pattern changed** → Update conventions
- ✅ **Integration added** → Update integrations doc
- ✅ **Breaking change** → Update versioning doc + mark old pattern as deprecated
- ✅ **Major refactor** → Update architecture doc

### Who Updates
- **Developer**: Update docs in same PR as code changes
- **Tech Lead**: Review doc accuracy during PR review
- **Team Lead**: Periodic audit (quarterly recommended)

### How to Update
1. Edit Markdown files in `/docs/`
2. Keep Mermaid diagrams updated (use [Mermaid Live Editor](https://mermaid.live/))
3. Mark obsolete information as deprecated (don't just delete)
4. Update "Last Updated" date in this README
5. Commit docs with code changes (not separately)

---

## TODO Items Across Documentation

Items marked as "TODO" indicate:
- Features not yet implemented
- Improvements recommended
- Infrastructure not yet set up

**High-priority TODOs**:
- [ ] Implement `CHANGELOG.md` (see [06-versioning-and-evolution.md](./06-versioning-and-evolution.md))
- [ ] Set up automated linting (PHP_CodeSniffer, ESLint, Stylelint)
- [ ] Move hardcoded footer content to ACF options
- [ ] Clarify any tech lead questions marked in docs

---

## Documentation Conventions

### File Naming
- Numbered for reading order: `00-`, `01-`, etc.
- Lowercase with hyphens: `overview.md`, `style-and-anti-patterns.md`

### Markdown Standards
- Use `###` for main sections, `####` for subsections
- Use ✅ for good examples, ❌ for bad examples
- Use code fences with language: ` ```php `, ` ```scss `, ` ```javascript `
- Use Mermaid for diagrams
- Use tables for comparisons
- Use emoji sparingly (✅ ❌ ⚠️ only)

### Factual Accuracy
- **NEVER hallucinate**: All information verified against actual code
- **Mark speculation**: Use "TODO: Clarify with tech lead" for unknowns
- **Document reality**: Describe what IS, not what SHOULD BE (except in TODO sections)

---

## Converting to .cursor/rules

These documentation files can be distilled into compact `.cursor/rules` for AI-assisted development:

**Example extraction**:
```
From 02-conventions.md:
- File naming: lowercase-with-hyphens
- Functions: {theme_prefix}_{name}
- Classes: PascalCase_With_Underscores
- SCSS: BEM methodology (.block__element--modifier)

From 05-style-and-anti-patterns.md:
- ALWAYS escape output (esc_html, esc_attr, esc_url)
- NEVER hardcode URLs (use get_template_directory_uri)
- NEVER inline styles (enqueue properly)
```

**Suggested .cursor/rules structure**:
1. Project context (from 00-overview.md)
2. Key conventions (from 02-conventions.md)
3. Reusable components list (from 03-reuse-catalog.md)
4. Prohibited patterns (from 05-style-and-anti-patterns.md)
5. When to bump version (from 06-versioning-and-evolution.md)

---

## Questions or Clarifications

If documentation is unclear or appears incorrect:

1. **Check the code first** - Documentation describes reality, code is source of truth
2. **Ask tech lead** - Dmitry Shutko or Igor Drozdnyk
3. **Update documentation** - Fix inaccuracies in same PR as code fix
4. **Mark as TODO** - If answer is unknown, mark "TODO: Clarify with tech lead"

---

## License & Attribution

**Theme License**: GPL v2 or later (WordPress standard)

**Documentation License**: Same as theme (GPL v2+)

**Authors**: 
- Dmitry Shutko (shoot56) - https://procoders.tech/
- Igor Drozdnyk - https://procoders.tech/

**GitHub**: shoot56/curixus-project (main branch)

**Client**: curixus Marketing Agency

---

## Document Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2026-01-05 | 1.0.0 | Initial documentation creation | AI Analysis + Human Review |

---

**Last Updated**: January 5, 2026  
**Documentation Version**: 1.0.0  
**Theme Version**: 1.0.0

