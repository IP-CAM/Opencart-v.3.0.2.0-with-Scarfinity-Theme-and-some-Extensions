<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		$this->document->addStyle('catalog/view/javascript/swiper/dist/css/swiper.min.css');

		// Скрипт для работы большого слайдера
		$this->document->addScript('catalog/view/javascript/swiper/dist/js/swiper.min.js', 'footer');
		$this->document->addScript('catalog/view/javascript/catalog-product-card.js', 'footer');
		$this->document->addScript('catalog/view/javascript/slideshow.js', 'footer');

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['heading_title'] = $this->config->get('config_meta_title');

		$data['isMobile'] = constant('isMobile');
		$data['isTablet'] = constant('isTablet');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
