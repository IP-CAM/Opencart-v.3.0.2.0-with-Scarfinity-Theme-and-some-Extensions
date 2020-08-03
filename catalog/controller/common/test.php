<?php
class ControllerCommonTest extends Controller {
	public function index() {
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');

		$data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        
		$data['heading_title'] = $this->config->get('config_meta_title');

		$this->response->setOutput($this->load->view('common/test', $data));
	}
}
