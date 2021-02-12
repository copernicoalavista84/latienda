<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');

if (!class_exists('Configuration')) {
    include(dirname(__FILE__).'/../../../classes/Configuration.php');
}

if (!class_exists('Tools')) {
    include(dirname(__FILE__).'/../../../classes/Tools.php');
}

if (!class_exists('Context')) {
    include(dirname(__FILE__).'/../backward_compatibility/Context.php');
}

$context = Context::getContext();

$cart = $context->cart;
// If empty cart return
if (!$cart || !$cart->id) {
    die;
}

// Create mapper to get the recoverUrl
$id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
$id_shop_group = (int) version_compare(_PS_VERSION_, '1.5.0', '<') ? 0 : $context->cart->id_shop_group;
$id_shop = (int) version_compare(_PS_VERSION_, '1.5.0', '<') ? 0 : $context->cart->id_shop;
$cartMapper = new CartsGuruCartMapper($id_lang_default, $id_shop_group, $id_shop);

header('Content-Type: application/json');
echo Tools::jsonEncode(
    array(
        'cartId' => $cart->id,
        'recoverUrl' => $cartMapper->getCartRecoverUrl($cart)
    )
);
