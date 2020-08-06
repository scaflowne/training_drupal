<?php

namespace Drupal\bits_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Database\Connection;

/**
 * Class SimpleForm.
 */
class SimpleForm extends FormBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;


  /**
   * Constructs a new ListingEmpty.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(AccountInterface $current_user, Connection $database) {
    $this->currentUser = $current_user;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['titulo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
    ];
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre de usuario'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => $this->currentUser->getAccountName(),
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Correo electrónico'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#default_value' => $this->currentUser->getEmail(),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $this->database->merge('bits_forms_simple')
      ->keys([
        'title' => $values['titulo'],
        'username' => $values['username'],
        'email' => $values['email'],
      ])->execute();

      \Drupal::messenger()->addMessage($this->t('Save Form'));
  }

}
