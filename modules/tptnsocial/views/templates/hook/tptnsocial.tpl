<section id="tptnsocial" class="footer-block col-xs-12 col-lg-3">
	<h4>{l s='Contact us' mod='tptnsocial'}</h4>
	<div class="toggle-footer">	
		<ul>
			{if $tptnaddress != ''}<li>{$tptnaddress|escape:'html':'UTF-8'}</li>{/if}
			{if $tptnphone != ''}<li>{$tptnphone|escape:'html':'UTF-8'}</li>{/if}
			{if $tptnemail != ''}<li>{$tptnemail|escape:'html':'UTF-8'}</li>{/if}
		</ul>
		<ul class="social-icons">	
			{if $tptnfacebook != ''}<li class="facebook"><a class="fa fa-facebook" href="{$tptnfacebook|escape:html:'UTF-8'}" title="Facebook"></a></li>{/if}
			{if $tptntwitter != ''}<li class="twitter"><a class="fa fa-twitter" href="{$tptntwitter|escape:html:'UTF-8'}" title="Twitter"></a></li>{/if}
			{if $tptngoogle != ''}<li class="google"><a class="fa fa-google-plus" href="{$tptngoogle|escape:html:'UTF-8'}" title="Google+"></a></li>{/if}
			{if $tptninstagram != ''}<li class="instagram"><a class="fa fa-instagram" href="{$tptninstagram|escape:html:'UTF-8'}" title="Instagram"></a></li>{/if}
			{if $tptnyoutube != ''}<li class="youtube"><a class="fa fa-youtube-play" href="{$tptnyoutube|escape:html:'UTF-8'}" title="Youtube"></a></li>{/if}
		</ul>
	</div>
</section>