<?php

namespace Drupal\social_auth_google\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\social_auth\Form\SocialAuthSettingsForm;

/**
 * Settings form for Social Auth Google.
 */
class GoogleAuthSettingsForm extends SocialAuthSettingsForm {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array_merge(['social_auth_google.settings'], parent::getEditableConfigNames());
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_auth_google_settings';
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
    ];

    $form['domain_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Domain settings'),
      '#open' => FALSE,
    ];

    $form['google_settings']['client_id'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('Copy the Client ID here'),
    ];

    $form['google_settings']['client_secret'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('Copy the Client Secret here'),
    ];

    $form['domain_settings']['restricted_domain'] = [
      '#type' => 'textfield',
      '#required' => FALSE,
      '#title' => $this->t('Restricted Domain'),
      '#default_value' => $config->get('restricted_domain'),
      '#description' => $this->t('If you want to restrict the users to a specific domain, insert your domain here. For example mycollege.edu. Note that this works only for Google Apps hosted accounts. Leave this blank if you are not sure what this is.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $this->config('social_auth_google.settings')
      ->set('client_id', $values['client_id'])
      ->set('client_secret', $values['client_secret'])
      ->set('restricted_domain', $values['restricted_domain'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
