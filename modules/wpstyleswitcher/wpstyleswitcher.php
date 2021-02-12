<?php
/**
* 2007-2015 PrestaShop
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
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class WPStyleSwitcher extends Module
{
	public function __construct()
	{
		$this->name = 'wpstyleswitcher';
		$this->tab = 'front_office_features';
		$this->version = '1.1.0';
		$this->author = 'WEB-PLUS';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Novus theme colors and settings');
		$this->description = $this->l('Setup theme colors and settings');
		$this->cssFile = $this->local_path.'views/css/wpstyleswitcher.css';
	}

	public function install()
	{
			
			Configuration::updateValue('WPSTYLE_GOOGLE_LINK', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic&subset=latin,latin-ext');
			Configuration::updateValue('WPSTYLE_GOOGLE_NAME', '\'Open Sans\', sans-serif');
			Configuration::updateValue('WPSTYLE_GOOGLE_LINK2', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic&subset=latin,latin-ext');
			Configuration::updateValue('WPSTYLE_GOOGLE_NAME2', '\'Open Sans\', sans-serif');
			Configuration::updateValue('WPSTYLE_TEXT_SIZE', 14);
			Configuration::updateValue('WPSTYLE_TEXT_SIZE2', 13);
			Configuration::updateValue('WPSTYLE_TEXT_SIZE3', 20);
			Configuration::updateValue('WPSTYLE_HEAD_UPR', '1');
			Configuration::updateValue('WPSTYLE_MENU_UPR', '1');

			Configuration::updateValue('WPSTYLE_COLUMN', 'left');
			Configuration::updateValue('WPSTYLE_BTN', '#888888');
			Configuration::updateValue('WPSTYLE_BTN_CL', '#ffffff');
			Configuration::updateValue('WPSTYLE_BTN_HVR', '#666666');
			Configuration::updateValue('WPSTYLE_BTN_HVR_CL', '#ffffff');
			Configuration::updateValue('WPSTYLE_EXCL_BTN', '#39569d');
			Configuration::updateValue('WPSTYLE_EXCL_BTN_CL', '#ffffff');
			Configuration::updateValue('WPSTYLE_EXCL_BTN_HVR', '#3E61B3');
			Configuration::updateValue('WPSTYLE_EXCL_BTN_HVR_CL', '#ffffff');

			Configuration::updateValue('WPSTYLE_CART_ICON_PRD', '#444444');

			Configuration::updateValue('WPSTYLE_BLCK_HDR_BG', '#39569d');
			Configuration::updateValue('WPSTYLE_BLCK_HDR_CLR', '#ffffff');

			Configuration::updateValue('WPSTYLE_CAT_CL', '#ffffff');
			Configuration::updateValue('WPSTYLE_CAT_CL2', '#ffffff');
			Configuration::updateValue('WPSTYLE_CAT_CL3', '#111111');
			Configuration::updateValue('WPSTYLE_CAT_CL4', '#444444');
			Configuration::updateValue('WPSTYLE_CAT_CL5', '#ffffff');
			Configuration::updateValue('WPSTYLE_CAT_CL6', '#666666');
			Configuration::updateValue('WPSTYLE_CAT_CL7', '#111111');

			Configuration::updateValue('WPSTYLE_MENU_TYPE', 'megamenu');
			Configuration::updateValue('WPSTYLE_MENU_BG', '#3a5cb0');
			Configuration::updateValue('WPSTYLE_MENU_BG2', '#245395');
			Configuration::updateValue('WPSTYLE_MENU_BG4', '#4161A0');
			Configuration::updateValue('WPSTYLE_MENU_BG5', '#000000');
			Configuration::updateValue('WPSTYLE_MENU_BR1', '#2c3e6b');
			Configuration::updateValue('WPSTYLE_MENU_BR2', '#5d76b3');

			Configuration::updateValue('WPSTYLE_LBL_1', '#ffd314');
			Configuration::updateValue('WPSTYLE_LBLT_1', '#000000');
			Configuration::updateValue('WPSTYLE_LBL_2', '#CD2026');
			Configuration::updateValue('WPSTYLE_LBLT_2', '#ffffff');
			Configuration::updateValue('WPSTYLE_STCK_GRD', '1');

			Configuration::updateValue('WPSTYLE_HIDE_LEFT', '0');
			Configuration::updateValue('WPSTYLE_FNC_BTN', '1');
			Configuration::updateValue('WPSTYLE_PATTERN', 'none.png');
			Configuration::updateValue('WPSTYLE_BACKGROUND', '#a3c2e1');
			Configuration::updateValue('WPSTYLE_BACKGROUND2', '#3a6ba3');

			return (parent::install() && $this->registerHook('displayHeader') && $this->registerHook('backOfficeHeader'));
		}



	public function getContent()
	{
		$output = '';
		$errors = array();
		if (Tools::isSubmit('submitWPStyleSwitcher'))
		{
				$wp_google_name = (string)(Tools::getValue('WPSTYLE_GOOGLE_NAME'));
				$wp_google_link = (string)(Tools::getValue('WPSTYLE_GOOGLE_LINK'));
				$wp_google_name2 = (string)(Tools::getValue('WPSTYLE_GOOGLE_NAME2'));
				$wp_google_link2 = (string)(Tools::getValue('WPSTYLE_GOOGLE_LINK2'));
				$wp_text_size = (int)(Tools::getValue('WPSTYLE_TEXT_SIZE'));
				$wp_text_size2 = (int)(Tools::getValue('WPSTYLE_TEXT_SIZE2'));
				$wp_text_size3 = (int)(Tools::getValue('WPSTYLE_TEXT_SIZE3'));
				$wp_head_upr = (string)(Tools::getValue('WPSTYLE_HEAD_UPR'));
				$wp_menu_upr = (string)(Tools::getValue('WPSTYLE_MENU_UPR'));
				$wp_clmn = Tools::getValue('WPSTYLE_COLUMN');
				$wp_btn = (string)(Tools::getValue('WPSTYLE_BTN'));
				$wp_btn_cl = (string)(Tools::getValue('WPSTYLE_BTN_CL'));
				$wp_btn_hvr = (string)(Tools::getValue('WPSTYLE_BTN_HVR'));
				$wp_btn_hvr_cl = (string)(Tools::getValue('WPSTYLE_BTN_HVR_CL'));
				$wp_excl_btn = (string)(Tools::getValue('WPSTYLE_EXCL_BTN'));
				$wp_excl_btn_cl = (string)(Tools::getValue('WPSTYLE_EXCL_BTN_CL'));
				$wp_excl_btn_hvr = (string)(Tools::getValue('WPSTYLE_EXCL_BTN_HVR'));
				$wp_excl_btn_hvr_cl = (string)(Tools::getValue('WPSTYLE_EXCL_BTN_HVR_CL'));
				$wp_cart_icon_prd = (string)(Tools::getValue('WPSTYLE_CART_ICON_PRD'));
				$wp_menu_bg2 = (string)(Tools::getValue('WPSTYLE_MENU_BG2'));
				$wp_blck_hdr_bg = (string)(Tools::getValue('WPSTYLE_BLCK_HDR_BG'));
				$wp_blck_hdr_clr = (string)(Tools::getValue('WPSTYLE_BLCK_HDR_CLR'));
				$wp_cat_cl = (string)(Tools::getValue('WPSTYLE_CAT_CL'));
				$wp_cat_cl2 = (string)(Tools::getValue('WPSTYLE_CAT_CL2'));
				$wp_cat_cl3 = (string)(Tools::getValue('WPSTYLE_CAT_CL3'));
				$wp_cat_cl4 = (string)(Tools::getValue('WPSTYLE_CAT_CL4'));
				$wp_cat_cl5 = (string)(Tools::getValue('WPSTYLE_CAT_CL5'));
				$wp_cat_cl6 = (string)(Tools::getValue('WPSTYLE_CAT_CL6'));
				$wp_cat_cl7 = (string)(Tools::getValue('WPSTYLE_CAT_CL7'));
				$wp_menu_type = (string)(Tools::getValue('WPSTYLE_MENU_TYPE'));
				$wp_menu_bg = (string)(Tools::getValue('WPSTYLE_MENU_BG'));
				$wp_menu_bg4 = (string)(Tools::getValue('WPSTYLE_MENU_BG4'));
				$wp_menu_bg5 = (string)(Tools::getValue('WPSTYLE_MENU_BG5'));
				$wp_menu_br1 = (string)(Tools::getValue('WPSTYLE_MENU_BR1'));
				$wp_menu_br2 = (string)(Tools::getValue('WPSTYLE_MENU_BR2'));
				$wp_lbl_1 = (string)(Tools::getValue('WPSTYLE_LBL_1'));
				$wp_lblt_1 = (string)(Tools::getValue('WPSTYLE_LBLT_1'));
				$wp_lbl_2 = (string)(Tools::getValue('WPSTYLE_LBL_2'));
				$wp_lblt_2 = (string)(Tools::getValue('WPSTYLE_LBLT_2'));
				$wp_stck_grd = (string)(Tools::getValue('WPSTYLE_STCK_GRD'));
				$wp_hide_left = (string)(Tools::getValue('WPSTYLE_HIDE_LEFT'));
				$wp_fnc_btn = (string)(Tools::getValue('WPSTYLE_FNC_BTN'));
				$wp_bg = (string)(Tools::getValue('WPSTYLE_BACKGROUND'));
				$wp_bg2 = (string)(Tools::getValue('WPSTYLE_BACKGROUND2'));
				$wp_ptn = (string)(Tools::getValue('WPSTYLE_PATTERN'));

				if (!Validate::isInt($wp_text_size) || $wp_text_size <= 0)
					$errors[] = $this->l('Font size must be number higher then 0. Please enter a positive number.');
				if (!Validate::isInt($wp_text_size2) || $wp_text_size2 <= 0)
					$errors[] = $this->l('Font size must be number higher then 0. Please enter a positive number.');
				if (!Validate::isInt($wp_text_size3) || $wp_text_size3 <= 0)
					$errors[] = $this->l('Font size must be number higher then 0. Please enter a positive number.');
				else
				{
				Configuration::updateValue('WPSTYLE_GOOGLE_LINK', $wp_google_link);
				Configuration::updateValue('WPSTYLE_GOOGLE_NAME', $wp_google_name);
				Configuration::updateValue('WPSTYLE_GOOGLE_LINK2', $wp_google_link2);
				Configuration::updateValue('WPSTYLE_GOOGLE_NAME2', $wp_google_name2);
				Configuration::updateValue('WPSTYLE_TEXT_SIZE', $wp_text_size);
				Configuration::updateValue('WPSTYLE_TEXT_SIZE2', $wp_text_size2);
				Configuration::updateValue('WPSTYLE_TEXT_SIZE3', $wp_text_size3);
				Configuration::updateValue('WPSTYLE_HEAD_UPR', $wp_head_upr);
				Configuration::updateValue('WPSTYLE_MENU_UPR', $wp_menu_upr);
				Configuration::updateValue('WPSTYLE_COLUMN', $wp_clmn);
				Configuration::updateValue('WPSTYLE_BTN', $wp_btn);
				Configuration::updateValue('WPSTYLE_BTN_CL', $wp_btn_cl);
				Configuration::updateValue('WPSTYLE_BTN_HVR', $wp_btn_hvr);
				Configuration::updateValue('WPSTYLE_BTN_HVR_CL', $wp_btn_hvr_cl);
				Configuration::updateValue('WPSTYLE_EXCL_BTN', $wp_excl_btn);
				Configuration::updateValue('WPSTYLE_EXCL_BTN_CL', $wp_excl_btn_cl);
				Configuration::updateValue('WPSTYLE_EXCL_BTN_HVR', $wp_excl_btn_hvr);
				Configuration::updateValue('WPSTYLE_EXCL_BTN_HVR_CL', $wp_excl_btn_hvr_cl);
				Configuration::updateValue('WPSTYLE_CART_ICON_PRD', $wp_cart_icon_prd);
				Configuration::updateValue('WPSTYLE_MENU_BG2', $wp_menu_bg2);
				Configuration::updateValue('WPSTYLE_BLCK_HDR_BG', $wp_blck_hdr_bg);
				Configuration::updateValue('WPSTYLE_BLCK_HDR_CLR', $wp_blck_hdr_clr);
				Configuration::updateValue('WPSTYLE_CAT_CL', $wp_cat_cl);
				Configuration::updateValue('WPSTYLE_CAT_CL2', $wp_cat_cl2);
				Configuration::updateValue('WPSTYLE_CAT_CL3', $wp_cat_cl3);
				Configuration::updateValue('WPSTYLE_CAT_CL4', $wp_cat_cl4);
				Configuration::updateValue('WPSTYLE_CAT_CL5', $wp_cat_cl5);
				Configuration::updateValue('WPSTYLE_CAT_CL6', $wp_cat_cl6);
				Configuration::updateValue('WPSTYLE_CAT_CL7', $wp_cat_cl7);
				Configuration::updateValue('WPSTYLE_MENU_TYPE', $wp_menu_type);
				Configuration::updateValue('WPSTYLE_MENU_BG', $wp_menu_bg);
				Configuration::updateValue('WPSTYLE_MENU_BG4', $wp_menu_bg4);
				Configuration::updateValue('WPSTYLE_MENU_BG5', $wp_menu_bg5);
				Configuration::updateValue('WPSTYLE_MENU_BR1', $wp_menu_br1);
				Configuration::updateValue('WPSTYLE_MENU_BR2', $wp_menu_br2);
				Configuration::updateValue('WPSTYLE_LBL_1', $wp_lbl_1);
				Configuration::updateValue('WPSTYLE_LBLT_1', $wp_lblt_1);
				Configuration::updateValue('WPSTYLE_LBL_2', $wp_lbl_2);
				Configuration::updateValue('WPSTYLE_LBLT_2', $wp_lblt_2);
				Configuration::updateValue('WPSTYLE_STCK_GRD', $wp_stck_grd);
				Configuration::updateValue('WPSTYLE_HIDE_LEFT', $wp_hide_left);
				Configuration::updateValue('WPSTYLE_FNC_BTN', $wp_fnc_btn);
				Configuration::updateValue('WPSTYLE_BACKGROUND', $wp_bg);
				Configuration::updateValue('WPSTYLE_BACKGROUND2', $wp_bg2);
				Configuration::updateValue('WPSTYLE_PATTERN', $wp_ptn);

			$this->_writeCss();
			}
			if (isset($errors) && count($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
			$output .= $this->displayConfirmation($this->l('Settings updated'));
		}
	$output .= $this->renderPatterns();
		return $output.$this->renderForm();
	}


public function getmyimages($imgdir) 
{
	$allowed_types = array('png','gif');
	$dimg = opendir($imgdir);
	$a_img = array();

	while ($imgfile = readdir($dimg))
	{
		if (in_array(Tools::strtolower(Tools::substr($imgfile, -3)), $allowed_types))
		{
		$a_img[] = $imgfile;
		sort($a_img);
		reset ($a_img);
		}
	}
	return $a_img;
}

	public function renderPatterns()
	{
		$patterns = $this->getmyimages(_PS_MODULE_DIR_.$this->name.'/views/img/patterns/');
		$pat = '';

		foreach ($patterns as $pattern)
		$pat .= '
			<li><img src="'._MODULE_DIR_.$this->name.'/views/img/patterns/'.$pattern.'" title="'.Tools::substr($pattern, 0, -4).'" alt="'.Tools::substr($pattern, 0, -4).'" /></li>
			';

		$this->context->smarty->assign(
			array(
				'patterns' => $pat
			)
		);

		return $this->display(__FILE__, 'patterns.tpl');
	}

	public function renderForm()
	{
		$patterns = $this->getmyimages(_PS_MODULE_DIR_.$this->name.'/views/img/patterns/');
		$query = array();
		foreach ($patterns as $key => $value)
		{
			$query[] = array(
					'id' => $key,
					'label' => $value,
				);
		}

			$fields_form_global = array(
					'form' => array(
					'tab_name' => 'global_tab',
					'legend' => array(
					'title' => $this->l('General settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
						array(
							'type' => 'color',
							'label' => $this->l('Background - 1st color of gradient'),
							'desc' => $this->l('Default color: #a3c2e1'),
							'name' => 'WPSTYLE_BACKGROUND',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'color',
							'label' => $this->l('Background - 2nd color of gradient'),
							'desc' => $this->l('Default color: #3a6ba3. If you want one background color without gradient, select same color as for 1st color of gradient'),
							'name' => 'WPSTYLE_BACKGROUND2',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'select',
							'label' => $this->l('Background pattern'),
							'desc' => $this->l('Choose the background image pattern. To disable patterns, select none.png. Some patterns are transparent (background color will be visible), some not. To add more patterns, copy them into patterns folder of wpstyleswitcher module. Provided patterns are from subtlepatterns.com'),
							'name' => 'WPSTYLE_PATTERN',
							'options' => array(
								'query' => $query,
								// 'id' => 'id',
								'id' => 'label',
								'name' => 'label'
							)
						),
						array(
							'type' => 'select',
							'label' => $this->l('Sidebar position'),
							'desc' => $this->l('Choose the sidebar position. Left (default) or right.'),
							'name' => 'WPSTYLE_COLUMN',
							'options' => array(
									'query' => array(
											array(
												'id' => 'left',
												'name' => $this->l('left')),
											array(
												'id' => 'right',
												'name' => $this->l('right')),
									),
								'id' => 'id',
								'name' => 'name'
							)
						),
						array(
						'type' => 'color',
						'label' => $this->l('Reduction label background color'),
						'desc' => $this->l('Default color: #ffd314'),
						'name' => 'WPSTYLE_LBL_1',
						'class' => 'color mColorPickerInput'
					),
						array(
							'type' => 'color',
							'label' => $this->l('Reduction label text color'),
							'desc' => $this->l('Default color: #000000'),
							'name' => 'WPSTYLE_LBLT_1',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'color',
							'label' => $this->l('New label background color'),
							'desc' => $this->l('Default color: #CD2026'),
							'name' => 'WPSTYLE_LBL_2',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'color',
							'label' => $this->l('New label text color'),
							'desc' => $this->l('Default color: #ffffff'),
							'name' => 'WPSTYLE_LBLT_2',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'switch',
							'label' => $this->l('Stock availability in grid view'),
							'name' => 'WPSTYLE_STCK_GRD',
							'values' => array(
								array(
									'id' => 'stck_grd_on',
									'value' => 1,
									'label' => $this->l('Enabled')
								),
								array(
									'id' => 'stck_grd_off',
									'value' => 0,
									'label' => $this->l('Disabled')
								)
							),
						),
						array(
							'type' => 'switch',
							'label' => $this->l('Display sidebar on mobiles and tablets'),
							'name' => 'WPSTYLE_HIDE_LEFT',
							'values' => array(
								array(
									'id' => 'hide_left_on',
									'value' => 1,
									'label' => $this->l('Enabled')
								),
								array(
									'id' => 'hide_left_off',
									'value' => 0,
									'label' => $this->l('Disabled')
								)
							),
						),
						array(
							'type' => 'switch',
							'label' => $this->l('Display product icons - quick view, wishlist, compare'),
							'name' => 'WPSTYLE_FNC_BTN',
							'values' => array(
								array(
									'id' => 'fnc_btn_on',
									'value' => 1,
									'label' => $this->l('Enabled')
								),
								array(
									'id' => 'fnc_btn_off',
									'value' => 0,
									'label' => $this->l('Disabled')
								)
							),
						),
						array(
							'type' => 'color',
							'label' => $this->l('Exclusive blocks background color'),
							'desc' => $this->l('Default color: #39569d'),
							'name' => 'WPSTYLE_BLCK_HDR_BG',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'color',
							'label' => $this->l('Exclusive blocks text color'),
							'desc' => $this->l('Default color: #ffffff'),
							'name' => 'WPSTYLE_BLCK_HDR_CLR',
							'class' => 'color mColorPickerInput'
						),
					),
				),
			);

			$fields_form_fonts = array(
				'form' => array(
						'tab_name' => 'fonts_tab',
						'legend' => array(
							'title' => $this->l('Fonts'),
							'icon' => 'icon-edit'
						),
					'input' => array(
							array(
								'type' => 'text',
								'label' => $this->l('Google font url for headings'),
								'desc' => $this->l('Example: http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic&subset=latin,latin-ext ').' <br><a href="https://www.google.com/fonts" target="_blank">'.$this->l('Check google fonts').'</a>',
								'name' => 'WPSTYLE_GOOGLE_LINK',
								'size' => 60
							),
							array(
								'type' => 'text',
								'label' => $this->l('Google font family for headings'),
								'name' => 'WPSTYLE_GOOGLE_NAME',
								'desc' => $this->l('Example: \'Open Sans\', sans-serif '),
								'suffix_wrapper' => true,
								'size' => 30
							),
							array(
								'type' => 'text',
								'label' => $this->l('Font size for headings'),
								'name' => 'WPSTYLE_TEXT_SIZE3',
								'desc' => $this->l('Default: 20'),
								'suffix_wrapper' => true,
								'size' => 30,
								'suffix' => 'pixels'
							),
							array(
								'type' => 'switch',
								'label' => $this->l('Font headings uppercase'),
								'name' => 'WPSTYLE_HEAD_UPR',
								'values' => array(
									array(
										'id' => 'headupr_on',
										'value' => 1,
										'label' => $this->l('Enabled')
									),
									array(
										'id' => 'headupr_off',
										'value' => 0,
										'label' => $this->l('Disabled')
									)
								),
							),
							array(
								'type' => 'text',
								'label' => $this->l('Google font url for normal text'),
								'desc' => $this->l('Example: http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic&subset=latin,latin-ext ').' <br><a href="https://www.google.com/fonts" target="_blank">'.$this->l('Check google fonts').'</a>',
								'name' => 'WPSTYLE_GOOGLE_LINK2',
								'size' => 60
							),
							array(
								'type' => 'text',
								'label' => $this->l('Google font family for normal text'),
								'name' => 'WPSTYLE_GOOGLE_NAME2',
								'desc' => $this->l('Example: \'Open Sans\', sans-serif '),
								'suffix_wrapper' => true,
								'size' => 30
							),
							array(
								'type' => 'text',
								'label' => $this->l('Font size for normal text'),
								'name' => 'WPSTYLE_TEXT_SIZE',
								'desc' => $this->l('Default: 14'),
								'suffix_wrapper' => true,
								'size' => 30,
								'suffix' => 'pixels'
						),
					),
				),
			);

			$fields_form_buttons = array(
				'form' => array(
						'tab_name' => 'buttons_tab',
						'legend' => array(
							'title' => $this->l('Buttons'),
							'icon' => 'icon-edit'
						),
					'input' => array(
					array(
						'type' => 'color',
						'label' => $this->l('Button background'),
						'desc' => $this->l('Default color: #888888'),
						'name' => 'WPSTYLE_BTN',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Button text color'),
						'desc' => $this->l('Default color: #ffffff'),
						'name' => 'WPSTYLE_BTN_CL',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Button hover background'),
						'desc' => $this->l('Default color: #666666'),
						'name' => 'WPSTYLE_BTN_HVR',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Button hover text color'),
						'desc' => $this->l('Default color: #ffffff'),
						'name' => 'WPSTYLE_BTN_HVR_CL',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Exclusive button background'),
						'desc' => $this->l('Default color: #39569d'),
						'name' => 'WPSTYLE_EXCL_BTN',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Exclusive button text color'),
						'desc' => $this->l('Default color: #ffffff'),
						'name' => 'WPSTYLE_EXCL_BTN_CL',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Exclusive button hover background'),
						'desc' => $this->l('Default color: #3E61B3'),
						'name' => 'WPSTYLE_EXCL_BTN_HVR',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Exclusive button hover text color'),
						'desc' => $this->l('Default color: #ffffff'),
						'name' => 'WPSTYLE_EXCL_BTN_HVR_CL',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Cart icon color'),
						'desc' => $this->l('Default color: #444444'),
						'name' => 'WPSTYLE_CART_ICON_PRD',
						'class' => 'color mColorPickerInput'
					),
					),
				),
			);


			$fields_form_menu = array(
				'form' => array(
						'tab_name' => 'menu_tab',
						'legend' => array(
						'title' => $this->l('Menu'),
						'icon' => 'icon-edit'
						),
					'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('Menu display type'),
						'desc' => $this->l('Megamenu or dropdown.'),
						'name' => 'WPSTYLE_MENU_TYPE',
						'options' => array(
								'query' => array(
										array(
											'id' => 'megamenu',
											'name' => $this->l('megamenu')),
										array(
											'id' => 'dropdown',
											'name' => $this->l('dropdown')),
								),
							'id' => 'id',
							'name' => 'name'
						)
					),			
					array(
						'type' => 'color',
						'label' => $this->l('Menu background - 1st color of gradient'),
						'desc' => $this->l('Default color: #3a5cb0'),
						'name' => 'WPSTYLE_MENU_BG',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Menu background - 2nd color of gradient'),
						'desc' => $this->l('Default color: #245395'),
						'name' => 'WPSTYLE_MENU_BG2',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('1px left border for menu item separator'),
						'desc' => $this->l('Default color: #2c3e6b'),
						'name' => 'WPSTYLE_MENU_BR1',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('1px right border for menu item separator'),
						'desc' => $this->l('Default color: #5d76b3'),
						'name' => 'WPSTYLE_MENU_BR2',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('1st level background on hover'),
						'desc' => $this->l('Default color: #4161A0'),
						'name' => 'WPSTYLE_MENU_BG4',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Menu font color'),
						'desc' => $this->l('Default color: #ffffff'),
						'name' => 'WPSTYLE_CAT_CL',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Menu active font color'),
						'desc' => $this->l('Default color: #ffffff'),
						'name' => 'WPSTYLE_CAT_CL5',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Megamenu headings text color'),
						'desc' => $this->l('Default color: #444444'),
						'name' => 'WPSTYLE_CAT_CL4',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Megamenu columns text color'),
						'desc' => $this->l('Default color: #666666'),
						'name' => 'WPSTYLE_CAT_CL6',
						'class' => 'color mColorPickerInput'
					),
					array(
						'type' => 'color',
						'label' => $this->l('Megamenu subitems text on hover'),
						'desc' => $this->l('Default color: #111111'),
						'name' => 'WPSTYLE_CAT_CL7',
						'class' => 'color mColorPickerInput'
					),
						array(
							'type' => 'color',
							'label' => $this->l('Dropdown background on hover'),
							'desc' => $this->l('Default color: #000000'),
							'name' => 'WPSTYLE_MENU_BG5',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'color',
							'label' => $this->l('Dropdown font color'),
							'desc' => $this->l('Default color: #111111'),
							'name' => 'WPSTYLE_CAT_CL3',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'color',
							'label' => $this->l('Dropdown font color on hover'),
							'desc' => $this->l('Default color: #ffffff'),
							'name' => 'WPSTYLE_CAT_CL2',
							'class' => 'color mColorPickerInput'
						),
						array(
							'type' => 'text',
							'label' => $this->l('Font size for 1st level menu items'),
							'name' => 'WPSTYLE_TEXT_SIZE2',
							'desc' => $this->l('Default: 13'),
							'suffix_wrapper' => true,
							'size' => 30,
							'suffix' => 'pixels'
						),
						array(
							'type' => 'switch',
							'label' => $this->l('1st level menu items uppercase'),
							'name' => 'WPSTYLE_MENU_UPR',
							'values' => array(
								array(
									'id' => 'menuupr_on',
									'value' => 1,
									'label' => $this->l('Enabled')
								),
								array(
									'id' => 'menuupr_off',
									'value' => 0,
									'label' => $this->l('Disabled')
								)
							),
						),
					),
				),
			);

			$fields_form_save = array(
			'form' => array(
				'tab_name' => 'save_tab',
				'legend' => array(
					'title' => $this->l('Save configuration'),
					'icon' => 'icon-save'
					),
				'submit' => array(
					'name' => 'submitWPStyleSwitcher',
					'class' => 'btn btn-default pull-right',
					'title' => $this->l('Save')
					),
				),
			);

		$helper = new HelperForm();
		$helper->show_toolbar = true;
		$helper->table = $this->table;

		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->module = $this;
		$this->fields_form = array();
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitWPStyleSwitcher';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form_global, $fields_form_fonts, $fields_form_buttons, $fields_form_menu, $fields_form_save));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'WPSTYLE_GOOGLE_NAME' => Tools::getValue('WPSTYLE_GOOGLE_NAME', Configuration::get('WPSTYLE_GOOGLE_NAME')),
			'WPSTYLE_GOOGLE_LINK' => Tools::getValue('WPSTYLE_GOOGLE_LINK', Configuration::get('WPSTYLE_GOOGLE_LINK')),
			'WPSTYLE_GOOGLE_NAME2' => Tools::getValue('WPSTYLE_GOOGLE_NAME2', Configuration::get('WPSTYLE_GOOGLE_NAME2')),
			'WPSTYLE_GOOGLE_LINK2' => Tools::getValue('WPSTYLE_GOOGLE_LINK2', Configuration::get('WPSTYLE_GOOGLE_LINK2')),
			'WPSTYLE_TEXT_SIZE' => Tools::getValue('WPSTYLE_TEXT_SIZE', Configuration::get('WPSTYLE_TEXT_SIZE')),
			'WPSTYLE_TEXT_SIZE2' => Tools::getValue('WPSTYLE_TEXT_SIZE2', Configuration::get('WPSTYLE_TEXT_SIZE2')),
			'WPSTYLE_TEXT_SIZE3' => Tools::getValue('WPSTYLE_TEXT_SIZE3', Configuration::get('WPSTYLE_TEXT_SIZE3')),
			'WPSTYLE_HEAD_UPR' => Tools::getValue('WPSTYLE_HEAD_UPR', Configuration::get('WPSTYLE_HEAD_UPR')),
			'WPSTYLE_MENU_UPR' => Tools::getValue('WPSTYLE_MENU_UPR', Configuration::get('WPSTYLE_MENU_UPR')),
			'WPSTYLE_COLUMN' => Tools::getValue('WPSTYLE_COLUMN', Configuration::get('WPSTYLE_COLUMN')),
			'WPSTYLE_BTN' => Tools::getValue('WPSTYLE_BTN', Configuration::get('WPSTYLE_BTN')),
			'WPSTYLE_BTN_CL' => Tools::getValue('WPSTYLE_BTN_CL', Configuration::get('WPSTYLE_BTN_CL')),
			'WPSTYLE_BTN_HVR' => Tools::getValue('WPSTYLE_BTN_HVR', Configuration::get('WPSTYLE_BTN_HVR')),
			'WPSTYLE_BTN_HVR_CL' => Tools::getValue('WPSTYLE_BTN_HVR_CL', Configuration::get('WPSTYLE_BTN_HVR_CL')),
			'WPSTYLE_EXCL_BTN' => Tools::getValue('WPSTYLE_EXCL_BTN', Configuration::get('WPSTYLE_EXCL_BTN')),
			'WPSTYLE_EXCL_BTN_CL' => Tools::getValue('WPSTYLE_EXCL_BTN_CL', Configuration::get('WPSTYLE_EXCL_BTN_CL')),
			'WPSTYLE_EXCL_BTN_HVR' => Tools::getValue('WPSTYLE_EXCL_BTN_HVR', Configuration::get('WPSTYLE_EXCL_BTN_HVR')),
			'WPSTYLE_EXCL_BTN_HVR_CL' => Tools::getValue('WPSTYLE_EXCL_BTN_HVR_CL', Configuration::get('WPSTYLE_EXCL_BTN_HVR_CL')),
			'WPSTYLE_CART_ICON_PRD' => Tools::getValue('WPSTYLE_CART_ICON_PRD', Configuration::get('WPSTYLE_CART_ICON_PRD')),
			'WPSTYLE_MENU_BG2' => Tools::getValue('WPSTYLE_MENU_BG2', Configuration::get('WPSTYLE_MENU_BG2')),
			'WPSTYLE_BLCK_HDR_BG' => Tools::getValue('WPSTYLE_BLCK_HDR_BG', Configuration::get('WPSTYLE_BLCK_HDR_BG')),
			'WPSTYLE_BLCK_HDR_CLR' => Tools::getValue('WPSTYLE_BLCK_HDR_CLR', Configuration::get('WPSTYLE_BLCK_HDR_CLR')),
			'WPSTYLE_CAT_CL' => Tools::getValue('WPSTYLE_CAT_CL', Configuration::get('WPSTYLE_CAT_CL')),
			'WPSTYLE_CAT_CL2' => Tools::getValue('WPSTYLE_CAT_CL2', Configuration::get('WPSTYLE_CAT_CL2')),
			'WPSTYLE_CAT_CL3' => Tools::getValue('WPSTYLE_CAT_CL3', Configuration::get('WPSTYLE_CAT_CL3')),
			'WPSTYLE_CAT_CL4' => Tools::getValue('WPSTYLE_CAT_CL4', Configuration::get('WPSTYLE_CAT_CL4')),
			'WPSTYLE_CAT_CL5' => Tools::getValue('WPSTYLE_CAT_CL5', Configuration::get('WPSTYLE_CAT_CL5')),
			'WPSTYLE_CAT_CL6' => Tools::getValue('WPSTYLE_CAT_CL6', Configuration::get('WPSTYLE_CAT_CL6')),
			'WPSTYLE_CAT_CL7' => Tools::getValue('WPSTYLE_CAT_CL7', Configuration::get('WPSTYLE_CAT_CL7')),
			'WPSTYLE_MENU_TYPE' => Tools::getValue('WPSTYLE_MENU_TYPE', Configuration::get('WPSTYLE_MENU_TYPE')),
			'WPSTYLE_MENU_BG' => Tools::getValue('WPSTYLE_MENU_BG', Configuration::get('WPSTYLE_MENU_BG')),
			'WPSTYLE_MENU_BG4' => Tools::getValue('WPSTYLE_MENU_BG4', Configuration::get('WPSTYLE_MENU_BG4')),
			'WPSTYLE_MENU_BG5' => Tools::getValue('WPSTYLE_MENU_BG5', Configuration::get('WPSTYLE_MENU_BG5')),
			'WPSTYLE_MENU_BR1' => Tools::getValue('WPSTYLE_MENU_BR1', Configuration::get('WPSTYLE_MENU_BR1')),
			'WPSTYLE_MENU_BR2' => Tools::getValue('WPSTYLE_MENU_BR2', Configuration::get('WPSTYLE_MENU_BR2')),
			'WPSTYLE_LBL_1' => Tools::getValue('WPSTYLE_LBL_1', Configuration::get('WPSTYLE_LBL_1')),
			'WPSTYLE_LBLT_1' => Tools::getValue('WPSTYLE_LBLT_1', Configuration::get('WPSTYLE_LBLT_1')),
			'WPSTYLE_LBL_2' => Tools::getValue('WPSTYLE_LBL_2', Configuration::get('WPSTYLE_LBL_2')),
			'WPSTYLE_LBLT_2' => Tools::getValue('WPSTYLE_LBLT_2', Configuration::get('WPSTYLE_LBLT_2')),
			'WPSTYLE_STCK_GRD' => Tools::getValue('WPSTYLE_STCK_GRD', Configuration::get('WPSTYLE_STCK_GRD')),
			'WPSTYLE_HIDE_LEFT' => Tools::getValue('WPSTYLE_HIDE_LEFT', Configuration::get('WPSTYLE_HIDE_LEFT')),
			'WPSTYLE_FNC_BTN' => Tools::getValue('WPSTYLE_FNC_BTN', Configuration::get('WPSTYLE_FNC_BTN')),
			'WPSTYLE_BACKGROUND' => Tools::getValue('WPSTYLE_BACKGROUND', Configuration::get('WPSTYLE_BACKGROUND')),
			'WPSTYLE_BACKGROUND2' => Tools::getValue('WPSTYLE_BACKGROUND2', Configuration::get('WPSTYLE_BACKGROUND2')),
			'WPSTYLE_PATTERN' => Tools::getValue('WPSTYLE_PATTERN', Configuration::get('WPSTYLE_PATTERN')),
			);
	}


public function hookBackOfficeHeader($params)
{
	$this->context->controller->addCSS($this->_path.'views/css/admin.css');
}

public function hookDisplayHeader($params)
{
		$wpcssstamp = '?'.filemtime($this->cssFile);
		$theme_settings = array(
				'wp_clmn' => Configuration::get('WPSTYLE_COLUMN'),
				'wpstyleswitchercss' => _MODULE_DIR_.$this->name.'/views/css/wpstyleswitcher.css',
				'wp_google_link' => str_replace(array('http://','https://'), '', Configuration::get('WPSTYLE_GOOGLE_LINK')),
				'wp_google_link2' => str_replace(array('http://','https://'), '', Configuration::get('WPSTYLE_GOOGLE_LINK2')),
				'wpcssstamp' => $wpcssstamp
		);
			$wp_google_name = Configuration::get('WPSTYLE_GOOGLE_NAME');
			$wp_google_link = Configuration::get('WPSTYLE_GOOGLE_LINK');
			$wp_google_name2 = Configuration::get('WPSTYLE_GOOGLE_NAME2');
			$wp_google_link2 = Configuration::get('WPSTYLE_GOOGLE_LINK2');
			$wp_text_size = Configuration::get('WPSTYLE_TEXT_SIZE');
			$wp_text_size2 = Configuration::get('WPSTYLE_TEXT_SIZE2');
			$wp_text_size3 = Configuration::get('WPSTYLE_TEXT_SIZE3');
			$wp_menu_upr = Configuration::get('WPSTYLE_MENU_UPR');
			$wp_head_upr = Configuration::get('WPSTYLE_HEAD_UPR');
			$wp_btn = Configuration::get('WPSTYLE_BTN');
			$wp_btn_cl = Configuration::get('WPSTYLE_BTN_CL');
			$wp_btn_hvr = Configuration::get('WPSTYLE_BTN_HVR');
			$wp_btn_hvr_cl = Configuration::get('WPSTYLE_BTN_HVR_CL');
			$wp_excl_btn = Configuration::get('WPSTYLE_EXCL_BTN');
			$wp_excl_btn_cl = Configuration::get('WPSTYLE_EXCL_BTN_CL');
			$wp_excl_btn_hvr = Configuration::get('WPSTYLE_EXCL_BTN_HVR');
			$wp_excl_btn_hvr_cl = Configuration::get('WPSTYLE_EXCL_BTN_HVR_CL');
			$wp_cart_icon_prd = Configuration::get('WPSTYLE_CART_ICON_PRD');
			$wp_blck_hdr_bg = Configuration::get('WPSTYLE_BLCK_HDR_BG');
			$wp_menu_bg2 = Configuration::get('WPSTYLE_MENU_BG2');
			$wp_blck_hdr_clr = Configuration::get('WPSTYLE_BLCK_HDR_CLR');
			$wp_cat_cl = Configuration::get('WPSTYLE_CAT_CL');
			$wp_cat_cl2 = Configuration::get('WPSTYLE_CAT_CL2');
			$wp_cat_cl3 = Configuration::get('WPSTYLE_CAT_CL3');
			$wp_cat_cl4 = Configuration::get('WPSTYLE_CAT_CL4');
			$wp_cat_cl5 = Configuration::get('WPSTYLE_CAT_CL5');
			$wp_cat_cl6 = Configuration::get('WPSTYLE_CAT_CL6');
			$wp_cat_cl7 = Configuration::get('WPSTYLE_CAT_CL7');
			$wp_menu_type = Configuration::get('WPSTYLE_MENU_TYPE');
			$wp_menu_bg = Configuration::get('WPSTYLE_MENU_BG');
			$wp_menu_bg4 = Configuration::get('WPSTYLE_MENU_BG4');
			$wp_menu_bg5 = Configuration::get('WPSTYLE_MENU_BG5');
			$wp_menu_br1 = Configuration::get('WPSTYLE_MENU_BR1');
			$wp_menu_br2 = Configuration::get('WPSTYLE_MENU_BR2');
			$wp_lbl_1 = Configuration::get('WPSTYLE_LBL_1');
			$wp_lblt_1 = Configuration::get('WPSTYLE_LBLT_1');
			$wp_lbl_2 = Configuration::get('WPSTYLE_LBL_2');
			$wp_lblt_2 = Configuration::get('WPSTYLE_LBLT_2');
			$wp_stck_grd = Configuration::get('WPSTYLE_STCK_GRD');
			$wp_hide_left = Configuration::get('WPSTYLE_HIDE_LEFT');
			$wp_fnc_btn = Configuration::get('WPSTYLE_FNC_BTN');
			$wp_bg = Configuration::get('WPSTYLE_BACKGROUND');
			$wp_bg2 = Configuration::get('WPSTYLE_BACKGROUND2');
			$wp_ptn = Configuration::get('WPSTYLE_PATTERN');

			$this->smarty->assign(array(
			'wp_google_link' => $wp_google_link,
			'wp_google_name' => $wp_google_name,
			'wp_google_link2' => $wp_google_link2,
			'wp_google_name2' => $wp_google_name2,
			'wp_text_size' => $wp_text_size,
			'wp_text_size2' => $wp_text_size2,
			'wp_text_size3' => $wp_text_size3,
			'wp_head_upr' => $wp_head_upr,
			'wp_menu_upr' => $wp_menu_upr,
			'wp_btn' => $wp_btn,
			'wp_btn_cl' => $wp_btn_cl,
			'wp_btn_hvr' => $wp_btn_hvr,
			'wp_btn_hvr_cl' => $wp_btn_hvr_cl,
			'wp_excl_btn' => $wp_excl_btn,
			'wp_excl_btn_cl' => $wp_excl_btn_cl,
			'wp_excl_btn_hvr' => $wp_excl_btn_hvr,
			'wp_excl_btn_hvr_cl' => $wp_excl_btn_hvr_cl,
			'wp_cart_icon_prd' => $wp_cart_icon_prd,
			'wp_blck_hdr_bg' => $wp_blck_hdr_bg,
			'wp_blck_hdr_clr' => $wp_blck_hdr_clr,
			'wp_cat_cl' => $wp_cat_cl,
			'wp_cat_cl2' => $wp_cat_cl2,
			'wp_cat_cl3' => $wp_cat_cl3,
			'wp_cat_cl4' => $wp_cat_cl4,
			'wp_cat_cl5' => $wp_cat_cl5,
			'wp_cat_cl6' => $wp_cat_cl6,
			'wp_cat_cl7' => $wp_cat_cl7,
			'wp_menu_type' => $wp_menu_type,
			'wp_menu_bg' => $wp_menu_bg,
			'wp_menu_bg2' => $wp_menu_bg2,
			'wp_menu_bg4' => $wp_menu_bg4,
			'wp_menu_bg5' => $wp_menu_bg5,
			'wp_menu_br1' => $wp_menu_br1,
			'wp_menu_br2' => $wp_menu_br2,
			'wp_lbl_1' => $wp_lbl_1,
			'wp_lbl_2' => $wp_lbl_2,
			'wp_hide_left' => $wp_hide_left,
			'wp_fnc_btn' => $wp_fnc_btn,
			'wp_bg' => $wp_bg,
			'wp_ptn' => $wp_ptn
			));

			$this->context->smarty->assign('wptheme', $theme_settings);
		}

		private function _writeCss()
		{
			$wp_hide_left = Configuration::get('WPSTYLE_HIDE_LEFT');
			$wp_stck_grd =  Configuration::get("WPSTYLE_STCK_GRD");
			$wp_fnc_btn = Configuration::get('WPSTYLE_FNC_BTN');
			$wp_menu_type = Configuration::get('WPSTYLE_MENU_TYPE');
			$wp_head_upr = Configuration::get('WPSTYLE_HEAD_UPR');	
			$wp_menu_upr = Configuration::get('WPSTYLE_MENU_UPR');

			if ($wp_hide_left == 1)
				$wp_hide_left = 'inline';

			else if ($wp_hide_left == 0)
				$wp_hide_left = 'none';

			if ($wp_stck_grd == 1)
				$wp_stck_grd = 'inline';

			else if ($wp_stck_grd == 0)
				$wp_stck_grd = 'none';

			if ($wp_fnc_btn == 1)
				$wp_fnc_btn = 'inline-block';

			else if ($wp_fnc_btn == 0)
				$wp_fnc_btn = 'none';

			if ($wp_head_upr == 1)
				$wp_head_upr = 'uppercase';

			else if ($wp_head_upr == 0)
				$wp_head_upr = 'none';

			if ($wp_menu_upr == 1)
				$wp_menu_upr = 'uppercase';

			else if ($wp_menu_upr == 0)
				$wp_menu_upr = 'none';


			$css = '';
			$css .= '

#left_column {
	float: '.Configuration::get('WPSTYLE_COLUMN').';
}

.button.button-small, .button.button-medium, #layer_cart .layer_cart_cart .button-container .btn.continue {
	background: '.Configuration::get('WPSTYLE_BTN').';
	color: '.Configuration::get('WPSTYLE_BTN_CL').'!important;
}

#layer_cart .layer_cart_cart .button-container .btn.continue:hover, .button.button-small span:hover, .button.button-small:hover, .button.button-medium:hover {
	background: '.Configuration::get('WPSTYLE_BTN_HVR').';
	color: '.Configuration::get('WPSTYLE_BTN_HVR_CL').'!important;
}

#wpbxslider-wrap .btn-default, .cart_block .cart-buttons a#button_order_cart, .box-info-product .exclusive, .button.ajax_add_to_cart_button span, #cart_block #cart-buttons a#button_order_cart, input.exclusive_mini, input.exclusive_small, input.exclusive, input.exclusive_large, input.exclusive_mini_disabled, input.exclusive_small_disabled, input.exclusive_disabled, input.exclusive_large_disabled, a.exclusive_mini, a.exclusive_small, a.exclusive, a.exclusive_large, span.exclusive_mini, span.exclusive_small, span.exclusive, span.exclusive_large, span.exclusive_large_disabled, #primary_block p.buttons_bottom_block input, #new_comment_form button, .button.button-medium.exclusive {
	background-color: '.Configuration::get('WPSTYLE_EXCL_BTN').';
	color: '.Configuration::get('WPSTYLE_EXCL_BTN_CL').'!important;
}

#wpbxslider-wrap .btn-default:hover, .cart_block .cart-buttons a#button_order_cart:hover span, .cart_block .cart-buttons a#button_order_cart:hover, #wpbxslider-wrap .btn-default:hover, #layer_cart .layer_cart_cart .button-container .btn:hover, .button.button-medium.exclusive:hover, .box-info-product .exclusive:hover, .button.ajax_add_to_cart_button span:hover   {
	background-color: '.Configuration::get('WPSTYLE_EXCL_BTN_HVR').';
	color: '.Configuration::get('WPSTYLE_EXCL_BTN_HVR_CL').'!important;
}

#layer_cart .layer_cart_cart .button-container .btn {
	background-color: '.Configuration::get('WPSTYLE_EXCL_BTN').';
	color: '.Configuration::get('WPSTYLE_EXCL_BTN_CL').'!important;
}


/* Exclusive block headers color  */
#special_block_right p.title_block {
	background: '.Configuration::get('WPSTYLE_BLCK_HDR_BG').';
}

#special_block_right p.title_block a {
	color: '.Configuration::get('WPSTYLE_BLCK_HDR_CLR').';
}


/* menu */
#block_top_menu {
	background: '.Configuration::get('WPSTYLE_MENU_BG').';
	background: -moz-linear-gradient(top, '.Configuration::get('WPSTYLE_MENU_BG').' 0%, '.Configuration::get('WPSTYLE_MENU_BG2').' 56%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.Configuration::get('WPSTYLE_MENU_BG').'), color-stop(56%,'.Configuration::get('WPSTYLE_MENU_BG2').'));
	background: -webkit-linear-gradient(top, '.Configuration::get('WPSTYLE_MENU_BG').' 0%, '.Configuration::get('WPSTYLE_MENU_BG2').' 56%);
	background: -o-linear-gradient(top, '.Configuration::get('WPSTYLE_MENU_BG').' 0%, '.Configuration::get('WPSTYLE_MENU_BG2').' 56%);
	background: -ms-linear-gradient(top, '.Configuration::get('WPSTYLE_MENU_BG').' 0%, '.Configuration::get('WPSTYLE_MENU_BG2').' 56%);
	background: linear-gradient(to bottom, '.Configuration::get('WPSTYLE_MENU_BG').' 0%, '.Configuration::get('WPSTYLE_MENU_BG2').' 56%);
}
.sf-menu > li {
	border-left: 1px solid '.Configuration::get('WPSTYLE_MENU_BR1').';
	border-right: 1px solid '.Configuration::get('WPSTYLE_MENU_BR2').';
}

.sf-menu > li > a, .sf-menu > li > a.sf-with-ul:before {
	color: '.Configuration::get('WPSTYLE_CAT_CL').';
}

/* 1st level menu items font size */
.sf-menu > li > a {
	font-size: '.Configuration::get('WPSTYLE_TEXT_SIZE2').'px;	
	text-transform: '.$wp_menu_upr.';
}

/* menu active background */
.sf-menu > li.sfHover, .sf-menu > li.sfHoverForce, .sf-menu > li > a:hover, .sf-menu > li > a:active {
	background: '.Configuration::get('WPSTYLE_MENU_BG4').';
}

/* menu active font color */
.sf-menu > li > a:hover, .sf-menu > li.sfHover > a, .sf-menu > li.sfHoverForce > a, .sf-menu > li > a.sf-with-ul:hover:before, .sf-menu > li.sfHover > a.sf-with-ul:before {
	color: '.Configuration::get('WPSTYLE_CAT_CL5').';
}


/* responsive menu background color */
@media (max-width: 767px) {
.cat-title {
 background: '.Configuration::get('WPSTYLE_MENU_BG').';
 border: 1px solid '.Configuration::get('WPSTYLE_MENU_BG').';
 color: '.Configuration::get('WPSTYLE_CAT_CL').';
}
}

/* labels */
#featured-products_block_center ul li span.new, #special_block ul li span.new, ul#product_list li .new, .new-label {
	background: '.Configuration::get('WPSTYLE_LBL_1').';
	color: '.Configuration::get('WPSTYLE_LBLT_1').';
}

.product_list.grid .price-percent-reduction, #featured-products_block_center .reduction-percent, #new-products_block .reduction-percent, #special_block .reduction-percent {
	background: '.Configuration::get('WPSTYLE_LBL_2').';
	color: '.Configuration::get('WPSTYLE_LBLT_2').';
}

ul.product_list.grid .availability, #featured-products_block_center .availability, #new-products_block .availability, #special_block .availability {
	display: '.$wp_stck_grd.';
}

/* Functional buttons */
.functional-buttons, button.bt_compare  {
	display: '.$wp_fnc_btn.'!important;
}

/* Google fonts */
body#product h1, .footer-container #footer h4, #index #special_block h3, #index #new-products_block h3, #index #featured-products_block_center h3, .page-heading,
.our_price_display, #wpbxslider-wrap .wpbxslider-description h2, .ei-title h2,
#product h3.page-product-heading, .page-product-box li.section-line a,
.block .title_block, .block h4,
#block_top_menu .cat-title, #block_top_menu .sf-menu > li > a {
 font-family: '.Configuration::get('WPSTYLE_GOOGLE_NAME').';
}

/* headings */
body#product h1, #index #special_block h3, #index #new-products_block h3, #index #featured-products_block_center h3, .page-heading,
#wpbxslider-wrap .wpbxslider-description h2, .ei-title h2, 
#product h3.page-product-heading, .page-product-box li.section-line a {
	font-size: '.Configuration::get('WPSTYLE_TEXT_SIZE3').'px;
	text-transform: '.$wp_head_upr.';
}

body {
 font-family: '.Configuration::get('WPSTYLE_GOOGLE_NAME2').';
 font-size: '.Configuration::get('WPSTYLE_TEXT_SIZE').'px!important;
}


@media (max-width: 767px) {
#left_column {
	display: '.$wp_hide_left.';
  }
}

@media (min-width: 1200px) {
body {
	background: '.Configuration::get('WPSTYLE_BACKGROUND').';
	background: url("'._MODULE_DIR_.$this->name.'/views/img/patterns/'.Configuration::get('WPSTYLE_PATTERN').'"), -moz-linear-gradient(top, '.Configuration::get('WPSTYLE_BACKGROUND').' 0%, '.Configuration::get('WPSTYLE_BACKGROUND2').' 64%);
	background: url("'._MODULE_DIR_.$this->name.'/views/img/patterns/'.Configuration::get('WPSTYLE_PATTERN').'"), -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.Configuration::get('WPSTYLE_BACKGROUND').'), color-stop(64%,'.Configuration::get('WPSTYLE_BACKGROUND2').'));
	background: url("'._MODULE_DIR_.$this->name.'/views/img/patterns/'.Configuration::get('WPSTYLE_PATTERN').'"), -webkit-linear-gradient(top,  '.Configuration::get('WPSTYLE_BACKGROUND').' 0%,'.Configuration::get('WPSTYLE_BACKGROUND2').' 64%);
	background: url("'._MODULE_DIR_.$this->name.'/views/img/patterns/'.Configuration::get('WPSTYLE_PATTERN').'"), -o-linear-gradient(top,  '.Configuration::get('WPSTYLE_BACKGROUND').' 0%,'.Configuration::get('WPSTYLE_BACKGROUND2').' 64%);
	background: url("'._MODULE_DIR_.$this->name.'/views/img/patterns/'.Configuration::get('WPSTYLE_PATTERN').'"), -ms-linear-gradient(top,  '.Configuration::get('WPSTYLE_BACKGROUND').' 0%,'.Configuration::get('WPSTYLE_BACKGROUND2').' 64%);
	background: url("'._MODULE_DIR_.$this->name.'/views/img/patterns/'.Configuration::get('WPSTYLE_PATTERN').'"), linear-gradient(to bottom,  '.Configuration::get('WPSTYLE_BACKGROUND').' 0%,'.Configuration::get('WPSTYLE_BACKGROUND2').' 64%);
}
}

';

if ($wp_menu_type == 'dropdown')
{
	$css .= '

@media (min-width: 767px) {
#block_top_menu .submenu-container { width: auto}  
.sf-menu a {
	display: block;
	position: relative;
    font-weight: normal;       
    padding: 10px 20px;
    text-decoration: none;
}

#block_top_menu a {
  -webkit-transition: none;
    -moz-transition: none;
    -o-transition: none;
    transition: none;
}

.sf-menu li li li a {
    padding: 10px 20px;
}

.sf-menu > li > ul > li {
width: auto;
padding: 0;
}
            
.sf-menu li {
position: relative;
float: left;
text-transform: uppercase
}

.sf-menu > li {	
	float: left; 	  
}
                
.sf-menu > li > ul {
padding: 0 ;
}

.sf-menu li li li ul, ul.sf-menu li ul li a {
width: 200px;
font-weight: normal
}

/* fix 4th level item padding from left */
.sf-menu li li li ul {padding-left: 0}

li.category-thumbnail {display: none} 

.sf-menu li li ul {
display: none!important;
position: absolute;
}

.sf-menu li ul {
border: 1px solid #ddd;
}

.sf-menu li li:hover > ul {
display: block!important;
}

.sf-menu li li li a {  
display: block;
line-height: 21px
}

.sf-menu li ul {
	padding-top: 0;
	padding-bottom: 0;
}

.sf-menu > li > ul > li ul {
	margin-top: -1px
}

.sf-menu > li > ul > li > a {
	font-weight: 600
}

.sf-menu > li > ul > li > a.sf-with-ul {
	position: relative
}
.sf-menu > li > ul > li a.sf-with-ul:before {
	content: "\f105";
    font-family: "FontAwesome";
    font-size: 16px;
    font-weight: normal;
    line-height: 14px;
    padding-left: 4px;
    position: absolute;
    right: 10px;
    top: 10px;
    vertical-align: 10px;
}
}

/* Dropdown font color */
.sf-menu > li > ul > li > a, .sf-menu li li li a {
	color: '.Configuration::get('WPSTYLE_CAT_CL3').';
}
 
/* Dropdown font color on hover */ 
.sf-menu > li > ul > li > a:hover, .sf-menu li li li a:hover {
	color: '.Configuration::get('WPSTYLE_CAT_CL2').';
}

/* Dropdown background on hover */
.sf-menu li li li a:hover, .sf-menu li li a:hover {
	background: '.Configuration::get('WPSTYLE_MENU_BG5').';
}

.sf-menu li li li ul { width: auto; padding-left: 0}

';
}

else if ($wp_menu_type == 'megamenu')
{
	$css .= '

/* Megamenu headings text color */
.sf-menu > li > ul > li > a {
	color: '.Configuration::get('WPSTYLE_CAT_CL4').';
}

/* Megamenu columns text color */
.sf-menu li li li a {
	color: '.Configuration::get('WPSTYLE_CAT_CL6').';	
}

/* Megamenu subitems text on hover */
.sf-menu > li > ul > li > a:hover, .sf-menu li li li a:hover  {
	color: '.Configuration::get('WPSTYLE_CAT_CL7').';
}


';
}
		$write_css = fopen($this->cssFile, 'w') or die('can\'t open file "'.$this->cssFile.'"');
		fwrite($write_css, $css);
		fclose($write_css);
		}

}

?>