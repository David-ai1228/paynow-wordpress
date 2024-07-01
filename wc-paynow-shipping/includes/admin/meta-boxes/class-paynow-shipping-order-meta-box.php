<?php
/**
 * PayNow_Shipping_Order_Meta_Box class file.
 *
 * @package paynow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Display shipping info for PayNow shipping order.
 */
class PayNow_Shipping_Order_Meta_Box {

	/**
	 * Add meta box at order edit screen.
	 *
	 * @param string $post_type The post type.
	 * @param object $post The post object.
	 * @return void
	 */
	public static function add_meta_box( $post_type, $post ) {
		if ( 'shop_order' === $post_type ) {
			global $theorder;
			if ( ! is_object( $theorder ) ) {
				$theorder = wc_get_order( $post->ID );
			}

			foreach ( $theorder->get_items( 'shipping' ) as $item_id => $item ) {
				if ( PayNow_Shipping::is_paynow_shipping( $item->get_method_id() ) !== false ) {
					add_meta_box( 'paynow-shipping-info', __( 'PayNow Shipping Info', 'wc-paynow-shipping' ), array( __CLASS__, 'output' ), 'shop_order', 'side', 'high' );
					break;
				}
			}
		}
	}

	/**
	 * Output the meta box content.
	 *
	 * @param object $post The post object.
	 * @return void
	 */
	public static function output( $post ) {
		global $theorder;

		if ( ! is_object( $theorder ) ) {
			$theorder = wc_get_order( $post->ID );
		}

		// paynow 物流單號.
		echo '<table>';
		echo '<tr><th><div id="order-id" data-order-id="' . esc_html( $post->ID ) . '">' . esc_html__( 'PayNow Logistic Number', 'wc-paynow-shipping' ) . '</div></th><td>' . esc_html( $theorder->get_meta( PayNow_Shipping_Order_Meta::LogisticNumber ) ) . '</td></tr>';

		echo '<tr><th>' . esc_html__( 'Logistic Service', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $theorder->get_meta( PayNow_Shipping_Order_Meta::LogisticService ) ) . '</td></tr>';

		$service_id    = $theorder->get_meta( PayNow_Shipping_Order_Meta::LogisticServiceId );
		$payment_no    = $theorder->get_meta( PayNow_Shipping_Order_Meta::PaymentNo );
		$validation_no = $theorder->get_meta( PayNow_Shipping_Order_Meta::ValidationNo );

		$status = $theorder->get_meta( PayNow_Shipping_Order_Meta::Status );
		if ( '0' === $status ) {
			$status_txt = '訂單成立中';
		} elseif ( '1' === $status ) {
			$status_txt = '無效訂單';
		} else {
			$status_txt = 'N/A';
		}
		$delivery_status    = ( empty( $theorder->get_meta( PayNow_Shipping_Order_Meta::DeliveryStatus ) ) ) ? 'N/A' : $theorder->get_meta( PayNow_Shipping_Order_Meta::DeliveryStatus );
		$logistic_code      = ( empty( $theorder->get_meta( PayNow_Shipping_Order_Meta::LogisticCode ) ) ) ? 'N/A' : $theorder->get_meta( PayNow_Shipping_Order_Meta::LogisticCode );
		$logistic_code_desc = ( empty( $theorder->get_meta( PayNow_Shipping_Order_Meta::DetailStatusDesc ) ) ) ? 'N/A' : $theorder->get_meta( PayNow_Shipping_Order_Meta::DetailStatusDesc );

		$update_at = $theorder->get_meta( PayNow_Shipping_Order_Meta::StatusUpdateAt );

		// 物流商託運單號.
		echo '<tr><th>' . esc_html__( 'Payment NO', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $payment_no ) . '</td></tr>';

		// 物流商驗證碼，如需使用 IBON 列印請搭配 paymentno 使用. (只有C2C才有 Validation NO).
		if ( PayNow_Shipping_Logistic_Service::SEVEN === $service_id || PayNow_Shipping_Logistic_Service::SEVENFROZEN_C2C === $service_id ) {
			echo '<tr><th>' . esc_html__( 'Validation NO', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $validation_no ) . '</td></tr>';
		}

		echo '<tr><th>' . esc_html__( 'Status', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $status_txt ) . '</td></tr>';
		echo '<tr><th>' . esc_html__( 'Delivery Status', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $delivery_status ) . '</td></tr>';
		echo '<tr><th>' . esc_html__( 'Logistic Code', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $logistic_code ) . '</td></tr>';
		echo '<tr><th>' . esc_html__( 'Logistic Code Description', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $logistic_code_desc ) . '</td></tr>';

		do_action( 'paynow_shipping_admin_meta_before_last_query', $theorder );

		echo '<tr><th>' . esc_html__( 'Logistic Status Last Query', 'wc-paynow-shipping' ) . '</th><td>' . esc_html( $update_at ) . '</td></tr>';

		if ( PayNow_Shipping_Logistic_Service::TCAT === $service_id ) {
			// 黑貓物流單無法取消 since API 1.4.
			$cancel_btn = '';
		} else {
			$cancel_btn = '<button class="button cancel-shipping" data-id="' . esc_html( $post->ID ) . '">取消</button>';
		}

		echo '<tr id="paynow-action"><th>物流單動作</th><td><button class="button print-label" data-id=' . esc_html( $post->ID ) . ' data-service="' . esc_html( $service_id ) . '">列印</button><button class="button update-delivery-status" data-id="' . esc_html( $post->ID ) . '">更新</button>'.$cancel_btn.'</td></tr>';
		echo '</table>';
		?>


		<?php
		wc_enqueue_js(
			'jQuery(function($) {
$(".print-label").click(function(){
    window.open(ajaxurl + "?" + $.param({
        action: "paynow_shipping_print_label",
        orderids: $(this).data("id"),
		service: $(this).data("service"),
    }), "_blank", "toolbar=yes,scrollbars=yes,resizable=yes");
});
});'
		);
	}
}
