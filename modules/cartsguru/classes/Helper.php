<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruHelper
{
    /**
    * Check is multi store is supported
    */
    public static function isMultiStoreSupported()
    {
        return version_compare(_PS_VERSION_, '1.5.0', '>=');
    }

    /**
    * Check is cart rule are supported (else need use Discount)
    */
    public static function isCartRuleSupported()
    {
        return version_compare(_PS_VERSION_, '1.5.0.1', '>=');
    }

    /**
  	* Clear smarty cache folders
   	*/
    public static function clearSmartyCache()
  	{
        if (method_exists('Hook', 'exec')) {
            Hook::exec('actionAdminPerformanceControllerAfter', array('controller' => null));
        }
  	}

    /**
    * Check count of orders to know if it's a new customer
    *
    * @param string $email Customer email
    * @param boolean $excludeOne Exclude current order, so still a new customer just after first order
    *
    * @return boolean Returns if it's a new customer
    */
    public static function isNewCustomer($email, $excludeOne = false)
    {
        $sql = "SELECT o.id_order AS id FROM " . _DB_PREFIX_ . "orders o JOIN " . _DB_PREFIX_ . "customer c ON o.id_customer = c.id_customer WHERE c.email = '" . pSQL($email) . "'";

        // Need filter on the good shop
        if (self::isMultiStoreSupported()) {
            $id_shop = (int)Context::getContext()->shop->id;

            $sql .= ' and o.id_shop = ' . (int)$id_shop;
        }
        $orders = Db::getInstance()->ExecuteS($sql);

        return $excludeOne ? count($orders) <= 1 : count($orders) === 0;
    }

    public static function getGroupNames($customerGroups, $language)
    {
        $customerGroupNames = array();
        foreach ($customerGroups as $id) {
            $group = new Group((int)$id);
            if ($group) {
                array_push($customerGroupNames, $group->name[(int)$language->id]);
            }
        }
        return $customerGroupNames;
    }
}
