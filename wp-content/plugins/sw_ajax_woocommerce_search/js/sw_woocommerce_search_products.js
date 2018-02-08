	// Autocomplete */
(function($) {
		$.fn.Soautocomplete = function(option) {
			return this.each(function() {
				this.timer = null;
				this.items = new Array();

				$.extend(this, option);

				$(this).attr('autocomplete', 'off');

				// Focus
				$(this).on('focus', function() {
					var parent_div = $(this).parent();
					parent_div.find('ul.dropdown-menu').show();
				});

				// Blur
				$(this).on('blur', function() {
					setTimeout(function(object) {
						object.hide();
					}, 200, this);
				});

				// Keydown
				$(this).on('keydown', function(event) {
					switch(event.keyCode) {
						case 27: // escape
							this.hide();
							break;
						default:
							this.request();
							break;
					}
				});

				// Click
				this.click = function(event) {
					event.preventDefault();

					value = $(event.target).parent().attr('data-value');

					if (value && this.items[value]) {
						this.select(this.items[value]);
					}
				}

				// Show
				this.show = function() {
					var pos = $(this).position();

					$(this).siblings('ul.dropdown-menu').css({
						top: pos.top + $(this).outerHeight(),
						left: pos.left
					});

					$(this).siblings('ul.dropdown-menu').show();
				}

				// Hide
				this.hide = function() {
					$(this).siblings('ul.dropdown-menu').hide();
				}

				// Request
				this.request = function() {
					clearTimeout(this.timer);

					this.timer = setTimeout(function(object) {
						object.source($(object).val(), $.proxy(object.response, object));
					}, 200, this);
				}

				// Response
				this.response = function(json,selector) {
					html = '';

					if (json.length) {
						for (i = 0; i < json.length; i++) {
							this.items[json[i]['value']] = json[i];
						}

						for (i = 0; i < json.length; i++) {
							if (!json[i]['category']) {
							html += '<li class="media" data-value="' + json[i]['value'] + '" title="' + json[i]['label'] + '">';
							if(json[i]['image'] && json[i]['show_image'] && json[i]['show_image'] == 1 ) {
								html += '	<a class="media-left" href="' + json[i]['link'] + '"><img width="' + json[i]['width'] + 'px" height="' + json[i]['height'] + 'px" class="pull-left" src="' + json[i]['image'] + '"></a>';
							}
							json[i]['character'] = (json[i]['character']).toLowerCase(json[i]['character']);
							var regex = new RegExp('%20', 'g');
							json[i]['character'] = (json[i]['character']).replace(regex, ' ');
							json[i]['label'] = (json[i]['label']).toLowerCase(json[i]['label']);
							json[i]['label'] = (json[i]['label']).replace(json[i]['character'], '<b>'+json[i]['character']+'</b>');
							html += '<div class="media-body">';
							html += '<a href="' + json[i]['link'] + '"><span>'	+ json[i]['label'] + '</span></a>';
							if(json[i]['price'] && json[i]['show_price'] && json[i]['show_price'] == 1){
								html += '<div class="price">'+json[i]['text_price']+': '+json[i]['price']+'</div>';
							}
							html += '</div></li>';
							html += '<li class="clearfix"></li>';
							}
						}

						// Get all the ones with a categories
						var category = new Array();

						for (i = 0; i < json.length; i++) {
							if (json[i]['category']) {
								if (!category[json[i]['category']]) {
									category[json[i]['category']] = new Array();
									category[json[i]['category']]['name'] = json[i]['category'];
									category[json[i]['category']]['item'] = new Array();
								}

								category[json[i]['category']]['item'].push(json[i]);
							}
						}

						for (i in category) {
							html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

							for (j = 0; j < category[i]['item'].length; j++) {
								html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
							}
						}
					}

					if (html) {
						this.show();
					} else {
						this.hide();
					}

					$(this).siblings('ul.dropdown-menu').html(html);
					$(".icon-loading",selector).removeClass("loading");
				}

				$(this).after('<ul class="dropdown-menu"></ul>');
				
			});
		}
})(window.jQuery);

	
(function ($) {
	jQuery(document).ready(function($) {
		$( ".swsearch-wrapper .search" ).each(function() {
			var selector = $(this);
			if(selector.data("ajax_search")){
				var total = 0;
				var showimage  = selector.data("show_image");
				var showprice  = selector.data("show_price");
				var character  = selector.data("character");
				var height 		 = selector.data("height_image");
				var width 		 = selector.data("width_image");
				var limit 		 = selector.data("limit");
				var no_result  = selector.data("no_result");
				var text_price = selector.data("text_price");
				var xhr;
				$(selector).find('.autosearch-input').Soautocomplete({
					delay: 500,
					source: function(request, response) {
						var category_id = $("select[name=\"category\"]",selector).first().val();
						var ajaxurl			= selector.data("admin_url").replace( '%%endpoint%%', 'sw_search_products_callback' );
						if(typeof(category_id) == 'undefined')
							category_id = 0;
						if( xhr && xhr.readystate != 4 ){
							$(".icon-loading",selector).removeClass('loading');
							xhr.abort();
						}
						if( request.length >= character ){							
							$(".icon-loading",selector).addClass('loading');
							xhr = $.ajax({
								url: ajaxurl,
								dataType: 'json',
								data: {
									action : "sw_search_products_callback",
									filter_category_id : category_id,
									limit : limit,
									width : width,			
									height : 	height,
									filter_name : 	encodeURIComponent(request)
								},
								success: function(json) {
									if(json.length > 0){
										response($.map(json, function(item,selector) {
											total = 0;
											if(item.total){
												total = item.total;
											}
											return {
												character : encodeURIComponent(request),
												price:   item.price,
												label:   item.name,
												image:   item.image,
												link:    item.link,
												show_price:  showprice,
												show_image:  showimage,
												width	:  width,
												height	:  height,
												value:   item.product_id,
												text_price:  text_price,
											}
										}));
									}else{
										html = '<li class="no-result">'+no_result+'</li>';		
										$('ul.dropdown-menu',selector).html(html);
										$("ul.dropdown-menu",selector).show();	
										$(".icon-loading",selector).removeClass('loading');
									}
								}
							});
						}else{
							$("ul.dropdown-menu",selector).hide();
						}
					},
				});
			}
		});
	});
})(jQuery);