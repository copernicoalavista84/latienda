{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Brief description' mod='mxexportcustomer'}</h3>
	<p>
		{l s='This module helps you export your customers data in every product. This can export major data such as Name, email, phone and Address. In each product, you can export the customers who have orders. This is to determine who are the customer who bought it and reach them out simple by exporting their information, and ask them feedback through email, phone and other information.' mod='mxexportcustomer'}
	</p>
</div>
<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Export Customer' mod='mxexportcustomer'}</h3>
	<p>
		{l s='How to export customer bought your product' mod='mxexportcustomer'}
		<ul>
			<li>
				{l s='Go to' mod='mxexportcustomer'} <a href="{$productLink|escape:'htmlall':'UTF-8'}">{l s='Product Page' mod='mxexportcustomer'}</a>.
			</li>
			<li>
				{l s='Select a product you wanted to export customers.' mod='mxexportcustomer'}
			</li>
			<li>
				{l s='Then go to modules' mod='mxexportcustomer'}
			</li>
			<li>
				{l s='Select module Export Customers' mod='mxexportcustomer'}
			</li>
			<li>
				{l s='We provided options for your exported file, please input and selected from it.' mod='mxexportcustomer'}
			</li>
			<li>
				{l s='Then press button export.' mod='mxexportcustomer'}
			</li>
			<li>
				{l s='If there are no data exported that means this specific product does not have a sales.' mod='mxexportcustomer'}
			</li>
		</ul>
	</p>
</div>
<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Usage of this module' mod='mxexportcustomer'}</h3>
	<p>
		<ul>
			<li>{l s='This module will export only the data of customer who bought the product which you are trying to export.' mod='mxexportcustomer'}</li>
			<li>{l s='Use to Identify who are the customer who bought you product.' mod='mxexportcustomer'}</li>
			<li>{l s='You can export file into csv and pdf' mod='mxexportcustomer'}</li>
			<li>{l s='You can import exported csv file to mailchimp. See this ' mod='mxexportcustomer'} <a href="https://kb.mailchimp.com/lists/growth/import-subscribers-to-a-list">{l s='link' mod='mxexportcustomer'}</a></li>
			<li>{l s='You can have a ready to print file which is in PDF' mod='mxexportcustomer'}</li>
		</ul>
	</p>
</div>