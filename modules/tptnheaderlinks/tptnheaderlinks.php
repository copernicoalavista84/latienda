<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once _PS_MODULE_DIR_.'tptnheaderlinks/HeaderLinksClass.php';

class TptnHeaderLinks extends Module
{
	public $html = '';

	public function __construct()
	{
		$this->name = 'tptnheaderlinks';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Header Links');
		$this->description = $this->l('Adds custom links in header.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.99.99');
	}

	public function install()
	{
		return 	parent::install() &&
				$this->installDB() &&
				$this->registerHook('displayTop') &&
				$this->installFixtures();
	}

	public function installDB()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnheaderlinks` (
				`id_info` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id_info`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
		);

		$return &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnheaderlinks_lang` (
				`id_info` INT UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
				`text_info` text NOT NULL,
				`url_info` text NOT NULL,
				PRIMARY KEY (`id_info`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
		);

		return $return;
	}

	public function uninstall()
	{
		return parent::uninstall() && $this->uninstallDB();
	}

	public function uninstallDB($drop_table = true)
	{
		$ret = true;
		if($drop_table)
			$ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tptnheaderlinks`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tptnheaderlinks_lang`');

		return $ret;
	}

	public function getContent()
	{
		$id_info = (int)Tools::getValue('id_info');

		if (Tools::isSubmit('saveModule'))
		{
			if (!Tools::getValue('text_info_'.(int)Configuration::get('PS_LANG_DEFAULT'), false))
				return $this->html . $this->displayError($this->l('You must fill in Texts field.')) . $this->renderForm();
			elseif ($this->processSaveModule())
				return $this->html . $this->renderList();
			else
				return $this->html . $this->renderForm();
		}
		elseif (Tools::isSubmit('updateModule') || Tools::isSubmit('addModule'))
		{
			$this->html .= $this->renderForm();
			return $this->html;
		}
		else if (Tools::isSubmit('deleteModule'))
		{
			$info = new HeaderLinks((int)$id_info);
			$info->delete();
			$this->_clearCache('tptnheaderlinks.tpl');
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$this->html .= $this->renderList();
			return $this->html;
		}
	}

	public function processSaveModule()
	{
		if ($id_info = Tools::getValue('id_info'))
			$info = new HeaderLinks((int)$id_info);
		else
		{
			$info = new HeaderLinks();
			if (Shop::isFeatureActive())
			{
				$shop_ids = Tools::getValue('checkBoxShopAsso_configuration');
				if (!$shop_ids)
				{
					$this->html .= '<div class="alert alert-danger conf error">'.$this->l('You have to select at least one shop.').'</div>';
					return false;
				}
			}
			else
				$info->id_shop = Shop::getContextShopID();
		}

		$languages = Language::getLanguages(false);
		
		foreach ($languages AS $lang) {
			$info->text_info[$lang['id_lang']] = Tools::getValue('text_info_'.$lang['id_lang']);
			$info->url_info[$lang['id_lang']] = Tools::getValue('url_info_'.$lang['id_lang']);
		}
		
		if (Shop::isFeatureActive() && !$info->id_shop)
		{
			$saved = true;
			foreach ($shop_ids as $id_shop)
			{
				$info->id_shop = $id_shop;
				$saved &= $info->add();
			}
		}
		else
			$saved = $info->save();

		if ($saved)
			$this->_clearCache('tptnheaderlinks.tpl');
		else
			$this->html .= '<div class="alert alert-danger conf error">'.$this->l('An error occurred while attempting to save.').'</div>';

		return $saved;
	}


	protected function renderForm()
	{
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$fields_form = array(
			'legend' => array(
				'title' => $this->l('Custom Texts'),
			),
			'input' => array(
				array(
					'type' => 'hidden',
					'name' => 'id_info'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Texts'),
					'lang' => true,
					'name' => 'text_info'
				),
				array(
					'type' => 'text',
					'label' => $this->l('URL'),
					'lang' => true,
					'name' => 'url_info'
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
			),
			'buttons' => array(
				array(
					'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
					'title' => $this->l('Back to list'),
					'icon' => 'process-icon-back'
				)
			)
		);

		if (Shop::isFeatureActive() && Tools::getValue('id_info') == false)
		{
			$fields_form['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Shop association'),
				'name' => 'checkBoxShopAsso_theme'
			);
		}


		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'tptnheaderlinks';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang)
			$helper->languages[] = array(
				'id_lang' => $lang['id_lang'],
				'iso_code' => $lang['iso_code'],
				'name' => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
			);

		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'saveModule';

		$helper->fields_value = $this->getFormValues();

		return $helper->generateForm(array(array('form' => $fields_form)));
	}

	protected function renderList()
	{
		$this->fields_list = array();
		$this->fields_list['id_info'] = array(
				'title' => $this->l('ID'),
				'type' => 'text',
				'search' => false,
				'orderby' => false,
			);
		$this->fields_list['text_info'] = array(
				'title' => $this->l('Texts'),
				'type' => 'text',
				'search' => false,
				'orderby' => false,
			);
		$this->fields_list['url_info'] = array(
				'title' => $this->l('URL'),
				'type' => 'text',
				'search' => false,
				'orderby' => false,
			);
		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP)
			$this->fields_list['shop_name'] = array(
					'title' => $this->l('Shop'),
					'type' => 'text',
					'search' => false,
					'orderby' => false,
				);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_info';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] = array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addModule&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new')
		);

		$helper->title = $this->displayName;
		$helper->table = 'Module';
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		$content = $this->getListContent($this->context->language->id);

		return $helper->generateList($content, $this->fields_list);
	}

	protected function getListContent($id_lang = null)
	{
		if (is_null($id_lang))
			$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$sql = 'SELECT r.`id_info`, rl.`text_info`, rl.`url_info`, s.`name` as shop_name
			FROM `'._DB_PREFIX_.'tptnheaderlinks` r
			LEFT JOIN `'._DB_PREFIX_.'tptnheaderlinks_lang` rl ON (r.`id_info` = rl.`id_info`)
			LEFT JOIN `'._DB_PREFIX_.'shop` s ON (r.`id_shop` = s.`id_shop`)
			WHERE `id_lang` = '.(int)$id_lang.' AND (';

		if ($shop_ids = Shop::getContextListShopID())
			foreach ($shop_ids as $id_shop)
				$sql .= ' r.`id_shop` = '.(int)$id_shop.' OR ';

		$sql .= ' r.`id_shop` = 0 )';

		$content = Db::getInstance()->executeS($sql);

		foreach ($content as $key => $value)
			$content[$key]['text'] = substr(strip_tags($value['text']), 0, 200);

		return $content;
	}

	public function getFormValues()
	{
		$fields_value = array();
		$id_info = (int)Tools::getValue('id_info');

		foreach (Language::getLanguages(false) as $lang)
			if ($id_info)
			{
				$info = new HeaderLinks((int)$id_info);
				$fields_value['text_info'][(int)$lang['id_lang']] = $info->text_info[(int)$lang['id_lang']];
				$fields_value['url_info'][(int)$lang['id_lang']] = $info->url_info[(int)$lang['id_lang']];
			}
			else {
				$fields_value['text_info'][(int)$lang['id_lang']] = Tools::getValue('text_info_'.(int)$lang['id_lang'], '');
				$fields_value['url_info'][(int)$lang['id_lang']] = Tools::getValue('url_info_'.(int)$lang['id_lang'], '');
			}
		$fields_value['id_info'] = $id_info;

		return $fields_value;
	}

	public function hookdisplayTop($params)
	{
		if (!$this->isCached('tptnheaderlinks.tpl', $this->getCacheId()))
		{
			$infos = $this->getInfos($this->context->language->id, $this->context->shop->id);
			$this->context->smarty->assign('infos', $infos);
		}

		return $this->display(__FILE__, 'tptnheaderlinks.tpl', $this->getCacheId());
	}

	public function getInfos($id_lang, $id_shop)
	{
		$sql = 'SELECT r.`id_info`, r.`id_shop`, rl.`text_info`, rl.`url_info`
			FROM `'._DB_PREFIX_.'tptnheaderlinks` r
			LEFT JOIN `'._DB_PREFIX_.'tptnheaderlinks_lang` rl ON (r.`id_info` = rl.`id_info`)
			WHERE `id_lang` = '.(int)$id_lang.' AND  `id_shop` = '.(int)$id_shop;

		return Db::getInstance()->executeS($sql);
	}

	public function installFixtures()
	{
		$return = true;
		$tab_texts = array(
			array(
				'text_info' => 'Home',
				'url_info' => 'http://templatin.com'
			),
		);

		$shops_ids = Shop::getShops(true, null, true);
		$return = true;
		foreach ($tab_texts as $tab)
		{
			$info = new HeaderLinks();
			foreach (Language::getLanguages(false) as $lang) {
				$info->text_info[$lang['id_lang']] = $tab['text_info'];
				$info->url_info[$lang['id_lang']] = $tab['url_info'];
			}
			foreach ($shops_ids as $id_shop)
			{
				$info->id_shop = $id_shop;
				$return &= $info->add();
			}
		}

		return $return;
	}
}
