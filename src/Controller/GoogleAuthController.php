<?php

namespace Drupal\social_auth_google\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\social_auth_google\GoogleAuthManager;
use Drupal\social_api\Plugin\NetworkManager;
use Drupal\social_auth\SocialAuthUserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
  private $networkManager;

  /**
   * The Google authentication manager.
   *
   * @var \Drupal\social_auth_google\GoogleAuthManager
   */
  private $googleManager;

  /**
   * The user manager.
   *
   * @var \Drupal\social_auth\SocialAuthUserManager
   */
  private $userManager;

  /**
   * GoogleLoginController constructor.
   *
   * @param \Drupal\social_api\Plugin\NetworkManager $network_manager
   *   Used to get an instance of social_auth_google network plugin.
   * @param \Drupal\social_auth_google\GoogleAuthManager $google_manager
   *   Used to manage authentication methods.
   * @param \Drupal\social_auth\SocialAuthUserManager $user_manager
   *   Manages user login/registration.
   */
  public function __construct(NetworkManager $network_manager, GoogleAuthManager $google_manager, SocialAuthUserManager $user_manager) {
    $this->networkManager = $network_manager;
    $this->googleManager = $google_manager;
    $this->userManager = $user_manager;
    $this->userManager->setPluginId('social_auth_google');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.network.manager'),
      $container->get('google_auth.manager'),
      $container->get('social_auth.user_manager')
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
    $client->setScopes(array('email', 'profile'));

    return new RedirectResponse($client->createAuthUrl());
  }

  /**
   * Callback function to login user.
   */
  public function callback() {
    /* @var \Google_Client $client */
    $client = $this->networkManager->createInstance('social_auth_google')->getSdk();

    $this->googleManager->setClient($client)
      ->authenticate()
      ->createService();

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
