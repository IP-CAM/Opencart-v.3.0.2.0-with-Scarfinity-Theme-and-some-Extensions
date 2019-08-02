<?php
class ModelExtensionShippingPickup extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/pickup');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_pickup_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_pickup_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['pickup'] = array(
				'code'         => 'pickup.pickup',
				'title'        => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00, $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'pickup',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_pickup_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

	public function getFields() {
		$fields = array();

		// Список точек самовывоза
		$query = $this->db->query("SELECT location_id, name, address, geocode, telephone, fax, image, open, comment FROM " . DB_PREFIX . "location");

		$data_locations = array();
		
		foreach ($query->rows as $result) {
			$data_locations[] = array(
				'name' 		=> $result['name'] . ' ' . $result['address'],
				'address' 	=> $result['address'],
				'telephone' => $result['telephone']
			);
		}

		$fields[] = array(
			'type'			=> 'text',
			'name'			=> 'city',
			'value'			=> 'Новосибирск',
			'label'			=> 'Выберите город',
			'placeholder'	=> 'Выберите город',
			'sort_order'	=> 0,
			'required'		=> true,
			'error'			=> 'Выберите город!',
			'hidden'		=> false,
			'disabled'		=> true
		);

		$fields[] = array(
			'type'			=> 'select',
			'name'			=> 'address_1',
			'label'			=> 'Выберете магазин',
			'placeholder'	=> 'Выберете магазин',
			'options'		=> $data_locations,
			'sort_order'	=> 0,
			'required'		=> true,
			'error'			=> 'Выберите пункт самовывоза!'
		);

		return $fields;
	}
}