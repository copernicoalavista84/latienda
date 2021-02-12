<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruAdminModuleFrontController extends ModuleFrontController
{
    public function display()
    {
        $saved_auth_key = Configuration::get('CARTSG_API_AUTH_KEY');
        $cartsguru_auth_key = Tools::getValue('cartsguru_auth_key');
        $cartsguru_admin_action = Tools::getValue('cartsguru_admin_action');
        $cartsguru_admin_data = Tools::getValue('cartsguru_admin_data');

        //Check key
        if (empty($saved_auth_key) || empty($cartsguru_auth_key) || $saved_auth_key !== $cartsguru_auth_key) {
            die;
        }

        //Get data
        $data = !empty($cartsguru_admin_data) ? Tools::jsonDecode(stripcslashes($cartsguru_admin_data), true) : null;

        $result = null;
        switch ($cartsguru_admin_action) {
            case 'toggleFeatures':
                $result = CartsGuruAdminFrontController::toggleFeatures($data);
                break;
            case 'displayConfig':
                $result = CartsGuruAdminFrontController::getConfig();
                break;
            case 'getCoupons':
                $result = CartsGuruCartRuleManager::getList();
                break;
            case 'createCoupons':
                $result = CartsGuruCartRuleManager::createCoupons($data);
                break;
            case 'deleteCoupons':
                $result = CartsGuruCartRuleManager::deleteCoupons($data);
                break;
        }

        //Send result
        header('Content-Type: application/json; charset=utf-8');
        if ($result) {
            echo Tools::jsonEncode($result);
        }
        exit;
    }
}
