CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * How it works
 * Support requests
 * Maintainers

INTRODUCTION
------------

Social Auth Google is a Google authentication integration for Drupal. It is
based on the Social Auth and Social API projects

It adds to the site:
 * A new url: /user/login/google.
 * A settings form on /admin/config/social-api/social-auth/google page.
 * A Google Logo in the Social Auth Login block.

REQUIREMENTS
------------

This module requires the following modules:

 * Social Auth (https://drupal.org/project/social_auth)
 * Social API (https://drupal.org/project/social_api)

INSTALLATION
------------

 * Run composer to install the dependencies.
   composer require "drupal/social_auth_google:~2.0"

 * Install the dependencies: Social API and Social Auth.

 * Install as you would normally install a contributed Drupal module. See:
   https://drupal.org/documentation/install/modules-themes/modules-8
   for further information.

 * A more comprehensive installation instruction for Drupal 8 can be found at
   https://www.drupal.org/node/2923804/

CONFIGURATION
-------------

 * Add your Google project OAuth information in
   Configuration » User Authentication » Google.

 * Place a Social Auth Google block in Structure » Block Layout.

 * If you already have a Social Auth Login block in the site, rebuild the cache.


HOW IT WORKS
------------

User can click on the Google logo on the Social Auth Login block
You can also add a button or link anywhere on the site that points
to /user/login/google, so theming and customizing the button or link
is very flexible.

When the user opens the /user/login/google link, it automatically takes
user to Google Accounts for authentication. Google then returns the user to
Drupal site. If we have an existing Drupal user with the same email address
provided by Google, that user is logged in. Otherwise a new Drupal user is
created.

SUPPORT REQUESTS
----------------

Before posting a support request, carefully read the installation
instructions provided in module documentation page.

Before posting a support request, check Recent log entries at
admin/reports/dblog

Once you have done this, you can post a support request at module issue queue:
https://www.drupal.org/project/issues/social_auth_google

When posting a support request, please inform if you were able to see any errors
in Recent log entries.

MAINTAINERS
-----------

Current maintainers:
 * Getulio Sánchez (gvso) - https://www.drupal.org/u/gvso
 * Himanshu Dixit (himanshu-dixit) - https://www.drupal.org/u/himanshu-dixit
