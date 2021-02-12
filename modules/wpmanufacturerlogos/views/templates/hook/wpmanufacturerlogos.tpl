{*
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
*}

<!-- MODULE Manufacturer logos -->
<script type="text/javascript">
{literal}
function sliderInit() {
	$("#wp_man_wrapper").carouFredSel({
		debug	: true,
		circular: {/literal}{$wp_man_cir|escape:'htmlall':'UTF-8'}{literal},
	  infinite: {/literal}{$wp_man_inf|escape:'htmlall':'UTF-8'}{literal},
	  align   : "center",
		responsive: true,
		width: '100%',
	    auto	: {
    		play	: {/literal}{$wp_man_auto|escape:'htmlall':'UTF-8'}{literal},
	    	timeoutDuration : {/literal}{$wp_man_pause_time|intval}{literal}
	    },
	    items	: {
			visible: {
					min: 1,
					max: {/literal}{$wp_man_display_items|intval}{literal}
					 },
			start	: {/literal}{$wp_man_rand|intval}{literal},
			width   : "{/literal}{if $wp_man_logo_size == small_default}{$smallSize.width}{else}{$manufacturerlogoSize.width}{/if}{literal}",
			height  : "{/literal}{if $wp_man_logo_size == small_default}{$smallSize.height}{else}{$manufacturerlogoSize.height}{/if}{literal}"
			
		},		
		scroll	: {
			items	: {/literal}{$wp_man_scroll_items|intval}{literal},
			fx	    : "{/literal}{$wp_man_fx|escape:'htmlall':'UTF-8'}{literal}",
			duration: {/literal}{$wp_man_fx_time|intval}{literal},
			pauseOnHover: {/literal}{$wp_man_mouseover|escape:'htmlall':'UTF-8'}{literal}
		},
		prev	: {
			button	: "#wp_man_prev",
			key		: "left"
		},
		next	: {
			button	: "#wp_man_next",
			key		: "right"
		},    
		swipe: {
					onMouse: true,
					onTouch: true
			   }
	}, 
	{
  wrapper : {
 	        element : "div",
 	        classname : "wp_caroufredsel_wrapper"
 	    },
	    classnames		: {
		    selected		: "selected",
		    hidden			: "hidden",
		    disabled		: "disabled",
		    paused			: "paused",
		    stopped			: "stopped"
	    }

    });
};
$(function(){
    $('#wp_man').addClass('loader'); // display loader
    $(window).load(function() {
      $('#wp_man').removeClass('loader'); 
      $('#wp_man_wrapper').show(200);
    sliderInit();
    
    $("#wp_man_wrapper").touchwipe({      
      wipeLeft: function() {
        $('#wp_man_wrapper').trigger('next', {/literal}{$wp_man_scroll_items|intval}{literal});
      },
      wipeRight: function() {
        $('#wp_man_wrapper').trigger('prev', {/literal}{$wp_man_scroll_items|intval}{literal});
      } 
    });   


}); 
});
{/literal}
</script>
          
<div class="row marg30">             
<div id="wp_man">
	<div id="wp_man_wrapper">
    {if $wpmanufacturers}	
     	{foreach from=$wpmanufacturers item=manufacturer name=manufacturer}
       	<a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}" title="{l s='More about' mod='wpmanufacturerlogos'} {$manufacturer.name|escape:'html':'UTF-8'}"><img src="{$img_manu_dir}{$manufacturer.id_manufacturer|escape:'htmlall':'UTF-8'}-{$wp_man_logo_size}{if $wp_man_logo_size == small}_default{/if}.jpg" alt="{$manufacturer.name|escape:'htmlall':'UTF-8'}" /></a>                                                                                                                                                                               
     	{/foreach}
    {else}
    	<p>{l s='No manufacturer' mod='wpmanufacturerlogos'}</p>
    {/if}		
	</div>
		<a id="wp_man_prev" class="prev" href="#"></a>
  	<a id="wp_man_next" class="next" href="#"></a>
</div>
</div>
<!-- MODULE Manufacturer logos -->
 
