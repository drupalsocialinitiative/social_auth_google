<?php

/**
 * @file
 * Contains \Drupal\google_login\Settings\GoogleLoginSettingsInterface.
 */

namespace Drupal\google_login\Settings;

/**
 * Class FacebookSettingsInterface.
 *
 * @package Drupal\google_login\Settings
 */
interface GoogleLoginSettingsInterface {

  /**
   * Gets the client ID.
   *
   * @return string
   *   The client ID.
   */
  public function getClientId();

  /**
   * Gets the client secret.
   *
   * @return string
   *   The client secret.
   */
  public function getClientSecret();

}
