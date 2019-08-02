<?php
class ModelExtensionPaymentSberOnline extends Model {
	public function getMethod($shipping_method, $total) {
		$this->load->language('extension/payment/sber_online');

		// $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_cod_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		// if ($this->config->get('payment_cod_total') > 0 && $this->config->get('payment_cod_total') > $total) {
		// 	$status = false;
		// } elseif (!$this->cart->hasShipping()) {
		// 	$status = false;
		// } elseif (!$this->config->get('payment_cod_geo_zone_id')) {
		// 	$status = true;
		// } elseif ($query->num_rows) {
		// 	$status = true;
		// } else {
		// 	$status = false;
		// }

		$status = in_array($shipping_method, ['flat.flat', 'pickup.pickup', 'weight.weight']);

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'sber_online',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => 0
			);
		}

		return $method_data;
	}
}
