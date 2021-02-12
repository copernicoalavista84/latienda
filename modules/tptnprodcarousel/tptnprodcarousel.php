<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnProdCarousel extends Module
{
	private $_html = '';
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $spacer_size = '5';

	public function __construct()
	{
		$this->name = 'tptnprodcarousel';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Products Carousel');
		$this->description = $this->l('Displays carousel for Featured and specific category products on homepage.');
	}

	function install()
	{
		$this->clearCache();
		if ( !parent::install()
			|| !Configuration::updateGlobalValue('TPTNCRSL_SELECTED', '3,4')
			|| !Configuration::updateValue('TPTNCRSL_TOTAL', 50)
			|| !Configuration::updateValue('TPTNCRSL_SORTBY', 1)
			|| !Configuration::updateValue('TPTNCRSL_SORTWAY', 0)
			|| !$this->registerHook('displayHome')
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct') )
				return false;
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall()
			|| !Configuration::deleteByName('TPTNCRSL_SELECTED')
			|| !Configuration::deleteByName('TPTNCRSL_TOTAL')
			|| !Configuration::deleteByName('TPTNCRSL_SORTBY')
			|| !Configuration::deleteByName('TPTNCRSL_SORTWAY') )
			return false;
		return true;
	}

	public function getContent()
	{
		if (Tools::isSubmit('submitModule')) {
			$this->clearCache();

			$items = Tools::getValue('items');
			if (!(is_array($items) && count($items) && Configuration::updateValue('TPTNCRSL_SELECTED', (string)implode(',', $items))))
				$errors[] =$this->l('Unable to update settings.');
			
			if (isset($errors) AND sizeof($errors))
				$this->_html .= $this->displayError(implode('<br />', $errors));
			else
				Configuration::updateValue('TPTNCRSL_TOTAL', Tools::getValue('tptncrsl_total'));
				Configuration::updateValue('TPTNCRSL_SORTBY', Tools::getValue('tptncrsl_sortby'));
				Configuration::updateValue('TPTNCRSL_SORTWAY', Tools::getValue('tptncrsl_sortway'));
				$this->clearCache();
				$this->_html .= $this->displayConfirmation($this->l('Settings updated'));
		}

		$this->_html .= $this->renderForm();

		return $this->_html;
	}

	public function clearCache()
	{
		$this->_clearCache('tptnprodcarousel.tpl');
	}

	public function renderChoicesSelect()
	{
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$items = $this->getMenuItems();
		
		$html = '<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';

		$shop = new Shop((int)Shop::getContextShopID());
		$html .= '<optgroup label="'.$this->l('Categories').'">';	
		$html .= $this->generateCategoriesOption(
			Category::getNestedCategories(null, (int)$this->context->language->id, true), $items);
		$html .= '</optgroup>';		
	
		$html .= '</select>';
		return $html;
	}

	private function getMenuItems()
	{	
		$conf = Configuration::get('TPTNCRSL_SELECTED');
		if (strlen($conf))
			return explode(',', Configuration::get('TPTNCRSL_SELECTED'));
		else
			return array();		
	}
	
	private function makeMenuOption()
	{
		$menu_item = $this->getMenuItems();

		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		$html = '<select multiple="multiple" name="items[]" id="items" style="width: 300px; height: 160px;">';
		foreach ($menu_item as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $values);
			$id = (int)substr($item, strlen($values[1]), strlen($item));
	
			$category = new Category((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($category))
				$html .= '<option selected="selected" value="'.$id.'">'.$category->name.'</option>'.PHP_EOL;		
		}
		return $html.'</select>';
	}

	private function generateCategoriesOption($categories, $items_to_skip = null)
	{
		$html = '';

		foreach ($categories as $key => $category)
		{
			if (isset($items_to_skip))
			{
				$shop = (object) Shop::getShop((int)$category['id_shop']);
				$html .= '<option value="'.(int)$category['id_category'].'">'
					.str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name'].' ('.$shop->name.')</option>';
			}

			if (isset($category['children']) && !empty($category['children']))
				$html .= $this->generateCategoriesOption($category['children'], $items_to_skip);
		}

		return $html;
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'link_choice',
						'label' => '',
						'name' => 'link',
						'lang' => true,
						'desc' => 'For displaying Featured Products, add "Home" in the Selected items box.'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Maximum products displayed'),
						'name' => 'tptncrsl_total',
						'class' => 'fixed-width-md'
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Products sort'),
						'name' => 'tptncrsl_sortby',
						'values' => array(
							array(
								'id' => 'position',
								'value' => 1,
								'label' => $this->l('By position')
							),
							array(
								'id' => 'name',
								'value' => 0,
								'label' => $this->l('By name')
							)
						)
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Products sort order'),
						'name' => 'tptncrsl_sortway',
						'values' => array(
							array(
								'id' => 'desc',
								'value' => 1,
								'label' => $this->l('Descending')
							),
							array(
								'id' => 'asc',
								'value' => 0,
								'label' => $this->l('Ascending')
							)
						)
					),
				),
				'submit' => array(
					'name' => 'submitModule',
					'title' => $this->l('Save')
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;		
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'fields_value' => $this->getConfigFieldsValues(),
			'choices' => $this->renderChoicesSelect(),
			'selected_links' => $this->makeMenuOption(),
		);
		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'tptncrsl_total' => Tools::getValue('tptncrsl_total', Configuration::get('TPTNCRSL_TOTAL')),
			'tptncrsl_sortby' => Tools::getValue('tptncrsl_sortby', Configuration::get('TPTNCRSL_SORTBY')),
			'tptncrsl_sortway' => Tools::getValue('tptncrsl_sortway', Configuration::get('TPTNCRSL_SORTWAY'))
		);
	}

	public function hookDisplayHome($params)
	{
		$cid = (Configuration::get('TPTNCRSL_SELECTED'));
		$menu_item = explode(',', $cid);
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		$tptnprod_total = Configuration::get('TPTNCRSL_TOTAL');
		$tptnprod_sortby = (Configuration::get('TPTNCRSL_SORTBY') ? 'position' : 'name');
		$tptnprod_sortway = (Configuration::get('TPTNCRSL_SORTWAY') ? 'DESC' : 'ASC');
		
		$categories = array();

		foreach ($menu_item as $item) {
			if (!$item)
				continue;
			$id = $item;

			$category = new Category((int)$id, $id_lang);
			if (Validate::isLoadedObject($category)) {
				$categories[$item]['id'] = $item;
				$categories[$item]['name'] = $category->name;
				$categories[$item]['products'] = $category->getProducts($id_lang, 1, $tptnprod_total, $tptnprod_sortby, $tptnprod_sortway);
			}
		}

		$this->smarty->assign(array(
			'categories' => $categories,
			'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
		));

		return $this->display(__FILE__, 'tptnprodcarousel.tpl', $this->getCacheId());
	}

	public function hookAddProduct($params)
	{
		$this->clearCache();
	}

	public function hookUpdateProduct($params)
	{
		$this->clearCache();
	}

	public function hookDeleteProduct($params)
	{
		$this->clearCache();
	}

}