<?php

namespace WPWR\Plugin;

/**
 * Scripts class
 *
 * @since   1.0
 */

defined('ABSPATH') || exit;

class Scripts
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

    // Load Backend CSS and JS
    add_action('admin_enqueue_scripts', array($this, 'backend_script_loader'));

    // Load Frontend CSS and JS
    add_action('wp_enqueue_scripts', array($this, 'frontend_script_loader'));
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

  /**
   * Load wp admin backend scripts
   *
   * @since 1.0
   * @return bool
   */
  public function backend_script_loader()
  {

    wp_localize_script(
      'fuel-logic-service-area-fuel-logic-service-area-editor-script-js',
      'wppb',
      array(
        'test'
      )
    );
  }

  /**
   * Load wp frontend scripts
   *
   * @since 1.0
   * @return bool
   */
  public function frontend_script_loader()
  {
    // Antd Drawer
    $asset_file = WPWR_JS_ROOT_DIR . 'drawer/build/index.asset.php';

    if (file_exists($asset_file)) {
      $asset = include $asset_file;
      wp_register_script('drawer-js', WPWR_JS_ROOT_URL . 'drawer/build/index.js', $asset['dependencies'], $asset['version'], true);
      wp_enqueue_style('drawer-css', WPWR_JS_ROOT_URL . 'drawer/build/style-index.css');
    }
  }
}
