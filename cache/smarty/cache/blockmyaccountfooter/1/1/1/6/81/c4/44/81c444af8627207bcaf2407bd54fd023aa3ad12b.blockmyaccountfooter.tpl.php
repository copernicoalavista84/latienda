<?php /*%%SmartyHeaderCode:186874657160264a931956a2-45499485%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81c444af8627207bcaf2407bd54fd023aa3ad12b' => 
    array (
      0 => '/var/www/html/jlmangassl/themes/default-bootstrap/modules/blockmyaccountfooter/blockmyaccountfooter.tpl',
      1 => 1556635332,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '186874657160264a931956a2-45499485',
  'variables' => 
  array (
    'link' => 0,
    'returnAllowed' => 0,
    'voucherAllowed' => 0,
    'HOOK_BLOCK_MY_ACCOUNT' => 0,
    'is_logged' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_60264a931c9120_17361946',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_60264a931c9120_17361946')) {function content_60264a931c9120_17361946($_smarty_tpl) {?>
<!-- Block myaccount module -->
<section class="footer-block col-xs-12 col-sm-4">
	<h4><a href="http://localhost/jlmangassl/es/mi-cuenta" title="Administrar mi cuenta de cliente" rel="nofollow">Mi cuenta</a></h4>
	<div class="block_content toggle-footer">
		<ul class="bullet">
			<li><a href="http://localhost/jlmangassl/es/historial-compra" title="Mis pedidos" rel="nofollow">Mis pedidos</a></li>
						<li><a href="http://localhost/jlmangassl/es/albaran" title="Mis facturas por abono" rel="nofollow">Mis facturas por abono</a></li>
			<li><a href="http://localhost/jlmangassl/es/direcciones" title="Mis direcciones" rel="nofollow">Mis direcciones</a></li>
			<li><a href="http://localhost/jlmangassl/es/datos-personales" title="Administrar mis datos personales" rel="nofollow">Mis datos personales</a></li>
			<li><a href="http://localhost/jlmangassl/es/descuento" title="Mis cupones de descuento" rel="nofollow">Mis cupones de descuento</a></li>			
            		</ul>
	</div>
</section>
<!-- /Block myaccount module -->
<?php }} ?>
