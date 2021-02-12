<?php /*%%SmartyHeaderCode:36637418360264a9308d5a0-50665853%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '148ab8cc029010b70fcf037adff57b93f927e656' => 
    array (
      0 => '/var/www/html/jlmangassl/themes/default-bootstrap/modules/blockcms/blockcms.tpl',
      1 => 1612794736,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '36637418360264a9308d5a0-50665853',
  'variables' => 
  array (
    'block' => 0,
    'cms_titles' => 0,
    'cms_key' => 0,
    'cms_title' => 0,
    'cms_page' => 0,
    'link' => 0,
    'show_price_drop' => 0,
    'PS_CATALOG_MODE' => 0,
    'show_new_products' => 0,
    'show_best_sales' => 0,
    'display_stores_footer' => 0,
    'show_contact' => 0,
    'contact_url' => 0,
    'cmslinks' => 0,
    'cmslink' => 0,
    'show_sitemap' => 0,
    'footer_text' => 0,
    'display_poweredby' => 0,
  ),
  'has_nocache_code' => true,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_60264a930fc798_43108128',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_60264a930fc798_43108128')) {function content_60264a930fc798_43108128($_smarty_tpl) {?>
	<!-- Block CMS module footer -->
	<section class="footer-block col-xs-12 col-sm-2" id="block_various_links_footer">
		<h4>Información</h4>
		<ul class="toggle-footer">
							<li class="item">
					<a href="http://localhost/jlmangassl/es/bajamos-precios" title="Promociones especiales">
						Promociones especiales
					</a>
				</li>
									<li class="item">
				<a href="http://localhost/jlmangassl/es/nuevos-productos" title="Novedades">
					Novedades
				</a>
			</li>
										<li class="item">
					<a href="http://localhost/jlmangassl/es/mas-vendido" title="Los más vendidos">
						Los más vendidos
					</a>
				</li>
										<li class="item">
					<a href="http://localhost/jlmangassl/es/tiendas" title="Nuestras tiendas">
						Nuestras tiendas
					</a>
				</li>
									<li class="item">
				<a href="http://localhost/jlmangassl/es/contactanos" title="Contáctenos">
					Contáctenos
				</a>
			</li>
																						<li class="item">
						<a href="http://localhost/jlmangassl/es/content/4-acerca" title="Sobre nosotros">
							Sobre nosotros
						</a>
					</li>
													<li>
				<a href="http://localhost/jlmangassl/es/mapa-web" title="Mapa del sitio">
					Mapa del sitio
				</a>
			</li>
					</ul>
		
	</section>
		<section class="color-pie bottom-footer col-xs-12">
		<div>
			<?php echo smartyTranslate(array('s'=>'[1] %3$s %2$s - Ecommerce software by %1$s [/1]','mod'=>'blockcms','sprintf'=>array('PrestaShop™',date('Y'),'©'),'tags'=>array('<a class="_blank" href="http://www.prestashop.com">')),$_smarty_tpl);?>
 - "La tienda del consumible" es una web de la empresa J.L. Mangas, S.L.
		</div>
	</section>
		<!-- /Block CMS module footer -->
<?php }} ?>
