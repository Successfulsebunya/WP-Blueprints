# Plugin Developer Lab

A WordPress Playground Blueprint for plugin development practice.

## Includes

* Query Monitor
* Debug Bar
* Error Log Monitor as the log viewer
* WP-CLI via `extraLibraries`
* Debug constants:
  * `WP_DEBUG`
  * `WP_DEBUG_LOG`
  * `WP_DEBUG_DISPLAY`
  * `SCRIPT_DEBUG`
  * `SAVEQUERIES`
* A sample plugin scaffold: `plugin-dev-lab-sample`

## Try It

Open the Blueprint in WordPress Playground:

```text
https://playground.wordpress.net/?blueprint-url=https://example.com/blueprints/plugin-developer-lab/blueprint.json
```

Replace the example URL with the hosted URL for this repository file.

## What To Test

* Visit `/wp-admin/plugins.php` and confirm all lab plugins are active.
* Open `/wp-json/plugin-dev-lab/v1/status` to test the sample REST route.
* Check `wp-content/debug.log` through the log viewer after loading a page.
* Use Query Monitor and Debug Bar to inspect hooks, queries, requests, and runtime state.

## Local Scaffold

The `plugin-scaffold/` directory mirrors the sample plugin written by the Blueprint.
Use it as the editable source if you later turn this sample into a zip-based or bundle-based Blueprint.
