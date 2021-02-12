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

<script type="text/javascript">
	var skey = "{$securekey|escape:'htmlall':'UTF-8'}";
	var ajaxAdmin = "{$ajaxAdmin|escape:'htmlall':'UTF-8'}";
	var id_product = "{$id_product|escape:'htmlall':'UTF-8'}";
</script>

<h3><i class="icon icon-credit-card"></i> {l s='Export Customer' mod='mxexportcustomer'}</h3>
<div id="exportForm">
	<div class="row">
		<div class="col-md-5">
			<label>{l s='Select combination' mod='mxexportcustomer'}</label>
			<select name="combination" id="mxcombi" class="form-control">
				<option value="0">{l s='All' mod='mxexportcustomer'}</option>
				{foreach from=$combination key=key item="value"}
					<option value="{$key|escape:'htmlall':'UTF-8'}">{$value|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<label>{l s='Type of file' mod='mxexportcustomer'}</label>
			<select name="type" id="mxtype" class="form-control">
				<option value="csv">{l s='.csv' mod='mxexportcustomer'}</option>
				<option value="xls">{l s='.pdf' mod='mxexportcustomer'}</option>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<label>{l s='Data to be exported' mod='mxexportcustomer'}</label>
			<div>
				<input type="checkbox" name="firstname" id="mxfname" class="btn btn-lg form-control-lg">
				<label for="mxfname">{l s='First Name' mod='mxexportcustomer'}</label>
			</div>
			<div>
				<input type="checkbox" name="lastname" id="mxlname" class="btn btn-lg form-control-lg">
				<label for="mxlname">{l s='Last Name' mod='mxexportcustomer'}</label>
			</div>
			<div>
				<input type="checkbox" name="phone" id="mxphone" class="btn btn-lg">
				<label for="mxphone">{l s='Phone' mod='mxexportcustomer'}</label>
			</div>
			<div>
				<input type="checkbox" name="email" id="mxemail" class="btn btn-lg">
				<label for="mxemail">{l s='Email' mod='mxexportcustomer'}</label>
			</div>
			<div>
				<input type="checkbox" name="address" id="mxaddress" class="btn btn-lg">
				<label for="mxaddress">{l s='Address' mod='mxexportcustomer'}</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<button class="btn btn-primary btn-lg" id="exportBtn">{l s='Export' mod='mxexportcustomer'}</button>
		</div>
	</div>
</form>