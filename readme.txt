=== Plugin Name ===
Contributors: Sir-Uli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=m%40il%2ewolf%2du%2eli&item_name=Donation%20for%20wolf%2du%2eli&page_style=wolfuli&no_shipping=1&return=http%3a%2f%2fwolf%2du%2eli&cancel_return=http%3a%2f%2fwolf%2du%2eli&cn=Message&tax=0&currency_code=EUR&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: comments, spam, block
Requires at least: 3.1
Tested up to: 4.4
Stable tag: 1.2

This plugin provides a possibility to block comments based on words of a predefined list.

== Description ==
This plugin provides a possibility to block comments based on words of a predefined list. The built-in lists of wordpress only allow to send comments to the moderation- or to the spam-queue but not to block them completely. This is remedied with this plugin, which allows to block comments based on a definable list.

Attention #1: Use wordlist with care, there is no logging of the blocked comments!

Attention #2: It will also match inside words, so "press" will match "WordPress" as well!

== Installation ==

1. Upload the directory `wu_block_comments` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Click on the 'Settings'-Link in the 'Plugins' menu in WordPress. This will redirect you to the 'Discussion'-section in the settings.
1. Scroll down below the comment blacklist

== Frequently Asked Questions ==

= How can i see what was blocked? =

There is no way to see that as this is not logged.

= Why was my comment blocked? =

You've defined a word to be blocked which was contained in your comment. Also check for parts of words which might be contained.

= I found a bug, can you help me? =

I certainly can, please leave a comment at my [Plugin-Website](http://wolf-u.li/4477/ "Plugin-Website of the author").

== Changelog ==

= 1.0 =
* Initial Version

= 1.1 =
* Added counter in dashboard in the "Right Now" section
* Cleanup of source code
* Cleanup of translation

== Upgrade Notice ==

= 1.0 =
This is the initial version.

= 1.1 =
New Feature: Dashboard "Right Now" shows the number of blocked comments (counting starts after update from zero).

== Screenshots ==

1. This is the message shown when a comment is blocked.
2. This is the settings section in the discussion settings.
3. This shows the counter in the section "Right Now" in the dashboard.