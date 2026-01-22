# ZZ Prompts Theme - Documentation

## Table of Contents
1. [Installation](#installation)
2. [Required Plugin](#required-plugin)
3. [Theme Setup](#theme-setup)
4. [Customizer Options](#customizer-options)
5. [Creating Prompts](#creating-prompts)
6. [Widgets](#widgets)
7. [Page Templates](#page-templates)
8. [Translation](#translation)
9. [Support](#support)

---

## Installation

### Method 1: WordPress Admin (Recommended)
1. Go to **Appearance → Themes → Add New**
2. Click **Upload Theme**
3. Choose the `zzprompts.zip` file
4. Click **Install Now**
5. After installation, click **Activate**

### Method 2: FTP Upload
1. Unzip `zzprompts.zip` on your computer
2. Upload the `zzprompts` folder to `/wp-content/themes/`
3. Go to **Appearance → Themes** and activate

---

## Required Plugin

After activating the theme, you'll see a notice:

> "This theme requires the following plugin: ZZ Prompts Core"

**Click "Begin installing plugins"** and install the required plugin. This plugin handles:
- Prompts Custom Post Type
- Categories & AI Tools taxonomies
- Likes & Copy tracking system
- View counting

⚠️ **Important:** The Prompts section won't appear in your admin until you activate this plugin.

---

## Theme Setup

### Step 1: Install Required Plugin
Follow the notice to install "ZZ Prompts Core" plugin.

### Step 2: Set Up Menus
1. Go to **Appearance → Menus**
2. Create a new menu
3. Add your pages/links
4. Assign to "Primary Menu" location

### Step 3: Configure Homepage
1. Go to **Settings → Reading**
2. Select "A static page"
3. Choose your homepage (or leave as default front page)

### Step 4: Set Up Widgets
1. Go to **Appearance → Widgets**
2. Configure Footer Columns 1-4
3. Configure Prompt Sidebar (for single prompt pages)

### Step 5: Add Your Logo
1. Go to **Appearance → Customize → Site Identity**
2. Upload your logo
3. Click **Publish**

---

## Customizer Options

Go to **Appearance → Customize → ZZ Prompts Settings**

### 🏠 Homepage
- Toggle visibility of each section (Hero, Prompts, How It Works, Features, Blog, CTA)
- Edit all titles and subtitles
- Set number of prompts to display

### 📐 Layout & Spacing
- Header bottom gap
- Hero internal padding
- Gap below hero section

### ⚡ Prompts
- Copy button text
- Success message
- Meta toggles (date, copies, author)
- Archive SEO heading & description

### 📰 Blog Archive
- Show/hide featured images
- Show/hide category
- Excerpt length
- Posts per page

### 📝 Blog Single Post
- Show/hide featured image
- Show/hide author, date, reading time
- Show/hide share buttons and tags

### 💬 Comments
- Enable/disable comments section
- Show/hide comment count and avatars

### 🎨 Colors & Branding
- Primary color (default: #6366F1)
- Accent color (default: #10b981)

### 🔗 Social Media
- Facebook, Twitter/X, Instagram, LinkedIn, YouTube, GitHub, Discord URLs

---

## Creating Prompts

### Add a New Prompt
1. Go to **Prompts → Add New**
2. Enter the prompt title
3. Write a description in the main editor (Gutenberg)
4. In the **"Prompt Data"** meta box, paste your actual AI prompt code
5. Assign a **Category** and **AI Tool**
6. Set a featured image (optional)
7. Click **Publish**

### Prompt Categories
Go to **Prompts → Categories** to manage categories like:
- Marketing
- Writing
- Coding
- Design
- Business

### AI Tools
Go to **Prompts → AI Tools** to manage tools like:
- ChatGPT
- Midjourney
- Claude
- DALL-E
- Gemini

---

## Widgets

The theme includes these custom widgets:

| Widget | Purpose | Best Location |
|--------|---------|---------------|
| **ZZ: Brand & Social** | Logo, description, social icons | Footer Column 1 |
| **ZZ: Popular Prompts** | Top prompts by likes/views | Prompt Sidebar |
| **ZZ: Category Tags** | Category/AI Tool cloud | Footer, Sidebar |
| **ZZ: Newsletter** | Email signup form | Footer Column 4 |
| **ZZ: Ad Banner** | Advertisement placement | Sidebar |
| **ZZ: Author Bio** | Author profile card | Sidebar |
| **ZZ: Popular Posts** | Trending blog posts | Blog Sidebar |

### Widget Areas
- **Main Sidebar** - Blog pages
- **Prompt Sidebar** - Single prompt pages
- **Footer Columns 1-4** - Footer area

---

## Page Templates

Create pages and assign these templates:

| Template | File | Usage |
|----------|------|-------|
| **About Us** | `page-templates/about.php` | Company/site information |
| **Contact Us** | `page-templates/contact.php` | Contact form & FAQ |
| **Login** | `page-templates/login.php` | User login page |
| **Forgot Password** | `page-templates/forgot-password.php` | Password reset |

### Setting Up Contact Page
1. Create a page titled "Contact"
2. Set template to "Contact Us"
3. (Optional) Go to **Customize → Contact Page** and add Contact Form 7 shortcode

---

## Translation

The theme is fully translation-ready.

### Creating a Translation
1. Copy `languages/zzprompts.pot` to your language file (e.g., `zzprompts-fr_FR.po`)
2. Use [Poedit](https://poedit.net/) or [Loco Translate](https://wordpress.org/plugins/loco-translate/) plugin
3. Translate strings
4. Save as `.po` and `.mo` files

### RTL Support
The theme fully supports RTL (Right-to-Left) languages like Arabic and Hebrew.

---

## Support

### Before Contacting Support
1. Ensure WordPress and PHP are up to date
2. Deactivate other plugins to check for conflicts
3. Switch to a default theme to verify the issue is theme-related

### Getting Help
- **Documentation:** You're reading it!
- **Email:** support@zzprompts.com
- **Response Time:** 24-48 hours (business days)

### What's Included in Support
✅ Theme installation help
✅ Bug fixes
✅ Feature clarification
✅ Customizer guidance

### What's NOT Included
❌ Third-party plugin conflicts
❌ Custom code modifications
❌ Server configuration
❌ SEO optimization

---

## Changelog

### Version 1.2.0
- Initial ThemeForest release
- Modern glassmorphism design
- Custom Post Type for Prompts
- Likes & Copy tracking
- Dark mode support
- RTL support
- Block patterns
- 8 custom widgets

---

**Thank you for choosing ZZ Prompts!**

© 2026 Shan Iqbal. All rights reserved.
