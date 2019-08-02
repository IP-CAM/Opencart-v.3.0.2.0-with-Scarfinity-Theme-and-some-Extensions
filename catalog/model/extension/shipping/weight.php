<?php
class ModelExtensionShippingWeight extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/weight');

		$method_data = array();

		$quote_data = array();

		$quote_data['weight'] = array(
			'code'         => 'weight.weight',
			'title'        => $this->language->get('text_description'),
			'cost'         => 100,
			'tax_class_id' => $this->config->get('shipping_flat_tax_class_id'),
			'text'         => $this->currency->format($this->tax->calculate(100, $this->config->get('shipping_flat_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
		);

		$method_data = array(
			'code'       => 'weight',
			'title'      => $this->language->get('text_title'),
			'quote'      => $quote_data,
			'sort_order' => $this->config->get('shipping_weight_sort_order'),
			'error'      => false
		);

		return $method_data;

		// $quote_data = array();

		// $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");

		// foreach ($query->rows as $result) {
		// 	if ($this->config->get('shipping_weight_' . $result['geo_zone_id'] . '_status')) {
		// 		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		// 		if ($query->num_rows) {
		// 			$status = true;
		// 		} else {
		// 			$status = false;
		// 		}
		// 	} else {
		// 		$status = false;
		// 	}

		// 	if ($status) {
		// 		$cost = '';
		// 		$weight = $this->cart->getWeight();

		// 		$rates = explode(',', $this->config->get('shipping_weight_' . $result['geo_zone_id'] . '_rate'));

		// 		foreach ($rates as $rate) {
		// 			$data = explode(':', $rate);

		// 			if ($data[0] >= $weight) {
		// 				if (isset($data[1])) {
		// 					$cost = $data[1];
		// 				}

		// 				break;
		// 			}
		// 		}

		// 		if ((string)$cost != '') {
		// 			$quote_data['weight_' . $result['geo_zone_id']] = array(
		// 				'code'         => 'weight.weight_' . $result['geo_zone_id'],
		// 				'title'        => $result['name'] . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
		// 				'cost'         => $cost,
		// 				'tax_class_id' => $this->config->get('shipping_weight_tax_class_id'),
		// 				'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('shipping_weight_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
		// 			);
		// 		}
		// 	}
		// }

		// $method_data = array();

		// if ($quote_data) {
		// 	$method_data = array(
		// 		'code'       => 'weight',
		// 		'title'      => $this->language->get('text_title'),
		// 		'quote'      => $quote_data,
		// 		'sort_order' => $this->config->get('shipping_weight_sort_order'),
		// 		'error'      => false
		// 	);
		// }

		// return $method_data;
	}

	public function getFields() {
		$fields = array();

		$fields[] = array(
			'type'			=> 'hidden',
			'name'			=> 'city',
			'value'			=> 'Новосибирск',
			'label'			=> 'Выберите город',
			'placeholder'	=> 'Выберите город',
			'sort_order'	=> 0,
			'required'		=> true,
			'error'			=> 'Выберите город!',
			'hidden'		=> true
		);

		$fields[] = array(
			'type'			=> 'text',
			'name'			=> 'company',
			'label'			=> 'Номер автобуса',
			'placeholder'	=> 'Введите номер автобуса',
			'sort_order'	=> 0,
			'required'		=> true,
			'error'			=> 'Укажите номар автобуса или автомобиля!'
		);

		return $fields;
	}
}
