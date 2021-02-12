<div id="tptnuserinfo">
	<div class="dropbtn">
		<i class="fa fa-user left"></i>
		<span class="hidden-xs">{l s='Account' mod='tptnuserinfo'}<i class="fa fa-angle-down right"></i></span>
	</div>
	<ul class="dropdown-content">
	{if $logged}
		<li><a href="{$link->getPageLink('my-account', true)|escape:'html'}" rel="nofollow" title="{l s='My account' mod='tptnuserinfo'}">{l s='My account' mod='tptnuserinfo'}</a></li>
		<li><a href="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Information' mod='tptnuserinfo'}">{l s='Information' mod='tptnuserinfo'}</a></li>
		<li><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Addresses' mod='tptnuserinfo'}">{l s='Addresses' mod='tptnuserinfo'}</a></li>
		<li><a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Orders' mod='tptnuserinfo'}">{l s='Orders' mod='tptnuserinfo'}</a></li>
		<li><a href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html'}" rel="nofollow" title="{l s='Sign out' mod='tptnuserinfo'}">{l s='Sign out' mod='tptnuserinfo'}</a></li>
	{else}
		<li><a href="{$link->getPageLink('my-account', true)|escape:'html'}" rel="nofollow" title="{l s='Sign in' mod='tptnuserinfo'}">{l s='Sign in' mod='tptnuserinfo'}</a></li>
		<li><a href="{$link->getPageLink('my-account', true)|escape:'html'}" rel="nofollow" title="{l s='Register' mod='tptnuserinfo'}">{l s='Register' mod='tptnuserinfo'}</a></li>
	{/if}
	</ul>
</div>