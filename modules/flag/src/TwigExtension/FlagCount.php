<?php

namespace Drupal\flag\TwigExtension;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Template\TwigExtension;

/**
 * Provides a Twig extension to get the flag count given a flag and flaggable.
 */
class FlagCount extends TwigExtension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      'flagcount' => new \Twig_Function_Function(array(
        'Drupal\flag\TwigExtension\FlagCount',
        'flagCount'
      )),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'flag.count';
  }

  /**
   * Gets the number of flaggings for the given flag and flaggable.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *  The flag entity.
   * @param \Drupal\Core\Entity\EntityInterface $flaggable
   *  The flaggable entity.
   *
   * @return string
   *  The number of times the flaggings for the given parameters.
   */
  public static function flagCount($flag, $flaggable) {
    $counts = \Drupal::service('flag.count')->getEntityFlagCounts($flaggable);
    return empty($counts) || !isset($counts[$flag->id()]) ? '0' : $counts[$flag->id()];
  }

}