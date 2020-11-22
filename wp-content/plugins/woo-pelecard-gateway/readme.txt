=== Woo Pelecard Gateway ===
Contributors: idofri, ramiy
Tags: WooCommerce, Payment, Gateway, Credit Cards, Shopping Cart, Pelecard, Extension, Invoice, Receipt
Requires at least: 3.0
Tested up to: 5.2
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends WooCommerce with Pelecard payment gateway.

== Description ==

**This is the Pelecard payment gateway for WooCommerce.**

= About Pelecard =
[http://www.pelecard.com](Pelecard) provides clearing solutions for over 20 years, and gives a solution for secure and advanced enterprises both large and small, including websites.

= About the plugin =

The plugin allows you to use Pelecard payment gateway with the WooCommerce plugin.

= Features =
* Accept all major credit cards
* Responsive payment form
* Invoices & Receipts

== Installation ==

= Installation =
1. In your WordPress Dashboard go to "Plugins" -> "Add Plugin".
2. Search for "Woo Pelecard Gateway".
3. Install the plugin by pressing the "Install" button.
4. Activate the plugin by pressing the "Activate" button.
5. Open the settings page for WooCommerce and click the "Checkout" tab.
6. Click on the sub tab for "Pelecard".
7. Configure your Pelecard Gateway settings.

= Minimum Requirements =
* WordPress version 3.0 or greater.
* PHP version 5.3 or greater.
* MySQL version 5.0 or greater.

= Recommended Requirements =
* Latest WordPress version.
* PHP version 5.6 or greater.
* MySQL version 5.6 or greater.

== Screenshots ==

1. Easy configuration.
2. Payment gateway selection.
3. Responsive, IFrame-based form.

== Frequently Asked Questions ==

= What is the cost for the gateway plugin? =
This plugin is a FREE download.

== Changelog ==

= 1.2.2 =
* Fixed syntax bug for PHP versions prior to 5.4.
* Fixed incorrect data sent to Tamal.

= 1.2.1 =
* Added filter hooks.
* Fixed HiddenPelecardLogo field logic.

= 1.2.0 =
* Restructured plugin.
* Added support for WC 3.x.
* Added Tokenization support.

= 1.1.12 =
* Added order discount for Tamal.

= 1.1.11 =
* Fixed Tamal default parameters.

= 1.1.10 =
* Fixed Tamal 'MaamRate' for Receipts.

= 1.1.9.4 =
* WordPress 4.7 compatible.
* Removed deprecated function(s).

= 1.1.9.3 =
* Added the 'wc_pelecard_gateway_request_args' filter hook.

= 1.1.9.2 =
* Added full transaction history
* Added gateway icon support (filter).
* Added advanced error logging.

= 1.1.9.1 =
* Fixed gateway response check.
* Fixed bug in constructor.

= 1.1.9 =
* Added the ability to customize min & max payments by cart's total.

= 1.1.8 =
* Added filter hooks.

= 1.1.7 =
* Fixed JS loading.

= 1.1.6 =
* Added Tamal document types.

= 1.1.5 =
* Added shipping to Tamal Invoices.

= 1.1.4 =
* Fixed major front-end bug.

= 1.1.3 =
* Added WordPress 4.5 & WooCommerce 2.5.5 compatibility

= 1.1.2 =
* Updated admin js.

= 1.1.1 =
* Update translation strings.
* Add translators comments.

= 1.1.0 =
* Added [Tamal API](http://accountbook.co.il/) for creating invoices.
* Improved tab-based admin menu.

= 1.0.5 =
* i18n: Remove po/mo files from the plugin.
* i18n: Use [translate.wordpress.org](https://translate.wordpress.org/) to translate the plugin.

= 1.0.4 =
* Updated plugin translation files.

= 1.0.3 =
* Added advanced gateway options.

= 1.0.2 =
* Improved data validations.

= 1.0.1 =
* Fixed XSS Vulnerability.

= 1.0.0 =
* First Release.

== Upgrade Notice ==

= 1.1.4 =
* Fixed major front-end bug.

= 1.1.3 =
* Added WordPress 4.5 & WooCommerce 2.5.5 compatibility

= 1.0.2 =
Improved data validations.

= 1.0.1 =
Fixed XSS Vulnerability.