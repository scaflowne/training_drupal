<?php

namespace Drupal\bits_entities\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\bits_entities\Entity\BitsEntitiesMessageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BitsEntitiesMessageController.
 *
 *  Returns responses for Message routes.
 */
class BitsEntitiesMessageController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Message revision.
   *
   * @param int $bits_entities_message_revision
   *   The Message revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($bits_entities_message_revision) {
    $bits_entities_message = $this->entityTypeManager()->getStorage('bits_entities_message')
      ->loadRevision($bits_entities_message_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('bits_entities_message');

    return $view_builder->view($bits_entities_message);
  }

  /**
   * Page title callback for a Message revision.
   *
   * @param int $bits_entities_message_revision
   *   The Message revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($bits_entities_message_revision) {
    $bits_entities_message = $this->entityTypeManager()->getStorage('bits_entities_message')
      ->loadRevision($bits_entities_message_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $bits_entities_message->label(),
      '%date' => $this->dateFormatter->format($bits_entities_message->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Message.
   *
   * @param \Drupal\bits_entities\Entity\BitsEntitiesMessageInterface $bits_entities_message
   *   A Message object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BitsEntitiesMessageInterface $bits_entities_message) {
    $account = $this->currentUser();
    $bits_entities_message_storage = $this->entityTypeManager()->getStorage('bits_entities_message');

    $langcode = $bits_entities_message->language()->getId();
    $langname = $bits_entities_message->language()->getName();
    $languages = $bits_entities_message->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $bits_entities_message->label()]) : $this->t('Revisions for %title', ['%title' => $bits_entities_message->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all message revisions") || $account->hasPermission('administer message entities')));
    $delete_permission = (($account->hasPermission("delete all message revisions") || $account->hasPermission('administer message entities')));

    $rows = [];

    $vids = $bits_entities_message_storage->revisionIds($bits_entities_message);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\bits_entities\BitsEntitiesMessageInterface $revision */
      $revision = $bits_entities_message_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $bits_entities_message->getRevisionId()) {
          $link = $this->l($date, new Url('entity.bits_entities_message.revision', [
            'bits_entities_message' => $bits_entities_message->id(),
            'bits_entities_message_revision' => $vid,
          ]));
        }
        else {
          $link = $bits_entities_message->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.bits_entities_message.translation_revert', [
                'bits_entities_message' => $bits_entities_message->id(),
                'bits_entities_message_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.bits_entities_message.revision_revert', [
                'bits_entities_message' => $bits_entities_message->id(),
                'bits_entities_message_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.bits_entities_message.revision_delete', [
                'bits_entities_message' => $bits_entities_message->id(),
                'bits_entities_message_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['bits_entities_message_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
