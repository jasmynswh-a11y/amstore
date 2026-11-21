# Local installation (LocalWP)

## 1. Create the local site

1. Install LocalWP from https://localwp.com/.
2. Choose **Create a new site**.
3. Name it `AMZ Store` (Local will create a local `.local` address).
4. Choose the preferred environment. Use the PHP/database versions offered as current and supported by your WordPress/WooCommerce versions.
5. Set a local WordPress administrator username and password. Do not reuse your GoDaddy password.
6. Start the site and click **WP Admin**.

## 2. Copy the theme and plugin

1. In LocalWP, open the site folder and navigate to `app/public/wp-content/`.
2. Copy `wordpress/wp-content/themes/amzstore/` from this ZIP into `wp-content/themes/`.
3. Copy `wordpress/wp-content/plugins/amzstore-core/` into `wp-content/plugins/`.
4. In WP Admin, open **Appearance → Themes** and activate **AMZ Store**.
5. Open **Plugins → Add New Plugin**, install **WooCommerce**, and activate it.
6. Activate **AMZ Store Core**.
7. Complete the WooCommerce setup wizard. For local testing, use test/dummy payment methods only.
8. Go to **Settings → Permalinks** and click **Save Changes**.

## 3. Set up the homepage and navigation

- Create a page called **Home**. The theme's `front-page.php` supplies the storefront homepage automatically when WordPress is set to display a static front page.
- Go to **Settings → Reading** and select Home as the static homepage.
- Go to **Appearance → Menus** (or **Appearance → Editor**, depending on your WordPress version) and create a Primary Menu with: Used Parts, New Parts, Compare Prices, Find Parts for My Car, Help & Contact.
- Create a page called **Used Parts** and add `[amz_parts_search]` to its content.
- Create a page called **Compare Prices** and add `[amz_price_comparison]`.
- Create a page called **Vehicle Search** and add `[amz_parts_search]`. The registration lookup still requires a vehicle-data provider.
- Upload the official AMZ Store logo under **Appearance → Customize → Site Identity → Logo** (or the Site Editor's Site Logo block if your WordPress setup uses a block theme screen).

## 4. Add a first product

1. Go to **Products → Add New**.
2. Add a descriptive title, full description, product image(s), regular price and stock quantity.
3. For used products, select the used condition grade in the **AMZ Store part details** panel.
4. Add the OE/part number, fitment, engine/trim, side, inspection status and known defects.
5. Select whether stock is **AMZ Store owned** or **External UK supplier**.
6. For partner stock, add supplier name/reference and acquisition cost. Confirm stock availability with the supplier before publishing and again before dispatch.
7. Add a reference date and like-for-like UK market/new prices if you have verified them.
8. Publish only after fitment, condition, price, delivery and warranty details have been checked.

## 5. Local testing

- Add one AMZ-owned used product and one supplier-sourced used product.
- Add a new aftermarket alternative as a separate product.
- Test search, product detail, add-to-basket and checkout using a test payment gateway.
- Check the site at mobile and desktop widths.
- Confirm stock quantity decreases correctly after a test order and is restored after cancellation/refund according to your WooCommerce settings.

## 6. Move to hosting later

When the local site is approved, use a reputable migration method or LocalWP's export/push workflow to move it to the chosen hosting account. Before changing DNS, confirm hosting is active, SSL is ready, email DNS records are preserved, backups exist and checkout has been tested on staging. Do not point the domain at the local `.local` address; it only works on your computer.
