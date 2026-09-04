# ShippingStandard Plugin

## Overview
Standard shipping plugin for GP247/Shop. It calculates shipping fees based on the cart subtotal and automatically applies free shipping when the configured threshold is met.

- Requirements: GP247/Shop (Core >= 3.0), package `gp247/shop` installed and active.
- Version: 2.3

## Features
- Calculate shipping fee from cart subtotal.
- Automatic free shipping when subtotal >= `shipping_free`.
- Easy configuration via the plugin `config.php`.
- **Per-store settings on a multi-store / marketplace site**: each store enables the plugin on its own and keeps its own fee / free-shipping threshold, inheriting the shared config until it overrides them (`storeScope: store`). Single-store sites are unaffected.
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
- Installation guide: `https://gp247.net/vi/docs/user-guide-extension/guide-to-installing-the-extension.html`

## License
Developed by GP247


