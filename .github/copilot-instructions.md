# SYSTEM INSTRUCTIONS: ZZ Prompts Theme (ThemeForest)

**Role:** Senior WordPress Architect & Performance Expert.  
**Goal:** Build production-ready theme using Vanilla CSS/PHP based on HTML Designs.  
**Constraint:** Code must be production-ready, secure, bloat-free, and RTL-ready.

---

## 1. PROJECT OVERVIEW

| Key | Value |
|-----|-------|
| **Theme Name** | ZZ Prompts |
| **Type** | Classic WordPress Theme (ThemeForest) |
| **Stack** | PHP, Vanilla CSS (BEM), jQuery |
| **Build Tools** | None (no npm/webpack) |
| **Namespace** | All classes use `zz-` prefix |

---

## 2. ARCHITECTURE & FILE SYSTEM (STRICT)

**Do NOT create new root files.** Use the existing structure:

### CSS Structure
```
assets/css/
├── core/               # Global Logic
│   ├── _variables.css  # Design tokens (ALWAYS check before adding colors)
│   ├── _reset.css
│   ├── _grid.css
│   ├── _typography.css
│   └── _forms.css
├── skins/modern/       # Visual Skin (Glassmorphism)
│   ├── header.css
│   ├── footer.css
│   ├── cards.css
│   ├── sidebar.css
│   └── buttons.css
├── components/         # Reusable components
│   ├── widgets.css
│   ├── pagination.css
│   ├── breadcrumbs.css
│   └── badges.css
├── pages/              # Page-specific styles
│   ├── home.css
│   ├── archive-prompts.css
│   ├── blog-single.css
│   └── search-results.css
├── i18n/               # RTL overrides
│   └── _rtl.css
├── shared-core.css     # Loader for core files
└── skin.css            # Main skin loader
```

### PHP Structure
```
├── template-parts/
│   ├── prompt/
│   │   ├── single-prompt-hero.php    # Single prompt page hero
│   │   ├── card-prompt.php           # Archive prompt card
│   │   └── content-single.php        # Full single prompt content
│   ├── blog/
│   │   ├── single-blog.php           # Blog single (Glass UI)
│   │   ├── single-blog-content.php   # Blog single content
│   │   └── card-blog.php             # Blog archive card
│   ├── home/
│   │   └── hero-home.php             # Homepage hero
│   ├── footer/
│   │   └── footer-main.php           # Main footer
│   ├── sidebar-prompts.php           # Prompt archive sidebar
│   ├── sidebar-blog.php              # Blog sidebar
│   └── sidebar-prompt-single.php     # Single prompt sidebar
├── inc/
│   ├── widgets.php         # Custom widgets
│   ├── theme-settings.php  # Customizer settings
│   ├── cpt-prompts.php     # Custom post type
│   ├── helpers.php         # Helper functions
│   └── _legacy_backup/     # Backed up legacy files
└── functions.php           # Main theme functions
```

---

## 3. DESIGN REFERENCE FILES (SOURCE OF TRUTH)

**Location:** `wp-content/themes/ZZ Designs Ready/`

| Page | File Path |
|------|-----------|
| Homepage | `Modern Layout/Homepage/final v1.html` |
| Single Prompt | `Modern Layout/Single Prompt Page/final v1.html` |
| Blog Single | `Modern Layout/Blog Single Page/` |
| Prompt Archive | `Modern Layout/Prompt Archive/` |
| Search Results | `Modern Layout/Search Results/final ready v1.html` |

**Rule:** When unsure about design, ALWAYS check these HTML files first.

---

## 4. WIDGET SYSTEM

### Registered Sidebars (functions.php)
| ID | Name | Usage |
|----|------|-------|
| `sidebar-1` | Main Sidebar | Blog pages |
| `sidebar-prompt` | Prompt Sidebar | Single prompt page |
| `footer-modern-1` to `footer-modern-6` | Footer Columns | Footer widgets |

### Custom Widgets (inc/widgets.php)
| Widget | Purpose | Caching |
|--------|---------|---------|
| ZZ: Brand & Social | Logo, description, social icons | No |
| ZZ: Popular Prompts | Top prompts by likes/views | ✅ Transient |
| ZZ: Category Tags | Taxonomy cloud | ✅ Transient |
| ZZ: Newsletter | Email signup form | No |
| ZZ: Ad Banner | Custom HTML/Script ads | No |
| ZZ: Author Bio | Author profile card | No |
| ZZ: Global Toast | Javascript-driven notifications | No |

**BEM Rule:** All widget classes use `.zz-widget-*` pattern
**Toast Rule:** Always use `initToast()` and `showToast(msg, type)` - styles in `_utilities.css`

---

## 5. CUSTOMIZER STRUCTURE

**Panel:** `zzprompts_options`

| Section | Key Settings |
|---------|--------------|
| Homepage | Hero title, subtitle, search placeholder, features |
| Prompts | Copy button text, likes toggle, meta options |
| Blog Archive | Show image, date, category, excerpt length |
| Blog Single Post | Show image, author, date, reading time, share buttons |
| Blog Comments | Enable/disable, show count, avatars |
| Footer | Copyright text |
| Colors & Branding | Primary (#6366F1), accent (#10b981), text colors |
| Social Media | Facebook, X, Instagram, LinkedIn, YouTube, GitHub, Discord |

---

## 6. CODE STANDARDS (CRITICAL)

### 🛡️ Security & PHP
- **Prefixing:** All functions/globals MUST start with `zz_` or `zzprompts_`.
- **Escaping:** ALL output must be escaped (`esc_html`, `esc_url`, `esc_attr`).
- **Sanitization:** All input must be sanitized (`sanitize_text_field`, `absint`).
- **ABSPATH Check:** Every PHP file must start with `defined('ABSPATH') || exit;`

### 🎨 CSS Class Naming (BEM with zz- prefix)
- **Block:** `.zz-block-name`
- **Element:** `.zz-block-name__element`
- **Modifier:** `.zz-block-name--modifier`
- **NEVER use:** `v1-`, `v2-`, `modern-`, `classic-`, `new-` prefixes in class names

### 🛡️ Anti-Spam Policy (Likes & Copies)
- **Layer 1:** Nonce verification on every AJAX request.
- **Layer 2:** 30-day cookie lock (`zz_liked_ID`, `zz_copied_ID`).
- **Layer 3:** 1-hour IP cooldown using WordPress Transients (`zz_lock_ID_IP`).
- **Layer 4:** Global cache clearing for affected widgets on successful interaction.

---

## 7. GLOBALIZATION (I18N & RTL) - STRICT

### 🌍 Translation
- **Strings:** ALL text strings must be translatable.
  - ✅ `<?php esc_html_e('Submit', 'zzprompts'); ?>`
  - ❌ `<span>Submit</span>`
- **Domain:** Always use `'zzprompts'` text domain.

### ↔️ RTL (Right-to-Left) Standards
- **CSS Logic:** Use **Logical Properties** wherever possible.
  - ✅ `margin-inline-start` instead of `margin-left`
  - ✅ `padding-inline-end` instead of `padding-right`
  - ✅ `text-align: start` instead of `text-align: left`
- **Fallback:** If physical properties needed, override in `assets/css/i18n/_rtl.css`.

---

## 8. PERFORMANCE & LOADING RULES

### 🛑 Conditional Loading
| File | Condition |
|------|-----------|
| `home.css` | `is_front_page()` |
| `prompts.css` | `is_singular('prompt')` or `is_post_type_archive('prompt')` |
| `single-prompt.css` | `is_singular('prompt')` |
| `archive-prompts.css` | `is_post_type_archive('prompt')` or `is_tax()` |
| `blog-single.css` | `is_singular('post')` |
| `search-results.css` | `is_search()` |
| `pagination.css` | `is_archive()` or `is_search()` or `is_home()` |
| `widgets.css` | Always (global - footer needs it) |

### 🎯 CSS Loading
- **Skin:** Always loads `skin.css`
- **Body Classes:** `zz-layout-modern`, `zz-style-default`
- **JS Localization:** `zzprompts_vars.layout` hardcoded to `'modern'`

### 🗄️ Caching Strategy
- **Widget Caching:** Use `get_transient()` / `set_transient()` for DB-heavy widgets.
- **Cache Key Format:** `zz_widget_{widget_name}_{instance_hash}`
- **Cache Duration:** `4 * HOUR_IN_SECONDS`
- **Cache Clear:** On widget update, call `delete_transient()` in `update()` method.

---

## 9. CSS VARIABLE REFERENCE

**Primary File:** `assets/css/core/_variables.css`

### Key Tokens
```css
/* Colors */
--zz-color-primary: #6366F1;
--zz-color-primary-dark: #4F46E5;
--zz-text-primary: #1E293B;
--zz-text-muted: #64748B;

/* Spacing */
--zz-space-1 to --zz-space-16

/* Radius */
--zz-radius-sm, --zz-radius-md, --zz-radius-lg, --zz-radius-pill

/* Shadows */
--zz-shadow-sm, --zz-shadow-md, --zz-shadow-lg
```

**Rule:** NEVER hardcode colors. Always use variables.

---

## 10. EXTERNAL DEPENDENCIES

| Dependency | Version | Source |
|------------|---------|--------|
| FontAwesome | 6.5.1 | CDN (includes X/Twitter icon) |
| Google Fonts | Inter, Fira Code | CDN |
| jQuery | WP Bundled | WordPress Core |

---

## 11. DESIGN GUIDELINES (MODERN V1)

| Property | Value |
|----------|-------|
| Container | `1320px` (`var(--zz-container-xl)`) |
| Header Container | `1536px` |
| Style | Glassmorphism, Airy, Rounded |
| Dark Mode | Handled via CSS Variables (no separate files) |
| Cards | Glass effect: `rgba(255,255,255,0.75)` + `backdrop-filter: blur(12px)` |

---

## 12. AI BEHAVIOR & OUTPUT RULES

1. **Read First:** Always read `CLAUDE.md` at session start.
2. **Check HTML:** When design is unclear, view files in `ZZ Designs Ready/`.
3. **Diffs Only:** Show only changed code blocks with context.
4. **No Hallucinations:** Do not invent files. Use the provided file list.
5. **Context Check:** Check `_variables.css` before creating new colors.
6. **Ask Permission:** Before deleting/replacing large code blocks, ask user.
7. **BEM Naming:** All classes follow `.zz-block__element--modifier` pattern.

---

## 13. QUICK COMMANDS

| Command | Action |
|---------|--------|
| `/start` | Read this file and wait for goal |
| Check design | View `ZZ Designs Ready/Modern Layout/` |
| Add widget | Edit `inc/widgets.php` + `assets/css/components/widgets.css` |
| Add Customizer option | Edit `inc/theme-settings.php` |

---

## 14. SEO GUIDELINES (MANDATORY)

### 🔍 Philosophy
SEO is handled by **clean structure, semantic HTML, and performance** — not hacks.
Theme remains **plugin-agnostic** and provides fallbacks when no SEO plugin is active.

### 📄 Meta & Canonical (Fallback System)
- **Location:** `inc/seo-schema.php`
- **Logic:** Auto-detects Yoast/RankMath/AIOSEO/SEO Framework
- **If no plugin:** Theme outputs:
  - `<meta name="description">` (auto-generated from excerpt)
  - `<link rel="canonical">` (points to page 1 for paginated)
  - `<meta property="og:*">` (basic Open Graph)
  - `<meta name="robots" content="noindex, follow">` on search results

### 📑 Heading Structure (STRICT)
| Page | H1 Content |
|------|------------|
| Homepage | Hero title |
| Prompt Single | Prompt title |
| Blog Single | Post title |
| Prompt Archive | `archive_seo_title` from Customizer |
| Category/Taxonomy | Term name |

**Rule:** Each page MUST have exactly ONE `<h1>`. No skipped hierarchy.

### 📊 JSON-LD Schema (inc/seo-schema.php)
| Page Type | Schema Type | Key Properties |
|-----------|-------------|----------------|
| Prompt Single | `CreativeWork` | name, text, author, likes, copies |
| Blog Single | `Article` | headline, author, datePublished, image |
| All Singles | `BreadcrumbList` | Home → Archive → Current |

### 🖼️ Image SEO Rules
- **ALL images MUST have `alt` attribute**
- Alt text: Descriptive, not keyword spam
- Empty `alt=""` only for decorative images
- `loading="lazy"` on below-fold images

### 🔗 Archive SEO (Customizer)
| Setting | Key | Default |
|---------|-----|---------|
| Archive H1 | `archive_seo_title` | "AI Prompt Library" |
| Intro Text | `archive_seo_description` | Curated collection text... |

**Rule:** Intro text only shown on page 1 without filters.

### ⚠️ What NOT to Do
- ❌ No hardcoded `<title>` tags (use `title-tag` support)
- ❌ No duplicate content across layouts
- ❌ No hidden text or keyword stuffing
- ❌ No SEO plugin assumptions in templates
- ❌ No fake breadcrumbs (use real navigation)
- ❌ No thin pages (min 600 words for blog)

### ✅ What Theme Handles
- ✅ `add_theme_support('title-tag')` 
- ✅ Semantic HTML structure
- ✅ Proper heading hierarchy
- ✅ Schema markup via hooks
- ✅ Canonical URL fallback
- ✅ Meta description fallback
- ✅ Search results noindex
- ✅ Archive intro text for SEO
- ✅ Image alt attributes
- ✅ Performance optimization

---

## 15. CHANGELOG (Recent Updates)

### 2026-01-18: Final Cleanup & Namespace Standardization
**Class Naming Standardization:**
- Removed ALL v1-, v2-, modern-, classic- prefixes from code
- Standardized to single `zz-` namespace everywhere
- Updated all CSS selectors: `body.layout-modern` → `body.zz-layout-modern`
- Updated all PHP template classes to use `zz-*` BEM pattern

**File Renaming (Role-Based Names):**
- `skin-modern.css` → `skin.css`
- `sidebar-archive-v2.php` → `sidebar-prompts.php`
- `sidebar-blog-v2.php` → `sidebar-blog.php`
- `sidebar-single-v2.php` → `sidebar-prompt-single.php`
- `content-v2.php` → `card-blog.php`
- `content-single-modern.php` → `single-blog.php`
- `content-archive-modern.php` → `card-prompt.php`
- `footer-modern.php` → `footer-main.php`
- `content-modern.php` → `hero-home.php`

**Deleted Legacy Files:**
- `template-parts/home/content-v1.php`
- `template-parts/home/content-v2.php`
- `page-templates/template-home-v2.php`

**CSS Class Migrations:**
- `v2-comment-*` → `zz-comment-*`
- `v2-blog-sidebar` → `zz-blog-sidebar`
- `v2-sidebar-widget` → `zz-sidebar-widget`
- `taxonomy-v1-*` → `zz-tax-*`

**helpers.php Cleanup:**
- Removed `zzprompts_add_v2_body_class()` legacy function
- Added `zzprompts_add_prompt_body_class()` (simplified)

**CSS Loading:**
- Always loads `skin.css` (renamed from skin-modern.css)
- Body classes: `zz-layout-modern`, `zz-style-default`
- `zzprompts_vars.layout` hardcoded to `'modern'`

### 2026-01-18: Search Results Page (Premium Design)
**New Files:**
- `search.php` - Complete rewrite with modern glassmorphism design
- `assets/css/pages/search-results.css` - Search page styles

**Search Page Features:**
- Hero section with centered search bar and result counts
- Type tabs: All | Prompts | Blog Posts (URL-based filtering via `?type=`)
- 3-column responsive grid (3 → 2 → 1)
- Keyword highlighting with `<mark class="zz-highlight">`
- Different card styles: Prompt (likes/copies) vs Blog (thumbnail/date)
- Sidebar: Related Keywords, Popular Searches, Ad slot
- No Results state with action buttons

**Removed:**
- `ZZ_Widget_Prompt_Search` widget (use `searchform.php` instead)

**Search Architecture:**
| Location | Behavior |
|----------|----------|
| Header Search | Global (prompts only) |
| Blog Sidebar | Posts only (`post_type=post`) |
| Prompt Archive | Prompts only (existing) |
| Search Results | Tabs for filtering by type |

---

**Last Updated:** 2026-01-18  
**Theme Version:** 1.0.0
