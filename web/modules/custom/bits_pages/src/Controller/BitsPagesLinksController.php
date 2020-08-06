<?php

namespace Drupal\bits_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class BitsPagesLinksController.
 */
class BitsPagesLinksController extends ControllerBase {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a BitsPagesLinksController object.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   *
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(RendererInterface $renderer, DateFormatterInterface $date_formatter) {
    $this->renderer = $renderer;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer'),
      $container->get('date.formatter'),
    );
  }

  /**
   * Links.
   *
   * @return string
   *   Return Hello string.
   */
  public function links() {

    $items = [
      Link::fromTextAndUrl('Administración de bloques',
        Url::fromUserInput('/admin/structure/block')),
      Link::fromTextAndUrl('Administración de contenidos',
        Url::fromUserInput('/admin/content')),
      Link::fromTextAndUrl('Administración de usuarios',
        Url::fromUserInput('/admin/people')),
      Link::fromTextAndUrl('Enlace a la portada del sitio',
        Url::fromUri('internal:/')),
      Link::createFromRoute('Enlace al nodo con id 1',
      'entity.node.canonical', ['node' => 1]),
      Link::fromTextAndUrl('Enlace a la edición del nodo con id 1',
        Url::fromUri('internal:/node/1/edit')),
      Link::fromTextAndUrl('Enlace externo a www.google.com',
        Url::fromUri('http://www.google.com',
          ['attributes' => ['target' => '_blank']])),
    ];

    $list = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#title' => 'My Menu',
      '#items' => $items,
      '#attributes' => ['class' => 'myclass'],
      '#wrapper_attributes' => ['class' => 'my_list_container'],
    ];

    $output['date'] = [
      '#type' => 'markup',
      '#markup' =>  $this->t('Today is @date', ['@date' => $this->dateFormatter->format(REQUEST_TIME, 'custom', 'Y:m:d')])
    ];

    $output['list'] = [
      '#type' => 'markup',
      '#markup' =>  $this->renderer->render($list)
    ];

    return $output;
  }

}
