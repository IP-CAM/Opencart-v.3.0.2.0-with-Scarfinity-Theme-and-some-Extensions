<?php

class ControllerExtensionModuleLastComment extends Controller {
	
	public function index($setting) {
		
		//Load language file
		$this->language->load('extension/module/last-coomment');

		//Set title from language file
		$data['heading_title'] = $this->language->get('heading_title');

		//Load Styles & Scripts
		// $this->document->addStyle('catalog/view/javascript/path/to/library.css');
		// $this->document->addScript('catalog/view/javascript/path/to/library.js');

		//Custom
		$data['field'] = $setting['field'];

		//Select template
		return $this->load->view('extension/module/last-coomment', $data);

	}
}
