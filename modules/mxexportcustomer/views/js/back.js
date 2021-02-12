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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
$(function() {
    $("#exportBtn").click(function(e){
        
        e.preventDefault();

        var exportfrm = $("#exportForm");

        var datas = {
            id_product : id_product,
            combi : exportfrm.find("select#mxcombi").val(),
            type : exportfrm.find("select#mxtype").val(),
            fname : exportfrm.find("input#mxfname").prop('checked'),
            lname : exportfrm.find("input#mxlname").prop('checked'),
            phone : exportfrm.find("input#mxphone").prop('checked'),
            email : exportfrm.find("input#mxemail").prop('checked'),
            address : exportfrm.find("input#mxaddress").prop('checked'),
        };

        $.ajax({
            type: 'POST',
            data: {
                data: datas,
                skey: skey,
                action: 1
            },
            url: ajaxAdmin,
            success: function(datas) {
                var win = window.open(datas, '_blank');
            }
        });
    });
});
