<?php

namespace Drupal\social_auth_google;

use Symfony\Component\HttpFoundation\RequestStack;
use Google_Client;
use Google_Service_Oauth2;

/**
 * Manages the authentication requests.
 */
class GoogleAuthManager {

  /**
   * The request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The Google client.
   *
   * @var Google_Client
   */
  protected $client;

  /**
   * Code returned by Google for authentication.
   *
   * @var string
   */
  protected $code;

  /**
   * Access token for OAuth authentication.
   *
   * @var string
   */
  protected $accessToken;

  /**
   * The Google Oauth2 object.
   *
   * @var Google_Service_Oauth2
   */
  protected $googleService;

  /**
   * GoogleLoginManager constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   *   Used to get the parameter code returned by Google.
   */
  public function __construct(RequestStack $request) {
    $this->request = $request->getCurrentRequest();
  }

  /**
   * Sets the client object.
   *
   * @param Google_Client $client
   *   Google Client object.
   *
   * @return $this
   *   The current object.
   */
  public function setClient(Google_Client $client) {
    $this->client = $client;
    return $this;
  }

  /**
   * Gets the client object.
   *
   * @return Google_Client
   *   The Google Client object.
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * Authenticates the users by using the access token.
   *
   * @return $this
   *   The current object.
   */
  public function oAuthAuthenticate() {
    $this->client->setAccessToken($this->getAccessToken());
    return $this;
  }

  /**
   * Gets the access token by using the returned code.
   *
   * @return string
   *   The access token.
   */
  public function getAccessToken() {
    if (!$this->accessToken) {
      $this->accessToken = $this->client->fetchAccessTokenWithAuthCode($this->getCode());
    }

    return $this->accessToken;
  }

  /**
   * Sets the access token.
   *
   * @param array $access_token
   *   The access token.
   *
   * @return $this
   *   The current object.
   */
  public function setAccessToken(array $access_token) {
    $this->accessToken = $access_token;
    return $this;
  }

  /**
   * Creates Google Oauth2 Service.
   */
  public function createService() {
    $this->googleService = new Google_Service_Oauth2($this->getClient());
  }

  /**
   * Returns the user information.
   *
   * @return \Google_Service_Oauth2_Userinfoplus
   *   The Google_Service_Userinfoplus object.
   */
  public function getUserInfo() {
    return $this->googleService->userinfo->get();
  }

  /**
   * Gets the code returned by Google to authenticate.
   *
   * @return string
   *   The code string returned by Google.
   */
  protected function getCode() {
    if (!$this->code) {
      $this->code = $this->request->query->get('code');
    }

    return $this->code;
  }

}
