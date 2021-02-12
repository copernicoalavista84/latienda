<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruCartMapper extends CartsGuruMapperAbstract
{
    /**
     * (non-PHPdoc)
     * @see CartsGuruMapperAbstract::mappObject()
     */
    public function mappObject($cart, $params, $mapAccount = true, $customFields = true)
    {
        $account_mapped = null;
        // Check if we have customer data
        if (array_key_exists('customer', $params)) {
            $account_mapped = $params['customer'];
        }

        $customerGroups = Customer::getGroupsStatic((int) $cart->id_customer);

        if (defined('CARTSGURU_ONLY_GROUP') && !in_array(CARTSGURU_ONLY_GROUP, $customerGroups)) {
            return null;
        }

        $cart_items = $cart->getProducts();
        $items = array();
        $product_mapper = new CartsGuruProductMapper($this->id_lang, $this->id_shop_group, $this->id_shop);

        foreach ($cart_items as $cart_item) {
            $product = new Product($cart_item['id_product'], false, $this->id_lang);
            $pmap_params = array('id_product_attribute' => (int)$cart_item['id_product_attribute']);
            $product_mapped = $product_mapper->create($product, $pmap_params);
            $product_mapped['quantity'] = (int) $cart_item['cart_quantity'];
            $product_mapped['totalET'] = (float) $cart_item['total'];
            $product_mapped['totalATI'] = (float) $cart_item['total_wt'];
            $items[] = $product_mapped;
        }

        if (!$account_mapped && $mapAccount) {
            $customer = new Customer((int) $cart->id_customer);
            $account_mapper = new CartsGuruAccountMapper($this->id_lang, $this->id_shop_group, $this->id_shop);
            $account_mapped = $account_mapper->create($customer);
        }

        $currency = new Currency((int)$cart->id_currency);

        $cart_mapped = array(
            'id' => (string) $cart->id,
            'ip' =>  isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ?
                    $_SERVER["HTTP_CF_CONNECTING_IP"] : Tools::getRemoteAddr(), // Required for the API lock IP Address
            'creationDate' => $this->formatDate($cart->date_add), // Date of the order as string in json format
            'totalET' => (float) $cart->getOrderTotal(false, Cart::ONLY_PRODUCTS), // without taxes & shipping
            'totalATI' => (float) $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS), // with taxes only product
            'currency' => (string) $currency->iso_code,
            'recoverUrl' => (version_compare(_PS_VERSION_, '1.5.0', '>=')?$this->getCartRecoverUrl($cart):''),
            'items' => $items
        );

        if ($mapAccount) {
            $cart_mapped = array_merge($cart_mapped, $account_mapped);
        }

        // If accound id is mandatory
        if ((!array_key_exists('accountId', $cart_mapped) || $cart_mapped['accountId'] == '') && $mapAccount) {
            return null;
        }

        // Add Custom fields
        if ($customFields) {
            $cart_mapped['custom'] = $this->getCustomFields($cart, $cart_mapped, $customerGroups);
        }

        return $cart_mapped;
    }

    /**
    * Get custom fields of cart, can be overrided for custom use
    *
    * @param $cart
    * @param $cart_mapped
    * @param $customerGroups
    * @return array
    */
    protected function getCustomFields($cart, $cart_mapped, $customerGroups)
    {
        $context = Context::getContext();

        return array(
            'language' => $context->language->iso_code,
            'customerGroup' => implode(',', CartsGuruHelper::getGroupNames($customerGroups, $context->language)),
            'isNewCustomer' => CartsGuruHelper::isNewCustomer($cart_mapped['email'])
        );
    }

    public function getCartRecoverUrl($cart, $step = 1)
    {
        $params = array(
          'cart_id' => (int)$cart->id,
          'cart_token' => md5(_COOKIE_KEY_.'recover_cart_'.(int)$cart->id)
        );

        if ($step > 1) {
            $params['step'] = $step;
        }

        return $this->link->getModuleLink('cartsguru', 'cartrecover', $params);
    }
}
