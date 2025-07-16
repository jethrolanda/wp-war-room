<?php

namespace WPWR\Plugin;


defined('ABSPATH') || exit;

class Shortcode
{
  /**
   * The single instance of the class.
   *
   * @since 1.0
   */
  protected static $_instance = null;

  /**
   * Class constructor.
   *
   * @since 1.0.0
   */
  public function __construct()
  {
    add_shortcode('war_room', array($this, 'warroom_shortcode'));
  }

  /**
   * Main Instance.
   * 
   * @since 1.0
   */
  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function warroom_shortcode($atts)
  {
    $atts = shortcode_atts(array(
      'foo' => 'no foo',
      'baz' => 'default baz'
    ), $atts, 'bartag');

    ob_start();


    echo 'hello world';
    return ob_get_clean();
  }
}
