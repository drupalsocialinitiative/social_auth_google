<?php

namespace Drupal\Tests\social_auth_google\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\social_auth_google\GoogleAuthManager;

/**
 * @coversDefaultClass Drupal\social_auth_google\GoogleAuthManager
 * @group social_auth_google
 */
class GoogleAuthManagerTest extends UnitTestCase {
  /**
   * Session object.
   *
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  protected $session;

  /**
   * Request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * Google Client object.
   *
   * @var \Google_Client
   */
  protected $client;

  /**
   * Google Authentication manager.
   *
   * @var GoogleAuthManager
   */
  protected $googleManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->session = $this->getMock('\Symfony\Component\HttpFoundation\Session\Session');
    $this->request = $this->getMock('\Symfony\Component\HttpFoundation\RequestStack');

    $this->googleManager = new GoogleAuthManager(
      $this->session,
      $this->request
    );

    $this->client = $this->getMockBuilder('\Google_Client')
      ->disableOriginalConstructor()
      ->getMock();
  }

  /**
   * Tests set Google_Client object.
   *
   * @covers ::setClient
   */
  public function testSetGoogleClient() {
    $this->assertInstanceOf('Drupal\social_auth_google\GoogleAuthManager', $this->setClient());
  }

  /**
   * Tests get Google_Client object.
   *
   * @covers ::getClient
   * @covers ::saveAccessToken
   * @covers ::createService
   */
  public function testGetGoogleClient() {
    $this->setClient();
    $this->assertInstanceOf('Google_Client', $this->googleManager->getClient());
  }

  /**
   * Sets \Google_Client object to GoogleAuthManager.
   *
   * @return GoogleAuthManager
   *    setClient() returns $this, the GoogleAuthManager object.
   */
  protected  function setClient() {
    return $this->googleManager->setClient($this->client);
  }

}
