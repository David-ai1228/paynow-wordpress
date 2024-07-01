<?php
/**
 * The checkout field for PayNow CVS shipping order.
 *
 * @package paynow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get PayNow shipping cvs checkout fields.
 *
 * @param array $fields The checkout fields.
 * @return array
 */
function get_paynow_shipping_cvs_field( $fields ) {
	$fields['shipping']['shipping_phone']      = array(
		'label'    => __( 'Shipping Phone', 'wc-paynow-shipping' ),
		'required' => true,
		'type'     => 'tel',
		'validate' => array( 'phone' ),
		'class'    => array( 'form-row-wide', 'paynow-shipping-field' ),
		'priority' => 100,
	);
	$fields['shipping']['paynow_service']      = array(
		'required' => false,
		'label'    => __( 'Service', 'wc-paynow-shipping' ),
		'type'     => 'text',
		'class'    => array( 'form-row-wide', 'paynow-shipping-field' ),
	);
	$fields['shipping']['paynow_storename']    = array(
		'required'          => false,
		'label'             => __( 'Store Name', 'wc-paynow-shipping' ),
		'type'              => 'text',
		'custom_attributes' => array(
			'readonly' => true,
		),
		'class'             => array( 'form-row-wide', 'paynow-shipping-field' ),
		'priority'          => 120,
	);
	$fields['shipping']['paynow_storeid']      = array(
		'required'          => false,
		'label'             => __( 'Store ID', 'wc-paynow-shipping' ),
		'type'              => 'text',
		'custom_attributes' => array(
			'readonly' => true,
		),
		'class'             => array( 'form-row-wide', 'paynow-shipping-field' ),
		'priority'          => 121,
	);
	$fields['shipping']['paynow_storeaddress'] = array(
		'required'          => false,
		'label'             => __( 'Store Address', 'wc-paynow-shipping' ),
		'type'              => 'text',
		'custom_attributes' => array(
			'readonly' => true,
		),
		'class'             => array( 'form-row-wide', 'paynow-shipping-field' ),
		'priority'          => 122,
	);

	return apply_filters( 'paynow_shipping_cvs_fields', $fields );
}
