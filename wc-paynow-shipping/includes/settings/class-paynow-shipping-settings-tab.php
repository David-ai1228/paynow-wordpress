<?php
/**
 * PayNow Shipping setting class.
 *
 * @package paynow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings class.
 */
class PayNow_Shipping_Settings_Tab extends WC_Settings_Page {

	private static $sections;
	/**
	 * Setting constructor.
	 */
	public function __construct() {

		$this->id    = 'paynow';
		$this->label = __( 'PayNow', 'wc-paynow-shipping' );

		self::$sections = array(
			'shipping' => __( 'Shipping Settings', 'wc-paynow-shipping' ),
		);

		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );

		add_action( 'admin_init', array( $this, 'paynow_shipping_redirect_default_tab' ) );

		add_filter( 'woocommerce_get_sections_' . $this->id, array( $this, 'paynow_shipping_sections' ), 10, 1 );

		parent::__construct();
	}

	/**
	 * Add shipping sections tab
	 *
	 * @param array $sections The settings section.
	 * @return array
	 */
	public function paynow_shipping_sections( $sections ) {

		if ( is_array( $sections ) && ! array_key_exists( 'shipping', $sections ) ) {
			$sections['shipping'] = __( 'Shipping Settings', 'wc-paynow-shipping' );
		}
		return $sections;
	}

	/**
	 * Get setting sections
	 *
	 * @return array
	 */
	public function get_sections() {

		// 如果 Paynow payment 沒有啟用，才回傳 shipping array.
		if ( ! is_plugin_active( 'wc-paynow-payment/wc-paynow-payment.php' ) ) {
			$sections = self::$sections;
			return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
		}

	}


	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public function get_settings( $current_section = '' ) {

		if ( 'shipping' === $current_section ) {
			$settings = apply_filters(
				'paynow_shipping_settings',
				array(
					array(
						'title' => __( 'General Shipping Settings', 'wc-paynow-shipping' ),
						'type'  => 'title',
						'id'    => 'shipping_general_setting',
					),
					array(
						'title'   => __( 'Debug Log', 'wc-paynow-shipping' ),
						'type'    => 'checkbox',
						'label'   => __( 'Enable logging', 'wc-paynow-shipping' ),
						'default' => 'no',
						'desc'    => sprintf( __( 'Log PayNow Shipping message, inside <code>%s</code>', 'wc-paynow-shipping' ), wc_get_log_file_path( 'wc-paynow-shipping' ) ),
						'id'      => 'paynow_shipping_debug_log_enabled',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'shipping_general_setting',
					),
					array(
						'title' => __( 'Store Settings', 'wc-paynow-shipping' ),
						'type'  => 'title',
						'desc'  => __( 'Enter your store information', 'wc-paynow-shipping' ),
						'id'    => 'paynow_shipping_store_settings',
					),
					array(
						'title'    => __( 'Sender Name', 'wc-paynow-shipping' ),
						'type'     => 'text',
						'desc'     => __( 'Please enter the sender name. It may be used when the order is returned.', 'wc-paynow-shipping' ),
						'desc_tip' => true,
						'id'       => 'paynow_shipping_sender_name',
					),
					array(
						'title' => __( 'Sender Address', 'wc-paynow-shipping' ),
						'type'  => 'text',
						'id'    => 'paynow_shipping_sender_address',
					),
					array(
						'title' => __( 'Sender Phone', 'wc-paynow-shipping' ),
						'type'  => 'text',
						'id'    => 'paynow_shipping_sender_phone',
					),
					array(
						'title' => __( 'Sender Email', 'wc-paynow-shipping' ),
						'type'  => 'email',
						'id'    => 'paynow_shipping_sender_email',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'shipping_store_setting',
					),
					array(
						'title' => __( 'Shipping Order Status Settings', 'wc-paynow-shipping' ),
						'type'  => 'title',
						'desc'  => __( 'Manage your shipping order status', 'wc-paynow-shipping' ),
						'id'    => 'paynow_shipping_shipping_settings',
					),
					array(
						'title'   => __( 'When products are located at sender CVS store, change order status to', 'wc-paynow-shipping' ),
						'type'    => 'select',
						'options' => self::paynow_get_order_status(),
						'id'      => 'paynow_shipping_order_status_at_sender_cvs',
					),
					array(
						'title'   => __( 'When products are at located receiver CVS store, change order status to', 'wc-paynow-shipping' ),
						'type'    => 'select',
						'options' => self::paynow_get_order_status(),
						'id'      => 'paynow_shipping_order_status_at_receiver_cvs',
					),
					array(
						'title'   => __( 'When customer pickuped or received products, change order status to', 'wc-paynow-shipping' ),
						'type'    => 'select',
						'options' => self::paynow_get_order_status(),
						'id'      => 'paynow_shipping_order_status_pickuped',
					),
					array(
						'title'   => __( "When the customer doesn't pickup products and the products are returned, change order status to", 'wc-paynow-shipping' ),
						'type'    => 'select',
						'options' => self::paynow_get_order_status(),
						'id'      => 'paynow_shipping_order_status_returned',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'shipping_order_setting',
					),
					array(
						'title' => __( 'TCat Shipping Settings', 'wc-paynow-shipping' ),
						'type'  => 'title',
						'id'    => 'shipping_TCat_setting',
					),
					array(
						'title'    => __( 'Estimate shipping date end', 'wc-paynow-shipping' ),
						'type'     => 'number',
						'default'  => 1,
						'desc'     => __( 'When will the estimate shipping date end. Default is 1, which means the estimate shipping end date is the the next day of the shipping order created.', 'wc-paynow-shipping' ),
						'desc_tip' => true,
						'id'       => 'paynow_shipping_tcat_deadline',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'shipping_TCat_setting',
					),
					array(
						'title' => __( 'API Settings', 'wc-paynow-shipping' ),
						'type'  => 'title',
						'desc'  => __( 'Enter your PayNow shipping user account and API Code', 'wc-paynow-shipping' ),
						'id'    => 'paynow_shipping_api_settings',
					),
					array(
						'title'   => __( 'Test Mode', 'wc-paynow-shipping' ),
						'type'    => 'checkbox',
						'label'   => __( 'Enable Test Mode', 'wc-paynow-shipping' ),
						'default' => 'yes',
						'desc'    => __( 'When enabled, you need to use the test-only User Account and API Code.', 'wc-paynow-shipping' ),
						'id'      => 'paynow_shipping_testmode_enabled',
					),
					array(
						'title'    => __( 'User Account', 'wc-paynow-shipping' ),
						'type'     => 'text',
						'desc'     => __( 'This is the user account when you apply PayNow shipping', 'wc-paynow-shipping' ),
						'desc_tip' => true,
						'id'       => 'paynow_shipping_user_account',
					),
					array(
						'title'    => __( 'API Code', 'wc-paynow-shipping' ),
						'type'     => 'text',
						'desc'     => __( 'This is the API Code when you apply PayNow shipping', 'wc-paynow-shipping' ),
						'desc_tip' => true,
						'id'       => 'paynow_shipping_api_code',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'paynow_shipping_api_settings',
					),
				)
			);
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * Get order status
	 *
	 * @return array
	 */
	private static function paynow_get_order_status() {
		$order_statuses = array(
			'' => __( 'No action', 'wc-paynow-shipping' ),
		);

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			if ( $slug == 'wc-cancelled' || $slug == 'wc-refunded' || $slug == 'wc-failed' ) {
				continue;
			}
			$order_statuses[ str_replace( 'wc-', '', $slug ) ] = $name;
		}


		return $order_statuses;
	}

	/**
	 * Redirect to shipping tab if paynow payment plugin is not activated.
	 *
	 * @return void
	 */
	public function paynow_shipping_redirect_default_tab() {

		global $pagenow;

		if ( 'admin.php' !== $pagenow ) {
			return;
		}

		if ( is_plugin_active( 'wc-paynow-payment/wc-paynow-payment.php' ) ) {
			return;
		}

		$page    = wp_unslash( $_GET['page'] );
		$tab     = wp_unslash( $_GET['tab'] );
		$section = wp_unslash( $_GET['section'] );

		if ( 'wc-settings' === $page && 'paynow' === $tab ) {

			if ( empty( $section ) ) {
				wp_redirect( admin_url( 'admin.php?page=wc-settings&tab=paynow&section=shipping' ) );
				exit;
			}
		}

	}

	/**
	 * Output setting tab
	 *
	 * @return void
	 */
	public function output() {

		global $current_section;

		if ( 'shipping' !== $current_section ) {
			return;
		}

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings
	 *
	 * @return void
	 */
	public function save() {

		global $current_section;

		if ( 'shipping' !== $current_section ) {
			return;
		}

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );
	}
}
