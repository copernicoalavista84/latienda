{**
 * 2007-2017 PrestaShop
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

<table cellspacing="0" cellpadding="5" style="width:100%">
	<tr><td style="font-weight:bold; font-size: 12pt; color: #444; border: 1px solid #333">{$product|escape:'html':'UTF-8'}</td></tr>
</table>
<table cellspacing="0" cellpadding="5" style="width:100%">
	<tr>
		{foreach from=$heads item='head'}
			<td style="font-size: 11pt; color: #444; border: 1px solid #333">{$head|escape:'html':'UTF-8'|upper}</td>
		{/foreach}
	</tr>
	{foreach from=$content item='res'}
		<tr>
			{foreach from=$heads item='head'}
				<td style="font-size: 11pt; color: #444;; border: 1px solid #333">{$res[$head]|escape:'html':'UTF-8'}</td>
			{/foreach}
		</tr>
	{/foreach}
</table>
