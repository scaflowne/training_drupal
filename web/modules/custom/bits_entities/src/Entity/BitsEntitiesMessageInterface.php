<?php

namespace Drupal\bits_entities\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Message entities.
 *
 * @ingroup bits_entities
 */
interface BitsEntitiesMessageInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Message name.
   *
   * @return string
   *   Name of the Message.
   */
  public function getName();

  /**
   * Sets the Message name.
   *
   * @param string $name
   *   The Message name.
   *
   * @return \Drupal\bits_entities\Entity\BitsEntitiesMessageInterface
   *   The called Message entity.
   */
  public function setName($name);

  /**
   * Gets the Message creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Message.
   */
  public function getCreatedTime();

  /**
   * Sets the Message creation timestamp.
   *
   * @param int $timestamp
   *   The Message creation timestamp.
   *
   * @return \Drupal\bits_entities\Entity\BitsEntitiesMessageInterface
   *   The called Message entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Message revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Message revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\bits_entities\Entity\BitsEntitiesMessageInterface
   *   The called Message entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Message revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Message revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\bits_entities\Entity\BitsEntitiesMessageInterface
   *   The called Message entity.
   */
  public function setRevisionUserId($uid);

}
