{if isset($tptncolbanner_slides)}
<div id="tptncolbanner" class="tptnbanner hidden-md hidden-sm hidden-xs">
	<ul>
	{foreach from=$tptncolbanner_slides item=slide name=ColBanner}
	{if $slide.active}
		<li>
			<a href="{$slide.url}" title="{$slide.title}">
				<img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`tptncolbanner/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" alt="{$slide.title|escape:'htmlall':'UTF-8'}" />
			</a>
		</li>
	{/if}
	{/foreach}
	</ul>
</div>
{/if}