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
class WPManufacturerLogos extends Module
{
	public function __construct()
	{
		$this->name = 'wpmanufacturerlogos';
		$this->tab = 'front_office_features';
		$this->version = '2.0.0';
		$this->author = 'WEB-PLUS';
		$this->module_key = '22ed5f4dc9507052850e0fc932d765c3';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct(); // The parent construct is required for translations

		$this->displayName = $this->l('Manufacturer logos in footer');
		$this->description = $this->l('Add block with logos in footer');

	}

	public function install()
	{
		Configuration::updateValue('WP_MAN_LOGO_SIZE', 'small_default');
		Configuration::updateValue('WP_MAN_DISPLAY_ITEMS', 8);
		Configuration::updateValue('WP_MAN_SCROLL_ITEMS', 8);
		Configuration::updateValue('WP_MAN_PAUSE_TIME', 5000);
		Configuration::updateValue('WP_MAN_CIRCULAR', 'false');
		Configuration::updateValue('WP_MAN_INFINITE', 'false');
		Configuration::updateValue('WP_MAN_MOUSEOVER_PAUSE', 'true');
		Configuration::updateValue('WP_MAN_AUTO_START', 'false');
		Configuration::updateValue('WP_MAN_RANDOM', '0');
		Configuration::updateValue('WP_MAN_FX', 'scroll');
		Configuration::updateValue('WP_MAN_FX_TIME', 500);
	if (!parent::install() || !$this->registerHook('displayHeader') || !$this->registerHook('wpfooter'))
			return false;
		return true;
	}

	public function getContent()
	{
	$output = '';
		if (Tools::isSubmit('submitWPManufacturer'))
		{
		$errors = array();

			$wp_man_logo_size = Tools::getValue('WP_MAN_LOGO_SIZE');
			$wp_man_display_items = (int)Tools::getValue('WP_MAN_DISPLAY_ITEMS');
			$wp_man_scroll_items = (int)Tools::getValue('WP_MAN_SCROLL_ITEMS');
			$wp_man_pause_time = (int)Tools::getValue('WP_MAN_PAUSE_TIME');
			$wp_man_auto_start = Tools::getValue('WP_MAN_AUTO_START');
			$wp_man_random = Tools::getValue('WP_MAN_RANDOM');
			$wp_man_circular = Tools::getValue('WP_MAN_CIRCULAR');
			$wp_man_infinite = Tools::getValue('WP_MAN_INFINITE');
			$wp_man_mouseover_pause = Tools::getValue('WP_MAN_MOUSEOVER_PAUSE');
			$wp_man_fx = Tools::getValue('WP_MAN_FX');
			$wp_man_fx_time = (int)Tools::getValue('WP_MAN_FX_TIME');

			if (!$wp_man_display_items || $wp_man_display_items <= 0 || !Validate::isInt($wp_man_display_items))
				$errors[] = $this->l('Invalid Visible items, you must enter number');
			elseif (!$wp_man_scroll_items || $wp_man_scroll_items <= 0 || !Validate::isInt($wp_man_scroll_items))
				$errors[] = $this->l('Invalid Scroll items, you must enter number');
			elseif (!$wp_man_fx_time || $wp_man_fx_time <= 0 || !Validate::isInt($wp_man_fx_time))
				$errors[] = $this->l('Invalid Duration value, you must enter number');
			elseif (!$wp_man_pause_time || $wp_man_pause_time <= 0 || !Validate::isInt($wp_man_pause_time))
				$errors[] = $this->l('Invalid Pause value, you must enter number');
			else
			{
			Configuration::updateValue('WP_MAN_LOGO_SIZE', $wp_man_logo_size);
			Configuration::updateValue('WP_MAN_DISPLAY_ITEMS', (int)$wp_man_display_items);
			Configuration::updateValue('WP_MAN_SCROLL_ITEMS', (int)$wp_man_scroll_items);
			Configuration::updateValue('WP_MAN_PAUSE_TIME', (int)$wp_man_pause_time);
			Configuration::updateValue('WP_MAN_AUTO_START', $wp_man_auto_start);
			Configuration::updateValue('WP_MAN_RANDOM', $wp_man_random);
			Configuration::updateValue('WP_MAN_CIRCULAR', $wp_man_circular);
			Configuration::updateValue('WP_MAN_INFINITE', $wp_man_infinite);
			Configuration::updateValue('WP_MAN_MOUSEOVER_PAUSE', $wp_man_mouseover_pause);
			Configuration::updateValue('WP_MAN_FX', $wp_man_fx);
			Configuration::updateValue('WP_MAN_FX_TIME', (int)$wp_man_fx_time);
			}
			if (isset($errors) && count($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else

		$output .= $this->displayConfirmation($this->l('Settings updated'));
		}
		return $output.$this->renderForm();
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
						'type' => 'select',
						'label' => $this->l('Select size of logo'),
						'desc' => $this->l('Choose small_default or manufacturerlogo size. small_default is default image size which already exists in Prestashop. If you want to use custom logo size, create new size in Preferences - Image with name manufacturerlogo and size as required. Then regenerate thumbnails.'),
						'name' => 'WP_MAN_LOGO_SIZE',
						'options' => array(
							'query' => array(
									array(
										'id' => 'small_default',
										'name' => $this->l('small_default')),
									array(
										'id' => 'manufacturerlogo',
										'name' => $this->l('manufacturerlogo')),
							),
						'id' => 'id',
						'name' => 'name'
						)
					),
					array(
						'type' => 'select',
						'label' => $this->l('Transition'),
						'name' => 'WP_MAN_FX',
						'options' => array(
							'query' => array(
									array(
													'id' => 'none',
													'name' => $this->l('none')),
									array(
												'id' => 'scroll',
													'name' => $this->l('scroll')),
									array(
												'id' => 'directscroll',
													'name' => $this->l('directscroll')),
									array(
												'id' => 'fade',
													'name' => $this->l('fade')),
									array(
												'id' => 'crossfade',
													'name' => $this->l('crossfade')),
									array(
												'id' => 'cover',
													'name' => $this->l('cover')),
									array(
												'id' => 'cover-fade',
													'name' => $this->l('cover-fade')),
									array(
												'id' => 'uncover',
													'name' => $this->l('uncover')),
									array(
												'id' => 'uncover-fade',
													'name' => $this->l('uncover-fade')),
										),
							'id' => 'id',
							'name' => 'name'
						)
					),
					array(
						'type' => 'text',
						'label' => $this->l('Visible items'),
						'name' => 'WP_MAN_DISPLAY_ITEMS',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('The number of visible items'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Scroll items'),
						'name' => 'WP_MAN_SCROLL_ITEMS',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('The number of items to scroll.'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Duration'),
						'name' => 'WP_MAN_FX_TIME',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('Determines the duration of the transition in milliseconds'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Pause'),
						'name' => 'WP_MAN_PAUSE_TIME',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('The amount of milliseconds the carousel will pause'),
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Auto start'),
						'name' => 'WP_MAN_AUTO_START',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'wp_man_auto_start_on',
								'value' => 'true',
								'label' => $this->l('Enabled')),
							array(
								'id' => 'wp_man_auto_start_off',
								'value' => 'false',
								'label' => $this->l('Disabled')),
						),
						'desc' => $this->l('Determines whether the carousel should scroll automatically or not')
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Random'),
						'name' => 'WP_MAN_RANDOM',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'wp_man_random_on',
								'value' => '"random"',
								'label' => $this->l('Enabled')),
							array(
								'id' => 'wp_man_random_off',
								'value' => 0,
								'label' => $this->l('Disabled')),
						),
						'desc' => $this->l('Carousel will start from random position')
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Circular'),
						'name' => 'WP_MAN_CIRCULAR',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'wp_man_circular_on',
								'value' => 'true',
								'label' => $this->l('Enabled')),
							array(
								'id' => 'wp_man_circular_off',
								'value' => 'false',
								'label' => $this->l('Disabled')),
						),
						'desc' => $this->l('Determines whether the carousel should be circular')
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Infinite'),
						'name' => 'WP_MAN_INFINITE',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'wp_man_infinite_on',
								'value' => 'true',
								'label' => $this->l('Enabled')),
							array(
								'id' => 'wp_man_infinite_off',
								'value' => 'false',
								'label' => $this->l('Disabled')),
						),
						'desc' => $this->l('Determines whether the carousel should be infinite')
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Mouseover pause'),
						'name' => 'WP_MAN_MOUSEOVER_PAUSE',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'wp_man_mouseover_on',
								'value' => 'true',
								'label' => $this->l('Enabled')),
							array(
								'id' => 'wp_man_mouseover_off',
								'value' => 'false',
								'label' => $this->l('Disabled')),
						),
					'desc' => $this->l('Determines whether the timeout between transitions should be paused')
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
	$helper->allow_employee_form_lang =
		Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

	$this->fields_form = array();
	$helper->identifier = $this->identifier;
	$helper->submit_action = 'submitWPManufacturer';
	$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
		.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
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
		'WP_MAN_LOGO_SIZE' => Tools::getValue('WP_MAN_LOGO_SIZE', Configuration::get('WP_MAN_LOGO_SIZE')),
		'WP_MAN_DISPLAY_ITEMS' => Tools::getValue('WP_MAN_DISPLAY_ITEMS', Configuration::get('WP_MAN_DISPLAY_ITEMS')),
		'WP_MAN_SCROLL_ITEMS' => Tools::getValue('WP_MAN_SCROLL_ITEMS', Configuration::get('WP_MAN_SCROLL_ITEMS')),
		'WP_MAN_PAUSE_TIME' => Tools::getValue('WP_MAN_PAUSE_TIME', Configuration::get('WP_MAN_PAUSE_TIME')),
		'WP_MAN_AUTO_START' => Tools::getValue('WP_MAN_AUTO_START', Configuration::get('WP_MAN_AUTO_START')),
		'WP_MAN_RANDOM' => Tools::getValue('WP_MAN_RANDOM', Configuration::get('WP_MAN_RANDOM')),
		'WP_MAN_CIRCULAR' => Tools::getValue('WP_MAN_CIRCULAR', Configuration::get('WP_MAN_CIRCULAR')),
		'WP_MAN_INFINITE' => Tools::getValue('WP_MAN_INFINITE', Configuration::get('WP_MAN_INFINITE')),
		'WP_MAN_MOUSEOVER_PAUSE' => Tools::getValue('WP_MAN_MOUSEOVER_PAUSE', Configuration::get('WP_MAN_MOUSEOVER_PAUSE')),
		'WP_MAN_FX' => Tools::getValue('WP_MAN_FX', Configuration::get('WP_MAN_FX')),
		'WP_MAN_FX_TIME' => Tools::getValue('WP_MAN_FX_TIME', Configuration::get('WP_MAN_FX_TIME')),
		);
	}

	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'css/wpmanufacturerlogos.css');
	}


	public function hookwpfooter($params)
	{
		$wp_man_logo_size = Configuration::get('WP_MAN_LOGO_SIZE');
		$wp_man_display_items = Configuration::get('WP_MAN_DISPLAY_ITEMS');
		$wp_man_scroll_items = Configuration::get('WP_MAN_SCROLL_ITEMS');
		$wp_man_pause_time = Configuration::get('WP_MAN_PAUSE_TIME');
		$wp_man_auto_start = Configuration::get('WP_MAN_AUTO_START');
		$wp_man_random = Configuration::get('WP_MAN_RANDOM');
		$wp_man_circular = Configuration::get('WP_MAN_CIRCULAR');
		$wp_man_infinite = Configuration::get('WP_MAN_INFINITE');
		$wp_man_mouseover_pause = Configuration::get('WP_MAN_MOUSEOVER_PAUSE');
		$wp_man_fx = Configuration::get('WP_MAN_FX');
		$wp_man_fx_time = Configuration::get('WP_MAN_FX_TIME');

		$this->smarty->assign(array(
			'wpmanufacturers' => Manufacturer::getManufacturers(),
			'smallSize' => Image::getSize('small_default'),
			'wp_man_logo_size' => Configuration::get('WP_MAN_LOGO_SIZE'),
			'manufacturerlogoSize' => Image::getSize('manufacturerlogo'),
			'wp_man_display_items' => $wp_man_display_items,
			'wp_man_scroll_items' => $wp_man_scroll_items,
			'wp_man_pause_time' => $wp_man_pause_time,
			'wp_man_auto' => $wp_man_auto_start,
			'wp_man_rand' => $wp_man_random,
			'wp_man_cir' => $wp_man_circular,
			'wp_man_inf' => $wp_man_infinite,
			'wp_man_mouseover' => $wp_man_mouseover_pause,
			'wp_man_fx' => $wp_man_fx,
			'wp_man_fx_time' => $wp_man_fx_time,
			'this_path' => $this->_path
			));
		return $this->display(__FILE__, 'wpmanufacturerlogos.tpl');
	}
}
?>