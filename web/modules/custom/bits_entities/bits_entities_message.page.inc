<?php

/**
 * @file
 * Contains bits_entities_message.page.inc.
 *
 * Page callback for Message entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Message templates.
 *
 * Default template: bits_entities_message.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bits_entities_message(array &$variables) {
  // Fetch BitsEntitiesMessage Entity Object.
  $bits_entities_message = $variables['elements']['#bits_entities_message'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
