<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class WPBlockNewProducts extends Module
{
	public function __construct()
	{
		$this->name = 'wpblocknewproducts';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'WEB-PLUS';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Novus new products block');
		$this->description = $this->l('Displays a block featuring your store\'s newest products.');
	}

	public function install()
	{
		if (parent::install() == false
			|| $this->registerHook('displayHome') == false
			|| $this->registerHook('displayHeader') == false
			|| $this->registerHook('addproduct') == false
			|| $this->registerHook('updateproduct') == false
			|| $this->registerHook('deleteproduct') == false
			|| Configuration::updateValue('WP_NEW_PRODUCTS_NBR', 12) == false)
					return false;
			return true;
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitWPBlockNewProducts'))
		{
			if (!($productNbr = Tools::getValue('WP_NEW_PRODUCTS_NBR')) || empty($productNbr))
				$output .= $this->displayError($this->l('Please complete the "products to display" field.'));

			elseif ((int)($productNbr) == 0)
				$output .= $this->displayError($this->l('Invalid number.'));
			else
			{
				Configuration::updateValue('PS_NB_DAYS_NEW_PRODUCT', (int)(Tools::getValue('PS_NB_DAYS_NEW_PRODUCT')));
				Configuration::updateValue('WP_NEW_PRODUCTS_NBR', (int)($productNbr));
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
		}
			return $output.$this->renderForm();
	}

	private function getNewProducts()
	{
		if (!Configuration::get('WP_NEW_PRODUCTS_NBR'))
			return;
		$newProducts = false;
		if (Configuration::get('PS_NB_DAYS_NEW_PRODUCT'))
			$newProducts = Product::getNewProducts((int)$this->context->language->id, 0, (int)Configuration::get('WP_NEW_PRODUCTS_NBR'));

		if (!$newProducts)
			return;
		return $newProducts;
	}



	public function hookDisplayHome($params)
	{
		$newProducts = Product::getNewProducts((int)($params['cookie']->id_lang), 0, (int)(Configuration::get('WP_NEW_PRODUCTS_NBR')));
		if (!$newProducts)
			return;

		$this->smarty->assign(array(
			'new_products' => $newProducts,
			'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
		));

		return $this->display(__FILE__, 'wpblocknewproducts.tpl');
	}



	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'views/css/wpblocknewproducts.css', 'all');
		$this->context->controller->addJS($this->_path.'views/js/wpblocknewproducts.js');
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
						'type' => 'text',
						'label' => $this->l('Products to display'),
						'name' => 'WP_NEW_PRODUCTS_NBR',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('Define the number of products to be displayed in this block.')
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Number of days for which the product is considered \'new\''),
						'name'  => 'PS_NB_DAYS_NEW_PRODUCT',
						'class' => 'fixed-width-xs',
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);


		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitWPBlockNewProducts';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'PS_NB_DAYS_NEW_PRODUCT' => Tools::getValue('PS_NB_DAYS_NEW_PRODUCT', Configuration::get('PS_NB_DAYS_NEW_PRODUCT')),
			'WP_NEW_PRODUCTS_NBR' => Tools::getValue('WP_NEW_PRODUCTS_NBR', Configuration::get('WP_NEW_PRODUCTS_NBR')),
		);
	}


}


