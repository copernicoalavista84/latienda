/**
* 2007-2018 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2018 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

$(function(){
	$('.payment_module.pointer-box').click(function(event) {
		element = $(this).parent().find('#modal_stripe');
		if(element.length > 0) {
			element.parent('.payment_option_form').show();
		}
		else {
			$('#modal_stripe').parent('.payment_option_form').hide();
		}
	});
});