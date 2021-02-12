<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruCartRuleMapper extends CartsGuruMapperAbstract
{
    /**
     * (non-PHPdoc)
     * @see CartsGuruMapperAbstract::mappObject()
     */
    public function mappObject($item, $params)
    {
        if (CartsGuruHelper::isCartRuleSupported()) {
            return $this->mapCartRule($item);
        } else {
            return $this->mapDiscount($item);
        }
    }

    private function mapCartRule($cartRule)
    {
        //Pre parse dates (can not do all inline due to php restriciton)
        $date_to = new DateTime($cartRule->date_to, new DateTimeZone('UTC'));
        $date_from = new DateTime($cartRule->date_from, new DateTimeZone('UTC'));

        $mapped = array(
            'title' => $cartRule->name[$this->id_lang],
            'code' => (string) $cartRule->code,
            'sendingStartDate' =>  $date_from->format('c'),
            'expirationDate' => $date_to->format('c'),
            'freeShipping' => (boolean)$cartRule->free_shipping,
            'reductionPercent' => (float)$cartRule->reduction_percent
        );

        return $mapped;
    }

    private function mapDiscount($discount)
    {
        //Pre parse dates (can not do all inline due to php restriciton)
        $date_to = new DateTime($discount->date_to, new DateTimeZone('UTC'));
        $date_from = new DateTime($discount->date_from, new DateTimeZone('UTC'));

        $mapped = array(
          'title' => $discount->description[$this->id_lang],
          'code' => $discount->name,
          'sendingStartDate' =>  $date_from->format('c'),
          'expirationDate' => $date_to->format('c')
        );

        switch ($discount->id_discount_type) {
            //percentage_discount
            case 1:
                $mapped['reductionPercent'] = (float)$discount->value;
                break;
            //amount_discount
            case 2:
                break;
            //free_shipping
            case 3:
                $mapped['freeShipping'] = true;
                break;
        }

        return $mapped;
    }
}
