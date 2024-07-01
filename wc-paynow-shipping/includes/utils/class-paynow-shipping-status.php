<?php
/**
 * PayNow_Shipping_Status class file.
 */
class PayNow_Shipping_Status {

	const AT_SENDER_CVS      = '0101'; // 商品已到寄件門市.
	const DELIVERING         = '5202'; // 交貨便收件.
	const AT_LOGISTIC_CENTER = '4060'; // 物流中心理貨中.
	const EC_RETURN          = '5201'; // EC 收退.
	const AT_RECEIVER_CVS    = '5000'; // 取件門市配達.
	const CUSTOMER_PICKUP    = '8000'; // 買家已取件.
	const PRODUCT_DELIVERED  = '8500'; // 商品配送完成.
	const TCAT_RETURN        = '8520'; // 黑貓收退.

}
