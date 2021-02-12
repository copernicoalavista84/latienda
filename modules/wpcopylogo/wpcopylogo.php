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

class WPCopyLogo extends Module
{
	public function __construct()
	{
		$this->name = 'wpcopylogo';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'WEB-PLUS';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Novus copyright info and payment logo block');
		$this->description = $this->l('Create easily block with any content.');
		$path = dirname(__FILE__);
		if (strpos(__FILE__, 'Module.php') !== false)
			$path .= '/../modules/'.$this->name;
		include_once $path.'/WPCopyLogoClass.php';
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('wpFooterCopyright') || !$this->registerHook('displayHeader'))
			return false;

		$res = Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wpcopylogo` (
			`id_wpcopylogo` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL ,		
			PRIMARY KEY (`id_wpcopylogo`))
			ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');

		if ($res)
			$res &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wpcopylogo_lang` (
				`id_wpcopylogo` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
				`wpcopylogo_text` text NOT NULL,
				`wpcopylogo_image_link` varchar(255) NOT NULL,
				PRIMARY KEY (`id_wpcopylogo`, `id_lang`))
				ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');

		if ($res)
			foreach
			(Shop::getShops(false) as $shop)
				$res &= $this->createExampleWPCopyLogo($shop['id_shop']);

			if (!$res)
				$res &= $this->uninstall();

			return $res;
	}

	private function createExampleWPCopyLogo($id_shop)
	{
		$wpcopylogo = new WPCopyLogoClass();
		$wpcopylogo->id_shop = (int)$id_shop;		
		foreach (Language::getLanguages(false) as $lang)
			$wpcopylogo->wpcopylogo_text[$lang['id_lang']] = '<p>Novus - Premium Prestashop template</p>';
			$wpcopylogo->wpcopylogo_image_link[$lang['id_lang']] = 'http://www.prestashop.com';
		return $wpcopylogo->add();
	}

	public function uninstall()
	{
		$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'wpcopylogo`');
		$res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'wpcopylogo_lang`');

		if (!$res || !parent::uninstall())
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
		$helper->name_controller = 'wpcopylogo';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = true;
		$helper->toolbar_scroll = true;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->title = $this->displayName;
		$helper->submit_action = 'submitUpdateWPCopyLogo';

		$file = dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$this->context->shop->id.'.png';
		$copy_img = (file_exists($file) ? '<img src="'.$this->_path.'img/wpcopylogo_image_'.(int)$this->context->shop->id.'.png">' : '');

		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->displayName,
				'image' => $this->_path.'logo.gif'
			),
			'submit' => array(
				'name' => 'submitUpdateWPCopyLogo',
				'title' => $this->l('Save '),
				'class' => 'button'
			),
			'input' => array(
				array(
					'type' => 'textarea',
					'label' => $this->l('Copyright text'),
					'name' => 'wpcopylogo_text',
					'lang' => true,
					'autoload_rte' => true,
					'hint' => $this->l('Text'),
					'cols' => 60,
					'rows' => 30
				),
				array(
					'type' => 'file',
					'label' => $this->l('Payment icons image'),
					'name' => 'wpcopylogo_image',
					'desc' => $this->l('Upload an image in PNG format'),
					'display_image' => true,
					'image' => $copy_img,
					'delete_url' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteCopyImage=1'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Image link'),					
					'name' => 'wpcopylogo_image_link',
					'lang' => true,
					'size' => 33,
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
		$wpcopylogo = WPCopyLogoClass::getByIdShop($id_shop);

		if (!$wpcopylogo) //if wpcopylogo ddo not exist for this shop => create a new example one
			$this->createExampleWPCopyLogo($id_shop);

		foreach ($this->fields_form[0]['form']['input'] as $input) //fill all form fields
				{
		if ($input['name'] != 'wpcopylogo_image')
				$helper->fields_value[$input['name']] = $wpcopylogo->{$input['name']};
		}

		$file = dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png';
		$helper->fields_value['wpcopylogo_image']['image'] = (file_exists($file) ? '<img src="'.$this->_path.'img/wpcopylogo_image_'.(int)$id_shop.'.png">' : '');
			if ($helper->fields_value['wpcopylogo_image'] && file_exists($file))
			$helper->fields_value['wpcopylogo_image']['size'] = filesize($file) / 1000;



		$this->_html .= $helper->generateForm($this->fields_form);
		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';
		$id_shop = (int)$this->context->shop->id;
		// Delete image
		if (Tools::isSubmit('deleteCopyImage') || Tools::isSubmit('deleteImage'))
		{
			if (!file_exists(dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png'))
				$errors .= $this->displayError($this->l('This action cannot be made.'));
			else
			{
				unlink(dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png');
				Configuration::updateValue('WPCOPYLOGO_IMAGE_DISABLE', 1);
				$this->_clearCache('wpcopylogo.tpl');
				Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id));
			}
			$this->_html .= $errors;
		}

		if (Tools::isSubmit('submitUpdateWPCopyLogo'))
		{
			$id_shop = (int)$this->context->shop->id;
			$wpcopylogo = WPCopyLogoClass::getByIdShop($id_shop);
			$wpcopylogo->copyFromPost();
			if (empty($wpcopylogo->id_shop))
				$wpcopylogo->id_shop = (int)$id_shop;
				$wpcopylogo->save();


			/* upload the image */
			if (isset($_FILES['wpcopylogo_image']) && isset($_FILES['wpcopylogo_image']['tmp_name']) && !empty($_FILES['wpcopylogo_image']['tmp_name']))
			{
				Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
				if (file_exists(dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png'))
					unlink(dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png');
				if ($error = ImageManager::validateUpload($_FILES['wpcopylogo_image']))
					$errors .= $error;
				elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['wpcopylogo_image']['tmp_name'], $tmp_name))
					return false;
				elseif (!ImageManager::resize($tmp_name, dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png'))
					$errors .= $this->displayError($this->l('An error occurred while attempting to upload the image.'));
				if (isset($tmp_name))
					unlink($tmp_name);
			}
			$this->_html .= $errors == '' ? $this->displayConfirmation($this->l('Settings updated successfully.')) : $errors;
			if (file_exists(dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png'))
			{
				list($width, $height, $type, $attr) = getimagesize(dirname(__FILE__).'/img/wpcopylogo_image_'.(int)$id_shop.'.png');
				Configuration::updateValue('WPCOPYLOGO_IMAGE_WIDTH', (int)round($width));
				Configuration::updateValue('WPCOPYLOGO_IMAGE_HEIGHT', (int)round($height));
				Configuration::updateValue('WPCOPYLOGO_IMAGE_DISABLE', 0);
			}
			$this->_clearCache('wpcopylogo.tpl');
			Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		return true;

	}

	public function hookwpFooterCopyright($params)
	{
		if (!$this->isCached('wpcopylogo.tpl', $this->getCacheId()))
		{
			$id_shop = (int)$this->context->shop->id;
			$wpcopylogo = WPCopyLogoClass::getByIdShop($id_shop);
			if (!$wpcopylogo)
				return;
			$wpcopylogo = new WPCopyLogoClass((int)$wpcopylogo->id, $this->context->language->id);
			if (!$wpcopylogo)
				return;
			$this->smarty->assign(array(
					'wpcopylogo' => $wpcopylogo,
					'default_lang' => (int)$this->context->language->id,
					'image_width' => Configuration::get('WPCOPYLOGO_IMAGE_WIDTH'),
					'image_height' => Configuration::get('WPCOPYLOGO_IMAGE_HEIGHT'),
					'id_lang' => $this->context->language->id,
					'wpcopylogo_image' => !Configuration::get('WPCOPYLOGO_IMAGE_DISABLE') && file_exists('modules/wpcopylogo/img/wpcopylogo_image_'.(int)$id_shop.'.png'),
					'image_path' => $this->_path.'img/wpcopylogo_image_'.(int)$id_shop.'.png'
				));
		}
		return $this->display(__FILE__, 'wpcopylogo.tpl', $this->getCacheId());
	}

	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS(($this->_path).'css/wpcopylogo.css', 'all');
	}
}


