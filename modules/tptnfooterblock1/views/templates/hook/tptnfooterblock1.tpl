<!-- Footer Block-1 Module -->
<section id="tptnfooterblock1" class="footer-block col-xs-12 col-lg-3">
	<h4>{$title|escape}</h4>
	<ul class="toggle-footer">
	{foreach from=$tptnfooterblock1 item=link}
		{if isset($link.$text)} 
			<li>
				<a href="{$link.$url|escape}" title="{$link.$text|escape}" {if $link.newWindow} onclick="window.open(this.href);return false;"{/if}>{$link.$text|escape}</a>
			</li>
		{/if}
	{/foreach}
	</ul>
</section>
<!-- / Footer Block-1 Module -->
