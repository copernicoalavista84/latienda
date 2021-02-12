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

class WPBlockFacebook extends Module
{
	public function __construct()
	{
		$this->name = 'wpblockfacebook';
		$this->tab = 'front_office_features';
		$this->version = '1.3.0';
		$this->author = 'WEB-PLUS';

		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Novus Facebook fanbox in sidebar');
		$this->description = $this->l('Displays a block for subscribing to your Facebook page.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}


	public function install()
	{
		return parent::install() &&
			Configuration::updateValue('wpblockfacebook_url', 'https://www.facebook.com/prestashop') &&
			$this->registerHook('leftColumn') &&
			$this->registerHook('displayHeader');
	}

	public function uninstall()
	{
		// Delete configuration
		return Configuration::deleteByName('wpblockfacebook_url') && parent::uninstall();
	}

	public function getContent()
	{
		$html = '';
		// If we try to update the settings
		if (Tools::isSubmit('submitModule'))
		{
			Configuration::updateValue('wpblockfacebook_url', Tools::getValue('wpblockfacebook_url'));
			$html .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->_clearCache('wpblockfacebook.tpl');
			Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}

		$html .= $this->renderForm();
		$wpfacebookurl = Configuration::get('wpblockfacebook_url');
		if (!strstr($wpfacebookurl, 'facebook.com')) $wpfacebookurl = 'https://www.facebook.com/'.$wpfacebookurl;
		$this->context->smarty->assign('wpfacebookurl', $wpfacebookurl);
		$this->context->smarty->assign('wpfacebook_js_url', $this->_path.'js/wpblockfacebook.js');
		$this->context->smarty->assign('wpfacebook_css_url', $this->_path.'css/wpblockfacebook.css');
		$html .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/_configure/preview.tpl');
		return $html;
	}

	public function hookLeftColumn()
	{
		if (!$this->isCached('wpblockfacebook.tpl', $this->getCacheId()))
		{
			$wpfacebookurl = Configuration::get('wpblockfacebook_url');
			if (!strstr($wpfacebookurl, 'facebook.com'))
				$wpfacebookurl = 'https://www.facebook.com/'.$wpfacebookurl;
			$this->context->smarty->assign('wpfacebookurl', $wpfacebookurl);
		}
		return $this->display(__FILE__, 'wpblockfacebook.tpl', $this->getCacheId());
	}

	public function hookHeader()
	{
		$this->page_name = Dispatcher::getInstance()->getController();
		$this->context->controller->addCss(($this->_path).'css/wpblockfacebook.css');
		$this->context->controller->addJS(($this->_path).'js/wpblockfacebook.js');
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
						'label' => $this->l('Facebook link (full URL is required)'),
						'name' => 'wpblockfacebook_url',
					),
				),
				'submit' => array(
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

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
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
			'wpblockfacebook_url' => Tools::getValue('wpblockfacebook_url', Configuration::get('wpblockfacebook_url')),
		);
	}
}
