<?php

if (!defined('_PS_VERSION_'))
	exit;
	
class TptnSocial extends Module
{
	public function __construct()
	{
		$this->name = 'tptnsocial';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Social block');
		$this->description = $this->l('Adds information and social details about your shop.');
	}
	
	public function install()
	{
		return (parent::install()
			&& Configuration::updateValue('ADDRESS', 'A-12, Riverdale Tower, Opp. Northern Extension, Brightfill Road, YourCity - YC7890.')
			&& Configuration::updateValue('PHONE', '1800-123-4567')
			&& Configuration::updateValue('EMAIL', 'name@domain.com')
			&& Configuration::updateValue('FACEBOOK', 'https://www.facebook.com/templatin')
			&& Configuration::updateValue('TWITTER', 'https://www.twitter.com/templatin')
			&& Configuration::updateValue('GOOGLE', '#')
			&& Configuration::updateValue('INSTAGRAM', '#')
			&& Configuration::updateValue('YOUTUBE', '#')
			&& $this->registerHook('displayFooter') );
	}
	
	public function uninstall()
	{
		return (Configuration::deleteByName('ADDRESS')
			&& Configuration::deleteByName('PHONE')
			&& Configuration::deleteByName('EMAIL')
			&& Configuration::deleteByName('FACEBOOK')
			&& Configuration::deleteByName('TWITTER')
			&& Configuration::deleteByName('GOOGLE')
			&& Configuration::deleteByName('INSTAGRAM')
			&& Configuration::deleteByName('YOUTUBE')
			&& parent::uninstall() );
	}
	
	public function hookdisplayFooter($params)
	{
		if (!$this->isCached('tptnsocial.tpl', $this->getCacheId()))
			$this->smarty->assign(array(
				'tptnaddress' => Configuration::get('ADDRESS'),
				'tptnphone' => Configuration::get('PHONE'),
				'tptnemail' => Configuration::get('EMAIL'),
				'tptnfacebook' => Configuration::get('FACEBOOK'),
				'tptntwitter' => Configuration::get('TWITTER'),
				'tptngoogle' => Configuration::get('GOOGLE'),
				'tptninstagram' => Configuration::get('INSTAGRAM'),
				'tptnyoutube' => Configuration::get('YOUTUBE')
			));
		return $this->display(__FILE__, 'tptnsocial.tpl', $this->getCacheId());
	}

	public function getContent()
	{
		$html = '';

		if (Tools::isSubmit('submitModule'))
		{
			Configuration::updateValue('ADDRESS', Tools::getValue('address'));
			Configuration::updateValue('PHONE', Tools::getValue('phone'));
			Configuration::updateValue('EMAIL', Tools::getValue('email'));
			Configuration::updateValue('FACEBOOK', Tools::getValue('facebook'));
			Configuration::updateValue('TWITTER', Tools::getValue('twitter'));
			Configuration::updateValue('GOOGLE', Tools::getValue('google'));
			Configuration::updateValue('INSTAGRAM', Tools::getValue('instagram'));
			Configuration::updateValue('YOUTUBE', Tools::getValue('youtube'));
			
			$html .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->_clearCache('tptnsocial.tpl');						
		}
		
		$html .= $this->renderForm();

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
						'type' => 'text',
						'label' => $this->l('Address'),
						'name' => 'address'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Phone number'),
						'name' => 'phone'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Email'),
						'name' => 'email'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Facebook URL'),
						'name' => 'facebook'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Twitter URL'),
						'name' => 'twitter'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Google+ URL'),
						'name' => 'google'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Instagram URL'),
						'name' => 'instagram'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Youtube URL'),
						'name' => 'youtube'
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
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
			'address'	=> Tools::getValue('address', Configuration::get('ADDRESS')),
			'phone'	=> Tools::getValue('phone', Configuration::get('PHONE')),
			'email'	=> Tools::getValue('email', Configuration::get('EMAIL')),
			'facebook'	=> Tools::getValue('facebook', Configuration::get('FACEBOOK')),
			'twitter'	=> Tools::getValue('twitter', Configuration::get('TWITTER')),
			'google'	=> Tools::getValue('google', Configuration::get('GOOGLE')),
			'instagram'	=> Tools::getValue('instagram', Configuration::get('INSTAGRAM')),
			'youtube'	=> Tools::getValue('youtube', Configuration::get('YOUTUBE'))
		);
	}	
}
?>
