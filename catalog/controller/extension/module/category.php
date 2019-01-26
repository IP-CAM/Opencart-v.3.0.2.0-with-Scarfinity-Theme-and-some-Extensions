<?php
class ControllerExtensionModuleCategory extends Controller {
	public function index() {
		$this->load->language('extension/module/category');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if(isset($this->request->get['route'])) {
			$routeParts = explode('/', (string)$this->request->get['route']);
			if(isset($routeParts[1])) {
				$data['page_name'] = $routeParts[1];
			} else {
				$data['page_name'] = '';
			}
		} else {
			$data['page_name'] = '';
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		$productsCount = $this->config->get('config_product_count') && ($data['category_id'] != 0);

		foreach ($categories as $category) {
			$children_data = array();

			$children = $this->model_catalog_category->getCategories($category['category_id']);

			if ($category['category_id'] == $data['category_id'] || $data['page_name'] == 'home' || $data['page_name'] == '') {
				foreach($children as $child) {
					$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name' => $child['name'] . ($productsCount ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}
			}

			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

			$data['categories'][] = array(
				'category_id' 		=> $category['category_id'],
				'name'        		=> $category['name'] . ($productsCount ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'children'    		=> $children_data,
				'children_count'	=> count($children),
				'href'        		=> $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}

		return $this->load->view('extension/module/category', $data);
	}
}