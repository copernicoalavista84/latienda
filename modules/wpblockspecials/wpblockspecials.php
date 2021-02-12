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

class WPBlockSpecials extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'wpblockspecials';
		$this->tab = 'pricing_promotion';
		$this->version = '1.1.1';
		$this->author = 'WEB-PLUS';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Novus specials block');
		$this->description = $this->l('Adds a block displaying your current discounted products.');
	}

	public function install()
	{
		$this->_clearCache('wpblockspecials.tpl');
		if (!Configuration::updateValue('WPSPECIALS_NBR', 12) || !parent::install() || !$this->registerHook('displayHome') || !$this->registerHook('displayHeader') || !$this->registerHook('addproduct') || !$this->registerHook('updateproduct') || !$this->registerHook('deleteproduct'))
			return false;
		return true;
	}

	public function uninstall()
	{
		$this->_clearCache('wpblockspecials.tpl');
		return parent::uninstall();
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitSpecials'))
		{
			Configuration::updateValue('WPSPECIALS_NBR', (int)Tools::getValue('WPSPECIALS_NBR'));
			$output .= $this->displayConfirmation($this->l('Settings updated'));
		}
		return $output.$this->renderForm();
	}

	public function hookDisplayHome($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		if (!$this->isCached('wpblockspecials.tpl', $this->getCacheId()))
		{
			$n = (int)(Configuration::get('WPSPECIALS_NBR'));
			if ($wpspecial = Product::getPricesDrop((int)($params['cookie']->id_lang), 0, $n))
			{
				$this->smarty->assign(array(
					'wpspecial' => $wpspecial,
					'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
				));
			}
		}
		return $this->display(__FILE__, 'wpblockspecials.tpl', $this->getCacheId());
	}

	public function hookHeader($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;
		$this->context->controller->addCSS(($this->_path).'views/css/wpblockspecials.css', 'all');
		$this->context->controller->addJS($this->_path.'views/js/wpblockspecials.js');
	}

	public function hookAddProduct($params)
	{
		$this->_clearCache('wpblockspecials.tpl');
	}

	public function hookUpdateProduct($params)
	{
		$this->_clearCache('wpblockspecials.tpl');
	}

	public function hookDeleteProduct($params)
	{
		$this->_clearCache('wpblockspecials.tpl');
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
						'label' => $this->l('Number of products displayed'),
						'name' => 'WPSPECIALS_NBR',
						'desc' => $this->l('The number of products displayed on homepage (default: 12)'),
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
		$helper->submit_action = 'submitSpecials';
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
			'WPSPECIALS_NBR' => Tools::getValue('WPSPECIALS_NBR', Configuration::get('WPSPECIALS_NBR')),
		);
	}
}
