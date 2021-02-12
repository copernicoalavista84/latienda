{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- MODULE Block best sellers -->
<div id="best-sellers_block_right" class="block hidden-md hidden-sm hidden-xs">
    <h4 class="title_block">
        <a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='Top sellers' mod='blockbestsellers'}">
            {l s='Top sellers' mod='blockbestsellers'}
        </a>
    </h4>
	<div class="block_content">
	{if $best_sellers && $best_sellers|@count > 0}
		<ul class="colprods">
            {foreach from=$best_sellers item=product name=myLoop}
            <li{if $smarty.foreach.myLoop.last} class="last"{/if}>
                <div class="left_block">
                    <a href="{$product.link|escape:'html'}" title="{$product.name|escape:'html':'UTF-8'}" class="prod-img">
                        <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html'}" height="{$smallSize.height}" width="{$smallSize.width}" alt="{$product.legend|escape:'html':'UTF-8'}"/>
                    </a>
                </div>
                <div class="right_block">
                    <a href="{$product.link|escape:'html'}" title="{$product.name|escape:'htmlall':'UTF-8'}" class="product-name">{$product.name|truncate:40:'...'|escape:'htmlall':'UTF-8'}</a>
                    {if !$PS_CATALOG_MODE}
                        <div class="price">{$product.price}</div>
                        {hook h="displayProductPriceBlock" product=$product type="price"}
                        {if isset($product.reduction) && $product.reduction}
                        <div class="old-price">{convertPrice price=$product.price_without_reduction}</div>
                        {/if}
                    {/if}
                </div>
            </li>
            {/foreach}
		</ul>
	{else}
		<p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block best sellers -->
