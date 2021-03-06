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

<!-- MODULE Block contact infos -->
<section id="block_contact_infos" class="footer-block col-xs-12 col-sm-3 col-md-3">
	<div>
        <h4>{l s='Store Information' mod='blockcontactinfos'}</h4>
        <div class="toggle-footer">
            {if $blockcontactinfos_company != ''}
            <span class="company">
            		{$blockcontactinfos_company|escape:'html':'UTF-8'}
            </span>                
            {/if}

            {if $blockcontactinfos_address != ''}
            <span class="address">  
                {$blockcontactinfos_address|escape:'html':'UTF-8'}
            </span>                
            {/if}

            {if $blockcontactinfos_phone != ''}
            	<span>
            		{l s='Call us now:' mod='blockcontactinfos'} 
            		<span>{$blockcontactinfos_phone|escape:'html':'UTF-8'}</span>
            	</span>
                <br>
            {/if}

            {if $blockcontactinfos_email != ''}
            	<span>
            		{l s='Email:' mod='blockcontactinfos'} 
            		<span>{mailto address=$blockcontactinfos_email|escape:'html':'UTF-8' encode="hex"}</span>
            	</span>
            {/if}
        </div>
    </div>
</section>
<!-- /MODULE Block contact infos -->
