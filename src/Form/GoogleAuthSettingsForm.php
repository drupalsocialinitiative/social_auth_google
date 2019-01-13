<?php

namespace Drupal\social_auth_google\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\social_auth\Form\SocialAuthSettingsForm;

/**
 * Settings form for Social Auth Google.
 */
class GoogleAuthSettingsForm extends SocialAuthSettingsForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_auth_google_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array_merge(
      parent::getEditableConfigNames(),
      ['social_auth_google.settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('social_auth_google.settings');

    $form['google_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Google Client settings'),
      '#open' => TRUE,
      '#description' => $this->t('You need to first create a Google App at <a href="@google-dev">@google-dev</a>',
        ['@google-dev' => 'https://console.developers.google.com']),
    ];

    $form['google_settings']['client_id'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('Copy the Client ID here.'),
    ];

    $form['google_settings']['client_secret'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('Copy the Client Secret here.'),
    ];

    $form['google_settings']['authorized_redirect_url'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Authorized redirect URIs'),
      '#description' => $this->t('Copy this value to <em>Authorized redirect URIs</em> field of your Google App settings.'),
      '#default_value' => Url::fromRoute('social_auth_google.callback')->setAbsolute()->toString(),
    ];

    $form['google_settings']['authorized_javascript_origin'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Authorized Javascript Origin'),
      '#description' => $this->t('Copy this value to <em>Authorized Javascript Origins</em> field of your Google App settings.'),
      '#default_value' => $GLOBALS['base_url'],
    ];

    $form['google_settings']['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced settings'),
      '#open' => FALSE,
    ];

    $form['google_settings']['advanced']['scopes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Scopes for API call'),
      '#default_value' => $config->get('scopes'),
      '#description' => $this->t('Define any additional scopes to be requested, separated by a comma (e.g.: https://www.googleapis.com/auth/youtube.upload,https://www.googleapis.com/auth/youtube.readonly).<br>
                                  The scopes  \'openid\' \'email\' and \'profile\' are added by default and always requested.<br>
                                  You can see the full list of valid scopes and their description <a href="@scopes">here</a>.', ['@scopes' => 'https://developers.google.com/apis-explorer/#p/']),
    ];

    $form['google_settings']['advanced']['endpoints'] = [
      '#type' => 'textarea',
      '#title' => $this->t('API calls to be made to collect data'),
      '#default_value' => $config->get('endpoints'),
      '#description' => $this->t('Define the Endpoints to be requested when user authenticates with Google for the first time<br>
                                  Enter each endpoint in different lines in the format <em>endpoint</em>|<em>name_of_endpoint</em>.<br>
                                  <b>For instance:</b><br>
                                  /youtube/v3/playlists?maxResults=2&mine=true&part=snippet|playlists_list<br>'),
    ];

    $form['google_settings']['advanced']['restricted_domain'] = [
      '#type' => 'textfield',
      '#required' => FALSE,
      '#title' => $this->t('Restricted Domain'),
      '#default_value' => $config->get('restricted_domain'),
      '#description' => $this->t('If you want to restrict the users to a specific domain, insert your domain here. For example mycollege.edu. Note that this works only for Google Apps hosted accounts.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('social_auth_google.settings')
      ->set('client_id', trim($values['client_id']))
      ->set('client_secret', trim($values['client_secret']))
      ->set('scopes', $values['scopes'])
      ->set('endpoints', $values['endpoints'])
      ->set('restricted_domain', $values['restricted_domain'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
