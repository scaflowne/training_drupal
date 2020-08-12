<?php

namespace Drupal\bits_entities;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\bits_entities\Entity\BitsEntitiesMessageInterface;

/**
 * Defines the storage handler class for Message entities.
 *
 * This extends the base storage class, adding required special handling for
 * Message entities.
 *
 * @ingroup bits_entities
 */
interface BitsEntitiesMessageStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Message revision IDs for a specific Message.
   *
   * @param \Drupal\bits_entities\Entity\BitsEntitiesMessageInterface $entity
   *   The Message entity.
   *
   * @return int[]
   *   Message revision IDs (in ascending order).
   */
  public function revisionIds(BitsEntitiesMessageInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Message author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Message revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\bits_entities\Entity\BitsEntitiesMessageInterface $entity
   *   The Message entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BitsEntitiesMessageInterface $entity);

  /**
   * Unsets the language for all Message with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
