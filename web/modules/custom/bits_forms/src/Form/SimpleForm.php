<?php

namespace Drupal\bits_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Database\Connection;
use Drupal\Component\Utility\EmailValidatorInterface;

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
   * The email validator.
   *
   * @var \Drupal\Component\Utility\EmailValidatorInterface
   */
  protected $emailValidator;

  /**
   * Constructs a new ListingEmpty.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   *
   * @param \Drupal\Component\Utility\EmailValidatorInterface $email_validator
   *   The email validator.
   */
  public function __construct(
      AccountInterface $current_user,
      Connection $database,
      EmailValidatorInterface $email_validator
    ) {
    $this->currentUser = $current_user;
    $this->database = $database;
    $this->emailValidator = $email_validator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('database'),
      $container->get('email.validator'),
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
      '#maxlength' => 30,
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

      // Validate title field
      if ( $key == 'titulo') {
        // Check if the firts letter is upper
        if (!ctype_upper(mb_substr($value, 0, 1))) {
          $form_state->setErrorByName(
            $key, $this->t('El Título debe iniciar en Mayuscula'));
        }
        if (!in_array(strlen($value), range(5, 30))) {
          $form_state->setErrorByName(
            $key,
            $this->t('El campo de Título debe tener entre 5 a 30 carácteres')
          );
        }
      }

      if (
          $key == 'username' &&
          $this->currentUser->id() &&
          $value !== $this->currentUser->getAccountName()
        ) {
        $form_state->setErrorByName(
          $key,
          $this->t('El Usuario no debe ser diferente al registrado en la cuenta')
        );
      }

      if ($key == 'email' && !$this->emailValidator->isValid(trim($value))) {
        $form_state->setErrorByName(
          $key,
          $this->t(
            $this->t('@emailaddress is an invalid email address.',
              ['@emailaddress' => $value])
          )
        );
      }

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
