<?php
class ModelExtensionShippingFlat extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/flat');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['flat'] = array(
				'code'         => 'flat.flat',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('shipping_flat_cost'),
				'tax_class_id' => $this->config->get('shipping_flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('shipping_flat_cost'), $this->config->get('shipping_flat_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'flat',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_flat_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

	public function getFields() {
		$fields = array();

		$transport = array(
			0 => array(
				'name' 		=> "ТК 'Энергия'",
				'address' 	=> '',
				'telephone' => ''
			),
			1 => array(
				'name' 		=> "ТК 'Деловые Линии'",
				'address' 	=> '',
				'telephone' => ''
			),
			2 => array(
				'name' 		=> "ТК 'ПЭК'",
				'address' 	=> '',
				'telephone' => ''
			),
			3 => array(
				'name' 		=> "ТК 'Байкал-сервис'",
				'address' 	=> '',
				'telephone' => ''
			),
			4 => array(
				'name' 		=> "ТК 'РАТЭК'",
				'address' 	=> '',
				'telephone' => ''
			),
			5 => array(
				'name' 		=> "ТК 'ЖелДорЭкспедиция'",
				'address' 	=> '',
				'telephone' => ''
			),
			6 => array(
				'name' 		=> "CDEK",
				'address' 	=> '',
				'telephone' => ''
			),
			7 => array(
				'name' 		=> "EMS Почта России",
				'address' 	=> '',
				'telephone' => ''
			),
		);

		$fields[] = array(
			'type'			=> 'select',
			'name'			=> 'company',
			'label'			=> 'Выберете транспортную компанию',
			'placeholder'	=> 'Транспортная компания',
			'options'		=> $transport,
			'sort_order'	=> 0,
			'required'		=> true,
			'error'			=> 'Выберете транспортную компанию!'
		);

		$fields[] = array(
			'type'			=> 'hidden',
			'id'			=> 'input-shipping-country',
			'name'			=> 'country',
			'value'			=> '176',
			'label'			=> 'Выберите страну',
			'placeholder'	=> 'Выберите страну',
			'sort_order'	=> 0,
			'required'		=> true,
			'error'			=> 'Выберите страну!',
			'disabled'		=> false
		);

		$fields[] = array(
			'type'			=> 'text',
			'name'			=> 'city',
			'value'			=> '',
			'label'			=> 'Выберите город',
			'placeholder'	=> 'Выберите город',
			'sort_order'	=> 0,
			'required'		=> true,
			'error'			=> 'Выберите город!',
			'disabled'		=> false
		);
		
		$fields[] = array(
			'type'			=> 'text',
			'name'			=> 'address_1',
			'label'			=> 'Адрес доставки',
			'placeholder'	=> 'Адрес доставки',
			'sort_order'	=> 1,
			'required'		=> true,
			'error'			=> 'Адрес должен быть от 3 до 128 символов!'
		);

		return $fields;
	}
}