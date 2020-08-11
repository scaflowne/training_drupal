<?php

namespace Drupal\bits_users\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'UserBlock' block.
 *
 * @Block(
 *  id = "user_block",
 *  admin_label = @Translation("Usuario Actual"),
 * )
 */
class UserBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a new UserBlock.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    // Only grant access to users with the 'access user block' permission.
    return AccessResult::allowedIfHasPermission($account, 'access user block');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Roles without authenticated
    $roles = array_filter($this->currentUser->getRoles(), function($v, $k) {
        return $v !== 'authenticated';
    }, ARRAY_FILTER_USE_BOTH);

    $build = [
      '#theme' => 'user_block',
      '#current_user' => [
        'uid' => $this->currentUser->id(),
        'displayName' => $this->currentUser->getDisplayName(),
        'roles' => $roles,
        'email' => $this->currentUser->getEmail(),
        'lastAccessedTime' => $this->currentUser->getLastAccessedTime(),
      ],
    ];

    return $build;
  }

}
