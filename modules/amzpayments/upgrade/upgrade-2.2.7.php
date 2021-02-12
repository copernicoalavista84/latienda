<?php
/**
 * 2013-2017 Amazon Advanced Payment APIs Modul
 *
 * for Support please visit www.patworx.de
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
 *  @author    patworx multimedia GmbH <service@patworx.de>
 *  @copyright 2013-2017 patworx multimedia GmbH
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_2_7($module)
{
    Configuration::updateValue('AMZ_SHOW_IN_CART_POPUP', true);
    Configuration::updateValue('AMZ_SHOW_REGISTRATION_PAGE', true);
    Configuration::updateValue('AMZ_BUTTON_ENHANCEMENT_CART', false);
    Configuration::updateValue('AMZ_BUTTON_ENHANCEMENT_MINI_CART', false);
    Configuration::updateValue('AMZ_FORCE_NAME_COMPLETION', false);
    Configuration::updateValue('AMZ_SHOW_AS_PAYMENT_METHOD', true);
    $module->registerHook('actionCarrierUpdate');
    return true;
}
