/*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$(document).ready(function(){

if ($('body').attr('id') === 'product' && $("#center_column #views_block #thumbs_list li a.shown img").length ) {  
                 
    var wplink = $("#center_column #views_block #thumbs_list li a.shown img").attr("src").replace('cart','thickbox');
         
         
    // default
    $('#center_column #image-block img#bigpic')
        .wrap('<span style="display:block; max-width: 100%"></span>')
        .css('display', 'block')
        .parent()
        .zoom({
        url: wplink, 
        icon: true        
        });
                     
    // mouse over thumb image
    $('#center_column #thumbs_list ul li a img').mouseover(function() { 
           var wpsrc = $(this).attr("src").replace('cart','thickbox');
           $('img.zoomImg').attr("src", wpsrc);
    });                 
     
    // color picker
    if ($("#center_column ul#color_to_pick_list").length > 0) { 
    $('#center_column ul#color_to_pick_list li a').click(function() {
     setTimeout(function(){      
        var wplinkpick = $("#center_column #views_block #thumbs_list li a.shown img").attr("src").replace('cart','thickbox');
        $('img.zoomImg').attr("src", wplinkpick);
     }, 100);
        
        
    });
    }

    //  on attribute selectbox change
    $('#attributes select').change(function() {          
      setTimeout(function(){        
        var wpselectpick = $("#center_column #views_block #thumbs_list li a.shown img").attr("src").replace('cart','thickbox');
        $('img.zoomImg').attr("src", wpselectpick);           
      }, 100);
    });
                
   
  }; 
});