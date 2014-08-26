if (typeof(Perch) == 'undefined') {
	Perch      = {};
	Perch.UI   = {};
	Perch.Apps = {};
}

Perch.UI.Assets	= function()
{
	var selected_asset  = false;
	var target_field    = false;
	var target_group_id = false;
	var target_bucket   = 'default';

	var current_page      = 1;
	var last_request_page = 0;
	var current_opts      = {};
	var orig_opts         = {};

	var w = $(window);
	var asset_index = [];

	var spinner;
	var spinning = false;

	var init	= function() {
		$('body').addClass('js');

		$.ajaxSetup({
		  cache: true,
		  data: { "v": Perch.version }
		});

		init_asset_badge();
		init_asset_chooser();
		init_tag_fields();
	};

	var init_asset_badge = function(badge) {
		if (!badge) var badge = $('.asset-badge');

		badge.on('change', 'input[type=checkbox]', function(e){
			var self = $(this);
			var cont = self.parents('.asset-badge').find('.asset-badge-thumb');
			if (self.is(':checked')) {
				cont.addClass('asset-strikethrough');
			}else{
				cont.removeClass('asset-strikethrough');
			}
		});
	};

	var init_asset_chooser = function() {
		var placeholder = $('.ft-choose-asset:not(.assets-disabled)');
		if (placeholder.length) {

			head.ready(['lang'], function(){
				var label = Perch.Lang.get('Select or upload an image');
				if (placeholder.is('.ft-file')) {
					label = Perch.Lang.get('Select or upload a file');
				}
				placeholder.html('<a href="#" class="disabled">'+label+'</a>');
			});


			head.ready(['lang', 'privs'], function(){
				placeholder.find('a.disabled').removeClass('disabled');
			});
			

			$('form').on('click', '.ft-choose-asset:not(.assets-disabled) a', function(e){
				e.preventDefault();
				var link = $(this);
				if (link.is('.disabled')) return;

				var self = link.parent('.ft-choose-asset');
								
				target_field    = self.attr('data-field');
				target_group_id = self.attr('data-input');
				target_bucket   = self.attr('data-bucket');
				open_asset_chooser(self.attr('data-type'));
			});

			placeholder.parents('.field').find('input[type=file]').hide();

			head.ready(['lang', 'privs'], function(){
				load_templates(function(){
					init_asset_panel();
				});	
			});

			placeholder.parents('.field-wrap').css('vertical-align', 'top');
		}
	};

	var init_asset_panel = function() {

		var template = Handlebars.templates['asset-chooser'];
 		$('.main').before(template({
 			upload_url: Perch.path+'/core/apps/assets/upload/'
 		})); 
		$('.asset-chooser').hide();

		$('.asset-field').on('click', '.alert .action', function(e){
			e.preventDefault();
			current_opts = {view: current_opts.view};// jQuery.extend(true, {}, orig_opts);
			filter_asset_field($('#asset-filter'));
		});

		$.getScript(Perch.path+'/core/assets/js/jquery.slimscroll.min.js', function(){
			var wh = $(window).height();
			var bh = $('body').height();
			
			$('.asset-field .inner').slimScroll({
				height: (wh-160)+'px',
				railVisible: true,
				alwaysVisible: true,
			}).bind('slimscroll', function(e, pos){
    			if (pos=='bottom') {
    				get_assets(current_opts, populate_chooser);
    			}
			});
			$('.asset-chooser').css('height', bh+20);
		});

		$('.asset-field').on('click keypress', '.grid-asset, .list-asset-title', function(e){
			e.preventDefault();
			var t = $(e.target);
			if (!t.is('.list-asset-title')) {
				if (!t.is('.grid-asset')) {
					t = t.parents('.grid-asset');
				}
			}
			select_grid_asset(t);
		});

		$('.asset-field').on('dblclick', '.grid-asset, .list-asset-title', function(e){
			e.preventDefault();
			var t = $(e.target);
			if (!t.is('.list-asset-title')) {
				if (!t.is('.grid-asset')) {
					t = t.parents('.grid-asset');
					select_grid_asset(t);
					var item = asset_index[selected_asset];
					update_form_with_selected_asset(item);
					close_asset_chooser();
					w.trigger('Perch.asset_deselected');
					selected_asset  = false;
					target_field    = false;
					target_group_id = false;
				}
			}

		});

		w.on('Perch.asset_selected', function(){
			$('.asset-topbar .actions .select').addClass('active');
		});
		
		w.on('Perch.asset_deselected', function(){
			$('.asset-topbar .actions .select').removeClass('active');
		});

		$('.asset-topbar').on('click', '.select.active', function(e){
			e.preventDefault();
			var item = asset_index[selected_asset];
			update_form_with_selected_asset(item);
			close_asset_chooser();
			w.trigger('Perch.asset_deselected');
			selected_asset  = false;
			target_field    = false;
			target_group_id = false;
		});

		$('.asset-topbar').on('click', '.add', function(e){
			e.preventDefault();
			w.trigger('Perch.asset_deselected');
			if ($('.asset-drop').is('.open')) {
				close_asset_drop();
			}else{
				open_asset_drop();
			}
			
		});

		$('.asset-topbar .close').on('click', function(e){
			e.preventDefault();
			w.trigger('Perch.asset_deselected');
			close_asset_chooser();
		});



		$.getScript(Perch.path+'/core/assets/js/dropzone.js');
		$.getScript(Perch.path+'/core/assets/js/spin.min.js');

		$.ajax({
			url: Perch.path+'/core/apps/assets/async/asset-filter.php',
			cache: false,
			success: function(r){
						var container = $('#asset-filter');
						container.append(r);
						container.on('click', 'a:not(.action)', function(e){
							var target = $(e.target);
							var li = target.parent('li');
							var ul = li.parent('ul');

							if (ul.is('.open') && (li!=ul.find('li').first())) {
								e.preventDefault();
							}else{
								e.preventDefault();
								filter_asset_field(container, target.attr('href'));
							}
						});

						container.on('submit', 'form', function(e){
							e.preventDefault();
							var field = $(e.target).find('input.search');
							filter_asset_field(container, '?q='+field.val());
						});
					}
		});

	};

	var filter_asset_field = function(container, href)
	{
		if (!href) href = ''; 

		if (href) {
			href.replace(
			    new RegExp("([^?=&]+)(=([^&]*))?", "g"),
			    function($0, $1, $2, $3) { current_opts[$1] = $3; }
			);
		}

		$.ajax({
			url: Perch.path+'/core/apps/assets/async/asset-filter.php',
			data:  current_opts,
			success: function(r){
						container.html(r);
						Perch.UI.Global.initSmartBar(container);
					}
		});

		reload_assets();
	}

	var open_asset_drop = function() {
		var drop = $('.asset-drop');
		var form = drop.find('form');
		drop.animate({height: '220px'}).addClass('open');
		$('.asset-field').animate({top:'60px'});

		var load_progress = 0;

		if (!drop.is('.dropzone')) {
			drop.addClass('dropzone');
			form.addClass('dropzone');
			form.dropzone({
				clickable: true, 
				dictDefaultMessage: Perch.Lang.get('Drop files here or click to upload'),
				uploadMultiple: false,
				totaluploadprogress: function(p) {
					load_progress = p;
				},
				success: function(y, x) {
					if (load_progress==100) {
						close_asset_drop();
						reload_assets();
					}
				},
				fallback: function(){
					$.getScript(Perch.path+'/core/assets/js/jquery.form.min.js', function(){
						form.ajaxForm({
							beforeSubmit: function(){
								show_spinner();
							},
							success: function(r) {
								hide_spinner();
								close_asset_drop();
								reload_assets();
							}
						});
					});
					
				},
				sending: function(file, xhr, formData){
					formData.append('bucket', target_bucket);
				},
				//forceFallback: true, // useful for testing!
			});
			
		}

	};

	var close_asset_drop = function() {
		$('.asset-drop').animate({height: '0'}).removeClass('open');
		$('.asset-field').animate({top:'60px'});
	};

	var open_asset_chooser = function(type) {
		var body = $('body');

		if (body.hasClass('sidebar-open')) {
			body.removeClass('sidebar-open').addClass('sidebar-closed');
		}
		$('.asset-chooser').addClass('transitioning').show().animate({'width': '744px'}, function(){
			$('.main').one('click', close_asset_chooser);
			current_opts = {'type': type, 'bucket': target_bucket};
			orig_opts = jQuery.extend(true, {}, current_opts);
			if (asset_index.length==0) get_assets(current_opts, populate_chooser);
			$('.asset-chooser').removeClass('transitioning');

		});

		$('.main, .topbar, .submit.stuck').animate({'left': '-800px', 'right': '800px'});
		$('.main').addClass('asset-chooser-open');	
		Perch.UI.Global.initSmartBar($('#asset-filter'));
	
		$('.metanav').on('click', '.logout', function(e){
			e.preventDefault();
			close_asset_chooser();
		});
	};

	var close_asset_chooser = function() {
		$('.asset-chooser').addClass('transitioning').animate({'width': '0'}, function(){
			$(this).hide();
		});
		$('.main').animate({'left': '0'});
		$('.topbar, .submit.stuck').animate({'left': '0', 'right': '55px'}, function(){
			$('.topbar').css('right', '');
		});
		$('.main').removeClass('asset-chooser-open').unbind('click', close_asset_chooser);

		$('.metanav').unbind();
	};

	var select_grid_asset = function(item) {
		selected_asset = item.attr('data-id');
		if (item.is('.selected')) {
			$('.asset-field .selected').removeClass('selected');
			w.trigger('Perch.asset_deselected');
			selected_asset = false
		}else{
			$('.asset-field .selected').removeClass('selected');
			item.addClass('selected');
			w.trigger('Perch.asset_selected');
		}

	};


	var load_templates = function(callback) {
		$.getScript(Perch.path+'/core/assets/js/handlebars.runtime.js', function(){
			$.getScript(Perch.path+'/core/assets/js/templates.js', function(){
				callback();
			});	
			Handlebars.registerHelper('Lang', function(str) {
			  return Perch.Lang.get(str);
			});
			
			Handlebars.registerHelper('hasPriv', function(str, block) {
			  if (Perch.Privs.has(str)>=0) {
			  	return block.fn(this);
			  }
			});
		});
	
	};

	var get_assets = function(opts, callback) {
		var cb = function(callback) {
			return function(result) {
				last_request_page = current_page;
				if (result.assets.length) {
					var i, l;
					for(i=0, l=result.assets.length; i<l; i++) {
						asset_index[result.assets[i].id] = result.assets[i];
					}
					current_page++;
				}
				callback(result);	
			};
		};
		if (current_page>last_request_page) {
			show_spinner();
			$.ajax({
				dataType: 'json',
				url: 	  Perch.path+'/core/apps/assets/async/get-assets.php?page='+current_page,
				data: 	  opts,
				success:  cb(callback),
				cache:    false
			});

		}
	};

	var reload_assets = function() {
		current_page = 1;
		last_request_page = 0;
		asset_index = [];
		$('.grid-asset, .list-asset').remove();
		get_assets(current_opts, populate_chooser);
	}

	var populate_chooser = function(data) {
		if (current_opts['view'] && current_opts['view']=='list') {
			var template = Handlebars.templates['asset-list'];
		}else{
			var template = Handlebars.templates['asset-grid'];	
		}				
		var target = $('.asset-field .inner');
		target.find('.notice').remove();
		hide_spinner();
		if (current_page>1 && !data.assets.length) return;
		target.append(template(data)); 
		
	}

	var show_spinner = function() {
		if (!spinner) spinner = new Spinner().spin($('.asset-field').get(0));
	};

	var hide_spinner = function() {
		if (spinner) {
			spinner.stop();
			spinner = false;	
		}
	}

	var update_form_with_selected_asset = function(item) {
		var field = $('#'+target_field);
		field.val(item.id);

		var data = item;
		data.asset_field = target_field;
		data.input_id 	 = target_group_id;

		var template = Handlebars.templates['asset-badge'];
		$('.asset-badge[data-for='+target_field+']').replaceWith(template(data));
		init_asset_badge($('.asset-badge[data-for='+target_field+']'));
		
	};

	var init_tag_fields = function() {
		var fields = $('input[data-tags]');
		if (fields.length) {
			$("<link/>", {
			   rel: 'stylesheet',
			   type: 'text/css',
			   href: Perch.path+'/core/assets/css/jquery.tagsinput.css'
			}).appendTo('head');
			$.getScript(Perch.path+'/core/assets/js/jquery.tagsinput.min.js', function(){
				reinit_tag_fields(fields);
			});
		}
	};

	var reinit_tag_fields = function(fields) {
		if (!fields) fields = $('input[data-tags]');

		fields.each(function(i, o){
			var self = $(o);
			self.tagsInput({
				autocomplete_url: Perch.path+self.attr('data-tags'),
				width: '340px',
				defaultText: Perch.Lang.get('Add a tag'),
				interactive: true,
			});
		});	
	};


	
	return {
		init: init
	};
	
}();

if (typeof(jQuery)!='undefined') {
	jQuery(function($) { 
		Perch.UI.Assets.init(); 
	});
}