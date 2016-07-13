SOCIAL AUTH GOOGLE MODULE

INTRODUCTION
------------

Social Auth Google Module is a Google Authentication integration for Drupal. It
is based on the Social Auth and Social API projects

It adds to the site:
* A new url: /user/login/google
* A settings form on /admin/config/social-api/social-auth/google page
* A Google+ Logo in the Social Auth Login block.

REQUIREMENTS
------------

This module requires the following modules:

 * Social Auth (https://drupal.org/project/social_auth)
 * Social API (https://drupal.org/project/social_api)

HOW IT WORKS
------------

User can click on the Google+ logo on the Social Auth Login block
You can also add a button or link anywhere on the site that points 
to /user/login/google, so theming and customizing the button or link
is very flexible.

When the user opens the /user/login/google link, it automatically takes
user to Google Accounts for authentication. Google then returns the user to
Drupal site. If we have an existing Drupal user with the same email address
provided by Google, that user is logged in. Otherwise a new Drupal user is
created.

SETUP
-----

Installation instructions for Drupal 8 can be found at
https://www.drupal.org/node/2764227


SUPPORT REQUESTS
----------------

Before posting a support request, carefully read the installation
instructions provided in module documentation page.

Before posting a support request, check Composer Manager status report at
admin/reports/composer-manager. This status page will show the Google API PHP
Client version if Drupal can detect it.

Before posting a support request, check Recent log entries at
admin/reports/dblog

Once you have done this, you can post a support request at module issue queue:
https://www.drupal.org/sandbox/gvso/2763739

When posting a support request, please inform what does the status report say
at admin/reports/composer-manager and if you were able to see any errors in
Recent log entries.
