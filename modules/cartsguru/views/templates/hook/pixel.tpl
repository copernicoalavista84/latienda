{*
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 *}
<script>
    {literal}
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','//connect.facebook.net/en_US/fbevents.js');
    {/literal}
    {if isset($pixel) && $pixel}
        fbq('init', '{$pixel|escape:'htmlall':'UTF-8'}'); fbq('track', 'PageView');
        {if isset($track_product_view) && $track_product_view}
            fbq('track', 'ViewContent', {
                content_name: '{$track_product_view.name|escape:'htmlall':'UTF-8'}',
                content_ids: ['{$track_product_view.id|escape:'htmlall':'UTF-8'}'],
                content_type: 'product',
                value: {$track_product_view.value|escape:'htmlall':'UTF-8'},
                currency: '{$currency_iso|escape:'htmlall':'UTF-8'}',
                product_catalog_id: '{$catalogId|escape:'htmlall':'UTF-8'}'
            });
        {/if}

        cg_onJQueryReady(function() {
          //Use prestashop event if possible, PS>=1.7
          if (window.prestashop){
            cgjQuery(document).ready(function () {
              prestashop.on('updateCart', function(event){
                var idProduct = event.reason.idProduct;
                var data =  {
                    content_ids: [idProduct],
                    content_type: 'product',
                    product_catalog_id: '{$catalogId|escape:'htmlall':'UTF-8'}'
                }

                if (cgjQuery("*[itemprop='price']").length > 0){
                  data.value = cgjQuery("*[itemprop='price']").attr('content');
                  data.currency = '{$currency_iso|escape:'htmlall':'UTF-8'}';
                }

                fbq('track', 'AddToCart', data);

              });
            });
          }
          //Bind us on ajax event PS<1.7
          else {
            cgjQuery(document).ajaxComplete(function(event, xhr, params) {
                if (xhr.responseJSON && xhr.responseJSON.productTotal && xhr.responseJSON.products) {
                    var products = [];
                    for (var i = 0; i < xhr.responseJSON.products.length; i++) {
                      products.push(xhr.responseJSON.products[i].id);
                    }
                    //parseFloat(xhr.responseJSON.productTotal.replace(',', '.')) has a problem
                    //since productTotal has the currency sign in it
                    //so the return value is null when the symbol is at the beggining
                    //Take out the currency symbol when it's at the beggining of the Total Price
                    var value = xhr.responseJSON.productTotal;
                    while (isNaN(value[0])) {
                        value = xhr.responseJSON.productTotal.slice(1);
                    }
                    fbq('track', 'AddToCart', {
                        content_ids: products,
                        content_type: 'product',
                        value: parseFloat(value.replace(',', '.')),
                        currency: '{$currency_iso|escape:'htmlall':'UTF-8'}',
                        product_catalog_id: '{$catalogId|escape:'htmlall':'UTF-8'}'
                    });
                }
            });
          }
        });
    {/if}
</script>
