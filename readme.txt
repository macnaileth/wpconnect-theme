=== tsunami wp connect theme ===
Contributors: Marco Nagel
Version: 0.1.0
Requires at least: 6.1.1
Tested up to: 6.1.1
Requires PHP: 8.0
License: CC BY-NC 4.0
License URI: https://creativecommons.org/licenses/by-nc/4.0/

Theme to run wordpress headless and provide setup data for an APP connecting to the WordPress API.

== Description ==
This makes the WordPress frontend completely headless: All output will be running via the WordPress API. You can setup
connection for your app here, output a redirect screen etc. This theme features a custom api endpoint for setup data, it will
also create a settings.json if you want to use my connect library for your app.

Also you can setup menus, additional css, fonts etc for the Gutenberg Editor and the frontend stopper page if needed and more.

REST route to access from your Frontend, site or app for menu structure: /wp-json/tsu-wpconnect/v1/site/structure/

== Frequently Asked Questions ==

None ATM

== Changelog ==

06.01.2023 - Started coding this thing and setup git repo.
07.01.2023 - Added Backend & Utilites class, added de_DE language files
16.01.2023 - Added some Settings for redirect landing page. Easy things first ;-)
27.01.2023 - Finished most of the settings for redirection and landing
29.01.2023 - Finished missing settings for frontend. added setting tabs for menu structure and api settings
12.02.2023 - basic menus could be created and deleted, started ui for menu item addition
16.04.2023 - finished menus, added REST route to menu structure

== upcoming ==

- JS lib for easy connection to frontend pages and apps

= 0.0.1 =

- Started Coding
- Added setup features for frontend screen

= 0.0.5 = 

- Landing page setup mostly finished

= 0.0.6 = 

- Added setting tabs for menu structure & api settings

= 0.1.0 = 

- Route ready and broadcasting

== Resources & third party ==




