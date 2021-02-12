<?php
/**
 * NOTICE OF LICENSE.
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Ohm Conception
 *  @copyright 2016 Ohm Conception
 *  @license   license,txt
 */

require_once dirname(__FILE__).'../../../config/config.inc.php';
require_once dirname(__FILE__).'../../../init.php';

include_once('mxexportcustomer.php');

$excust = new Mxexportcustomer();
$action = (int)Tools::getValue('action');
$skey = Tools::getValue('skey');

switch ($action) {
    case 1:
        $datas = Tools::getValue('data');
        $csv = $excust->gotolink($datas, $skey);
        echo $csv;
        break;
}
