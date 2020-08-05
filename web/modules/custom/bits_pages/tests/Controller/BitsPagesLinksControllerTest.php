<?php

namespace Drupal\bits_pages\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the bits_pages module.
 */
class BitsPagesLinksControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "bits_pages BitsPagesLinksController's controller functionality",
      'description' => 'Test Unit for module bits_pages and controller BitsPagesLinksController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests bits_pages functionality.
   */
  public function testBitsPagesLinksController() {
    // Check that the basic functions of module bits_pages.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
