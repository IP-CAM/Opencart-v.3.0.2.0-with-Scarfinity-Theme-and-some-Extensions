<?php
class ControllerCheckoutLogin extends Controller {
	public function index() {
		if($this->customer->isLogged()) {
			$data['customer'] = array(
				'firstname' => $this->customer->getFirstname(),
				'lastname' => $this->customer->getLastname(),
				'email' => $this->customer->getEmail(),
				'phone' => $this->customer->getTelephone(),
			);
		}

		$this->response->setOutput($this->load->view('checkout/customer', $data));
	}
}
