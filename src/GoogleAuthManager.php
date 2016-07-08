<?php

namespace Drupal\social_auth_google;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Manages the authentication requests
 */
class GoogleAuthManager {
  /**
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  private $session;

  /**
   * @var \Symfony\Component\HttpFoundation\Request
   */
  private $request;

  /**
   * @var \Google_Client
   */
  private $client;

  /**
   * Code return from google to authenticate
   *
   * @var string
   */
  private $code;

  /**
   * @var \Google_Service_Oauth2
   */
  private $googleService;

  /**
   * GoogleLoginManager constructor.
   *
   * @param \Symfony\Component\HttpFoundation\Session\Session $session
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   */
  public function __construct(Session $session, RequestStack $request) {
    $this->session = $session;
    $this->request = $request->getCurrentRequest();
  }

  /**
   * Get the access token
   *
   * @return array
   */
  public function getAccessToken() {
    return $this->session->get('social_auth_google_token');
  }

  /**
   * @param \Google_Client $client
   *
   * @return $this
   */
  public function setClient(\Google_Client $client) {
    $this->client = $client;
    return $this;
  }

  /**
   * @return $this
   */
  public function authenticate() {
    $this->client->authenticate($this->getCode());
    return $this;
  }

  /**
   * Save the access token
   *
   * @param string $key
   *
   * @return $this
   */
  public function saveAccessToken($key) {
    $this->session->set($key, $this->client->getAccessToken());
    return $this;
  }

  /**
   * Create Google Oauth2 Service
   */
  public function createService() {
    $this->googleService = new \Google_Service_Oauth2($this->client);
  }

  /**
   * @return \Google_Service_Oauth2_Userinfoplus
   */
  public function getUserInfo() {
    return $this->googleService->userinfo->get();
  }

  /**
   * Get the code return to authenticate
   *
   * @return mixed
   */
  protected function getCode() {
    if(!$this->code) {
      $this->code = $this->request->query->get('code');
    }

    return $this->code;
  }
}
