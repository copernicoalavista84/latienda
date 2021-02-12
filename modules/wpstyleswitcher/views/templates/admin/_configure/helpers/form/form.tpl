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

{extends file="helpers/form/form.tpl"}

{block name="script"}
$(document).ready(function() {
		$('#options_tab a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});
});
{/block}

{block name="after"}
{$smarty.block.parent}
{/block}

{block name="defaultForm"}
<div class="row">
<div class="col-lg-2">
<div id="options_tab" class="">
	<ul class="list-group">
		{foreach $fields as $tab}
			{if $tab.form.tab_name != 'save_tab'}<li {if $tab.form.tab_name == 'global_tab'}class="active"{/if}><a class="list-group-item {$tab.form.tab_name|escape:'html':'UTF-8'}" href="#{$tab.form.tab_name|escape:'html':'UTF-8'}">{$tab.form.legend.title|escape:'htmlall':'UTF-8'}</a></li>{/if}
		{/foreach}
	<ul>
</div>
</div>
<div class="col-lg-10 tab-content">
{$smarty.block.parent}
</div>
</div>
{/block}

{block name="fieldset"}
{if $fieldset.form.tab_name != 'save_tab'}<div class="tab-pane {if $fieldset.form.tab_name == 'global_tab'}active{/if}" id="{$fieldset.form.tab_name|escape:'htmlall':'UTF-8'}">{/if}
{$smarty.block.parent}
{if $fieldset.form.tab_name != 'save_tab'}</div>{/if}
{/block}



