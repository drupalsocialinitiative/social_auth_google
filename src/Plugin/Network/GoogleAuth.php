<?php

namespace Drupal\social_auth_google\Plugin\Network;

use Drupal\Core\Url;
use Drupal\social_api\SocialApiException;
use Drupal\social_auth\Plugin\Network\NetworkBase;
use Drupal\social_auth_google\Settings\GoogleAuthSettings;
use League\OAuth2\Client\Provider\Google;

/**
 * Defines a Network Plugin for Social Auth Google.
 *
 * @package Drupal\social_auth_google\Plugin\Network
 *
 * @Network(
 *   id = "social_auth_google",
 *   social_network = "Google",
 *   type = "social_auth",
 *   handlers = {
 *     "settings": {
 *       "class": "\Drupal\social_auth_google\Settings\GoogleAuthSettings",
 *       "config_id": "social_auth_google.settings"
 *     }
 *   }
 * )
 */
class GoogleAuth extends NetworkBase implements GoogleAuthInterface {

  /**
   * Sets the underlying SDK library.
   *
   * @return \League\OAuth2\Client\Provider\Google|false
   *   The initialized 3rd party library instance.
   *   False if library could not be initialized.
   *
   * @throws \Drupal\social_api\SocialApiException
   *   If the SDK library does not exist.
   */
  protected function initSdk() {

    $class_name = '\League\OAuth2\Client\Provider\Google';
    if (!class_exists($class_name)) {
      throw new SocialApiException(sprintf('The Google library for PHP League OAuth2 not found. Class: %s.', $class_name));
    }

    /** @var \Drupal\social_auth_google\Settings\GoogleAuthSettings $settings */
    $settings = $this->settings;

    if ($this->validateConfig($settings)) {
      // All these settings are mandatory.
      $league_settings = [
        'clientId' => $settings->getClientId(),
        'clientSecret' => $settings->getClientSecret(),
        'redirectUri' => Url::fromRoute('social_auth_google.callback')->setAbsolute()->toString(),
        'accessType' => 'offline',
        'verify' => FALSE,
        'hostedDomain' => $settings->getRestrictedDomain() == '' ? NULL : $settings->getRestrictedDomain(),
      ];

      // Proxy configuration data for outward proxy.
      $proxyUrl = $this->siteSettings->get('http_client_config')['proxy']['http'];
      if ($proxyUrl) {
        $league_settings['proxy'] = $proxyUrl;
      }

      return new Google($league_settings);
    }

    return FALSE;
  }

  /**
   * Checks that module is configured.
   *
   * @param \Drupal\social_auth_google\Settings\GoogleAuthSettings $settings
   *   The Google auth settings.
   *
   * @return bool
   *   True if module is configured.
   *   False otherwise.
   */
  protected function validateConfig(GoogleAuthSettings $settings) {
    $client_id = $settings->getClientId();
    $client_secret = $settings->getClientSecret();
    if (!$client_id || !$client_secret) {
      $this->loggerFactory
        ->get('social_auth_google')
        ->error('Define Client ID and Client Secret on module settings.');

      return FALSE;
    }

    return TRUE;
  }

}
