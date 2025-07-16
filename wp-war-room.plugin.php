<?php
if (!defined('ABSPATH')) {
	exit;
}
// Exit if accessed directly


class WP_War_Room
{

	/*
    |------------------------------------------------------------------------------------------------------------------
    | Class Members
    |------------------------------------------------------------------------------------------------------------------
     */
	private static $_instance;

	public $scripts;
	public $blocks;
	public $ajax;
	public $shortcode;
	public $rest;
	public $hubspot;
	public $callrail;
	// public $googleads;
	public $settings;

	const VERSION = '1.0';

	/*
  |------------------------------------------------------------------------------------------------------------------
  | Mesc Functions
  |------------------------------------------------------------------------------------------------------------------
  */

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{

		$this->scripts = WPWR\Plugin\Scripts::instance();
		$this->blocks = WPWR\Plugin\Blocks::instance();
		$this->ajax = WPWR\Plugin\Ajax::instance();
		// $this->shortcode = WPWR\Plugin\Shortcode::instance();
		// $this->rest = WPWR\Plugin\Rest::instance();
		$this->hubspot = WPWR\Plugin\Hubspot::instance();
		$this->callrail = WPWR\Plugin\Callrail::instance();
		// $this->googleads = WPWR\Plugin\Googleads::instance();
		$this->settings = WPWR\Plugin\Settings::instance();

		// Register Activation Hook
		register_activation_hook(WPWR_PLUGIN_DIR . 'wp-war-room.php', array($this, 'activate'));

		// Register Deactivation Hook
		register_deactivation_hook(WPWR_PLUGIN_DIR . 'wp-war-room.php', array($this, 'deactivate'));
	}

	/**
	 * Singleton Pattern.
	 *
	 * @since 1.0.0
	 */
	public static function instance()
	{

		if (!self::$_instance instanceof self) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}


	/**
	 * Trigger on activation
	 *
	 * @since 1.0.0
	 */
	public function activate() {}

	/**
	 * Trigger on deactivation
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {}
}
