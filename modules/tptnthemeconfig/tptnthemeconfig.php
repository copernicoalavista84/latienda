<?php

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class TptnThemeConfig extends Module
{
	public function __construct()
	{
		$this->name = 'tptnthemeconfig';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('MegaShop Configurator');
		$this->description = $this->l('Change the theme colors.');
	}
	
	public function install()
	{
		return (parent::install()
		&& Configuration::updateValue('Tconfig', 1)
		&& Configuration::updateValue('topbkg', '5D6A87')
		&& Configuration::updateValue('titlebkg', '2AC97A')
		&& Configuration::updateValue('cartbkg', 'F5535E')
		&& Configuration::updateValue('btnbkg', 'FF9F00')
		&& Configuration::updateValue('pnameclr', '105BD5')
		&& Configuration::updateValue('newbkg', '2AC97A')
		&& Configuration::updateValue('salebkg', 'F5535E')
		&& Configuration::updateValue('priceclr', 'F13340')
		&& $this->registerHook('displayHeader')
		&& $this->registerHook('displayTptnHead')
		&& $this->registerHook('displayTptnBody'));
	}
	
	public function uninstall()
	{
		//Delete configuration			
		return (Configuration::deleteByName('Tconfig')
				&& Configuration::deleteByName('topbkg')
				&& Configuration::deleteByName('titlebkg')
				&& Configuration::deleteByName('cartbkg')
				&& Configuration::deleteByName('btnbkg')
				&& Configuration::deleteByName('pnameclr')
				&& Configuration::deleteByName('newbkg')
				&& Configuration::deleteByName('salebkg')
				&& Configuration::deleteByName('priceclr')
				&& parent::uninstall() );
	}
	
	public function getContent()
	{
		$html = '';
		// If we try to update the settings
		if (Tools::isSubmit('submitModule'))
		{	
			Configuration::updateValue('Tconfig', Tools::getValue('t_config'));
			Configuration::updateValue('topbkg', Tools::getValue('top_bkg'));
			Configuration::updateValue('titlebkg', Tools::getValue('title_bkg'));
			Configuration::updateValue('cartbkg', Tools::getValue('cart_bkg'));
			Configuration::updateValue('btnbkg', Tools::getValue('btn_bkg'));
			Configuration::updateValue('pnameclr', Tools::getValue('pname_clr'));
			Configuration::updateValue('newbkg', Tools::getValue('new_bkg'));
			Configuration::updateValue('salebkg', Tools::getValue('sale_bkg'));
			Configuration::updateValue('priceclr', Tools::getValue('price_clr'));

			$html .= $this->displayConfirmation($this->l('Configuration updated'));
		}

		$html .= $this->renderForm();

		return $html;
	}

	public function renderForm() {

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Show Theme configurator?'),
						'name' => 't_config',
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Enabled')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('Disabled')
									)
								),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Top Horizontal Menu'),
						'name' => 'top_bkg',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Categories Title Background'),
						'name' => 'title_bkg',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Cart Background'),
						'name' => 'cart_bkg',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Button Background'),
						'name' => 'btn_bkg',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Product Name Color'),
						'name' => 'pname_clr',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'text',
						'label' => $this->l('NEW label Background'),
						'name' => 'new_bkg',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'text',
						'label' => $this->l('SALE label Background'),
						'name' => 'sale_bkg',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Price Color'),
						'name' => 'price_clr',
						'maxlength' => "6",
						'class' => 'fixed-width-sm'
					)
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
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
			't_config' => Tools::getValue('t_config', Configuration::get('Tconfig')),
			'top_bkg' => Tools::getValue('top_bkg', Configuration::get('topbkg')),
			'title_bkg' => Tools::getValue('title_bkg', Configuration::get('titlebkg')),
			'cart_bkg' => Tools::getValue('cart_bkg', Configuration::get('cartbkg')),
			'btn_bkg' => Tools::getValue('btn_bkg', Configuration::get('btnbkg')),
			'pname_clr' => Tools::getValue('pname_clr', Configuration::get('pnameclr')),
			'new_bkg' => Tools::getValue('new_bkg', Configuration::get('newbkg')),
			'sale_bkg' => Tools::getValue('sale_bkg', Configuration::get('salebkg')),
			'price_clr' => Tools::getValue('price_clr', Configuration::get('priceclr'))
		);
	}
	
	public function hookdisplayTptnHead($params)
	{
		$this->smarty->assign(array(
			'tptn_topbkg' => isset($_COOKIE['cktopbkg']) ? $_COOKIE['cktopbkg'] : Configuration::get('topbkg'),
			'tptn_titlebkg' => isset($_COOKIE['cktitlebkg']) ? $_COOKIE['cktitlebkg'] : Configuration::get('titlebkg'),
			'tptn_cartbkg' => isset($_COOKIE['ckcartbkg']) ? $_COOKIE['ckcartbkg'] : Configuration::get('cartbkg'),
			'tptn_btnbkg' => isset($_COOKIE['ckbtnbkg']) ? $_COOKIE['ckbtnbkg'] : Configuration::get('btnbkg'),
			'tptn_pnameclr' => isset($_COOKIE['ckpnameclr']) ? $_COOKIE['ckpnameclr'] : Configuration::get('pnameclr'),
			'tptn_newbkg' => isset($_COOKIE['cknewbkg']) ? $_COOKIE['cknewbkg'] : Configuration::get('newbkg'),
			'tptn_salebkg' => isset($_COOKIE['cksalebkg']) ? $_COOKIE['cksalebkg'] : Configuration::get('salebkg'),
			'tptn_priceclr' => isset($_COOKIE['ckpriceclr']) ? $_COOKIE['ckpriceclr'] : Configuration::get('priceclr')
		));

		return $this->display(__FILE__, 'tptnhead.tpl');
	}
	
	public function hookdisplayTptnBody($params)
	{
		if(Configuration::get('Tconfig') == 0) {
			return;
		}
		
		$this->smarty->assign(array(
			'tptn_topbkg' => isset($_COOKIE['cktopbkg']) ? $_COOKIE['cktopbkg'] : Configuration::get('topbkg'),
			'tptn_titlebkg' => isset($_COOKIE['cktitlebkg']) ? $_COOKIE['cktitlebkg'] : Configuration::get('titlebkg'),
			'tptn_cartbkg' => isset($_COOKIE['ckcartbkg']) ? $_COOKIE['ckcartbkg'] : Configuration::get('cartbkg'),
			'tptn_btnbkg' => isset($_COOKIE['ckbtnbkg']) ? $_COOKIE['ckbtnbkg'] : Configuration::get('btnbkg'),
			'tptn_pnameclr' => isset($_COOKIE['ckpnameclr']) ? $_COOKIE['ckpnameclr'] : Configuration::get('pnameclr'),
			'tptn_newbkg' => isset($_COOKIE['cknewbkg']) ? $_COOKIE['cknewbkg'] : Configuration::get('newbkg'),
			'tptn_salebkg' => isset($_COOKIE['cksalebkg']) ? $_COOKIE['cksalebkg'] : Configuration::get('salebkg'),
			'tptn_priceclr' => isset($_COOKIE['ckpriceclr']) ? $_COOKIE['ckpriceclr'] : Configuration::get('priceclr')
		));

		return $this->display(__FILE__, 'tptnbody.tpl');
	}

	public function hookDisplayHeader($params)
	{
		$this->context->controller->addJqueryPlugin('cooki-plugin');
		$this->context->controller->addjQueryPlugin('hoverIntent');
		$this->context->controller->addCSS($this->_path.'views/css/configstyle.css');
		$this->context->controller->addJS($this->_path.'views/js/colorpicker.js');
		$this->context->controller->addJS($this->_path.'views/js/configjs.js');
	}
}
?>
