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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$(document).ready(function(){

	if (typeof(wpsidebarslider_speed) == 'undefined')
		wpsidebarslider_speed = 500;
	if (typeof(wpsidebarslider_pause) == 'undefined')
		wpsidebarslider_pause = 6000;
	if (typeof(wpsidebarslider_loop) == 'undefined')
		wpsidebarslider_loop = true;
  if (typeof(wpsidebarslider_width) == 'undefined')
    wpsidebarslider_width = 1200; 
  if (typeof(wpsidebarslider_effect) == 'undefined')
     wpsidebarslider_effect = 'horizontal'; 
  

	
	if (!!$.prototype.bxSlider)
		$('#wpsidebarslider').bxSlider({
			useCSS: false,
			maxSlides: 1,
			mode: wpsidebarslider_effect,
			slideWidth: wpsidebarslider_width,
			infiniteLoop: wpsidebarslider_loop,
			hideControlOnEnd: true,
			pager: false,
			autoHover: true,
      nextText: '',
      prevText: '',
			auto: wpsidebarslider_loop,
			speed: wpsidebarslider_speed,
			pause: wpsidebarslider_pause,
			controls: true,
      onSliderLoad: function(){
        $("#wpsidebarslider-wrap").css("visibility", "visible");
      }
		});
    
    $('.wpsidebarslider-description').click(function () {
        window.location.href = $(this).prev('a').prop('href');
    });

  $("#wpsidebarslider-wrap .bx-controls").hide();  
  $("#wpsidebarslider-wrap .bx-wrapper").hover(function () {
      $("#wpsidebarslider-wrap .bx-controls").fadeToggle();
     });    


});