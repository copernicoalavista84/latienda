<?php
/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

class CartsGuruProductMapper extends CartsGuruMapperAbstract
{
    public function getImageSizeName()
    {
        if (defined('CARTSGURU_IMAGE_SIZE')) {
            return CARTSGURU_IMAGE_SIZE;
        }

        //since plug
        $type = Configuration::get('CARTSG_IMAGE_TYPE');

        return $type ? $type : 'cartsguru';
    }

    /**
     * (non-PHPdoc)
     * @see CartsGuruMapperAbstract::mappObject()
     */
    public function mappObject($product, $params)
    {
        $id_product_attribute = 0;
        if (isset($params['id_product_attribute'])) {
            $id_product_attribute = (int)$params['id_product_attribute'];
        }
        $category = new Category($product->id_category_default, $this->id_lang);
        $universe_name = '';
        $category_name = '';
        if ($category && (int) $category->id) {
            $category_name = $category->name;
            $categories = $category->getParentsCategories();
            if (count($categories) > 0) {
                $universe_name = $categories[count($categories) - 1]['name'];
                foreach ($categories as $cat) {
                    // prefer level depth 2 for universe 0 : root, 1 : accueil
                    if ((int) $cat['level_depth'] == 2) {
                        $universe_name = $cat['name'];
                    }
                }
            }
        }
        $image_cover_url = '';
        $best_image = $this->getBestImageProduct($product->id, $id_product_attribute);
        if ($best_image && (int)$best_image['id_image']) {
            $ids = $product->id.'-'.$best_image['id_image'];
            $image_cover_url = $this->link->getImageLink($product->link_rewrite, $ids, $this->getImageSizeName());
        }

        $url = '';
        if (version_compare(_PS_VERSION_, '1.6.0', '>=')) {
            //getProductLink($product, $alias, $category, $ean13, $id_lang, $id_shop, $ipa = 0, $force_routes = false, $relative_protocol = false, $add_anchor = false)
            $url = $this->link->getProductLink($product, null, null, null, $this->id_lang, $this->id_shop, $id_product_attribute);
        } else {
            $url = $this->link->getProductLink($product);
        }

        $mappedProduct = array(
            'id' => (string) $product->id, // SKU or product id
            'label' => (string) $product->name, // Designation
            'url' =>  $url,
            'imageUrl' => $image_cover_url,
            'universe' => $universe_name,
            'category' => $category_name
        );

        if (isset($product->id_manufacturer)) {
            $mappedProduct['custom'] = array('manufacturer' => Manufacturer::getNameById($product->id_manufacturer));
        }

        return $mappedProduct;
    }
    private function getBestImageProduct($id_product, $id_product_attribute = 0)
    {
        $image = array();
        if ((int) $id_product_attribute) {
            $image = Db::getInstance()->getRow(
                'SELECT pai.id_image FROM `' . _DB_PREFIX_ . 'product_attribute_image` pai
                    LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON i.`id_image` = pai.`id_image`
                    WHERE pai.`id_product_attribute` = ' . (int) $id_product_attribute.
                ' ORDER BY i.`cover` DESC, i.`position` ASC '
            );
        }
        if (! isset($image['id_image'])) {
            $image = Db::getInstance()->getRow(
                'SELECT i.`id_image` FROM `' . _DB_PREFIX_ . 'image` i
                    WHERE i.`id_product` = ' . (int) $id_product . ' AND i.`cover` = 1
                ORDER BY i.`cover` DESC, i.`position` ASC '
            );
        }
        return $image;
    }
}
