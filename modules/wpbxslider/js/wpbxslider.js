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

	if (typeof(wpbxslider_speed) == 'undefined')
		wpbxslider_speed = 500;
	if (typeof(wpbxslider_pause) == 'undefined')
		wpbxslider_pause = 6000;
	if (typeof(wpbxslider_loop) == 'undefined')
		wpbxslider_loop = true;
  if (typeof(wpbxslider_width) == 'undefined')
    wpbxslider_width = 1200; 
  if (typeof(wpbxslider_effect) == 'undefined')
     wpbxslider_effect = 'horizontal'; 
  

	
	if (!!$.prototype.bxSlider)
		$('#wpbxslider').bxSlider({
			useCSS: false,
			maxSlides: 1,
			mode: wpbxslider_effect,
			slideWidth: wpbxslider_width,
			infiniteLoop: wpbxslider_loop,
			hideControlOnEnd: true,
			pager: false,
      nextText: '',
      prevText: '',
			autoHover: true,
			auto: wpbxslider_loop,
			speed: wpbxslider_speed,
			pause: wpbxslider_pause,
			controls: true,
      onSliderLoad: function(){
        $("#wpbxslider-wrap").css("visibility", "visible");
      }
		});

    

    $('.wpbxslider-description').click(function () {
        window.location.href = $(this).prev('a').prop('href');
    });

   $("#wpbxslider-wrap .bx-controls").hide();  
	 $("#wpbxslider-wrap .bx-wrapper").hover(function () {
             $("#wpbxslider-wrap .bx-controls-direction, #wpbxslider-wrap .bx-controls").fadeToggle();
     });    


});