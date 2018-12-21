<?php
class ControllerRestPayment extends Controller {
	public function methods() {
		$this->load->language('api/payment');

		$json = array();

		// Payment Methods
		$json['payment_methods'] = array();

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
							$json['payment_methods'][$result['code']] = $method;
						}
					} else {
						$json['payment_methods'][$result['code']] = $method;
					}
				}
			}
		}

		$sort_order = array();

		foreach ($json['payment_methods'] as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $json['payment_methods']);

		if ($json['payment_methods']) {
			$this->session->data['payment_methods'] = $json['payment_methods'];
		} else {
			$json['error'] = $this->language->get('error_no_payment');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
