<?php

/**
 * @file
 * Contains \Drupal\google_login\Settings\GoogleLoginSettings.
 */

namespace Drupal\google_login\Settings;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\social_api\Settings\SettingsBase;

/**
 * Class FacebookSettings.
 *
 * @package Drupal\google_login\Settings
 */
class GoogleLoginSettings extends SettingsBase implements GoogleLoginSettingsInterface {

  /**
   * Client ID.
   *
   * @var string
   */
  protected $clientId;

  /**
   * Client secret.
   *
   * @var string
   */
  protected $clientSecret;

  /**
   * Redirect uri
   *
   * @var string
   */
  protected $redirectUri;

  /**
   * {@inheritdoc}
   */
  public function getClientId() {
    if (!$this->clientId) {
      $this->clientId = $this->config->get('client_id');
    }
    return $this->clientId;
  }

  /**
   * {@inheritdoc}
   */
  public function getClientSecret() {
    if (!$this->clientSecret) {
      $this->clientSecret = $this->config->get('client_secret');
    }
    return $this->clientSecret;
  }
}
