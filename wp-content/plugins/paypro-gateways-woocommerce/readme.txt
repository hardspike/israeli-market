=== PayPro Gateways - WooCommerce ===
Contributors: paypro
Tags: paypro, payments, betalingen, psp, gateways, woocommerce, ideal, bank transfer, paypal, afterpay, creditcard, visa, mastercard, mistercash, bancontact, sepa, overboeking, incasso
Requires at least: 3.8
Tested up to: 5.5
Stable tag: 1.3.2
License: GPLv2
License URI: http://opensource.org/licenses/GPL-2.0

With this plugin you easily add all PayPro payment gateways to your WooCommerce webshop.

== Description ==

This plugin is the official PayPro plugin for WooCommerce. It is easy to use, quick to install and actively maintained by PayPro. 

Currently the plugin supports the following gateways:

* iDEAL
* iDEAL QR 
* PayPal
* Bancontact
* Sofort
* Afterpay
* SEPA Overboeking
* Mastercard
* Visa

= Features =

* Support for all PayPro payment methods
* Settings for each payment method
* WordPress Multisite support
* Translations for English and Dutch
* Test mode support
* Debug mode for easy debugging
* Automatic status changes

= Note =

In order to use this plugin you need to have a PayPro account and you need to have setup a 'Webshop' in the PayPro dashboard.

== Installation ==

= Requirements =

* PHP version 5.3 or greater
* PHP extension cURL
* PHP extension OpenSSL
* WordPress 3.8 or greater
* WooCommerce 2.2 or greater

= Automatic installation =

1. In the WordPress admin panel go to Plugins -> New Plugin. Search for 'PayPro Gateways - WooCommerce'.
2. Go to Plugins -> Installed Plugins. Activate the plugin named 'PayPro Gateways - WooCommerce'.
3. Set your PayPro API key at WooCommerce -> Settings -> Checkout under the PayPro section.
4. Now select the payment methods you want to use and enable them.
5. Your webshop is now ready to use PayPro gateways.

= Manual installation =

1. Download the package
2. Unpack the zip file and upload the 'paypro-gateways-woocommerce' to the plugin directory. You can find the plugin directory in the 'wp-content' directory.
3. Go to Plugins -> Installed plugins. Activate the plugin named 'PayPro Gateways - WooCommerce'.
4. Set your PayPro API key at WooCommerce -> Settings -> Checkout under the PayPro section.
5. Now select the payment methods yout want to use and enable them.
6. Your webshop is now ready to use PayPro gateways.

Do you need help installing the PayPro plugin, please contact support@paypro.nl

== Frequently Asked Questions ==

= Where do I find my PayPro API key? =
You can find your PayPro API key in your dashboard at 'Webshop Koppelen' in the PayPro dashboard.

= When do I need to add a product ID? =
If you want to make use of affiliate marketing or you want to use the mastercard, visa or sofort gateway you have to supply a product ID.

= Where do I find my product ID? =
You can find your product id at 'Webshop Koppelen' in the PayPro dashboard.

== Screenshots ==

1. Overview of the PayPro settings.
2. Settings for an individual payment method.
3. Example of the checkout payment method selection.

== Changelog == 

= 1.3.2 =

* Fixed a bug where order_key would be sanitized incorrectly

= 1.3.1 =

* Fix missing files

= 1.3.0 =

* Added iDEAL QR pay method
* Implemented new PayPro API client
* Updates for compatability with Wordpress 5.0 and WooCommerce 3.5
* Updated various translations

= 1.2.4 =

* Correctly post shipping fields when provided

= 1.2.3 =

* Fixed a bug where the product ID would not be sent correctly

= 1.2.2 =

* Fixed a bug where product_id would be posted while it's invalid
* Fixed some small typos

= 1.2.1 =

* Fixed stock update call for WooCommerce 2.6

= 1.2.0 =

* Improved compatibability with WooCommerce 3.0 and 3.1
* Updated certificate bundle
* Updated Bancontact image and default title

= 1.1.0 =

* Reworked status updates for orders. Fixes the bugs with updating orders if there are multiple payments.
* Added an option to select the status for an order when a payment is completed.
* Multiple small fixes.

= 1.0.1 =

* Fixed a bug where orders with multiple payments would not update correctly.
* Fixed a bug where the layout of the PayPro settings would be wrong.
* Changed payment gateway images to the same size.
* Added extra sanitization.

= 1.0.0 =

First stable release
