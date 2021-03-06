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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


    <!-- Module WPSidebarSlider -->
    {if isset($wpsidebarslider_slides)}
        <div id="wpsidebarslider-wrap">
            <ul id="wpsidebarslider">
                {foreach from=$wpsidebarslider_slides item=slide}
                    {if $slide.active}
                        <li class="wpsidebarslider-container">
                            <a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.title|escape:'html':'UTF-8'}">
                                <img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`wpsidebarslider/slides/`$slide.image|escape:'htmlall':'UTF-8'`")}"
                                     alt="{$slide.title|escape:'htmlall':'UTF-8'}"/>
                            </a>
                            {if isset($slide.description) && trim($slide.description) != ''}
                                <div class="wpsidebarslider-description">{$slide.description|escape:'strip_tags'}</div>
                            {/if}
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>

    {/if}
    <!-- /Module WPSidebarSlider -->
