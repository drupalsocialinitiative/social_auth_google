<?php
/**
 * @file
 * Contains \Drupal\google_login\Form\GoogleLoginSettingsForm
 */

namespace Drupal\google_login\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class GoogleLoginSettingsForm
 *
 * @package Drupal\google_login\Form
 */
class GoogleLoginSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array('google_login.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'google_login_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('google_login.settings');

    $form['google_settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Google Client settings'),
      '#open' => TRUE,
    );

    $form['google_settings']['client_id'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('Copy the Client ID here'),
    );

    $form['google_settings']['client_secret'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('Copy the Client Secret here'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $this->config('google_login.settings')
      ->set('client_id', $values['client_id'])
      ->set('client_secret', $values['client_secret'])
      ->save();

    parent::submitForm($form, $form_state);
  }
}
