# WP Blueprint Exporter

A WordPress admin plugin that exports a basic WordPress Playground `blueprint.json` from the current site.

## MVP Export Support

* Active plugins, using likely wordpress.org slugs
* Active theme, using the active stylesheet slug
* Site title, tagline, timezone, date/time, and permalink options
* Admin login step
* PHP and WordPress version preferences
* Landing page

## Usage

1. Copy `plugins/wp-blueprint-exporter` into `wp-content/plugins/`.
2. Activate **WP Blueprint Exporter**.
3. Go to **Tools > Export Blueprint**.
4. Review the JSON preview.
5. Click **Download blueprint.json**.

## Current Limits

This plugin exports a setup recipe, not a full backup. It does not yet export custom/private plugin files, custom theme files, posts, pages, media uploads, menus, widgets, users, or database content.
