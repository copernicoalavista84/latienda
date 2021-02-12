
$(document).ready(function() {
	
	$('#tptn-config-switch').click(function(){
		if ($(this).hasClass('config-close')) {
			$('#tptn-config-inner').hide();
			$(this).removeClass('config-close');
			$.cookie('ckconfigclose', 0);
		} else {
			$('#tptn-config-inner').show();
			$(this).addClass('config-close');
			$.cookie('ckconfigclose', 1);
		}
		return false;
	});

	if ($.cookie('ckconfigclose') == 0) { 
		$('#tptn-config-inner').css("display","none");
		$('#tptn-config-switch').removeClass('config-close');
	}
	else if ($.cookie('ckconfigclose') == 1) {
		$('#tptn-config-inner').css("display","block");
		$('#tptn-config-switch').addClass('config-close');
	}
	
    //=== Top Bkg ===//
	
	var cktopbkg,
	eltopbkg = '#header-row';

	if($.cookie('cktopbkg')) {
		cktopbkg = $.cookie('cktopbkg');
	} else {
		cktopbkg = tptntopbkg_default;
	}

	$('#topbkg-input').colpick({

		color: cktopbkg,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(eltopbkg).css('border-bottom', '50px solid #'+hex);
			$('#topbkg-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('cktopbkg', hex);
			});
		}
	});

	//=== Category Title Bkg ===//
	
	var cktitlebkg,
	eltitlebkg = '#top-categ span, #tptnmobilemenu .toggler';

	if($.cookie('cktitlebkg')) {
		cktitlebkg = $.cookie('cktitlebkg');
	} else {
		cktitlebkg = tptntitlebkg_default;
	}

	$('#titlebkg-input').colpick({

		color: cktitlebkg,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(eltitlebkg).css('backgroundColor', '#'+hex);
			$('#titlebkg-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('cktitlebkg', hex);
			});
		}
	});
	
	//=== Cart Bkg ===//
	
	var ckcartbkg,
	elcartbkg = '.shopping_cart';

	if($.cookie('ckcartbkg')) {
		ckcartbkg = $.cookie('ckcartbkg');
	} else {
		ckcartbkg = tptncartbkg_default;
	}

	$('#cartbkg-input').colpick({

		color: ckcartbkg,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(elcartbkg).css('backgroundColor', '#'+hex);
			$('#cartbkg-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('ckcartbkg', hex);
			});
		}
	});

	//=== Button Background ===//
	
	var ckbtnbkg,
	elbtnbkg = '.tptncarousel .functional-buttons a, ul.product_list .functional-buttons a, button, input.button_mini, input.button_small, input.button, input.button_large, input.exclusive_mini, input.exclusive_small, input.exclusive, input.exclusive_large, a.button_mini, a.button_small, a.button, a.button_large, a.exclusive_mini, a.exclusive_small, a.exclusive, a.exclusive_large, span.button_mini, span.button_small, span.button, span.button_large, span.exclusive_mini, span.exclusive_small, span.exclusive, span.exclusive_large';

	if($.cookie('ckbtnbkg')) {
		ckbtnbkg = $.cookie('ckbtnbkg');
	} else {
		ckbtnbkg = tptnbtnbkg_default;
	}

	$('#btnbkg-input').colpick({

		color: ckbtnbkg,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(elbtnbkg).css('backgroundColor', '#'+hex);
			$('#btnbkg-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('ckbtnbkg', hex);
			});
		}
	});

	//=== Product Name Color ===//
	
	var ckpnameclr,
	elpnameclr = '.product-name a, .product-name';

	if($.cookie('ckpnameclr')) {
		ckpnameclr = $.cookie('ckpnameclr');
	} else {
		ckpnameclr = tptnpnameclr_default;
	}

	$('#pnameclr-input').colpick({

		color: ckpnameclr,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(elpnameclr).css({'color': '#'+hex});
			$('#pnameclr-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('ckpnameclr', hex);
			});
		}
	});

	//=== New icon Background ===//

	var cknewbkg,
	elnewbkg = '.new-box';

	if($.cookie('cknewbkg')) {
		cknewbkg = $.cookie('cknewbkg');
	} else {
		cknewbkg = tptnnewbkg_default;
	}

	$('#newbkg-input').colpick({

		color: cknewbkg,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(elnewbkg).css('backgroundColor', '#'+hex);
			$('#newbkg-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('cknewbkg', hex);
			});
		}
	});

	//=== Sale icon Background ===//

	var cksalebkg,
	elsalebkg = '.sale-box';

	if($.cookie('cksalebkg')) {
		cksalebkg = $.cookie('cksalebkg');
	} else {
		cksalebkg = tptnsalebkg_default;
	}

	$('#salebkg-input').colpick({

		color: cksalebkg,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(elsalebkg).css('backgroundColor', '#'+hex);
			$('#salebkg-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('cksalebkg', hex);
			});
		}
	});

	//=== Price Color ===//
	
	var ckpriceclr,
	elpriceclr = '.column .price, .tptncarousel .price, ul.product_list .price, .content_prices .our_price_display';

	if($.cookie('ckpriceclr')) {
		ckpriceclr = $.cookie('ckpriceclr');
	} else {
		ckpriceclr = tptnpriceclr_default;
	}

	$('#priceclr-input').colpick({

		color: ckpriceclr,

		onShow: function (colpkr) {
			$(colpkr).show();
			return false;
		},
		
		onHide: function (colpkr) {
			$(colpkr).hide();
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$(elpriceclr).css({'color': '#'+hex});
			$('#priceclr-input').css('backgroundColor', '#'+hex);
			$('.apply').click(function() {
				$.cookie('ckpriceclr', hex);
			});
		}
	});

	//=== RESET ALL COOKIES ===//
	$('.reset').click(function() {
        $.cookie('cktopbkg', null);
		$.cookie('cktitlebkg', null);
		$.cookie('ckcartbkg', null);
		$.cookie('ckbtnbkg', null);
		$.cookie('ckpnameclr', null);
		$.cookie('cknewbkg', null);
		$.cookie('cksalebkg', null);
		$.cookie('ckpriceclr', null);
	});

});
