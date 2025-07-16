<?php

namespace WPWR\Plugin;

/**
 * Plugins custom settings page that adheres to wp standard
 * see: https://developer.wordpress.org/plugins/settings/custom-settings-page/
 *
 * @since   1.0
 */

defined('ABSPATH') || exit;

/**
 * WP Settings Class.
 */
class Ajax
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
    // Fetch fuel savings data items via ajax 
    add_action("wp_ajax_WPWR_get_custom_block_patterns", array($this, 'WPWR_get_custom_block_patterns'));

    // Date filter
    add_action("wp_ajax_nopriv_date_filter", array($this, 'date_filter'));
    add_action("wp_ajax_date_filter", array($this, 'date_filter'));
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
   * Get fuel savings data.
   * 
   * @since 1.0
   */
  public function date_filter()
  {

    if (!defined('DOING_AJAX') || !DOING_AJAX) {
      wp_die();
    }

    /**
     * Verify nonce
     */
    if (isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'datefilter-nonce')) {
      wp_die();
    }

    global $wpwr;
    try {

      error_log(print_r($_POST, true));

      $dateStart = isset($_POST['date-start'])  ? $_POST['date-start'] : '';
      $dateEnd = isset($_POST['date-end'])  ? $_POST['date-end'] : '';
      $compareStart = isset($_POST['compare-start'])  ? $_POST['compare-start'] : '';
      $compareEnd = isset($_POST['compare-end'])  ? $_POST['compare-end'] : '';

      $hubspot = $wpwr->hubspot->get_hubspot_data(array(
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
      ));

      $callrailKickAss = $wpwr->callrail->get_callrail_data(array(
        'tag' => 'A-FLKickAss',
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
      ));

      $callrailNeedsFuel = $wpwr->callrail->get_callrail_data(array(
        'tag' => 'A-NeedsFuel',
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
      ));

      // Compare
      $hubspotCompare = $wpwr->hubspot->get_hubspot_data(array(
        'dateStart' => $compareStart,
        'dateEnd' => $compareEnd,
      ));

      $callrailKickAssCompare = $wpwr->callrail->get_callrail_data(array(
        'tag' => 'A-FLKickAss',
        'dateStart' => $compareStart,
        'dateEnd' => $compareEnd,
      ));

      $callrailNeedsFuelCompare = $wpwr->callrail->get_callrail_data(array(
        'tag' => 'A-NeedsFuel',
        'dateStart' => $compareStart,
        'dateEnd' => $compareEnd,
      ));

      wp_send_json(array(
        'status' => 'success',
        'data' => array(
          'hubspot' => count($hubspot),
          'callRailKickAss' => count($callrailKickAss),
          'callRailNeedsFuel' => count($callrailNeedsFuel),
          // compare
          'hubspotCompare' => count($hubspotCompare),
          'callrailKickAssCompare' => count($callrailKickAssCompare),
          'callrailNeedsFuelCompare' => count($callrailNeedsFuelCompare),
        ),
      ));
    } catch (\Exception $e) {

      wp_send_json(array(
        'status' => 'error',
        'message' => $e->getMessage()
      ));
    }
  }
}
