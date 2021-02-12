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
	{if !isset($content_only) || !$content_only}
						
				</div> <!-- #center_column -->
				</div> <!-- .row -->
				</div> <!-- .container -->
			</div> <!-- #columns -->

			{if isset($HOOK_FOOTER)}
			<!-- Footer -->
			<footer id="footer">
				<div class="footer_top">
					{hook h='displayFooterTop'}
				</div>
				<div class="footer_middle">
					<div class="container">
					<div class="row">
					<div id="servicios">
						<div style="font-weight:bolder;">SERVICIOS</div>
						</BR>
						<div><a href="http://www.jlmangas.com/content/1-entrega">ENTREGA</a></div>
						<div><a href="http://www.jlmangas.com/content/16-avisolegal">AVISO LEGAL</a></div>
						<div><a href="http://www.jlmangas.com/content/3-terminos-y-condiciones-de-uso">T&Eacute;RMINOS Y CONDICIONES DE USO</a></div>
						<div><a href="http://www.jlmangas.com/content/4-acerca">ACERCA DE NOSOTROS</a></div>
						<div><a href="http://www.jlmangas.com/content/5-pago-seguro">PAGO SEGURO</a></div>
						<div><a href="http://www.jlmangas.com/content/6-nuestras-tiendas">NUESTRAS TIENDAS</a></div>
						<div><a href="http://www.jlmangas.com/content/7-cookies">POL&Iacute;TICA DE COOKIES</a></div>
					</div>
					<div id="informacion">
						<div style="font-weight:bolder;">INFORMACI&Oacute;N</div>
						</BR>
						<div><a href="http://www.jlmangas.com/content/8-novedades">NOVEDADES</a></div>
						<div><a href="http://www.jlmangas.com/content/9-masvendido">&iexcl;LO M&Aacute;S VENDIDO!</a></div>
						<div><a href="http://www.jlmangas.com/content/10-tienda">NUESTRAS TIENDAS</a></div>
						<div><a href="http://www.jlmangas.com/content/11-contacto">CONTACTE CON NOSOTROS</a></div>
						<div><a href="http://www.jlmangas.com/content/12-buscar">C&Oacute;MO BUSCAR MI TINTA O T&Oacute;NER</a></div>
						<div><a href="http://www.jlmangas.com/content/13-calidad">CALIDADES DE CONSUMIBLES</a></div>
						<div><a href="http://www.jlmangas.com/content/17-mapa">MAPA DEL SITIO</a></div>
					</div>
					<div id="cuenta">
						<div style="font-weight:bolder;">MI CUENTA</div>
						</BR>
						<div><a href="https://www.jlmangas.com/es/historial-compra">MIS PEDIDOS</a></div>
						<div><a href="https://www.jlmangas.com/es/direcciones">MIS DIRECCIONES</a></div>
						<div><a href="https://www.jlmangas.com/es/datos-personales">MIS DATOS PERSONALES</a></div>
						<div><a href="https://www.jlmangas.com/es/albaran">MIS NOTAS DE CREDITO</a></div>
						<div><a href="https://www.jlmangas.com/content/19-proteccion">PROTECCI&Oacute;N DE DATOS</a></div>
					</div>

						{$HOOK_FOOTER}
					<div id="paypal">
					<img src="http://www.jlmangas.com/img/paypal.gif"/>
					</div>
					</div>
					</div>
				</div>
				<div class="footer_bottom">
					<div class="container">
						B81573842 - J.L. Mangas, S.L. &copy; 2017
					</div>
				</div>
			</footer>
			{/if}
			
		</div>
	{/if}

	{include file="$tpl_dir./global.tpl"}
	</body>
</html>