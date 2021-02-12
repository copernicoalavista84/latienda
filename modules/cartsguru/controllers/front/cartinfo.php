<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruCartinfoModuleFrontController extends ModuleFrontController
{
    public function display()
    {
        $cart = $this->context->cart;
        // If empty cart return
        if (!$cart || !$cart->id) {
            die;
        }

        // Create mapper to get the recoverUrl
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $id_shop_group = (int) version_compare(_PS_VERSION_, '1.5.0', '<') ? 0 : $this->context->cart->id_shop_group;
        $id_shop = (int) version_compare(_PS_VERSION_, '1.5.0', '<') ? 0 : $this->context->cart->id_shop;
        $cartMapper = new CartsGuruCartMapper($id_lang_default, $id_shop_group, $id_shop);

        header('Content-Type: application/json');
        echo Tools::jsonEncode(
            array(
                'cartId' => $cart->id,
                'recoverUrl' => $cartMapper->getCartRecoverUrl($cart)
            )
        );
    }
}
