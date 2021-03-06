{capture name=path}<a title="{l s='All Blog News' mod='smartblog'}" href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='All Blog News' mod='smartblog'}</a>
{if $title_category != ''}
    <span class="navigation-pipe">{$navigationPipe}</span>{$title_category}
{/if}
{/capture}
 
{if $postcategory == ''}
    <p class="error">{l s='No post found in this tag' mod='smartblog'}</p>
{else}
    <div id="smartblogcat" class="block">
    {foreach from=$postcategory item=post}
        {include file="./category_loop.tpl" postcategory=$postcategory}
    {/foreach}
    </div>
{/if}

{if isset($smartcustomcss)}
    <style>
        {$smartcustomcss}
    </style>
{/if}