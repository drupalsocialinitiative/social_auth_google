<?php

namespace Drupal\social_auth_google;

use Drupal\social_auth\AuthManager\OAuth2Manager;
use Drupal\Core\Config\ConfigFactory;

/**
 * Contains all the logic for Google OAuth2 authentication.
 */
class GoogleAuthManager extends OAuth2Manager {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   Used for accessing configuration object factory.
   */
  public function __construct(ConfigFactory $configFactory) {
    parent::__construct($configFactory->get('social_auth_google.settings'));
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate() {
    $this->setAccessToken($this->client->getAccessToken('authorization_code',
      ['code' => $_GET['code']]));
  }

  /**
   * {@inheritdoc}
   */
  public function getUserInfo() {
    $this->user = $this->client->getResourceOwner($this->getAccessToken());
    return $this->user;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthorizationUrl() {
    $scopes = [
      'email',
      'profile',
    ];

    $google_scopes = $this->getScopes();
    if ($google_scopes) {
      if (strpos($google_scopes, ',')) {
        $scopes = array_merge($scopes, explode(',', $google_scopes));
      }
      else {
        $scopes[] = $google_scopes;
      }
    }

    // Returns the URL where user will be redirected.
    return $this->client->getAuthorizationUrl([
      'scope' => $scopes,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getExtraDetails() {
    $endpoints = $this->getEndPoints();

    // Store the data mapped with endpoints define in settings.
    $data = [];

    if ($endpoints) {
      // Iterate through api calls define in settings and retrieve them.
      foreach (explode(PHP_EOL, $endpoints) as $endpoint) {
        // Endpoint is set as path/to/endpoint|name.
        $parts = explode('|', $endpoint);
        $call[$parts[1]] = $this->requestEndPoint($parts[0]);
        array_push($data, $call);
      }

      return json_encode($data);
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function requestEndPoint($path) {
    $url = 'https://www.googleapis.com' . $path;

    $request = $this->client->getAuthenticatedRequest('GET', $url, $this->getAccessToken());

    $response = $this->client->getResponse($request);

    return $response->getBody()->getContents();
  }

  /**
   * {@inheritdoc}
   */
  public function getState() {
    return $this->client->getState();
  }

}
