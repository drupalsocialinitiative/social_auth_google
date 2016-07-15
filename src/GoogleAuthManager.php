<?php

namespace Drupal\social_auth_google;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Manages the authentication requests.
 */
class GoogleAuthManager {
  /**
   * The session object.
   *
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  private $session;

  /**
   * The request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  private $request;

  /**
   * The Google client.
   *
   * @var \Google_Client
   */
  private $client;

  /**
   * Code returned by Google for authentication.
   *
   * @var string
   */
  private $code;

  /**
   * The Google Oauth2 object.
   *
   * @var \Google_Service_Oauth2
   */
  private $googleService;

  /**
   * GoogleLoginManager constructor.
   *
   * @param \Symfony\Component\HttpFoundation\Session\Session $session
   *   Used to access and store session variables.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   *   Used to get the parameter code returned by Google.
   */
  public function __construct(Session $session, RequestStack $request) {
    $this->session = $session;
    $this->request = $request->getCurrentRequest();
  }

  /**
   * Gets the access token.
   *
   * @return array
   *   Array with the token data.
   */
  public function getAccessToken() {
    return $this->session->get('social_auth_google_token');
  }

  /**
   * Sets the client object.
   *
   * @param \Google_Client $client
   *   Google Client object.
   *
   * @return $this
   *   The current object.
   */
  public function setClient(\Google_Client $client) {
    $this->client = $client;
    return $this;
  }

  /**
   * Gets the client object.
   *
   * @return \Google_Client.
   *   The Google Client object.
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * Authenticates the users by using the returned code.
   *
   * @return $this
   *   The current object.
   */
  public function authenticate() {
    $this->client->authenticate($this->getCode());
    return $this;
  }

  /**
   * Saves the access token.
   *
   * @param string $key
   *   The session key.
   *
   * @return $this
   *   The current object.
   */
  public function saveAccessToken($key) {
    $this->session->set($key, $this->getClient()->getAccessToken());
    return $this;
  }

  /**
   * Creates Google Oauth2 Service.
   */
  public function createService() {
    $this->googleService = new \Google_Service_Oauth2($this->getClient());
  }

  /**
   * Returns the user information.
   *
   * @return \Google_Service_Oauth2_Userinfoplus.
   *   Google_Service_Userinfoplus object.
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
