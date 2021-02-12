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

include_once(_PS_MODULE_DIR_.'wpsidebarslider/WPSidebarSlide.php');

class WPSidebarSlider extends Module
{
	private $_html = '';

	public function __construct()
	{
		$this->name = 'wpsidebarslider';
		$this->tab = 'front_office_features';
		$this->version = '1.2.7';
		$this->author = 'WEB-PLUS';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Novus image slider in sidebar');
		$this->description = $this->l('Adds an image slider to your sidebar.');
	}

	/**
	 * @see Module::install()
	 */
	public function install()
	{
		/* Adds Module */
		if (parent::install() && $this->registerHook('displayHeader') && $this->registerHook('leftColumn') && $this->registerHook('actionShopDataDuplication'))
		{
			/* Sets up configuration */
			$res = Configuration::updateValue('WPSIDEBARSLIDER_WIDTH', '1200');
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_SPEED', '500');
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_PAUSE', '6000');
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_LOOP', '1');
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_EFFECT', 'horizontal');


			/* Creates tables */
			$res &= $this->createTables();

			/* Adds samples */
			if ($res)
				$this->installSamples();

			// Disable on mobiles and tablets
			// $this->disableDevice(Context::DEVICE_MOBILE);

			return (bool)$res;
		}
		return false;
	}

	/**
	 * Adds samples
	 */
	private function installSamples()
	{
		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 2; ++$i)
		{
			$slide = new WPSidebarSlide();
			$slide->position = $i;
			$slide->active = 1;
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = 'Sample '.$i;

				$slide->url[$language['id_lang']] = 'http://www.prestashop.com';
				$slide->image[$language['id_lang']] = 'sample-'.$i.'.jpg';
			}
			$slide->add();
		}
	}

	/**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
		/* Deletes Module */
		if (parent::uninstall())
		{
			/* Deletes tables */
			$res = $this->deleteTables();
			/* Unsets configuration */
			$res &= Configuration::deleteByName('WPSIDEBARSLIDER_WIDTH');
			$res &= Configuration::deleteByName('WPSIDEBARSLIDER_SPEED');
			$res &= Configuration::deleteByName('WPSIDEBARSLIDER_PAUSE');
			$res &= Configuration::deleteByName('WPSIDEBARSLIDER_LOOP');
			$res &= Configuration::deleteByName('WPSIDEBARSLIDER_EFFECT');




			return (bool)$res;
		}
		return false;
	}

	/**
	 * Creates tables
	 */
	protected function createTables()
	{
		/* Slides */
		$res = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wpsidebarslider` (
				`id_wpsidebarslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id_wpsidebarslider_slides`, `id_shop`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wpsidebarslider_slides` (
			  `id_wpsidebarslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `position` int(10) unsigned NOT NULL DEFAULT \'0\',
			  `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
			  PRIMARY KEY (`id_wpsidebarslider_slides`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides lang configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'wpsidebarslider_slides_lang` (
			  `id_wpsidebarslider_slides` int(10) unsigned NOT NULL,
			  `id_lang` int(10) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  `url` varchar(255) NOT NULL,
			  `image` varchar(255) NOT NULL,
			  PRIMARY KEY (`id_wpsidebarslider_slides`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		return $res;
	}

	/**
	 * deletes tables
	 */
	protected function deleteTables()
	{
		$slides = $this->getSlides();
		foreach ($slides as $slide)
		{
			$to_del = new WPSidebarSlide($slide['id_slide']);
			$to_del->delete();
		}
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS `'._DB_PREFIX_.'wpsidebarslider`, `'._DB_PREFIX_.'wpsidebarslider_slides`, `'._DB_PREFIX_.'wpsidebarslider_slides_lang`;
		');
	}

	public function getContent()
	{
		$this->_html .= $this->headerHTML();

		/* Validate & process */
		if (Tools::isSubmit('submitSlide') || Tools::isSubmit('delete_id_slide') || Tools::isSubmit('submitSlider') || Tools::isSubmit('changeStatus'))
		{
			if ($this->_postValidation())
					{
						$this->_postProcess();
						$this->_html .= $this->renderForm();
						$this->_html .= $this->renderList();
					}
			else
				$this->_html .= $this->renderAddForm();

		$this->clearCache();
		}
		elseif (Tools::isSubmit('addSlide') || (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))))
			$this->_html .= $this->renderAddForm();
		else
		{
			$this->_html .= $this->renderForm();
			$this->_html .= $this->renderList();
		}
		return $this->_html;
	}


	private function _postValidation()
	{
		$errors = array();

		/* Validation for Slider configuration */
		if (Tools::isSubmit('submitSlider'))
		{

		if (!Validate::isInt(Tools::getValue('WPSIDEBARSLIDER_SPEED')) || !Validate::isInt(Tools::getValue('WPSIDEBARSLIDER_PAUSE')) || !Validate::isInt(Tools::getValue('WPSIDEBARSLIDER_WIDTH')))
			$errors[] = $this->l('Invalid values');

		} /* Validation for status */
		elseif (Tools::isSubmit('changeStatus'))
		{
			if (!Validate::isInt(Tools::getValue('id_slide')))
				$errors[] = $this->l('Invalid slide');
		}
		/* Validation for Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			/* Checks state (active) */
			if (!Validate::isInt(Tools::getValue('active_slide')) || (Tools::getValue('active_slide') != 0 && Tools::getValue('active_slide') != 1))
				$errors[] = $this->l('Invalid slide state');
			/* Checks position */
			if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0))
				$errors[] = $this->l('Invalid slide position');
			/* If edit : checks id_slide */
			if (Tools::isSubmit('id_slide'))
			{
				if (!Validate::isInt(Tools::getValue('id_slide')) && !$this->slideExists(Tools::getValue('id_slide')))
					$errors[] = $this->l('Invalid id_slide');
			}
			/* Checks title/url/description/image */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				if (Tools::strlen(Tools::getValue('title_'.$language['id_lang'])) > 40)
					$errors[] = $this->l('The title is too long.');
				if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 255)
					$errors[] = $this->l('The URL is too long.');
				if (Tools::strlen(Tools::getValue('description_'.$language['id_lang'])) > 4000)
					$errors[] = $this->l('The description is too long.');
				if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('url_'.$language['id_lang'])))
					$errors[] = $this->l('The URL format is not correct.');
				if (Tools::getValue('image_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename.');
				if (Tools::getValue('image_old_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_old_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename.');
			}

					/* Checks title/url/description for default lang */
			$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
			if (Tools::strlen(Tools::getValue('title_'.$id_lang_default)) == 0)
				$errors[] = $this->l('The title is not set.');
			if (Tools::strlen(Tools::getValue('url_'.$id_lang_default)) == 0)
				$errors[] = $this->l('The URL is not set.');
			if (!Tools::isSubmit('has_picture') && (!isset($_FILES['image_'.$id_lang_default]) || empty($_FILES['image_'.$id_lang_default]['tmp_name'])))
				$errors[] = $this->l('The image is not set.');
			if (Tools::getValue('image_old_'.$id_lang_default) && !Validate::isFileName(Tools::getValue('image_old_'.$id_lang_default)))
				$errors[] = $this->l('The image is not set.');
		} /* Validation for deletion */

		elseif (Tools::isSubmit('delete_id_slide') && (!Validate::isInt(Tools::getValue('delete_id_slide')) || !$this->slideExists((int)Tools::getValue('delete_id_slide'))))
			$errors[] = $this->l('Invalid id_slide');

		/* Display errors if needed */
		if (count($errors))
		{
			$this->_html .= $this->displayError(implode('<br />', $errors));
			return false;
		}

		/* Returns if validation is ok */
		return true;
	}

	private function _postProcess()
	{
		$errors = array();

		/* Processes Slider */
		if (Tools::isSubmit('submitSlider'))
		{
			$res = Configuration::updateValue('WPSIDEBARSLIDER_WIDTH', (int)Tools::getValue('WPSIDEBARSLIDER_WIDTH'));
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_SPEED', (int)Tools::getValue('WPSIDEBARSLIDER_SPEED'));
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_PAUSE', (int)Tools::getValue('WPSIDEBARSLIDER_PAUSE'));
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_LOOP', (int)Tools::getValue('WPSIDEBARSLIDER_LOOP'));
			$res &= Configuration::updateValue('WPSIDEBARSLIDER_EFFECT', Tools::getValue('WPSIDEBARSLIDER_EFFECT'));


		$this->clearCache();
			if (!$res)
			$errors[] = $this->displayError($this->l('The configuration could not be updated.'));
									else
		Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=6&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
		} /* Process Slide status */
		elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_slide'))
		{
			$slide = new WPSidebarSlide((int)Tools::getValue('id_slide'));
			if ($slide->active == 0)
				$slide->active = 1;
			else
				$slide->active = 0;
			$res = $slide->update();
			$this->clearCache();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
		}
		/* Processes Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			/* Sets ID if needed */
			if (Tools::getValue('id_slide'))
			{
				$slide = new WPSidebarSlide((int)Tools::getValue('id_slide'));
				if (!Validate::isLoadedObject($slide))
				{
					$this->_html .= $this->displayError($this->l('Invalid id_slide'));

			return false;
				}
			}
			else
				$slide = new WPSidebarSlide();
			/* Sets position */
			$slide->position = (int)Tools::getValue('position');
			/* Sets active */
			$slide->active = (int)Tools::getValue('active_slide');

			/* Sets each langue fields */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
				$slide->url[$language['id_lang']] = Tools::getValue('url_'.$language['id_lang']);
				$slide->legend[$language['id_lang']] = Tools::getValue('legend_'.$language['id_lang']);
				$slide->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);


				/* Uploads image and sets slide */
				$type = Tools::strtolower(Tools::substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));

				$imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
				if (isset($_FILES['image_'.$language['id_lang']]) &&
					isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
					!empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
					!empty($imagesize) &&
					in_array(
					Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) && in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
				{
					$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
					$salt = sha1(microtime());
					if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']]))
						$errors[] = $error;
					elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name))
						return false;
					elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/slides/'.Tools::encrypt($_FILES['image_'.$language['id_lang']]['name'].$salt).'.'.$type, null, null, $type))
						$errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
					if (isset($temp_name))
						@unlink($temp_name);
						$slide->image[$language['id_lang']] = Tools::encrypt($_FILES['image_'.($language['id_lang'])]['name'].$salt).'.'.$type;
				}
				elseif (Tools::getValue('image_old_'.$language['id_lang']) != '')
					$slide->image[$language['id_lang']] = Tools::getValue('image_old_'.$language['id_lang']);
			}

			/* Processes if no errors  */
			if (!$errors)
			{
				/* Adds */
				if (!Tools::getValue('id_slide'))
				{
					if (!$slide->add())
						$errors[] = $this->displayError($this->l('The slide could not be added.'));
								}
				/* Update */
				elseif (!$slide->update())
					$errors[] = $this->displayError($this->l('The slide could not be updated.'));
				$this->clearCache();
			}
		}
		/* Deletes */

		elseif (Tools::isSubmit('delete_id_slide'))
		{
			$slide = new WPSidebarSlide((int)Tools::getValue('delete_id_slide'));
			$res = $slide->delete();
			$this->clearCache();
			if (!$res)
				$this->_html .= $this->displayError('Could not delete.');
			else
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=1&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
		}

		/* Display errors if needed */
		if (count($errors))
			$this->_html .= $this->displayError(implode('<br />', $errors));
		elseif (Tools::isSubmit('submitSlide') && Tools::getValue('id_slide'))
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=4&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
		elseif (Tools::isSubmit('submitSlide'))
		Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=3&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
	}

	private function _prepareHook()
	{
		if (!$this->isCached('wpsidebarslider.tpl', $this->getCacheId()))
		{


		$slides = $this->getSlides(true);
			if (is_array($slides))
				foreach ($slides as &$slide)
				{
					$slide['sizes'] = @getimagesize((dirname(__FILE__).DIRECTORY_SEPARATOR.'slides'.DIRECTORY_SEPARATOR.$slide['image']));
					if (isset($slide['sizes'][0]) && $slide['sizes'][0])
						$slide['size'] = $slide['sizes'][3];
				}

	if (!$slides)
			return false;

		$this->smarty->assign(array('wpsidebarslider_slides' => $slides));

		}

		return true;
	}

	public function hookdisplayHeader($params)
	{
	$this->context->controller->addJqueryPlugin(array('bxslider'));
	$this->context->controller->addCSS($this->_path.'css/wpsidebarslider.css');
	$this->context->controller->addJS($this->_path.'js/wpsidebarslider.js');

		$slider = array(
			'width' => Configuration::get('WPSIDEBARSLIDER_WIDTH'),
			'speed' => Configuration::get('WPSIDEBARSLIDER_SPEED'),
			'pause' => Configuration::get('WPSIDEBARSLIDER_PAUSE'),
			'loop' => (bool)Configuration::get('WPSIDEBARSLIDER_LOOP'),
			'effect' => Configuration::get('WPSIDEBARSLIDER_EFFECT')
		);

		$this->smarty->assign('wpsidebarslider', $slider);
		return $this->display(__FILE__, 'header.tpl');
	}

	public function hookLeftColumn()
	{
		if (!$this->_prepareHook())
			return false;

		return $this->display(__FILE__, 'wpsidebarslider.tpl', $this->getCacheId());
	}

	public function clearCache()
	{
		$this->_clearCache('wpsidebarslider.tpl');
	}


	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
			INSERT IGNORE INTO '._DB_PREFIX_.'wpsidebarslider (id_wpsidebarslider_slides, id_shop)
			SELECT id_wpsidebarslider_slides, '.(int)$params['new_id_shop'].'
			FROM '._DB_PREFIX_.'wpsidebarslider
			WHERE id_shop = '.(int)$params['old_id_shop']);
		$this->clearCache();
	}

	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addJqueryUI('ui.sortable');
		/* Style & js for fieldset 'slides configuration' */
		$html = '<script type="text/javascript">
			$(function() {
				var $mySlides = $("#slides");
				$mySlides.sortable({
					opacity: 0.6,
					cursor: "move",
					update: function() {
						var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
						$.post("'.$this->context->shop->physical_uri.$this->context->shop->virtual_uri.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'", order);
						}
					});
				$mySlides.hover(function() {
					$(this).css("cursor","move");
					},
					function() {
					$(this).css("cursor","auto");
				});
			});
		</script>';

		return $html;
	}

	public function getNextPosition()
	{
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
				SELECT MAX(hss.`position`) AS `next_position`
				FROM `'._DB_PREFIX_.'wpsidebarslider_slides` hss, `'._DB_PREFIX_.'wpsidebarslider` hs
				WHERE hss.`id_wpsidebarslider_slides` = hs.`id_wpsidebarslider_slides` AND hs.`id_shop` = '.(int)$this->context->shop->id
		);

		return (++$row['next_position']);
	}

	public function getSlides($active = null)
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_wpsidebarslider_slides` as id_slide,
					   hssl.`image`,
					   hss.`position`,
					   hss.`active`,
					   hssl.`title`,
					   hssl.`url`,
					   hssl.`description`
			FROM '._DB_PREFIX_.'wpsidebarslider hs
			LEFT JOIN '._DB_PREFIX_.'wpsidebarslider_slides hss ON (hs.id_wpsidebarslider_slides = hss.id_wpsidebarslider_slides)
			LEFT JOIN '._DB_PREFIX_.'wpsidebarslider_slides_lang hssl ON (hss.id_wpsidebarslider_slides = hssl.id_wpsidebarslider_slides)
			WHERE id_shop = '.(int)$id_shop.'
			AND hssl.id_lang = '.(int)$id_lang.
			($active ? ' AND hss.`active` = 1' : ' ').'
			ORDER BY hss.position');
	}

	public function getAllImagesBySlidesId($id_slides, $active = null, $id_shop = null)
	{
		$this->context = Context::getContext();
		$images = array();

		if (!isset($id_shop))
			$id_shop = $this->context->shop->id;

		$results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hssl.`image`, hssl.`id_lang`
			FROM '._DB_PREFIX_.'wpsidebarslider hs
			LEFT JOIN '._DB_PREFIX_.'wpsidebarslider_slides hss ON (hs.id_wpsidebarslider_slides = hss.id_wpsidebarslider_slides)
			LEFT JOIN '._DB_PREFIX_.'wpsidebarslider_slides_lang hssl ON (hss.id_wpsidebarslider_slides = hssl.id_wpsidebarslider_slides)
			WHERE hs.`id_wpsidebarslider_slides` = '.(int)$id_slides.' AND hs.`id_shop` = '.(int)$id_shop.
			($active ? ' AND hss.`active` = 1' : ' ')
		);

		foreach ($results as $result)
			$images[$result['id_lang']] = $result['image'];

		return $images;
	}

	public function displayStatus($id_slide, $active)
	{
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
				$icon = ((int)$active == 0 ? 'icon-remove' : 'icon-check');
		$class = ((int)$active == 0 ? 'btn-danger' : 'btn-success');
		$html = '<a class="btn '.$class.'" href="'.AdminController::$currentIndex.
				'&configure='.$this->name.'
				&token='.Tools::getAdminTokenLite('AdminModules').'
				&changeStatus&id_slide='.(int)$id_slide.'" title="'.$title.'"><i class="'.$icon.'"></i> '.$title.'</a>';
		return $html;
	}

	public function slideExists($id_slide)
	{
		$req = 'SELECT hs.`id_wpsidebarslider_slides` as id_slide
				FROM `'._DB_PREFIX_.'wpsidebarslider` hs
				WHERE hs.`id_wpsidebarslider_slides` = '.(int)$id_slide;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}

	public function renderList()
	{
		$slides = $this->getSlides();
		foreach ($slides as $key => $slide)
			$slides[$key]['status'] = $this->displayStatus($slide['id_slide'], $slide['active']);

		$this->context->smarty->assign(
			array(
				'link' => $this->context->link,
				'slides' => $slides,
				'image_baseurl' => $this->_path.'slides/'
			)
		);

		return $this->display(__FILE__, 'list.tpl');
	}

	public function renderAddForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Slide informations'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'file_lang',
						'label' => $this->l('Select a file'),
						'name' => 'image',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Title'),
						'name' => 'title',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('URL'),
						'name' => 'url',
						'lang' => true,
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Description'),
						'name' => 'description',
						'autoload_rte' => true,
						'lang' => true,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Active'),
						'name' => 'active_slide',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
		{
			$slide = new WPSidebarSlide((int)Tools::getValue('id_slide'));
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_slide');
			$fields_form['form']['images'] = $slide->image;


			$has_picture = true;

			foreach (Language::getLanguages(false) as $lang)
				if (!isset($slide->image[$lang['id_lang']]))
					$has_picture &= false;

			if ($has_picture)
				$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'has_picture');
		}

			$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSlide';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
			'id_lang' => $language->id,
			'iso_code' => $language->iso_code
			),
			'fields_value' => $this->getAddFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'image_baseurl' => $this->_path.'slides/'
		);

		$helper->override_folder = '/';

		return $helper->generateForm(array($fields_form));
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
						'label' => $this->l('Max width'),
						'name' => 'WPSIDEBARSLIDER_WIDTH',
						'suffix' => 'px'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Speed'),
						'name' => 'WPSIDEBARSLIDER_SPEED',
						'suffix' => 'ms'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Pause'),
						'name' => 'WPSIDEBARSLIDER_PAUSE',
						'suffix' => 'ms'
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Auto play'),
						'name' => 'WPSIDEBARSLIDER_LOOP',
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
						'type' => 'select',
						'label' => $this->l('Effect'),
						'desc' => $this->l('Effect of transition'),
						'name' => 'WPSIDEBARSLIDER_EFFECT',
						'options' => array(
							'query' => array(
									array(
											'id' => 'horizontal',
											'name' => $this->l('Horizontal')),
									array(
											'id' => 'vertical',
											'name' => $this->l('Vertical')),
									array(
											'id' => 'fade',
											'name' => $this->l('Fade')),
								),
									'id' => 'id',
									'name' => 'name'
						)
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
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSlider';
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
			'WPSIDEBARSLIDER_WIDTH' => Tools::getValue('WPSIDEBARSLIDER_WIDTH', Configuration::get('WPSIDEBARSLIDER_WIDTH')),
			'WPSIDEBARSLIDER_SPEED' => Tools::getValue('WPSIDEBARSLIDER_SPEED', Configuration::get('WPSIDEBARSLIDER_SPEED')),
			'WPSIDEBARSLIDER_PAUSE' => Tools::getValue('WPSIDEBARSLIDER_PAUSE', Configuration::get('WPSIDEBARSLIDER_PAUSE')),
			'WPSIDEBARSLIDER_LOOP' => Tools::getValue('WPSIDEBARSLIDER_LOOP', Configuration::get('WPSIDEBARSLIDER_LOOP')),
			'WPSIDEBARSLIDER_EFFECT' => Tools::getValue('WPSIDEBARSLIDER_EFFECT', Configuration::get('WPSIDEBARSLIDER_EFFECT')),
		);
	}

	public function getAddFieldsValues()
	{
		$fields = array();


		if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
		{
			$slide = new WPSidebarSlide((int)Tools::getValue('id_slide'));
			$fields['id_slide'] = (int)Tools::getValue('id_slide', $slide->id);
		}
		else
			$slide = new WPSidebarSlide();

		$fields['active_slide'] = Tools::getValue('active_slide', $slide->active);
		$fields['has_picture'] = true;

		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$fields['image'][$lang['id_lang']] = Tools::getValue('image_'.(int)$lang['id_lang']);
			$fields['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], $slide->title[$lang['id_lang']]);
			$fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $slide->url[$lang['id_lang']]);
			$fields['description'][$lang['id_lang']] = Tools::getValue('description_'.(int)$lang['id_lang'], $slide->description[$lang['id_lang']]);

		}

		return $fields;
	}

}
