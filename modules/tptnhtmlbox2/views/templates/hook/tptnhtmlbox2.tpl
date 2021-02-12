{if $infos}
<div id="tptnhtmlbox2">
<div class="container">
	{foreach from=$infos item=info}
	<div class="box-content">
		<a href="{$info.url_info}">
			<em class="fa fa-{$info.icon_info}" style="background:#{$info.bkg_info}"></em>
			<span>{$info.text_info}</span>
		</a>
	</div>
	{/foreach}
</div>
</div>
{/if}