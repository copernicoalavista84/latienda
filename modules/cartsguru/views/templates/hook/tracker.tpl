{*
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 *}

<script>
  var cgTrkParams = window.cgTrkParams || {
    trackerUrl: '{$trackerUrl|escape:'javascript':'UTF-8'}',
    currency: '{$currency|escape:'javascript':'UTF-8'}',
    platform: 'prestashop',
    siteId: '{$siteId|escape:'htmlall':'UTF-8'}',
    features: {
      ci: !!'{$ci|escape:'javascript':'UTF-8'}',
      fbm: !!'{$fbm|escape:'javascript':'UTF-8'}',
      fbAds: !!'{$fbAds|escape:'javascript':'UTF-8'}',
      scoring: false,
      widgets: JSON.parse("{$widgets|escape:'javascript':'UTF-8' nofilter}")
    },
    fbSettings: {
      app_id:  '{$appId|escape:'htmlall':'UTF-8'}',
      page_id: '{$pageId|escape:'htmlall':'UTF-8'}' // ID of the page connected to FBM Application
    },
    data: JSON.parse("{$data|escape:'javascript':'UTF-8' nofilter}")
  },

  cgtrkStart = function () {
    CgTracker('init', cgTrkParams);

    CgTracker('track', {
      what:   'event',
      ofType: 'visit'
    });
    // Track quit event
    window.onbeforeunload = function noop () {
      setTimeout(function () {
        CgTracker('track', {
          what:    'event',
          ofType:  'quit'
        });
      }, 0);
    };
  },

  // Convert price to proper format
  parsePrice = function (price) {
    return parseFloat(price.replace(',', '.').replace(/[^0-9\.-]+/g,""));
  },

  // Adapt cart items
  adaptCartItems = function (items) {
    var result = [];
    for (var i = 0; i < items.length; i++) {
      var item = items[i],
      // price_wt is Presta 1.7 otherwise item.price
      totalET = item.price_wt ? item.price_wt : parsePrice(item.price) / item.quantity,
      // item.url is Presta 1.7
      url = item.url ? item.url : item.link,
      // item.cover is Presta 1.7
      imageUrl = item.cover && item.cover.medium ? item.cover.medium.url : item.image;

      result.push({
        id: item.id.toString(),
        totalET: totalET,
        label: item.name,
        quantity: item.quantity,
        url: url,
        imageUrl: imageUrl
      });
    }
    return result;
  }

  cg_onJQueryReady(function () {
    // Track XHR requests to get the updated cart
    cgjQuery(document).ajaxComplete(function (event, xhr, params) {
      if (xhr.hasOwnProperty('responseJSON') && typeof xhr.responseJSON != 'undefined' && (xhr.responseJSON.hasOwnProperty('products') || xhr.responseJSON.hasOwnProperty('cart'))) {
        // xhr.responseJSON.cart is Presta 1.7
        var data = xhr.responseJSON.cart ? xhr.responseJSON.cart : xhr.responseJSON,
        totalET;
        // Get total
        if (data.total) {
          totalET = parsePrice(data.total);
        }
        // Presta 1.7
        if (data.totals) {
          totalET = data.totals.total.amount;
        }
        // Adapt cart data
        var updatedCart = {
          siteId: cgTrkParams.siteId,
          currency: cgTrkParams.currency,
          totalET: totalET,
          items: adaptCartItems(data.products)
        }

        // check if we have a cart id
        var trackerData = CgTracker('getData'),
          currentCart = trackerData && trackerData.cart;

        if (currentCart && currentCart.cartId && currentCart.recoverUrl) {
          // Add missing properties
          updatedCart.cartId = currentCart.cartId.toString();
          updatedCart.recoverUrl = currentCart.recoverUrl;
          // Update tracker data
          CgTracker('updateData', {
            cart: updatedCart
          });

          return CgTracker('fireStoredEvents');
        }
        // Get the cartId and recoverUrl from backend
        cgjQuery.ajax({
            url: '{$cartInfoUrl|escape:'javascript':'UTF-8' nofilter}'
        }).done(function (data) {
          if (data && data.cartId && data.recoverUrl) {
            updatedCart.cartId = data.cartId.toString();
            updatedCart.recoverUrl = data.recoverUrl;
            // Update tracker data
            CgTracker('updateData', {
              cart: updatedCart
            });
            CgTracker('fireStoredEvents');
          }
        });
      }
    });
  });

  (function(d, s, id) {
    var cgs, cgt = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    cgs = d.createElement(s); cgs.id = id;
    cgs.src = '{$trackerUrl|escape:'javascript':'UTF-8'}/dist/tracker.build.min.js';
    cgt.parentNode.insertBefore(cgs, cgt);
    cgs.onload = cgtrkStart;
  }(document, 'script', 'cg-trk'));

  (function(d, s, id) {
    var cgs, cgt = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    cgs = d.createElement(s); cgs.id = id;
    cgs.src = '{$trackerUrl|escape:'javascript':'UTF-8'}/dist/platform/' + cgTrkParams.platform + '.min.js';
    cgt.parentNode.insertBefore(cgs, cgt);
  }(document, 'script', 'cg-evt'));

</script>
