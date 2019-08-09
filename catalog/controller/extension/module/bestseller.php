<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/bestseller');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['name'] = explode('-', $setting['name'])[0];

		$data['products'] = array();

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				$images = array();

				if($result['images']) {
					foreach ($result['images'] as $image) {
						$images[] = array(
							'popup' => $this->model_tool_image->resize($image['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
							'thumb' => $this->model_tool_image->resize($image['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height')),
							'lazy'	=> $this->model_tool_image->resize($image['image'], 256, 256)
						);
					}
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price_origin'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$props = array();

				foreach(explode(',', $result['location']) as $prop) {
					$values = explode(':', $prop);
					if(isset($values[1]) && isset($values[0])) {
						$props[$values[0]] = $values[1]; 
					}
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'isbn'		  => $result['isbn'],
					'image'       => $image,
					'images'      => $images,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
					'props'		  => $props,
					'date'		  => array(
						'added'	  	=> $result['date_added'],
						'modified'	=> $result['date_modified'],
						'available'	=> $result['date_available'],
					)
				);
			}

			return $this->load->view('extension/module/bestseller', $data);
		}
	}
}
