{if isset($categories) AND $categories}
{foreach from=$categories item=category}
<section class="tptncarousel tptnprods clearfix">
	<h4>
		{if $category.id == 2}
			{l s='Featured products' mod='tptnprodcarousel'}
		{else}
			<a href="{$link->getCategoryLink($category.id)|escape:'html':'UTF-8'}">{$category.name}</a>
		{/if}
	</h4>
	{if isset($category.products) AND $category.products}
		<div class="prodcrsl">
		{foreach from=$category.products item=product}
		<div class="ajax_block_product item" itemscope itemtype="https://schema.org/Product">
			<div class="product-image-container">
				<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
				</a>
				{if isset($product.new) && $product.new == 1}<span class="new-box">{l s='New' mod='tptnprodcarousel'}</span>{/if}
				{if $product.on_sale || (isset($product.reduction) && $product.reduction)}<span class="sale-box">{l s='Sale!' mod='tptnprodcarousel'}</span>{/if}
				<div class="functional-buttons">
					<div class="button-container">
					{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
						{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
							{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($product.id_product_attribute) && $product.id_product_attribute}&amp;ipa={$product.id_product_attribute|intval}{/if}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
							<a class="ajax_add_to_cart_button simptip" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" data-tooltip="{l s='Add to cart' mod='tptnprodcarousel'}" data-id-product-attribute="{$product.id_product_attribute|intval}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity >= 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
								<i class="fa fa-shopping-cart"></i>
							</a>
						{else}
							<span class="disabled"><i class="fa fa-shopping-cart"></i></span>
						{/if}
					{/if}
					</div>
					<div class="quickview">
						<a class="quick-view simptip" data-tooltip="{l s='Quick view' mod='tptnprodcarousel'}" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}"><i class="fa fa-expand"></i></a>
					</div>
				</div>
				{if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
				{hook h="displayProductPriceBlock" product=$product type="weight"}
			</div>
			<h5 class="product_name" itemprop="name">
				{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
				<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
					{$product.name|escape:'html':'UTF-8'}
				</a>
			</h5>
			{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
			<div class="content_price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
				{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
					<span itemprop="price" class="price product-price">
						{hook h="displayProductPriceBlock" product=$product type="before_price"}
						{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
					</span>
					<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
					{if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
						{hook h="displayProductPriceBlock" product=$product type="old_price"}
						<span class="old-price product-price">
							{displayWtPrice p=$product.price_without_reduction}
						</span>
						{* {if $product.specific_prices.reduction_type == 'percentage'}
							<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
						{/if} *}
					{/if}
					{hook h="displayProductPriceBlock" product=$product type="price"}
					{hook h="displayProductPriceBlock" product=$product type="unit_price"}
					{hook h="displayProductPriceBlock" product=$product type='after_price'}
				{/if}
			</div>
			{/if}
		</div>
		{/foreach}
		</div>
	{else}
	<p class="no-products">
		{l s='No Products' mod='tptnprodcarousel'}
	</p>
	{/if}
</section>
{/foreach}
{/if}