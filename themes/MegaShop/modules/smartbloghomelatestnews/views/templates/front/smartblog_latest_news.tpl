<section id="latest-blog" class="tptncarousel tptnprods clearfix">
	<h4>{l s='Our Blog' mod='smartbloghomelatestnews'}</h4>
	<div class="blogcrsl">
		{if isset($view_data) AND !empty($view_data)}
			{assign var='i' value=1}
			{foreach from=$view_data item=post}

					{assign var="options" value=null}
					{$options.id_post = $post.id}
					{$options.slug = $post.link_rewrite}
					<div class="blog-container">
						<div class="blog-img"><a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}"><img alt="{$post.title}" src="{$modules_dir}smartblog/images/{$post.post_img}-home-default.jpg" /></a></div>
						<div class="news-date">{$post.date_added|date_format}</div>
						<h5><a title="{$post.title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title}</a></h5>
						<p>{$post.short_description|escape:'htmlall':'UTF-8'}</p>
					</div>

				{$i=$i+1}
			{/foreach}
		{/if}
	</div>
</section>