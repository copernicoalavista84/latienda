<?php
/**
 * 2013-2018 Amazon Advanced Payment APIs Modul
*
* for Support please visit www.patworx.de
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
*  @author    patworx multimedia GmbH <service@patworx.de>
*  @copyright 2013-2018 patworx multimedia GmbH
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class AmzpaymentsPersonaldataModuleFrontController extends ModuleFrontController
{

    public $ssl = true;
    
    public $display_column_left = false;
    
    public $display_column_right = false;

    public $isLogged = false;

    public $service;

    protected $ajax_refresh = false;

    protected $css_files_assigned = array();

    protected $js_files_assigned = array();

    protected static $amz_payments = '';

    public function __construct()
    {
        $this->controller_type = 'modulefront';
        $this->module = Module::getInstanceByName(Tools::getValue('module'));
        if (! $this->module->active) {
            Tools::redirect('index');
        }
        $this->page_name = 'module-' . $this->module->name . '-' . Dispatcher::getInstance()->getController();
        parent::__construct();
    }

    public function init()
    {
        self::$amz_payments = new AmzPayments();
        $this->isLogged = (bool) $this->context->customer->id && Customer::customerIdExistsStatic((int) $this->context->cookie->id_customer);
        if (!$this->isLogged) {
            Tools::redirect('index');
        }
        parent::init();
        $this->display_column_left = false;
        $this->display_column_right = false;
        $this->customer = $this->context->customer;
    }

    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign('amzpayments', self::$amz_payments);
        $this->setTemplate('personaldata.tpl');
    }
    
    /**
     * Start forms process
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        if (Tools::isSubmit('submitIdentity')) {
            $this->customer->firstname = Tools::getValue('firstname');
            $this->customer->lastname = Tools::getValue('lastname');
                        
            if (self::$amz_payments->customerNamesError($this->customer->firstname, $this->customer->lastname)) {
                $this->errors[] = Tools::displayError('The information cannot be updated.');
            }
            
            $this->errors = array_merge($this->errors, $this->customer->validateController());
            if (!count($this->errors)) {
                if ($this->customer->update()) {
                    $this->context->cookie->customer_firstname = $this->customer->firstname;
                    $this->context->cookie->customer_lastname = $this->customer->lastname;
                    $this->context->smarty->assign('confirmation', 1);
                } else {
                    $this->errors[] = Tools::displayError('The information cannot be updated.');
                }
            }
        } else {
            $_POST = array_map('stripslashes', $this->customer->getFields());
        }
    }
}
