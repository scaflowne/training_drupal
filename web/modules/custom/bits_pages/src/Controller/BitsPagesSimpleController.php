<?php

namespace Drupal\bits_pages\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class BitsPagesSimpleController.
 */
class BitsPagesSimpleController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Esta es una pagina con un mensaje simple')
    ];
  }

}
