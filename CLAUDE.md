# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

KOP is a WordPress plugin for https://kingdomofpeace.net/. The site is hosted on wordpress.com's cheapest plan (no version control, no SSH access), so custom functionality must be deployed via plugin.

- **Theme:** twentytwentyfive
- **Purpose:** Small snippet-style customizations. Larger features should get their own dedicated plugin.

Current features include admin menu customizations and shortcodes for block patterns and related posts.

## Commands

```bash
# Install dependencies
composer install

# Install without dev dependencies (for production/release)
composer install --no-dev --optimize-autoloader
```

## Architecture

### Entry Point
- `kop.php` - Main plugin file, loads autoloader and initializes snippets

### Snippets (in `src/Snippets/`)
Each snippet is a separate class under `KOP\Snippets` namespace with a static `init()` method.

- `BlockPatterns` - Admin menu link + shortcodes:
  - `[block_pattern id="123"]` - Renders reusable blocks (supports Polylang)
  - `[related_posts_by_tags]` - Shows related posts based on shared tags

### Adding New Snippets

1. Create `src/Snippets/SnippetName.php`
2. Add `KOP\Snippets\SnippetName::init();` to `kop.php`

**Snippet template:**
```php
<?php
namespace KOP\Snippets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SnippetName {

    public static function init(): void {
        // Register hooks with static method callbacks
        add_action( 'hook_name', [ self::class, 'method_name' ] );
        add_filter( 'filter_name', [ self::class, 'filter_method' ] );
        add_shortcode( 'shortcode_name', [ self::class, 'shortcode_method' ] );
    }

    public static function method_name(): void {
        // Implementation
    }
}
```

### Dependencies
- `yahnis-elsts/plugin-update-checker` - Auto-updates from GitHub releases

## Release Process

Releases are automated via GitHub Actions on push to `production` branch:
1. Semantic version is calculated from commit messages (`feat:` = minor, `BREAKING CHANGE:` = major)
2. Version is updated in `kop.php`
3. Plugin ZIP is created (excluding files in `.distignore`)
4. GitHub Release is created with the ZIP asset

The plugin uses the update checker to pull updates from GitHub releases automatically.
