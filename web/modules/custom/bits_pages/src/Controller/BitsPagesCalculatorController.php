<?php

namespace Drupal\bits_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BitsPagesCalculatorController.
 */
class BitsPagesCalculatorController extends ControllerBase {

  /**
   * Calculator.
   *
   * @return string
   *   Return Hello string.
   */
  public function Calculator(Request $request) {

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

    return [
      '#type' => 'markup',
      '#markup' => $result,
    ];
  }

}
