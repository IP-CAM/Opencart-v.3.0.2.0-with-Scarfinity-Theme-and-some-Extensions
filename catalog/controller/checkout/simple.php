<?php
class ControllerCheckoutSimple extends Controller {
	public function validate() {
		$this->load->language('checkout/checkout');

		$json = array();

		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		$json['count_products'] = $this->cart->countProducts();
		$json['sub_total'] = $this->cart->getSubTotal();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customerSave() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		if (!$json) {
			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$this->request->post['email'] = 'noemail@scarfinity.ru';
				// $json['error']['email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			// Customer Group
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
		}

		if (!$json) {
			$this->session->data['account'] = 'guest';

			$this->session->data['guest']['customer_group_id'] = $customer_group_id;
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];

			$this->session->data['payment_address']['firstname'] = $this->request->post['firstname'];
			$this->session->data['payment_address']['lastname'] = $this->request->post['lastname'];
			$this->session->data['payment_address']['company'] = ''; // $this->request->post['company'];
			$this->session->data['payment_address']['address_1'] = ''; // $this->request->post['address_1'];
			$this->session->data['payment_address']['address_2'] = ''; // $this->request->post['address_2'];
			$this->session->data['payment_address']['postcode'] = ''; // $this->request->post['postcode'];
			$this->session->data['payment_address']['city'] = ''; // $this->request->post['city'];
			$this->session->data['payment_address']['country_id'] = ''; // $this->request->post['country_id'];
			$this->session->data['payment_address']['zone_id'] = ''; // $this->request->post['zone_id'];

			$this->session->data['payment_address']['country'] = '';
			$this->session->data['payment_address']['iso_code_2'] = '';
			$this->session->data['payment_address']['iso_code_3'] = '';
			$this->session->data['payment_address']['address_format'] = '';

			$this->session->data['payment_address']['zone'] = '';
			$this->session->data['payment_address']['zone_code'] = '';

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function guestSave() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		if (!$json) {
			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				// $json['error']['email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			// Customer Group
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
		}

		if (!$json) {
			$this->session->data['account'] = 'guest';

			$this->session->data['guest']['customer_group_id'] = $customer_group_id;
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = '';
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];

			if (isset($this->request->post['custom_field']['account'])) {
				$this->session->data['guest']['custom_field'] = $this->request->post['custom_field']['account'];
			} else {
				$this->session->data['guest']['custom_field'] = array();
			}

			$this->session->data['payment_address']['firstname'] = $this->request->post['firstname'];
			$this->session->data['payment_address']['lastname'] = $this->request->post['lastname'];
			$this->session->data['payment_address']['company'] = ''; // $this->request->post['company'];
			$this->session->data['payment_address']['address_1'] = ''; // $this->request->post['address_1'];
			$this->session->data['payment_address']['address_2'] = ''; // $this->request->post['address_2'];
			$this->session->data['payment_address']['postcode'] = ''; // $this->request->post['postcode'];
			$this->session->data['payment_address']['city'] = ''; // $this->request->post['city'];
			$this->session->data['payment_address']['country_id'] = ''; // $this->request->post['country_id'];
			$this->session->data['payment_address']['zone_id'] = ''; // $this->request->post['zone_id'];

			$this->session->data['payment_address']['country'] = '';
			$this->session->data['payment_address']['iso_code_2'] = '';
			$this->session->data['payment_address']['iso_code_3'] = '';
			$this->session->data['payment_address']['address_format'] = '';

			$this->session->data['payment_address']['zone'] = '';
			$this->session->data['payment_address']['zone_code'] = '';

			if (!empty($this->request->post['shipping_address'])) {
				$this->session->data['guest']['shipping_address'] = $this->request->post['shipping_address'];
			} else {
				$this->session->data['guest']['shipping_address'] = false;
			}

			if ($this->session->data['guest']['shipping_address']) {
				$this->session->data['shipping_address']['firstname'] = $this->request->post['firstname'];
				$this->session->data['shipping_address']['lastname'] = $this->request->post['lastname'];
				$this->session->data['shipping_address']['company'] = ''; // $this->request->post['company'];
				$this->session->data['shipping_address']['address_1'] = ''; // $this->request->post['address_1'];
				$this->session->data['shipping_address']['address_2'] = ''; // $this->request->post['address_2'];
				$this->session->data['shipping_address']['postcode'] = ''; // $this->request->post['postcode'];
				$this->session->data['shipping_address']['city'] = ''; // $this->request->post['city'];
				$this->session->data['shipping_address']['country_id'] = ''; // $this->request->post['country_id'];
				$this->session->data['shipping_address']['zone_id'] = ''; // $this->request->post['zone_id'];

				$this->session->data['shipping_address']['country'] = '';
				$this->session->data['shipping_address']['iso_code_2'] = '';
				$this->session->data['shipping_address']['iso_code_3'] = '';
				$this->session->data['shipping_address']['address_format'] = '';

				$this->session->data['shipping_address']['zone'] = '';
				$this->session->data['shipping_address']['zone_code'] = '';
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shippingMethodsGet() {
		$this->load->language('checkout/checkout');

		// Формирование ардреса, для запроса
		$address = array();

		if(isset($this->request->post['zone_id'])) {
			$address['zone_id'] = $this->request->post['zone_id'];
		} else {
			$address['zone_id'] = '';
		}

		if(isset($this->request->post['country_id'])) {
			$address['country_id'] = $this->request->post['country_id'];
		} else {
			$address['country_id'] = '';
		}

		// Метод возвращающий возможные способы доставки
		$method_data = array();

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensions('shipping');

		foreach ($results as $result) {
			if ($this->config->get('shipping_' . $result['code'] . '_status')) {
				$this->load->model('extension/shipping/' . $result['code']);

				$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($address);

				if ($quote) {
					$method_data[$result['code']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		$this->session->data['shipping_methods'] = $method_data;

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}
		
		$this->response->setOutput($this->load->view('checkout/shipping_method', $data));
	}

	public function shippingMethodSave() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
				$json['asdfasdfdsf'] = isset($this->session->data['shipping_methods']);
			}
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shippingAddressGet() {
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['shipping_address']['firstname'])) {
			$data['firstname'] = $this->session->data['shipping_address']['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->session->data['shipping_address']['lastname'])) {
			$data['lastname'] = $this->session->data['shipping_address']['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->session->data['shipping_address']['company'])) {
			$data['company'] = $this->session->data['shipping_address']['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->session->data['shipping_address']['address_1'])) {
			$data['address_1'] = $this->session->data['shipping_address']['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->session->data['shipping_address']['address_2'])) {
			$data['address_2'] = $this->session->data['shipping_address']['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->session->data['shipping_address']['postcode'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->session->data['shipping_address']['city'])) {
			$data['city'] = $this->session->data['shipping_address']['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->response->setOutput($this->load->view('checkout/shipping_address', $data));
	}

	public function shippingAddressSave() {
		$this->load->language('checkout/checkout');
		
		$json = array();

		// Validate if customer is logged in.
		// if ($this->customer->isLogged()) {
		// 	$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		// 	$json['error'] = 'User logged in';
		// }

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
			$json['error'] = 'This cart not requare shipping';
		}

		// Проверить валидность корзины по условиям
		// TODO

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
			$json['error'] = 'Some error with products';
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!$json) {
			$this->load->model('account/address');
			
			// if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			// 	$json['error']['firstname'] = $this->language->get('error_firstname');
			// }

			// if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			// 	$json['error']['lastname'] = $this->language->get('error_lastname');
			// }

			if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			if(isset($this->request->post['country_id'])) {
				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}

				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}

				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
					$json['error']['zone'] = $this->language->get('error_zone');
				}
			}

			if (!$json) {
				$address_id = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);

				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($address_id);

				// If no default address ID set we use the last address
				if (!$this->customer->getAddressId()) {
					$this->load->model('account/customer');
					
					$this->model_account_customer->editAddressId($this->customer->getId(), $address_id);
				}
			}
			
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function paymentMethodsGet() {
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['payment_address'])) {
			// Totals
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
					
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			// Payment Methods
			$method_data = array();

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('payment');

			$recurring = $this->cart->hasRecurringProducts();

			foreach ($results as $result) {
				if ($this->config->get('payment_' . $result['code'] . '_status')) {
					$this->load->model('extension/payment/' . $result['code']);

					$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method) {
						if ($recurring) {
							if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['payment_methods'] = $method_data;
		}

		if (empty($this->session->data['payment_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$data['payment_methods'] = array();
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}

		$data['scripts'] = $this->document->getScripts();

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->session->data['agree'])) {
			$data['agree'] = $this->session->data['agree'];
		} else {
			$data['agree'] = '';
		}

		$this->response->setOutput($this->load->view('checkout/payment_method', $data));
	}

	public function paymentMethodSave() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			// $json['error']['warning'] = 'aaaaa';//  $this->language->get('error_payment');
		}

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		if (!$json) {
			// $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
			$this->session->data['payment_method'] = $this->request->post['payment_method'];
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			//$json['sdfds'] = $this->session->data['payment_method'];
			//$json['asdfasd'] = !isset($this->session->data['payment_method']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function checkoutConfirm() {
		$redirect = '';

		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			if (!isset($this->session->data['shipping_address'])) {
				$redirect = $this->url->link('checkout/checkout', '', true);
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkout/checkout', '', true);
				$redirect = 'asdasdas';
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$redirect = $this->url->link('checkout/checkout', '', true);
		}

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!$redirect) {
			$order_data = array();

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$order_data['totals'] = $totals;

			$this->load->language('checkout/checkout');

			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				if ($this->request->server['HTTPS']) {
					$order_data['store_url'] = HTTPS_SERVER;
				} else {
					$order_data['store_url'] = HTTP_SERVER;
				}
			}
			
			$this->load->model('account/customer');

			if ($this->customer->isLogged()) {
				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

				$order_data['customer_id'] = $this->customer->getId();
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				$order_data['email'] = ''; //$customer_info['email'];
				$order_data['telephone'] = $customer_info['telephone'];
				$order_data['custom_field'] = null; // json_decode($customer_info['custom_field'], true);
			} elseif (isset($this->session->data['guest'])) {
				$order_data['customer_id'] = 0;
				$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['guest']['firstname'];
				$order_data['lastname'] = $this->session->data['guest']['lastname'];
				$order_data['email'] = ''; // $this->session->data['guest']['email'];
				$order_data['telephone'] = $this->session->data['guest']['telephone'];
				$order_data['custom_field'] = null; //$this->session->data['guest']['custom_field'];
			}

			$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
			$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
			$order_data['payment_company'] = '';//$this->session->data['payment_address']['company'];
			$order_data['payment_address_1'] = '';//$this->session->data['payment_address']['address_1'];
			$order_data['payment_address_2'] = '';//$this->session->data['payment_address']['address_2'];
			$order_data['payment_city'] = '';//$this->session->data['payment_address']['city'];
			$order_data['payment_postcode'] = '';//$this->session->data['payment_address']['postcode'];
			$order_data['payment_zone'] = '';//$this->session->data['payment_address']['zone'];
			$order_data['payment_zone_id'] = '';//$this->session->data['payment_address']['zone_id'];
			$order_data['payment_country'] = '';//$this->session->data['payment_address']['country'];
			$order_data['payment_country_id'] = '';//$this->session->data['payment_address']['country_id'];
			$order_data['payment_address_format'] = '';//$this->session->data['payment_address']['address_format'];
			$order_data['payment_custom_field'] = null; //(isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}

			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
				$order_data['shipping_company'] = ''; //$this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = ''; //$this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = ''; //$this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = ''; //$this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = ''; //$this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = ''; //$this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_id'] = ''; //$this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = ''; //$this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = ''; //$this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = ''; //$this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = null; //(isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

				if (isset($this->session->data['shipping_method']['title'])) {
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$order_data['shipping_method'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$order_data['shipping_code'] = '';
				}
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			$order_data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$order_data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				);
			}

			// Gift Voucher
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}

			$order_data['comment'] = $this->session->data['comment'];
			$order_data['total'] = $total_data['total'];

			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}

			$this->load->model('checkout/order');

			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

			$this->load->model('tool/upload');

			$data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$recurring = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year'),
					);

					if ($product['recurring']['trial']) {
						$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
					}

					if ($product['recurring']['duration']) {
						$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					} else {
						$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					}
				}

				$data['products'][] = array(
					'cart_id'    => $product['cart_id'],
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'recurring'  => $recurring,
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}

			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
					);
				}
			}

			$data['totals'] = array();

			foreach ($order_data['totals'] as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
			}

			// $data['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']);
		} else {
			$data['redirect'] = $redirect;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function checkoutPaymentConfirm() {
		$json = array();
		$json['xdsfg'] = $this->session->data['payment_method'];

		if ($this->session->data['payment_method'] == 'cod') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_free_checkout_order_status_id'));

			$json['continue'] = $this->url->link('checkout/success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function requisitesSave() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Check if guest checkout is available.
		if (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		if (!$json) {
			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$email = 'noemail@scarfinity.ru';
				// $json['error']['email'] = $this->language->get('error_email');
			} else {
				$email = $this->request->post['email'];
			}

			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		if (!$json) {
			$this->session->data['account'] = 'guest';

			$this->session->data['guest']['customer_group_id'] = $customer_group_id;
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = $email;
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];

			$this->session->data['guest']['custom_field'] = array();

			$this->session->data['payment_address']['firstname'] = $this->request->post['firstname'];
			$this->session->data['payment_address']['lastname'] = $this->request->post['lastname'];
			$this->session->data['payment_address']['company'] = '';
			$this->session->data['payment_address']['address_1'] = '';
			$this->session->data['payment_address']['address_2'] = '';
			$this->session->data['payment_address']['postcode'] = '';
			$this->session->data['payment_address']['city'] = '';
			$this->session->data['payment_address']['country_id'] = '';
			$this->session->data['payment_address']['zone_id'] = '';

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info) {
				$this->session->data['payment_address']['country'] = $country_info['name'];
				$this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['payment_address']['country'] = '';
				$this->session->data['payment_address']['iso_code_2'] = '';
				$this->session->data['payment_address']['iso_code_3'] = '';
				$this->session->data['payment_address']['address_format'] = '';
			}

			if (isset($this->request->post['custom_field']['address'])) {
				$this->session->data['payment_address']['custom_field'] = $this->request->post['custom_field']['address'];
			} else {
				$this->session->data['payment_address']['custom_field'] = array();
			}

			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone('');

			if ($zone_info) {
				$this->session->data['payment_address']['zone'] = $zone_info['name'];
				$this->session->data['payment_address']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['payment_address']['zone'] = '';
				$this->session->data['payment_address']['zone_code'] = '';
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shippingAndPaymentsSave() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			// $json['redirect'] = $this->url->link('checkout/checkout', '', true);
			// $json['error'] = 'User logged in';
		}

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		// Проверить валидность корзины по условиям
		// TODO
		// 
		// 

		$this->load->model('account/address');

		// Валидация полей
		if (!$json) {
			// Валидация всех полей

			// Арес доставки: 
			// 	- Старана
			// 	- Город
			// 	- Адрес

			if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if(isset($this->request->post['country_id'])) {
				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}

				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}

				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
					$json['error']['zone'] = $this->language->get('error_zone');
				}
			}

			// Валидация способа доставки
			if (!isset($this->request->post['shipping_method'])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$json['error']['warning'] = $this->language->get('error_shipping');
				}
			}

			// Валидация способа оплаты
			if (!isset($this->request->post['payment_method'])) {
				$json['error']['warning'] = $this->language->get('error_payment');
			} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error1']['warning'] = $this->language->get('error_payment');
			}

			if ($this->config->get('config_checkout_id')) {
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

				if ($information_info && $this->request->post['agree'] === 'false') {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}

		// Сохранение данных
		if (!$json) {

			// Сохранение адреса
			$address_id = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);

			$this->session->data['shipping_address'] = $this->model_account_address->getAddress($address_id);

			// If no default address ID set we use the last address
			if (!$this->customer->getAddressId()) {
				$this->load->model('account/customer');
				
				$this->model_account_customer->editAddressId($this->customer->getId(), $address_id);
			}

			// Сохранение способа доставки
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			$json['sm'] = $this->session->data['shipping_method'];

			// Сохранение способа оплаты
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
			$json['pm'] = $this->session->data['payment_method'];

			// Сохранине комментария к заказу
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function confirm() {
		$redirect = '';
		$json = array();

		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			if (!isset($this->session->data['shipping_address'])) {
				$redirect = $this->url->link('checkout/checkout', '', true);
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkout/checkout', '', true);
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$redirect = $this->url->link('checkout/checkout', '', true);
			$json['payment_address'] = '1';
		}

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkout/checkout', '', true);
			$json['payment_method'] = '1';
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!$redirect) {
			$order_data = array();

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$order_data['totals'] = $totals;

			$this->load->language('checkout/checkout');

			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				if ($this->request->server['HTTPS']) {
					$order_data['store_url'] = HTTPS_SERVER;
				} else {
					$order_data['store_url'] = HTTP_SERVER;
				}
			}
			
			$this->load->model('account/customer');

			if ($this->customer->isLogged()) {
				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

				$order_data['customer_id'] = $this->customer->getId();
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				$order_data['email'] = $customer_info['email'];
				$order_data['telephone'] = $customer_info['telephone'];
				$order_data['custom_field'] = ''; // json_decode($customer_info['custom_field'], true);
			} elseif (isset($this->session->data['guest'])) {
				$order_data['customer_id'] = 0;
				$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['guest']['firstname'];
				$order_data['lastname'] = $this->session->data['guest']['lastname'];
				$order_data['email'] = $this->session->data['guest']['email'];
				$order_data['telephone'] = $this->session->data['guest']['telephone'];
				$order_data['custom_field'] = ''; // $this->session->data['guest']['custom_field'];
			}

			$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
			$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
			$order_data['payment_company'] = $this->session->data['payment_address']['company'];
			$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
			$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
			$order_data['payment_city'] = $this->session->data['payment_address']['city'];
			$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
			$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
			$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
			$order_data['payment_country'] = $this->session->data['payment_address']['country'];
			$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
			$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
			$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}

			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
				$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

				if (isset($this->session->data['shipping_method']['title'])) {
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$order_data['shipping_method'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$order_data['shipping_code'] = '';
				}
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			$order_data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$order_data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				);
			}

			// Gift Voucher
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}

			$order_data['comment'] = $this->session->data['comment'];
			$order_data['total'] = $total_data['total'];

			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}

			$this->load->model('checkout/order');

			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

			// $data['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']);
		} else {
			$json['redirect'] = $redirect;
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
