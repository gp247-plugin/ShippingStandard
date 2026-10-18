# ShippingStandard Plugin

## Overview
Standard shipping plugin for GP247/Shop. It calculates shipping fees based on the cart subtotal and automatically applies free shipping when the configured threshold is met.

- Requirements: GP247/Shop (Core 3.0 or 3.1), package `gp247/shop` installed and active.
- Version: 2.4.0

## Features
- Calculate shipping fee from cart subtotal.
- Automatic free shipping when subtotal >= `shipping_free`.
- Easy configuration via the plugin `config.php`.
- **Per-store settings on a multi-store / marketplace site**: each store enables the plugin on its own and keeps its own fee / free-shipping threshold, inheriting the shared config until it overrides them (`storeScope: store`). Single-store sites are unaffected.
- **Runs on the standard shipping layer of `gp247/shop`** (from 2.4.0, on a shop that has it): the fee is quoted alongside other carriers at the shipping step, kept as shown when the order is placed and converted correctly to the display currency. With an older `gp247/shop`, the plugin works as before.
- Exposes front/admin routes for quick checks: `plugin/shippingstandard/index`, `GP247_ADMIN_PREFIX/shippingstandard`.

## Installation
You can install this plugin in one of the following ways:

1) Online installation
- Open the Plugin Library in Admin.
- Find and install the "ShippingStandard" plugin.

2) Install from .zip
- Download the plugin .zip package.
- Upload/import the .zip in Admin and follow the prompts to complete installation.

3) Manual installation (for developers)
- Extract the plugin package.
- Copy it to `app/GP247/Plugins/ShippingStandard`.
- Log in to Admin and enable it from the Extensions area.

4) Install from the command line (CLI, gp247 3.x)

Since gp247 3.x you can download **ShippingStandard** from the GP247 library and install it straight from the command line, without opening the admin. Open a terminal in the website's root folder and run:

```bash
# 1) Once per website: register the (free) API License that connects the site to the GP247 library
php artisan gp247:ext-register-license

# 2) Download the plugin from the library and install it
php artisan gp247:ext-install --type=plugin --key=ShippingStandard
```

- Before step 1, make sure `APP_URL` in `.env` is the website's **real domain** (not `http://localhost`) — the license is bound to that domain.
- Once installed, the plugin is **enabled** and caches are refreshed automatically; nothing else is needed in the admin.
- The command checks the requirements declared in `gp247.json` (core version, composer packages, required plugins) and stops with a clear message if something is missing.
- This plugin requires the `gp247/shop` package; if it is missing, the command stops and tells you.
- If the folder `app/GP247/Plugins/ShippingStandard` is already on the server (copied manually or shipped with the installer), the command **installs it in place** instead of downloading it again.
- The command refuses a plugin that is already installed. To move to a newer version, run `php artisan gp247:ext-update --type=plugin --key=ShippingStandard`.
- Append `--json` to get machine-readable output (for scripts/CI).
- The steps in the **Usage** section below still apply: step 1 (enable) is already done by the command, while step 2 (configure `fee` / `shipping_free`) and step 3 (verify integration) are still up to you.
- More: [Installing Plugins & Templates](https://github.com/gp247net/gp247-docs/blob/main/extension/install-extension.md) · [Command reference](https://github.com/gp247net/gp247-docs/blob/main/system/command-line-reference.md).

## Usage
1) Enable the plugin
- Go to Admin > Extensions > Shipping and enable "ShippingStandard".

2) Configure parameters
- Edit `app/GP247/Plugins/ShippingStandard/config.php`:

```php
<?php
return [
    'fee' => 20, // Base shipping fee
    'shipping_free' => 10000, // Free shipping threshold (subtotal >= value)
];
```

- Tips:
  - Set `shipping_free = 0` if you always want to charge the fee (no free-shipping threshold).
  - Adjust `fee` to match your policy.

3) Verify integration
- During checkout, the plugin automatically computes the fee based on the current subtotal.
- Optionally visit `plugin/shippingstandard/index` to verify the front route is reachable.

## Documentation
- GitHub: `https://github.com/gp247net/ShippingStandard`
- Installation guide: `https://gp247.net/en/docs/user-guide-extension/guide-to-installing-the-extension.html`

## License
Developed by GP247


