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
<!-- Module WPCopyLogo -->
<div class="row">
	<div class="copy-logo-text col-xs-12 col-ms-6">
  	{if $wpcopylogo->wpcopylogo_text}{$wpcopylogo->wpcopylogo_text|stripslashes}{/if}
    </div>

    <div class="copy-logo-icon col-xs-12 col-ms-6">
    {if $wpcopylogo->wpcopylogo_image_link}<a href="{$wpcopylogo->wpcopylogo_image_link|escape:'htmlall':'UTF-8'}" title="{l s='Payment methods' mod='wpcopylogo'}">{/if}
  	 {if $wpcopylogo_image}<img src="{$link->getMediaLink($image_path)|escape:'html'}" alt="{l s='Payment methods' mod='wpcopylogo'}" {if $image_width}width="{$image_width}"{/if} {if $image_height}height="{$image_height}" {/if}/>{/if}
  	{if $wpcopylogo->wpcopylogo_image_link}</a>{/if}      
  	</div>
</div>  	

<!-- /Module WPCopyLogo -->
