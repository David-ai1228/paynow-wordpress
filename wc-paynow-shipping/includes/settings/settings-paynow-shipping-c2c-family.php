<?php
/**
 * PayNow Shipping c2c Family setting array.
 *
 * @package paynow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for PayNow Shipping c2c Family
 */
return array(
	'title'                    => array(
		'title'       => __( 'Title', 'wc-paynow-shipping' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'wc-paynow-shipping' ),
		'default'     => __( 'PayNow Shipping C2C Family', 'wc-paynow-shipping' ),
		'desc_tip'    => true,
	),
	'description'              => array(
		'title'       => __( 'Description', 'wc-paynow-shipping' ),
		'type'        => 'textarea',
		'description' => __( 'This controls the description which the user sees during checkout.', 'wc-paynow-shipping' ),
		'desc_tip'    => true,
	),
	'cost'                     => array(
		'title'   => __( 'Shipping Cost', 'wc-paynow-shipping' ),
		'type'    => 'number',
		'default' => 0,
		'min'     => 0,
		'step'    => 1,
	),
	'free_shipping_requires'   => array(
		'title'   => __( 'Free shipping requires', 'wc-paynow-shipping' ),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => '',
		'options' => array(
			''           => __( 'N/A', 'wc-paynow-shipping' ),
			'coupon'     => __( 'A valid free shipping coupon', 'wc-paynow-shipping' ),
			'min_amount' => __( 'A minimum order amount', 'wc-paynow-shipping' ),
			'either'     => __( 'A minimum order amount OR a coupon', 'wc-paynow-shipping' ),
			'both'       => __( 'A minimum order amount AND a coupon', 'wc-paynow-shipping' ),
		),
	),
	'free_shipping_min_amount' => array(
		'title'       => __( 'Minimum order amount for free shipping', 'wc-paynow-shipping' ),
		'type'        => 'price',
		'default'     => 0,
		'placeholder' => wc_format_localized_price( 0 ),
		'description' => __( 'Users will need to spend this amount to get free shipping.', 'wc-paynow-shipping' ),
		'desc_tip'    => true,
	),
);
