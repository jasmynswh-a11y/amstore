# AMZ Store — local WordPress project

A locally installable WordPress theme and companion plugin for **AMZ Store (amzstore.co.uk)**. The design follows familiar UK automotive-retail patterns: a compact retail header, prominent part/vehicle search, category navigation, product cards, clear condition/source labels and transparent used-versus-new price references. It uses original AMZ Store styling rather than copying another retailer's branding or exact layout.

## What's included

- `wordpress/wp-content/themes/amzstore/` — responsive AMZ Store theme.
- `wordpress/wp-content/plugins/amzstore-core/` — WooCommerce product fields for owned/supplier stock, used condition, OE number, fitment, side, inspection status, defects, supplier references and price benchmarks.
- Shortcodes: `[amz_parts_search]` and `[amz_price_comparison]`.
- Optional 400-product demo importer: 200 used + 200 new sample products, each with a unique external image URL.
- `docs/LOCAL-SETUP.md` — LocalWP installation steps.
- `docs/PRODUCT-ENTRY.md` — manual product and supplier workflow.
- `docs/LAUNCH-CHECKLIST.md` — pre-launch checks.

## Requirements

- A local WordPress site (LocalWP is recommended).
- A current supported PHP version and MySQL/MariaDB database.
- WooCommerce installed and activated for catalogue, basket and checkout.

This ZIP does **not** contain WordPress core or WooCommerce. Install those through LocalWP/WordPress and the official plugin directory. No paid theme or plugin is bundled.

## Quick start

1. Create a new blank WordPress site in LocalWP.
2. Open the site's `app/public/wp-content/` folder.
3. Copy the `amzstore` folder into `themes/`.
4. Copy the `amzstore-core` folder into `plugins/`.
5. In WordPress Admin, activate **AMZ Store** under Appearance → Themes.
6. Install and activate **WooCommerce** under Plugins → Add New.
7. Activate **AMZ Store Core** under Plugins.
8. Go to Settings → Permalinks and click **Save Changes**.
9. Go to Settings → Reading and set a static homepage. Select the page named Home after creating it (or create a page titled Home and use the front-page template automatically).
10. Add products under Products → Add New.

See `docs/LOCAL-SETUP.md` for full instructions.

## Important functionality boundaries

This is a working local theme/plugin foundation, not a connected live marketplace. Before accepting real orders, AMZ Store must configure payment processing, shipping, tax/VAT, transactional email, privacy/cookie controls, legal pages and backups.

- UK registration lookup is a **design/interface placeholder**. It does not query DVLA or identify a vehicle until a licensed/approved vehicle-data provider and API credentials are configured.
- Price references are manually entered by an administrator. They are not live internet searches, verified competitor offers or automatically updated averages.
- External supplier products are recorded as partner-sourced stock, but supplier availability is not automatically synced. Confirm availability before accepting/dispatching partner-stock orders.
- Product compatibility must be verified using OE numbers and vehicle variant details. Make/model text alone is not a fitment guarantee.
- Add only genuine, authorised product images. Do not use competitor images without permission.

## Branding

The header has a text-based AMZ Store mark by default. Upload your supplied logo in Appearance → Customize → Site Identity → Logo to use the official artwork. Set site title to AMZ Store and site icon to your approved favicon.


## 400-image local display test

1. Install and activate WooCommerce, then activate AMZ Store Core.
2. In WordPress Admin, open **WooCommerce → AMZ Demo Catalogue**.
3. Click **Create 400 demo products with 400 image links**.
4. Open the Shop page and the Used Parts / New Parts product categories to test multiple product cards and image loading.

The importer creates 200 used and 200 new products and skips demo SKUs already present if you run it again. Each product uses a unique seeded Lorem Picsum URL. These are generic placeholder photos for layout testing, not real car-part images or product evidence. The images load externally, so internet access is required. Delete products with the `AMZ-DEMO-` SKU prefix when testing is finished. Do not accept real orders for demo listings.
