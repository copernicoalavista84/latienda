<?php

if (!defined('_CAN_LOAD_FILES_')) {
	exit;
}

class TptnFooterBlock1 extends Module
{
	protected $error = false;

	public function __construct()
	{
		$this->name = 'tptnfooterblock1';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Footer block 1');
		$this->description = $this->l('Adds a custom block in footer.');
		$this->confirmUninstall = $this->l('Are you sure you want to delete all your links?');
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayFooter')) {
			return false;
		}

		$success = Configuration::updateValue('TPTNFOOTERBLOCK1_TITLE', array('1' => 'Custom Block'));
		$success &= Db::getInstance()->execute('
		CREATE TABLE '._DB_PREFIX_.'tptnfooterblock1 (
		`id_blocklink` int(10) NOT NULL AUTO_INCREMENT,
		`new_window` TINYINT(1) NOT NULL,
		PRIMARY KEY(`id_blocklink`))
		ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8');
		$success &= Db::getInstance()->execute('
		CREATE TABLE '._DB_PREFIX_.'tptnfooterblock1_shop (
		`id_blocklink` int(10) NOT NULL AUTO_INCREMENT,
		`id_shop` int(10) NOT NULL,
		PRIMARY KEY(`id_blocklink`, `id_shop`))
		ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8');
		$success &= Db::getInstance()->execute('
		CREATE TABLE '._DB_PREFIX_.'tptnfooterblock1_lang (
		`id_blocklink` int(10) NOT NULL,
		`id_lang` int(10) NOT NULL,
		`text` varchar(62) NOT NULL,
		`url` varchar(254) NOT NULL,
		PRIMARY KEY(`id_blocklink`, `id_lang`))
		ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8');
		
		if (!$success) {
			parent::uninstall();
			return false;
		}
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall() ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'tptnfooterblock1') ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'tptnfooterblock1_lang') ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'tptnfooterblock1_shop') ||
			!Configuration::deleteByName('TPTNFOOTERBLOCK1_TITLE')
		) {
			return false;
		}
		return true;
	}

	public function getLinkById($id)
	{
		if ((int)$id > 0) {
			$sql = 'SELECT b.`id_blocklink`, b.`new_window` FROM `'._DB_PREFIX_.'tptnfooterblock1` b WHERE b.id_blocklink='.(int)$id;

			if (!$results = Db::getInstance()->getRow($sql)) {
				return false;
			}

			$link['id_blocklink'] = (int)$results['id_blocklink'];
			$link['newWindow'] = $results['new_window'];

			$results = Db::getInstance()->executeS('SELECT `id_lang`, `text`, `url` FROM '._DB_PREFIX_.'tptnfooterblock1_lang WHERE `id_blocklink`='.(int)$link['id_blocklink']);

			$results_lang = array();
			foreach ($results as $result) {
				$results_lang[(int)$result['id_lang']] = $result;
			}

			foreach (Language::getLanguages(false) as $lang) {
				$link['text'][(int)$lang['id_lang']] = (isset($results_lang[(int)$lang['id_lang']])) ? $results_lang[(int)$lang['id_lang']]['text'] : false;
				$link['url'][(int)$lang['id_lang']] = (isset($results_lang[(int)$lang['id_lang']])) ? $results_lang[(int)$lang['id_lang']]['url'] : false;
			}
			return $link;
		}

		return false;
	}

	public function getLinks()
	{
		$result = array();

		$sql = 'SELECT b.`id_blocklink`, b.`new_window` FROM `'._DB_PREFIX_.'tptnfooterblock1` b';
		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_ALL) {
			$sql .= ' JOIN `'._DB_PREFIX_.'tptnfooterblock1_shop` bs ON b.`id_blocklink` = bs.`id_blocklink` AND bs.`id_shop` IN ('.implode(', ', Shop::getContextListShopID()).') ';
		}

		if (!$links = Db::getInstance()->executeS($sql)) {
			return false;
		}

		$i = 0;
		foreach ($links as $link) {
			$result[$i]['id'] = $link['id_blocklink'];
			$result[$i]['newWindow'] = $link['new_window'];

			if (!$contents = Db::getInstance()->executeS('SELECT `id_lang`, `text`, `url` FROM '._DB_PREFIX_.'tptnfooterblock1_lang WHERE `id_blocklink`='.(int)$link['id_blocklink'])) {
				return false;
			}

			foreach ($contents as $content) {
				$result[$i]['text_'.$content['id_lang']] = $content['text'];
				$result[$i]['url_'.$content['id_lang']] = $content['url'];
			}
			$i++;
		}

		return $result;
	}

	public function addLink()
	{
		if (!($languages = Language::getLanguages(true))) {
			return false;
		}
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');

		if ((int)Tools::getValue('id_blocklink') > 0) {
			$id_link = (int)Tools::getValue('id_blocklink');
			if (!Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'tptnfooterblock1 SET `new_window` = '.pSQL((int)Tools::getValue('newWindow')).' WHERE `id_blocklink` = '.(int)$id_link)) {
				return false;
			}
			if (!Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tptnfooterblock1_lang WHERE `id_blocklink` = '.(int)$id_link)) {
				return false;
			}

			foreach ($languages as $language) {
				if (!empty($_POST['text_'.$language['id_lang']]) || !empty($_POST['url_'.$language['id_lang']])) {
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tptnfooterblock1_lang (`id_blocklink`, `id_lang`, `text`, `url`) VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL($_POST['text_'.$language['id_lang']]).'\', \''.pSQL($_POST['url_'.$language['id_lang']]).'\')')) {
						return false;
					}
				} else {
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tptnfooterblock1_lang VALUES ('.(int)$id_link.', '.$language['id_lang'].', \''.pSQL($_POST['text_'.$id_lang_default]).'\', \''.pSQL($_POST['url_'.$id_lang_default]).'\')')) {
						return false;
					}
				}
			}
		} else {
			if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tptnfooterblock1 (`id_blocklink`, `new_window`) VALUES (NULL, '.((isset($_POST['newWindow']) && Tools::getValue('newWindow')) == 'on' ? 1 : 0).')') || !$id_link = Db::getInstance()->Insert_ID()) {
				return false;
			}

			foreach ($languages as $language) {
				if (!empty($_POST['text_'.$language['id_lang']]) || !empty($_POST['url_'.$language['id_lang']])) {
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tptnfooterblock1_lang (`id_blocklink`, `id_lang`, `text`, `url`) VALUES ('.(int)$id_link.', '.(int)$language['id_lang'].', \''.pSQL(Tools::getValue('text_'.$language['id_lang'])).'\', \''.pSQL(Tools::getValue('url_'.$language['id_lang'])).'\')')) {
						return false;
					}
				} else {
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'tptnfooterblock1_lang (`id_blocklink`, `id_lang`, `text`, `url`) VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL($_POST['text_'.$id_lang_default]).'\', \''.pSQL($_POST['url_'.$id_lang_default]).'\')')) {
						return false;
					}
				}
			}
		}

		Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tptnfooterblock1_shop WHERE id_blocklink='.(int)$id_link);

		$shops = Shop::getShops(true, null, true);
		if (!Shop::isFeatureActive() || (Shop::isFeatureActive() && count($shops) == 1)) {
			Db::getInstance()->insert('tptnfooterblock1_shop', array(
				'id_blocklink' => (int)$id_link,
				'id_shop' => (int)Context::getContext()->shop->id,
			));
		} else {
			$assos_shop = Tools::getValue('checkBoxShopAsso_configuration');
			if (empty($assos_shop)) {
				return false;
			}
			foreach ($assos_shop as $id_shop => $row) {
				Db::getInstance()->insert('tptnfooterblock1_shop', array(
					'id_blocklink' => (int)$id_link,
					'id_shop' => (int)$id_shop,
				));
			}
		}

		return true;
	}

	public function deleteLink()
	{
		return (Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tptnfooterblock1 WHERE `id_blocklink` = '.(int)Tools::getValue('id')) &&
			Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tptnfooterblock1_shop WHERE `id_blocklink` = '.(int)Tools::getValue('id')) &&
			Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'tptnfooterblock1_lang WHERE `id_blocklink` = '.(int)Tools::getValue('id')));
	}

	public function updateTitle()
	{
		$languages = Language::getLanguages();
		$result = array();
		foreach ($languages as $language) {
			$result[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
		}
		return Configuration::updateValue('TPTNFOOTERBLOCK1_TITLE', $result);
	}

	public function getContent()
	{
		$this->_html = '';

		if (Tools::isSubmit('submitLinkAdd')) {
			if (empty($_POST['text_'.Configuration::get('PS_LANG_DEFAULT')]) || empty($_POST['url_'.Configuration::get('PS_LANG_DEFAULT')])) {
				$this->_html .= $this->displayError($this->l('You must fill in all fields.'));
			} elseif (!Validate::isUrl(str_replace('http://', '', $_POST['url_'.Configuration::get('PS_LANG_DEFAULT')]))) {
				$this->_html .= $this->displayError($this->l('Bad URL'));
			} else {
				if ($this->addLink()) {
					$this->_html .= $this->displayConfirmation($this->l('The link has been added.'));
				} else {
					$this->_html .= $this->displayError($this->l('An error occurred during link creation.'));
				}
			}
		} elseif (Tools::isSubmit('submitTitle')) {
			if (empty($_POST['title_'.Configuration::get('PS_LANG_DEFAULT')])) {
				$this->_html .= $this->displayError($this->l('"title" field cannot be empty.'));
			} elseif (!Validate::isGenericName($_POST['title_'.Configuration::get('PS_LANG_DEFAULT')])) {
				$this->_html .= $this->displayError($this->l('The \'title\' field is invalid'));
			} elseif (!$this->updateTitle()) {
				$this->_html .= $this->displayError($this->l('An error occurred during title updating.'));
			} else {
				$this->_html .= $this->displayConfirmation($this->l('The block title has been updated.'));
			}
		} elseif (Tools::isSubmit('deletetptnfooterblock1') && Tools::getValue('id')) {
			if (!is_numeric(Tools::getValue('id')) || !$this->deleteLink()) {
				$this->_html .= $this->displayError($this->l('An error occurred during link deletion.'));
			} else {
				$this->_html .= $this->displayConfirmation($this->l('The link has been deleted.'));
			}
		}

		$this->_html .= $this->renderForm();
		$this->_html .= $this->renderList();

		return $this->_html;
	}

	public function renderList()
	{
		$fields_list = array(
			'id' => array(
				'title' => $this->l('Link ID'),
				'type' => 'text',
			),
			'text_'.$this->context->language->id => array(
				'title' => $this->l('Text'),
				'type' => 'text',
			),
			'url_'.$this->context->language->id => array(
				'title' => $this->l('URL'),
				'type' => 'text',
			)
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->identifier = 'id';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = false;

		$helper->title = $this->l('Link list');
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$links = $this->getLinks();
		if (is_array($links) && count($links)) {
			return $helper->generateList($links, $fields_list);
		} else {
			return false;
		}
	}

	public function renderForm()
	{
		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Block Title'),
					'icon' => 'icon-edit'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Block title'),
						'name' => 'title',
						'lang' => true,
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'submitTitle',
				)
			),
		);

		$fields_form_2 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Add a new link'),
					'icon' => 'icon-plus-sign-alt'
				),
				'input' => array(
					array(
						'type' => 'hidden',
						'name' => 'id_blocklink',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Text'),
						'name' => 'text',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('URL'),
						'name' => 'url',
						'lang' => true,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Open in a new window'),
						'name' => 'newWindow',
						'is_bool' => true,
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

				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'submitLinkAdd',
				)
			),
		);

		if (Shop::isFeatureActive()) {
			$fields_form_2['form']['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Shop association'),
				'name' => 'checkBoxShopAsso',
			);
		}

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = 'id_blocklink';
		$helper->submit_action = 'submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form_1, $fields_form_2));
	}

	public function getConfigFieldsValues()
	{
		$fields_values = array(
			'id_blocklink' => Tools::getValue('id_blocklink'),
			'newWindow' => Tools::getValue('newWindow')
		);


		$languages = Language::getLanguages(false);

		foreach ($languages as $lang) {
			$fields_values['title'][$lang['id_lang']] = Tools::getValue('title', Configuration::get('TPTNFOOTERBLOCK1_TITLE', $lang['id_lang']));
			$fields_values['text'][$lang['id_lang']] = Tools::getValue('text_'.(int)$lang['id_lang']);
			$fields_values['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang']);
		}

		if (Tools::getIsset('updatetptnfooterblock1') && (int)Tools::getValue('id') > 0) {
			$fields_values = array_merge($fields_values, $this->getLinkById((int)Tools::getValue('id')));
		}

		return $fields_values;
	}

	public function hookdisplayFooter($params)
	{
		$links = $this->getLinks();

		$this->smarty->assign(array(
			'tptnfooterblock1' => $links,
			'title' => Configuration::get('TPTNFOOTERBLOCK1_TITLE', $this->context->language->id),
			'text' => 'text_'.$this->context->language->id,
			'url' => 'url_'.$this->context->language->id
		));
		if (!$links)
			return false;

		return $this->display(__FILE__, 'tptnfooterblock1.tpl');
	}
}