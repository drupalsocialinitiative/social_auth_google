<?php

namespace Drupal\social_auth_google\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\social_auth_google\GoogleAuthManager;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\social_auth\SocialAuthUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Manages requests to Google API.
 */
class GoogleAuthController extends ControllerBase {

  /**
   * The network plugin manager.
   *
   * @var \Drupal\social_api\Plugin\NetworkManager
   */
  protected $networkManager;

  /**
   * The Google authentication manager.
   *
   * @var \Drupal\social_auth_google\GoogleAuthManager
   */
  protected $googleManager;

  /**
   * The user manager.
   *
   * @var \Drupal\social_auth\SocialAuthUserManager
   */
  protected $userManager;

  /**
   * The session manager.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

  /**
   * GoogleLoginController constructor.
   *
   * @param \Drupal\social_api\Plugin\NetworkManager $network_manager
   *   Used to get an instance of social_auth_google network plugin.
   * @param \Drupal\social_auth\SocialAuthUserManager $user_manager
   *   Manages user login/registration.
   * @param \Drupal\social_auth_google\GoogleAuthManager $google_manager
   *   Used to manage authentication methods.
   * @param SessionInterface $session
   *   Used to store the access token into a session variable.
   */
  public function __construct(NetworkManager $network_manager, SocialAuthUserManager $user_manager, GoogleAuthManager $google_manager, SessionInterface $session) {
    $this->networkManager = $network_manager;
    $this->userManager = $user_manager;
    $this->googleManager = $google_manager;
    $this->session = $session;
    // Sets the plugin id.
    $this->userManager->setPluginId('social_auth_google');
    // Sets the session keys to nullify if user could not logged in.
    $this->userManager->setSessionKeysToNullify(['social_auth_google_access_token']);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.network.manager'),
      $container->get('social_auth.user_manager'),
      $container->get('social_auth_google.manager'),
      $container->get('session')
    );
  }

  /**
   * Redirect to Google Services Authentication page.
   *
   * @return \Zend\Diactoros\Response\RedirectResponse
   *   Redirection to Google Accounts.
   */
  public function redirectToGoogle() {
    /* @var \Google_Client $client */
    $client = $this->networkManager->createInstance('social_auth_google')->getSdk();
    $client->setScopes(['email', 'profile']);

    return new RedirectResponse($client->createAuthUrl());
  }

  /**
   * Callback function to login user.
   */
  public function callback() {
    /* @var \Google_Client $client */
    $client = $this->networkManager->createInstance('social_auth_google')->getSdk();

    $this->googleManager->setClient($client)->authenticate();

    // Saves access token so that event subscribers can call Google API.
    $this->session->set('social_auth_google_access_token', $this->googleManager->getAccessToken());

    // Gets user information.
    $user = $this->googleManager->getUserInfo();
    // If user information could be retrieved.
    if ($user) {
      return $this->userManager->authenticateUser($user->getEmail(), $user->getName(), $user->getId(), $user->getPicture());
    }

    drupal_set_message($this->t('You could not be authenticated, please contact the administrator'), 'error');
    return $this->redirect('user.login');
  }

}
