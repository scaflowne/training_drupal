<?php

namespace Drupal\bits_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Class BitsPagesCalculatorController.
 */
class BitsPagesCalculatorController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a new BitsPagesCalculatorController.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
    );
  }

  /**
   * Calculator.
   *
   * @return string
   *   Return Hello string.
   */
  public function Calculator(Request $request) {

    // Assing value to GETs URL.
    $op = $request->query->get('op');
    $num1 = $request->query->get('num1');
    $num2 = $request->query->get('num2');
    $result = '';
    $flag = TRUE;
    if ($op && is_numeric($num1) && is_numeric($num2)) {
      switch ($op) {
        case 'suma':
            $op = '+';
            $result = $num1 + $num2;
          break;
        case 'resta':
            $op = '-';
            $result = $num1 - $num2;
          break;
        case 'multi':
            $op = '*';
            $result = $num1 * $num2;
          break;
        case 'divi':
            $op = '/';
            $result = ($num2 == 0)? 0 : $num1 / $num2;
          break;
        default:
          $flag = FALSE;
          $result = $this->t(
            'El parametro [op] debe ser igual a suma,
            resta,multi o divi <br>  Ejemplo,
            /bits_pages.calculator?op=resta&num1=2&num2=10');
         break;
      }
      if ( $flag ) {
        $result = $this->t('La operacion: :num1:op:num2=:result',
          [
            ':num1' => $num1,
            ':num2' => $num2,
            ':op' => $op,
            ':result' => $result
          ],
        );
      }
    }
    else {
      $mgs = [];
      if ( $op ) {
        $mgs[] = 'op=[ suma, resta, multi, divi ]';
      }
      if ( $num1 ) {
        $mgs[] = 'num1=[ debe ser numero ]';
      }
      if ( $num2 ) {
        $mgs[] = 'num2=[ debe ser numero ]';
      }
      $result = $this->t(
        'Hace falta el/los parametros o cometio un error en sus: :parameters',
        [':parameters' => implode(',', $mgs)]
      );
    }

    $general = $this->t('Welcome to my Calculator page.');
    if ($this->currentUser->hasPermission('administer nodes')) {
      $general = $this->t('Welcome <b>:name</b> user to my Calculator page.',
        [':name' => $this->currentUser->getAccountName()]);
    }

    $return['general'] = [
      '#type' => 'markup',
      '#markup' => "<p>{$general}</p>",
    ];

    $return['description'] = [
      '#type' => 'markup',
      '#markup' => $this->t('<p>Parameters:<br/>
        op=[ suma, resta, multi, divi ]<br/>
        num1=[ debe ser numero ]<br/>
        num2=[ debe ser numero ]</p>'),
    ];

    $return['result'] = [
      '#type' => 'markup',
      '#markup' => $result,
    ];

    return $return;
  }

}
