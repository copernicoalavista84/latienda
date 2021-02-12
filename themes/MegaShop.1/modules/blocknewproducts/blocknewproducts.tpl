{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- MODULE Block new products -->
<div id="new-products_block_right" class="block hidden-md hidden-sm hidden-xs">
	<h4 class="title_block">
		<a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='New products' mod='blocknewproducts'}">
			{l s='New products' mod='blocknewproducts'}
		</a>
	</h4>
	<div class="block_content">
	{if $new_products !== false}
		<ul class="colprods">
		{foreach from=$new_products item='product' name='newProducts'}
			<li{if $smarty.foreach.newProducts.last} class="last"{/if}>
				<div class="left_block">
					<a href="{$product.link|escape:'html'}" title="{$product.legend|escape:html:'UTF-8'}">
						<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html'}" alt="{$product.legend|escape:html:'UTF-8'}" />
					</a>
				</div>
				<div class="right_block">
					<a class="product-name" href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">{$product.name|truncate:40:'...'|escape:'htmlall':'UTF-8'}</a>
					{if !$PS_CATALOG_MODE}
						<div class="price">{convertPrice price=$product.price}</div>
						{hook h="displayProductPriceBlock" product=$product type="price"}
						{if isset($product.reduction) && $product.reduction}
						<div class="old-price">{displayWtPrice p=$product.price_without_reduction}</div>
						{/if}
					{/if}
				</div>
			</li>
		{/foreach}
		</ul>
	{else}
		<p>{l s='Do not allow new products at this time.' mod='blocknewproducts'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block new products -->
