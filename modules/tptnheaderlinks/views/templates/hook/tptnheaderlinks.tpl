{if $infos}
<div id="tptnheaderlinks" class="hidden-xs hidden-sm">
<ul>
	{foreach from=$infos item=info}
	<li>
		<a href="{$info.url_info}">{$info.text_info}</a>
	</li>
{/foreach}
</ul>
</div>
{/if}