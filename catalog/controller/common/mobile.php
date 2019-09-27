<?php
class ControllerCommonMobile extends Controller {
	public function index() {

		$this->load->language('product/special');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['isMobile'] = constant('isMobile');
		$data['isTablet'] = constant('isTablet');
		
		$this->document->addStyle('catalog/view/theme/scarfinity/stylesheet/mobile.css');

		$limit = 20;
		$page = 1;

		$filter_data = array(
			'filter_category_id' 	=> 0,
			'filter_sub_category' 	=> true,
			'start'              	=> ($page - 1) * $limit,
			'limit'              	=> $limit
		);


		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
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

			$colors = array();

			if($result['colors']) {
				foreach ($result['colors'] as $color) {
					$colors[] = array(
						'image' => array(
							'thumb' => $this->model_tool_image->resize($color['image'], 32, 32),
							'popup' => $this->model_tool_image->resize($color['image'], 1024, 1024),
						)
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
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}

			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'isbn'		  => $result['isbn'],
				'image'       => $image,
				'images'   	  => $images,
				'colors'	  => $colors,
				'name'        => $result['name'],
				'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'rating'      => $result['rating'],
				// 'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url),
				'date'		  => array(
					'added'	  	=> $result['date_added'],
					'modified'	=> $result['date_modified'],
					'available'	=> $result['date_available'],
				)
			);
		}
        
		$data['cards'] = [0, 1, 2, 3, 4, 5];
		
		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					// Level 3
					$level3_data = array();
					$level3 = $this->model_catalog_category->getCategories($child['category_id']);

					foreach ($level3 as $child3) {
						$filter3_data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);

						$level3_data[] = array(
							'name' => $child3['name'],
							'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child3['category_id'])
						);
					}


					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
						'children' => $level3_data
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		$data['home'] = $this->url->link('common/home');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['catalog'] = $this->url->link('product/catalog');
		$data['special'] = $this->url->link('product/special');
		$data['contact'] = $this->url->link('information/contact');
		$data['about_us'] = $this->url->link('information/information', 'information_id=4');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['about_us'] = $this->url->link('information/information', 'information_id=4');

		$this->response->setOutput($this->load->view('common/mobile', $data));
	}
}
