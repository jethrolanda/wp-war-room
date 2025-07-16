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
class Settings
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

    /**
     * Register our war_room_settings_init to the admin_init action hook.
     */
    add_action('admin_init', array($this, 'war_room_settings_init'));


    /**
     * Register our war_room_options_page to the admin_menu action hook.
     */
    add_action('admin_menu', array($this, 'war_room_options_page'));
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
   * @internal never define functions inside callbacks.
   * these functions could be run multiple times; this would result in a fatal error.
   */

  /**
   * custom option and settings
   */
  public function war_room_settings_init()
  {
    // Register a new setting for "warroom" page.
    register_setting('warroom', 'warroom_options');

    // Register a new section in the "warroom" page.
    add_settings_section(
      'warroom_section_developers',
      '',
      '',
      'warroom'
    );

    // Register a new field in the "warroom_section_developers" section, inside the "warroom" page.
    add_settings_field(
      'warroom_field_callrail_api', // As of WP 4.6 this value is used only internally.
      // Use $args' label_for to populate the id inside the callback.
      __('Call Rail API Key', 'warroom'),
      array($this, 'warroom_field_callrail_api_cb'),
      'warroom',
      'warroom_section_developers',
      array(
        'label_for'         => 'warroom_field_callrail_api',
        'class'             => 'warroom_row',
      )
    );
    add_settings_field(
      'warroom_field_hubspot_api', // As of WP 4.6 this value is used only internally.
      // Use $args' label_for to populate the id inside the callback.
      __('Hubspot API Key', 'warroom'),
      array($this, 'warroom_field_hubspot_api_cb'),
      'warroom',
      'warroom_section_developers',
      array(
        'label_for'         => 'warroom_field_hubspot_api',
        'class'             => 'warroom_row',
      )
    );
    add_settings_field(
      'warroom_field_googleads_api', // As of WP 4.6 this value is used only internally.
      // Use $args' label_for to populate the id inside the callback.
      __('Google Ads API', 'warroom'),
      array($this, 'warroom_field_googleads_api_cb'),
      'warroom',
      'warroom_section_developers',
      array(
        'label_for'         => 'warroom_field_googleads_api',
        'class'             => 'warroom_row',
      )
    );
  }



  /**
   * Pill field callbakc function.
   *
   * WordPress has magic interaction with the following keys: label_for, class.
   * - the "label_for" key value is used for the "for" attribute of the <label>.
   * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
   * Note: you can add custom key value pairs to be used inside your callbacks.
   *
   * @param array $args
   */
  public function warroom_field_callrail_api_cb($args)
  {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('warroom_options'); ?>
    <input style="width: 400px" type="text" placeholder="API Key" id="<?php echo esc_attr($args['label_for']); ?>" name="warroom_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : ''; ?>" />

  <?php
  }

  public function warroom_field_hubspot_api_cb($args)
  {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('warroom_options');
  ?>
    <input style="width: 400px" type="text" placeholder="API Key" id="<?php echo esc_attr($args['label_for']); ?>" name="warroom_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : ''; ?>" />

  <?php
  }

  public function warroom_field_googleads_api_cb($args)
  {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('warroom_options');
  ?>
    <input style="width: 400px" type="text" placeholder="API Key" id="<?php echo esc_attr($args['label_for']); ?>" name="warroom_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : ''; ?>" />
    <p class="description">
      <?php esc_html_e('This is the API Key for Google Ads API', 'fuellogic-app'); ?>
    </p>
    <input style="width: 400px" type="text" placeholder="Client ID" id="warroom_field_googleads_client_id" name="warroom_options[warroom_field_googleads_client_id]" value="<?php echo isset($options['warroom_field_googleads_client_id']) ? esc_attr($options['warroom_field_googleads_client_id']) : ''; ?>" />
    <p class="description">
      <?php esc_html_e('Client ID', 'fuellogic-app'); ?>
    </p>
    <input style="width: 400px" type="text" placeholder="Client Secret" id="warroom_field_googleads_client_secret" name="warroom_options[warroom_field_googleads_client_secret]" value="<?php echo isset($options['warroom_field_googleads_client_secret']) ? esc_attr($options['warroom_field_googleads_client_secret']) : ''; ?>" />
    <p class="description">
      <?php esc_html_e('Client Secret', 'fuellogic-app'); ?>
    </p>
  <?php
  }

  /**
   * Add the top level menu page.
   */
  public function war_room_options_page()
  {
    add_menu_page(
      'War Room Settings',
      'War Room',
      'manage_options',
      'war-room',
      array($this, 'options_page')
    );
  }

  /**
   * Top level menu callback function
   */
  public function options_page()
  {
    // check user capabilities
    if (! current_user_can('manage_options')) {
      return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
      // add settings saved message with the class of "updated"
      add_settings_error('war-room_messages', 'war-room_message', __('Settings Saved', 'warroom'), 'updated');
    }

    // show error/update messages
    settings_errors('war-room_messages');
  ?>
    <div class="wrap">
      <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
      <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "warroom"
        settings_fields('warroom');
        // output setting sections and their fields
        // (sections are registered for "warroom", each field is registered to a specific section)
        do_settings_sections('warroom');
        // output save settings button
        submit_button('Save Settings');
        ?>
      </form>
    </div>
<?php
  }
}
