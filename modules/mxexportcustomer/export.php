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
$skey = Tools::getValue('skey');

if ($skey == $excust->secure_key) {
    $mxcust = new Mxexportcustomer();
    $mxcust->exporNow();
}
