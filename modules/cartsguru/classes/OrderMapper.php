<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruOrderMapper extends CartsGuruMapperAbstract
{
    /**
     * @see CartsGuruMapperAbstract::mappObject()
     */
    public function mappObject($order, $params)
    {
        $customerGroups = Customer::getGroupsStatic((int) $order->id_customer);
        if (defined('CARTSGURU_ONLY_GROUP') && !in_array(CARTSGURU_ONLY_GROUP, $customerGroups)) {
            return null;
        }

        $order_items = $order->getProducts();
        $items = array();
        $product_mapper = new CartsGuruProductMapper($this->id_lang, $this->id_shop_group, $this->id_shop);
        foreach ($order_items as $order_item) {
            $product = new Product($order_item['product_id'], false, $this->id_lang);
            $pmap_params = array('id_product_attribute' => $order_item['product_attribute_id']);
            $product_mapped = $product_mapper->create($product, $pmap_params);
            $product_mapped['label'] = $order_item['product_name'];
            $product_mapped['quantity'] = (int) $order_item['product_quantity'];
            //Even if we have backward compatibility, be sure it will continue to work on > 1.6
            if (version_compare(_PS_VERSION_, '1.5.0', '<')) {
                $product_mapped['totalET'] = (float) $order_item['total_price'];
                $product_mapped['totalATI'] = (float) $order_item['total_wt'];
            } else {
                $product_mapped['totalET'] = (float) $order_item['total_price_tax_excl'];
                $product_mapped['totalATI'] = (float) $order_item['total_price_tax_incl'];
            }
            $items[] = $product_mapped;
        }

        $customer = new Customer((int) $order->id_customer);
        $account_mapper = new CartsGuruAccountMapper($this->id_lang, $this->id_shop_group, $this->id_shop);
        $account_mapped = $account_mapper->create($customer, $params);
        $status_cg_name = 'Undefined';
        $order_state_id = 0;
        if (version_compare(_PS_VERSION_, '1.5.0', '<')) {
            if (isset($params['newOrderStatus'])) {
                $order_state_id = (int) $params['newOrderStatus']->id;
            } else {
                $order_state_id = (int) $order->getCurrentState();
            }
        } else {
            $order_state_id = (int) $order->current_state;
        }
        if ((int) $order_state_id) {
            $current_status = new OrderState((int) $order_state_id, $this->id_lang);
            $status_cg_name = $current_status->name;
        }

        $currency = new Currency((int)$order->id_currency);
        $discounts = $this->getDiscounts($order);

        $productDiscountTotalET = 0;
        foreach ($discounts as $discount) {
            if (isset($discount['totalET']) && !$discount['freeShipping']) {
                $productDiscountTotalET += $discount['totalET'];
            }
        }

        $order_mapped = array(
            'id' => (string) $order->id,
            'cartId' => (string) $order->id_cart,
            'state' => $status_cg_name,
            'creationDate' => $this->formatDate($order->date_add), // Date of the order as string in json format
            'totalET' => (float) $order->getTotalProductsWithoutTaxes() - $productDiscountTotalET,  // Amount excluded taxes and excluded shipping - proudct discounts
            'totalATI' => (float) $order->total_paid, // Total ttc
            'paymentMethod' => (string)$order->payment,
            'currency' => (string) $currency->iso_code,
            'discounts' => $discounts,
            'items' => $items
        );

        if (!empty($order->source)) {
            $order_mapped['source'] = $order->source;
        }

        $order_mapped = array_merge($order_mapped, $account_mapped);

        // Add Custom fields
        $order_mapped['custom'] = $this->getCustomFields($order, $order_mapped, $customerGroups);

        return $order_mapped;
    }

    protected function getDiscounts($order)
    {
        $items = array();

        if (version_compare(_PS_VERSION_, '1.5.0', '<')) {
            $orderDiscounts = $order->getDiscounts();
            foreach ($orderDiscounts as $orderDiscount) {
                //Array ( [id_order_discount] => 1 [id_order] => 24 [id_discount] => 1 [name] => PS-14 [value] => 350.00 )
                $discount = new Discount($orderDiscount['id_discount']);
                $items[] = array(
                  'totalATI' => (float)$orderDiscount['value'],
                  'freeShipping' => $discount->id_discount_type == 3, //=> 'free_shipping',
                  'code' => $orderDiscount['name']
                );
            }
        } else {
            $orderCartRules = $order->getCartRules();
            foreach ($orderCartRules as $orderCartRule) {
                //$cartRule Array ( [id_order_cart_rule] => 1 [id_order] => 52 [id_cart_rule] => 6 [id_order_invoice] => 0 [name] => Demo [value] => 10.02 [value_tax_excl] => 8.35 [free_shipping] => 0 )
                $cartRule = new CartRule($orderCartRule['id_cart_rule']);
                if (Validate::isLoadedObject($cartRule)) {
                    $items[] = array(
                        'totalET' => (float)$orderCartRule['value_tax_excl'],
                        'totalATI' => (float)$orderCartRule['value'],
                        'freeShipping' => (boolean)$orderCartRule['free_shipping'],
                        'code' => $cartRule->code
                    );
                }
            }
        }

        return $items;
    }

    /**
    * Get custom fields of order, can be overrided for custom use
    *
    * @param $order
    * @param $order_mapped
    * @param $customerGroups
    * @return array
    */
    protected function getCustomFields($order, $order_mapped, $customerGroups)
    {
        $context = Context::getContext();

        return array(
            'language' => $context->language->iso_code,
            'customerGroup' => implode(',', CartsGuruHelper::getGroupNames($customerGroups, $context->language)),
            'isNewCustomer' => CartsGuruHelper::isNewCustomer($order_mapped['email'], true)
        );
    }
}
