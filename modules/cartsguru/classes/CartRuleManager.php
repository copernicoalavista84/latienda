<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruCartRuleManager
{
    public static function getMapper()
    {
        return new CartsGuruCartRuleMapper((int) Configuration::get('PS_LANG_DEFAULT'));
    }

    public static function getList($params = array())
    {
        $items = null;
        $result = array();
        $mapper = self::getMapper();

        if (CartsGuruHelper::isCartRuleSupported()) {
            $items = self::getCartRules($params);
        } else {
            $items = self::getDiscounts($params);
        }

        foreach ($items as $item) {
            $result[] = $mapper->create($item);
        }

        return $result;
    }

    private static function getCartRules($params)
    {
        $cartRules = array();
        $id_shop = Context::getContext()->shop->id;

        $sql = 'SELECT cr.id_cart_rule as id FROM  `' . _DB_PREFIX_  . 'cart_rule` cr ' .
             'LEFT OUTER JOIN `' . _DB_PREFIX_  . 'cart_rule_shop` crs on cr.id_cart_rule = crs.id_cart_rule ' .
             'WHERE cr.active = 1 AND (cr.id_customer is null OR cr.id_customer = 0) AND cr.reduction_amount = 0 ' .
             'AND (shop_restriction = 0 OR crs.id_shop = '. (int)$id_shop .') ';

        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $item = new CartRule($row['id']);
                $cartRules[] = $item;
            }
        }

        return $cartRules;
    }

    private static function getDiscounts($params)
    {
        $discounts = array();

        /* Discount types :
         1 => 'percentage_discount',
         2 => 'amount_discount',
         3 => 'free_shipping'
        */
        $sql = 'SELECT id_discount as id FROM  `' . _DB_PREFIX_  . 'discount` ' .
             'WHERE active = 1 AND (id_customer is null OR id_customer = 0) AND id_discount_type IN (1,3)';

        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                $item = new Discount($row['id']);
                $discounts[] = $item;
            }
        }

        return $discounts;
    }

    public static function createCoupons($data)
    {
        if (CartsGuruHelper::isCartRuleSupported()) {
            return self::createCartRules($data);
        } else {
            return self::createDiscounts($data);
        }
    }

    private static function createCartRules($data)
    {
        $created = 0;
        foreach ($data['coupons'] as $coupon) {
            $from = new DateTime($coupon['sendingStartDate'], new DateTimeZone('UTC'));
            $to = new DateTime($coupon['expirationDate'], new DateTimeZone('UTC'));

            $cartrule = new CartRule();
            $cartrule->date_from = $from->format('Y-m-d H:i:s');
            $cartrule->date_to = $to->format('Y-m-d H:i:s');
            $cartrule->description = 'Carts Guru generated rule';
            $cartrule->quantity = 100000;
            $cartrule->quantity_per_user = 1;
            $cartrule->priority = 1;
            $cartrule->partial_use = false;
            $cartrule->code = $coupon['code'];

            if ($data['freeShipping']) {
                $cartrule->free_shipping = true;
            }
            if ($data['reductionPercent']) {
                $cartrule->reduction_percent  = $data['reductionPercent'];
            }

            $cartrule->cart_rule_restriction = true; //Coupon is not combinable
            $cartrule->shop_restriction = true;

            $cartrule->highlight = false;
            $cartrule->active = true;

            /* Lang fields */
            $cartrule->name = array(
               (int) Configuration::get('PS_LANG_DEFAULT') => $data['title']
            );

            if ($cartrule->add()) {
                //Add shop restriciton
                $shop_id = Context::getContext()->shop->id;
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_shop` (`id_cart_rule`,`id_shop`) VALUES ('. (int)$cartrule->id .','. (int)$shop_id . ')  ');

                $created += 1;
            }
        }
        return $created === sizeof($data['coupons']);
    }

    private static function createDiscounts($data)
    {
        $created = 0;
        foreach ($data['coupons'] as $coupon) {
            $from = new DateTime($coupon['sendingStartDate'], new DateTimeZone('UTC'));
            $to = new DateTime($coupon['expirationDate'], new DateTimeZone('UTC'));

            $discount = new Discount();
            $discount->name = $coupon['code'];
            $discount->date_from = $from->format('Y-m-d H:i:s');
            $discount->date_to = $to->format('Y-m-d H:i:s');
            $discount->quantity = 100000;
            $discount->quantity_per_user = 1;

            /* Discount types :
             1 => 'percentage_discount',
             2 => 'amount_discount',
             3 => 'free_shipping'
            */
            if ($data['freeShipping']) {
                $discount->id_discount_type = 3;
                $discount->value = 0;
            }
            if ($data['reductionPercent']) {
                $discount->id_discount_type = 1;
                $discount->value = $data['reductionPercent'];
            }

            $discount->cumulable = false;
            $discount->cart_display = false;
            $discount->active = true;

            /* Lang fields */
            $discount->description = array(
               (int) Configuration::get('PS_LANG_DEFAULT') => $data['title']
            );

            if ($discount->add()) {
                $created += 1;
            }
        }
        return $created === sizeof($data['coupons']);
    }

    public static function deleteCoupons($params)
    {
        $codes = array();

        foreach ($params['couponCodes'] as $code) {
            $codes[] = "'" . pSQL($code) . "'";
        }

        if (CartsGuruHelper::isCartRuleSupported()) {
            return self::deleteCartRules($codes);
        } else {
            return self::deleteDiscounts($codes);
        }
    }

    private static function deleteCartRules($codes)
    {
        $sql = 'DELETE FROM '._DB_PREFIX_.'cart_rule WHERE code IN (' . join($codes, ',') . ')';
        return Db::getInstance()->execute($sql);
    }

    private static function deleteDiscounts($codes)
    {
        $sql = 'DELETE FROM '._DB_PREFIX_.'discount WHERE name IN (' . join($codes, ',') . ')';
        return Db::getInstance()->execute($sql);
    }
}
