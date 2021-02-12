<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnHtmlBox1 extends Module
{
	public function __construct()
	{
		$this->name = 'tptnhtmlbox1';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Header Custom Texts');
		$this->description = $this->l('Adds custom texts in header.');
		$path = dirname(__FILE__);
		if (strpos(__FILE__, 'Module.php') !== false)
			$path .= '/../modules/'.$this->name;
		include_once $path.'/HtmlClass1.php';
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayNav') )
			return false;

		$res = Db::getInstance()->execute(
			'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnhtmlbox1` (
			`id_htmlbox1` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL ,
			PRIMARY KEY (`id_htmlbox1`))
			ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
		);

		if ($res)
			$res &= Db::getInstance()->execute(
				'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnhtmlbox1_lang` (
				`id_htmlbox1` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
				`paragraph` text NOT NULL,
				PRIMARY KEY (`id_htmlbox1`, `id_lang`))
				ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
			);

		if ($res)
			foreach (Shop::getShops(false) as $shop)
				$res &= $this->createSample($shop['id_shop']);

		if (!$res)
			$res &= $this->uninstall();

		return (bool)$res;
	}

	private function createSample($id_shop)
	{
		$htmlbox1 = new HtmlClass1();
		$htmlbox1->id_shop = (int)$id_shop;
		
		foreach (Language::getLanguages(false) as $lang)
		{
			$htmlbox1->paragraph[$lang['id_lang']] = '
				<ul>
				<li>Lorem ipsum dolor sit amet conse</li>
				<li>Suntin culpa qui officia deserunt</li>
				<li>Seddo eiusmod tempor incididunt</li>
				</ul>
			';		
		}

		return $htmlbox1->add();
	}

	public function uninstall()
	{
		$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tptnhtmlbox1`');
		$res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tptnhtmlbox1_lang`');

		if ($res == 0 || !parent::uninstall())
			return false;

		return true;
	}

	private function initForm()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language)
			$languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'tptnhtmlbox1';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = true;
		$helper->toolbar_scroll = true;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->title = $this->displayName;
		$helper->submit_action = 'submitModule';

		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('Settings'),
				'icon' => 'icon-cogs'
			),
			'submit' => array(
				'name' => 'submitModule',
				'title' => $this->l('Save')
			),
			'input' => array(
				array(
					'type' => 'textarea',
					'label' => $this->l('Custom texts'),
					'name' => 'paragraph',
					'lang' => true,
					'autoload_rte' => true,
					'cols' => 60,
					'rows' => 30
				),
			)
		);

		return $helper;
	}

	private function initToolbar()
	{
		$this->toolbar_btn['save'] = array(
			'href' => '#',
			'desc' => $this->l('Save')
		);

		return $this->toolbar_btn;
	}

	public function getContent()
	{
		$this->_html = '';
		$this->postProcess();

		$helper = $this->initForm();

		$id_shop = (int)$this->context->shop->id;
		$htmlbox1 = HtmlClass1::getByIdShop($id_shop);

		if (!$htmlbox1)
			$this->createSample($id_shop);

		foreach ($this->fields_form[0]['form']['input'] as $input)
		{
			$helper->fields_value[$input['name']] = $htmlbox1->{$input['name']};
		}

		$this->_html .= $helper->generateForm($this->fields_form);

		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';
		$id_shop = (int)$this->context->shop->id;
		
		if (Tools::isSubmit('submitModule'))
		{
			$id_shop = (int)$this->context->shop->id;
			$htmlbox1 = HtmlClass1::getByIdShop($id_shop);
			$htmlbox1->copyFromPost();
			if (empty($htmlbox1->id_shop))
				$htmlbox1->id_shop = (int)$id_shop;
			$htmlbox1->save();

			$this->_html .= $errors == '' ? $this->displayConfirmation($this->l('Settings updated successfully.')) : $errors;
			
			$this->_clearCache('tptnhtmlbox1.tpl');
			Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id));
		}

		return true;
	}

	public function hookDisplayNav($params)
	{
		if (!$this->isCached('tptnhtmlbox1.tpl', $this->getCacheId()))
		{
			$id_shop = (int)$this->context->shop->id;
			$htmlbox1 = HtmlClass1::getByIdShop($id_shop);
			if (!$htmlbox1)
				return;
			$htmlbox1 = new HtmlClass1((int)$htmlbox1->id, $this->context->language->id);
			if (!$htmlbox1)
				return;
			$this->smarty->assign(
				array(
					'htmlbox1' => $htmlbox1
				)
			);
		}

		return $this->display(__FILE__, 'tptnhtmlbox1.tpl', $this->getCacheId());
	}
}