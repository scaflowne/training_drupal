<?php

namespace Drupal\bits_entities;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Message entities.
 *
 * @ingroup bits_entities
 */
class BitsEntitiesMessageListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Message ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\bits_entities\Entity\BitsEntitiesMessage $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.bits_entities_message.edit_form',
      ['bits_entities_message' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
