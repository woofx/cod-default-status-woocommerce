=== COD Default Status for WooCommerce ===
Contributors: rafaatxyz,woofx
Donate link: woofx.kaizenflow.xyz
Tags: woocommerce, order status, cod, cash on delviery
Requires at least: 4.8
Tested up to: 5.3
Requires PHP: 5.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0.0
WC tested up to: 3.8

Set default status for Cash on Delivery (COD) orders. Also manage inventory reduction behavior for COD orders.

== Description ==

This plugin is made for people who need to confirm Cash-on-Delivery (COD) orders before processing them.

By default, WooCommerce sets COD order statuses to 'Processing' and reduces stock accordingly. If you need to reduce the stock only when an order is confirmed, this plugin is for you.

Set the default status of COD payment method to 'On-hold' or 'Pending Payment' and check 'Do not reduce stock'. And you are all set.

When the order is confirmed, and its status set to 'Processing' (usually by an admin or manager), stocks will be reduced.

Contribute [https://github.com/woofx/cod-default-status-woocommerce](https://github.com/woofx/cod-default-status-woocommerce)

== Installation ==

1. Upload the plugin to your woocommerce site, Activate it
1. Go to `WooCommerce > Settings > Payments > Cash on Delviery`
1. Below the default options, you will find the default status options
1. Set your preferences and update. Done!

== Screenshots ==

1. Activate the plugin.
2. Set default status from 'Cash on Delviery' settings page.

== Changelog ==

= 1.0.1 =
* Order Hook bugfix

= 1.0.0 =
* Inital Commit

