<?php
/**
* 2007-2017 PrestaShop
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
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mxexportcustomer extends Module
{
    protected $config_form = false;
    public $secure_key;
    public $title;
    public $date;
    public $available_in_your_account = true;

    /** @var Smarty */
    public $smarty;

    /** @var Shop */
    public $shop;

    public function __construct()
    {
        $this->name = 'mxexportcustomer';
        $this->tab = 'export';
        $this->version = '1.0.0';
        $this->author = 'OHM Conception';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Export customer by Product');
        $this->description = $this->l('You can now export customers who bought your products. This is great tool to reach out your customers.');
        $this->secure_key = Tools::encrypt($this->name);
        $this->module_key = 'e2efc6072a24fdccc9b9d9fdda5db09a';

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() &&
            $this->installTab() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayAdminProductsExtra');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTab();
    }

    public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminExportCustomer";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Link Widget";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentThemes');
        $tab->module = $this->name;
        return $tab->add();
    }

    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminExportCustomer');
        $tab = new Tab($id_tab);
        return $tab->delete();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitMxexportcustomerModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('productLink', $this->context->link->getAdminLink('AdminProducts'));

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $protocol = Configuration::get('PS_SSL_ENABLED') ? Tools::getHttpHost(true) : _PS_BASE_URL_;

        $id_product = $params['id_product'];

        $this->context->smarty->assign('combination', $this->getProductGroups($id_product));
        $this->context->smarty->assign('securekey', $this->secure_key);
        $this->context->smarty->assign('ajaxAdmin', $protocol.$this->_path.'ajax_admin.php');
        $this->context->smarty->assign('id_product', $id_product);


        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/exportForm.tpl');
    }

    private function getProductGroups($id_product)
    {
        $variants = $this->getAttributeCombinations($id_product, $this->context->language->id);

        $groups = array();
        foreach ($variants as $value) {
            $groups[$value['id_product_attribute']][$value['id_attribute']] = $value;
        }

        $groupsVar = array();
        foreach ($groups as $key => $value) {
            $txtval = "";
            
            $valen = count($value);
            $ctr = 0;

            foreach ($value as $grpval) {
                $txtval .= $grpval['group_name'] . " - " . $grpval['attribute_name'];

                if (++$ctr !== $valen) {
                    $txtval .= ", ";
                }
            }

            $groupsVar[$key] = $txtval;
        }

        return $groupsVar;
    }

    public function getAttributeCombinations($product, $id_lang = null, $groupByIdAttributeGroup = true)
    {
        if (!Combination::isFeatureActive()) {
            return array();
        }
        if (is_null($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }

        $sql = 'SELECT pa.*, product_attribute_shop.*, ag.`id_attribute_group`, ag.`is_color_group`, agl.`name` AS group_name, ag.`group_type` AS group_type, al.`name` AS attribute_name,
                    a.`id_attribute`, a.`color`
                FROM `'._DB_PREFIX_.'product_attribute` pa
                '.Shop::addSqlAssociation('product_attribute', 'pa').'
                LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)$id_lang.')
                WHERE pa.`id_product` = '.(int)$product.'
                GROUP BY pa.`id_product_attribute`'.($groupByIdAttributeGroup ? ',ag.`id_attribute_group`' : '').'
                ORDER BY a.`id_attribute`';

        $res = Db::getInstance()->executeS($sql);

        //Get quantity of each variations
        foreach ($res as $key => $row) {
            $cache_key = $row['id_product'].'_'.$row['id_product_attribute'].'_quantity';

            if (!Cache::isStored($cache_key)) {
                Cache::store(
                    $cache_key,
                    StockAvailable::getQuantityAvailableByProduct($row['id_product'], $row['id_product_attribute'])
                );
            }

            $res[$key]['quantity'] = Cache::retrieve($cache_key);
        }

        return $res;
    }


    public function exporNow()
    {
        $id_product = 0;
        $combi = 0;
        $fname = 0;
        $lname = 0;
        $phone = 0;
        $email = 0;
        $address = 0;

        if (Tools::getIsset('id_product')) {
            $id_product = (int)Tools::getValue('id_product');
        }

        if (Tools::getIsset('combi')) {
            $combi = (int)Tools::getValue('combi');
        }

        if (Tools::getIsset('type')) {
            $type = Tools::getValue('type');
        }

        if (Tools::getIsset('fname')) {
            $fname = 1;
        }

        if (Tools::getIsset('lname')) {
            $lname = 1;
        }

        if (Tools::getIsset('phone')) {
            $phone = 1;
        }

        if (Tools::getIsset('email')) {
            $email = 1;
        }

        if (Tools::getIsset('address')) {
            $address = 1;
        }

        $orders = $this->getIdOrderProduct($id_product, $combi);
        
        $mydatas = array();

        for ($i=0; $i < count($orders); $i++) {
            $order = $orders[$i];

            $datas = array();
            
            if ($fname) {
                $datas['firstname'] = pSQL($order['firstname']);
            }
            
            if ($lname) {
                $datas['lastname'] = pSQL($order['lastname']);
            }
            
            if ($phone) {
                $datas['phone'] = pSQL($order['phone']);
            }
            
            if ($email) {
                $datas['email'] = pSQL($order['email']);
            }

            if ($address) {
                $datas['address'] = $order['address1'].$order['address2'];
                $datas['city'] = $order['city'];
                $datas['postcode'] = $order['postcode'];
            }
            
            $mydatas[] = $datas;
        }

        if ($type == 'csv') {
            $this->downloadCsv($mydatas, 'PRODUCT' . $id_product . '-' . time() .  '.csv');
        } else {
            $this->downloadPdf($mydatas, 'PRODUCT' . $id_product . '-' . time() .  '.pdf', $id_product, $combi);
        }
    }

    public function getIdOrderProduct($id_product, $product_attribute_id = 0)
    {
        return Db::getInstance()->executeS('
            SELECT o.reference, c.firstname,c.lastname, a.phone, a.address1, a.address2, a.postcode, a.city, c.email
            FROM '._DB_PREFIX_.'orders o
            LEFT JOIN '._DB_PREFIX_.'order_detail od
                ON o.id_order = od.id_order
            INNER JOIN '._DB_PREFIX_.'customer c
                ON o.id_customer = c.id_customer
            INNER JOIN '._DB_PREFIX_.'address a
                ON a.id_customer = c.id_customer
            WHERE od.product_id = '. (int)$id_product . (((int) $product_attribute_id != 0)? ' AND product_attribute_id = ' . (int) $product_attribute_id :'') . '
            GROUP BY c.id_customer
            ORDER BY o.date_add DESC
        ');
    }

    public function getProductDetails($id_product, $product_attribute_id = 0)
    {
        $product = new Product();
        return $product->getProductName($id_product, $product_attribute_id);
    }

    public function gotolink($datas, $skey)
    {
        $protocol = Configuration::get('PS_SSL_ENABLED') ? Tools::getHttpHost(true) : _PS_BASE_URL_;

        $goto = "";

        if ($skey) {
            $goto .= "&skey=" . $skey;
        }

        if (!empty($datas['id_product'])) {
            $goto .= "&id_product=" . (int)$datas['id_product'];
        }

        if (!empty($datas['combi'])) {
            $goto .= "&combi=" . (int)$datas['combi'];
        }

        if (!empty($datas['type'])) {
            $goto .= "&type=" . $datas['type'];
        }

        if (!empty($datas['fname']) && $datas['fname'] !== "false") {
            $goto .= '&fname';
        }

        if (!empty($datas['lname']) && $datas['lname'] !== "false") {
            $goto .= '&lname';
        }

        if (!empty($datas['phone']) && $datas['phone'] !== "false") {
            $goto .= '&phone';
        }

        if (!empty($datas['email']) && $datas['email'] !== "false") {
            $goto .= '&email';
        }

        if (!empty($datas['address']) && $datas['address'] !== "false") {
            $goto .= '&address';
        }

        return $protocol.$this->_path.'export.php?'.$goto;
    }

    private function downloadCsv($results, $name = null)
    {
        if (count($results) == 0) {
            return null;
        }

        if (!$name) {
            $name = md5(uniqid() . microtime(true) . mt_rand()). '.csv';
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='. $name);
        header('Pragma: no-cache');
        header("Expires: 0");

        $outstream = fopen("php://output", "w");

        fputcsv($outstream, array_keys(reset($results)));

        foreach ($results as $result) {
            fputcsv($outstream, $result);
        }

        fclose($outstream);
    }

    public function downloadPdf($results, $name = null, $id_product = 0, $combi = 0)
    {
        if (empty($results)) {
            return 0;
        }

        if (!$name) {
            $name = md5(uniqid() . microtime(true) . mt_rand()). '.pdf';
        }

        $det = $this->getProductDetails($id_product, $combi);

        $this->context->smarty->assign(array(
            'heads' => array_keys(reset($results)),
            'product' => $det,
            'content' => $results,
        ));

        $temp = $this->context->smarty->fetch($this->local_path.'views/templates/admin/export-pdf.tpl');
        $pdf_renderer = new PDFGenerator((bool) Configuration::get('PS_PDF_USE_CACHE'), 'P');
        $pdf_renderer->createHeader($this->getHeader());
        $pdf_renderer->createFooter($this->getFooter());
        $pdf_renderer->setFontForLang(Context::getContext()->language->iso_code);
        $pdf_renderer->createContent($temp);
        $pdf_renderer->writePage();

        $pdf_renderer->render($name);
    }

    public function getHeader()
    {
        $this->assignCommonHeaderData();
        $temp = $this->local_path.'views/templates/admin/header.tpl';
        return $this->context->smarty->fetch($temp);
    }

    /**
     * Returns the template's HTML footer
     *
     * @return string HTML footer
     */
    public function getFooter()
    {
        $shop_address = $this->getShopAddress();

        $id_shop = (int)$this->shop->id;

        $this->context->smarty->assign(array(
            'available_in_your_account' => $this->available_in_your_account,
            'shop_address' => $shop_address,
            'shop_fax' => Configuration::get('PS_SHOP_FAX', null, null, $id_shop),
            'shop_phone' => Configuration::get('PS_SHOP_PHONE', null, null, $id_shop),
            'shop_email' => Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop),
            'free_text' => Configuration::get('PS_INVOICE_FREE_TEXT', (int)Context::getContext()->language->id, null, $id_shop)
        ));

        $temp = $this->local_path.'views/templates/admin/footer.tpl';
        return $this->context->smarty->fetch($temp);
    }

    /**
     * Returns the shop address
     *
     * @return string
     */
    protected function getShopAddress()
    {
        $shop_address = '';

        $shop_address_obj = $this->shop->getAddress();
        if (isset($shop_address_obj) && $shop_address_obj instanceof Address) {
            $shop_address = AddressFormat::generateAddress($shop_address_obj, array(), ' - ', ' ');
        }

        return $shop_address;
    }

    /**
     * Returns the invoice logo
     */
    protected function getLogo()
    {
        $id_shop = (int)$this->shop->id;

        return  _PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $id_shop);
    }

    /**
     * Assign common header data to smarty variables
     */

    public function assignCommonHeaderData()
    {
        $this->setShopId();
        $id_shop = (int)$this->shop->id;
        $shop_name = Configuration::get('PS_SHOP_NAME', null, null, $id_shop);

        $path_logo = $this->getLogo();

        $width = 0;
        $height = 0;
        if (!empty($path_logo)) {
            list($width, $height) = getimagesize($path_logo);
        }

        // Limit the height of the logo for the PDF render
        $maximum_height = 100;
        if ($height > $maximum_height) {
            $ratio = $maximum_height / $height;
            $height *= $ratio;
            $width *= $ratio;
        }

        $this->context->smarty->assign(array(
            'logo_path' => $path_logo,
            'img_ps_dir' => 'http://'.Tools::getMediaServer(_PS_IMG_)._PS_IMG_,
            'img_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
            'date' => $this->date,
            'title' => $this->title,
            'shop_name' => $shop_name,
            'shop_details' => Configuration::get('PS_SHOP_DETAILS', null, null, (int)$id_shop),
            'width_logo' => $width,
            'height_logo' => $height
        ));
    }

    protected function setShopId()
    {
        if (isset($this->order) && Validate::isLoadedObject($this->order)) {
            $id_shop = (int)$this->order->id_shop;
        } else {
            $id_shop = (int)Context::getContext()->shop->id;
        }

        $this->shop = new Shop($id_shop);
        if (Validate::isLoadedObject($this->shop)) {
            Shop::setContext(Shop::CONTEXT_SHOP, (int)$this->shop->id);
        }
    }
}
