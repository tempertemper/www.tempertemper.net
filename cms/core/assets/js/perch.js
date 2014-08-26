if (typeof(Perch) == 'undefined') {
	Perch	= {};
	Perch.UI	= {};
	Perch.Apps	= {};
}

Perch.UI.Global	= function()
{
	var doresize = false;
	var confirmDialogue = false;
	var keepAlivePoll = 5*60*1000; // 5mins
	var oldIE, veryOldIE;

	var init	= function() {
		$('body').addClass('js');

		if (document.all && document.querySelector && !document.addEventListener) oldIE = true;
		if (document.all && !document.querySelector) veryOldIE = true;

		head.load(
			{ lang  : Perch.path+'/core/inc/js_lang.php?v='+Perch.version },
			{ privs : Perch.path+'/core/inc/js_privs.php?v='+Perch.version }
		);

		$.ajaxSetup({
		  cache: true,
		  data: { "v": Perch.version }
		});

		initSidebar();
		initAppMenu();
		initTopbar();
		initSmartBar($('.main'));
		initPopups();
		hideMessages();
		initEditForm();
		initCheckboxSets();
		initFieldHelpers();
		if (!oldIE) initDeleteButtons();
		if (!oldIE) initConfirmButtons();
		initDashboard();
		initKeepAlive();
	};

	var initPopups = function() {
		$('a.assist, a.draft-preview').click(function(e){
			e.preventDefault();
			window.open($(this).attr('href'));
		});
	};
	
	var hideMessages = function() {
		if ($('p.alert.success')) {
			setTimeout("$('p.alert.success').selfHealingRemove()", 5000);
		};
	};
	
	var initEditForm = function() {
		$(window).on('load', function(){
			stickButtons();
			autowidthForms();
		});
		$(window).on('resize', function(){
			doresize = true;
			setTimeout(function(){
				if (doresize) {
					stickButtons();
					doresize = false;
				}
			}, 1000);
			autowidthForms();
		});
		
		fixCSS();
		saveOnCmdS();
	};

	var saveOnCmdS = function() {
		if (!oldIE) {
			var f = $('form.magnetic-save-bar');
			if (f.length) {
				document.addEventListener("keydown", function(e) {
				  if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
				    e.preventDefault();
				    f.submit();
				  }
				}, false);
			}
		}
	};
	
	var stickButtons = function() {
		if (oldIE) return;

		var btns = $('.magnetic-save-bar p.submit');
		if (btns.length && !btns.hasClass('nonstick')) {
			var w = $(window);
		
			var t = btns.position().top;
			var bh = (btns.outerHeight(true));
			var wh = w.height();
			
			var msg = $('p.alert');
			
			if (msg) t=t-msg.outerHeight(true);
		
			if ($('body').height() > wh) {
				w.scroll(function(){
					var position = w.scrollTop() + wh-bh-70;
					
					if (t > position) { 
						btns.addClass('stuck');
						btns.parents('form').addClass('with-stuck');
						var pos_t = position-t;
						if (-pos_t<50) {
							var transparency = (0.7/100)*((-pos_t/50)*100);
						}else{
							var transparency = 0.7;
						}
					
						if (btns.hasClass('error')) {
							btns.css('background-color', 'rgba(179, 64, 64, '+transparency+')');
						}else{
							btns.css('background-color', 'rgba(191, 191, 191, '+transparency+')');
						}
				
					}else{
						btns.removeClass('stuck');
						btns.parents('form').removeClass('with-stuck');
						btns.css('background-color', 'rgba(255, 255, 255, 1)');	
					}
				});
				w.scroll();
			}
		}
	};
	
	var autowidthForms = function() {
		var textareas = $('textarea.autowidth, .editor-wrap');
		if (textareas.length) {
			textareas.each(function(i, o){
				var self = $(o);
				var field = self.parents('div.field');
				var label = field.find('label:first');
				if (field && label) {
					var w = field.innerWidth()-label.outerWidth();
					if (w>200) self.width(w-41);	
				}
			});
		}
	}

	var fixCSS = function() {
		$('.divider').prev('div.field').addClass('predivider');
	}

	var initTopbar = function() {
		var body = $('body');

		$.getScript(Perch.path+'/core/assets/js/headroom.min.js', function(){
			var headroom  = new Headroom($('.topbar').get(0), {
				offset : 60,
				tolerance : 0,
			});
			headroom.init();
		});
		
		/*
		$(window).on('scroll', function(e){
			if (body.scrollTop()>0) {
				body.addClass('scrolled');
			}else{
				body.removeClass('scrolled');
			}
		});
	*/
	}
	
	var initSmartBar = function($container) {

		if ($container) {
			var smartbar = $container.find('.smartbar:not(.ready)');
		}else{
			var smartbar = $('.smartbar:not(.ready)');
		}
		
		if (smartbar.length) {
			var filters = smartbar.find('.filter');
			
			filters.on('click', 'li a', function(e){
				var self = $(this).parent('li');
				var ul = self.parents('ul');
				
				if (!ul.hasClass('open') || (self.is(':first-child'))) e.preventDefault();
				
				if (ul.hasClass('open')) {
					ul.removeClass('open');
					ul.parent('li').find('.proxy').remove();
				}else{
					ul.parent('li').append($('<p class="proxy">.</p>'));
					ul.addClass('open');
				}
			});
			
			filters.each(function(i, o){
				var self = $(o);
				self.css('width', self.find('li:first-child').outerWidth());	
			});

			smartbar.addClass('ready');
		}
	}
	
	var initSidebar = function() {
		var sidebarCoookie = 'cmssb';
		var body = $('body');
		
		if ($.cookie(sidebarCoookie)==1) {
			body.addClass('sidebar-closed').removeClass('sidebar-open');
		}
		
		head.ready(['lang'], function(){
			$('.topbar').append($('<a href="#" id="sidebar-toggle" class="icon"><span>'+Perch.Lang.get('Toggle sidebar')+'</span></a>'));

			var tog = $('#sidebar-toggle');
			tog.on('click', function(e){
				e.preventDefault();
				body.toggleClass('sidebar-closed').toggleClass('sidebar-open');
				if (body.hasClass('sidebar-closed')) {
					$.cookie(sidebarCoookie, 1, { path: '/' });
				}else{
					$.cookie(sidebarCoookie, 0, { path: '/' });
				}
				autowidthForms();
				$(window).trigger('perch.sidebar-toggle');
			});
		});
		

	}
	
	
	var initAppMenu = function() {
		var appmenu = $('#appmenu ul.appmenu');
		var items = appmenu.find('li');
		
		if (items.length>1 && !veryOldIE) {
			appmenu.addClass('menu');
			var cont = $('#appmenu');
			cont.addClass('menucont');
			var selectedText = appmenu.find('li.selected a:not(.add)').text();
			if (selectedText) {
				var select = true;
			}else{ 
				selectedText = Perch.Lang.get('Apps');
				var select = false;
			}
			var trigger = $('<a class="trigger" href="#">'+selectedText+'</a>');
			appmenu.before(trigger).hide();
			if (select) trigger.parent('li').addClass('selected');
			appmenu.prepend($('<li class="dumb"><a class="trigger" href="#">'+selectedText+'</a></li><li class="spaceinvader"></li>'));
			cont.hover(function(){
				appmenu.show();
			}, function(){
				appmenu.hide();
			});
			trigger.click(function(e){
				e.preventDefault();
				appmenu.show();
			});
			appmenu.find('a.trigger').click(function(e){
				e.preventDefault();
				appmenu.hide();
			});
		}
		$('.topbar .mainnav').show();
	};
	
	var initDeleteButtons = function() {
		$('a.inline-delete').click(function(e){
			e.preventDefault();
			var self = $(this);
			var message = Perch.Lang.get('Delete this item?');
			if (self.attr('data-msg')) message = self.attr('data-msg');
			openConfirmDialogue(e, message, function(){
				// ok
				$.post(self.attr('href'), {'_perch_ajax':1, 'formaction':'delete', 'token':Perch.token}, function(r){
					window.location = r;
				});
		
			}, function(){
				// cancel
				closeConfirmDialogue();
			}, 'delete');
		});
	};

	var initConfirmButtons = function() {
		$('a.inline-confirm').click(function(e){
			e.preventDefault();
			var self = $(this);
			var message = Perch.Lang.get('Are you sure?');
			if (self.attr('data-msg')) message = self.attr('data-msg');
			openConfirmDialogue(e, message, function(){
				// ok
				$.post(self.attr('href'), {'_perch_ajax':1, 'formaction':'confirm', 'token':Perch.token}, function(r){
					window.location = r;
				});
		
			}, function(){
				// cancel
				closeConfirmDialogue();
			}, 'confirm');
		});
	};
	
	var openConfirmDialogue = function(event, message, ok_function, cancel_function, display_class) {
		if (confirmDialogue) closeConfirmDialogue();
		var target = $(event.target);

		confirmDialogue = $('<div id="confirm-dialogue" class="'+display_class+'"><p>'+message+'</p><a href="#" class="ok">'+Perch.Lang.get('Yes')+'</a><a href="#" class="cancel">'+Perch.Lang.get('No')+'</a><span class="speak"></span></div>');
		$('.main').append(confirmDialogue);
		confirmDialogue.css({
			top: target.offset().top-(confirmDialogue.outerHeight()+10),
			left: target.offset().left-(confirmDialogue.outerWidth()/2)-36
		}).fadeIn('fast');

		confirmDialogue.find('a.ok').click(function(e){
			e.preventDefault();
			ok_function();
			closeConfirmDialogue();
		});
		confirmDialogue.find('a.cancel').click(function(e){
			e.preventDefault();
			cancel_function();
			closeConfirmDialogue();
		});
	};
	
	var closeConfirmDialogue = function() {
		if (confirmDialogue) {
			confirmDialogue.fadeOut('fast', function(){
				confirmDialogue = false;
			});
			
		}
	};

	var initKeepAlive = function() {
		if ($('form').length) {
			setInterval(function(){
				$.get(Perch.path+'/core/inc/keepalive.php');
			}, keepAlivePoll);
		}
	};
	
	var initCheckboxSets = function() {
		if ($('form .checkboxes').length) {
			$('form .checkboxes').on('change', 'input[type=checkbox]', function(){
				var self = $(this);
				if (self.is(':checked')) {
					if (self.hasClass('single')) {
						self.parents('.checkboxes').find('input[type=checkbox]:not(.single, :disabled)').attr('checked', false);
					}else{
						self.parents('.checkboxes').find('input[type=checkbox].single').attr('checked', false);
					}
				}
			});
		}
	};

	var initFieldHelpers = function() {
		// URLIFY
		var fields = $('form input[data-urlify]');
		if (fields.length) {
			fields.parents('form').on('change', 'input[data-urlify]', function(){
				var self = $(this);
				var target = $('#'+self.attr('data-urlify'));
				if (target.length) {
					var out = self.val();
					out = out.replace(/[^\w-\s]/gi, '');
					out = out.replace(/\s/gi, '-');
					out = out.replace(/-{2,}/gi, '-');
					out = out.replace(/-$/, '');
					out = out.toLowerCase();
					target.val(out);
				}
			});
		}
		
		// COPY
		var fields = $('form input[data-copy]');
		if (fields.length) {
			fields.parents('form').on('change', 'input[data-copy]', function(){
				var self = $(this);
				var target = $('#'+self.attr('data-copy'));
				if (target.length) {
					var out = self.val();
					target.val(out);
				}
			});
		}
	};

	var initDashboard = function() {
		var dash = $('#dashboard');
		if (dash.length) {
			dash.sortable({
	            handle: 'h2',
	            cursor: 'move',
	            stop: function() {
	            	var out = [];
	            	dash.find('.widget[data-app]').each(function(i,o){
	            		out.push($(o).attr('data-app'));
	            	});
					$.post(window.location, {'_perch_ajax':1, 'formaction':'reorder', 'token':Perch.token, 'order':out.join(',')}, function(r){
						Perch.token = r;
					});
	            }
	        });
		}
	}
	
	return {
		init: init,
		resizeFields: autowidthForms,
		initSmartBar: initSmartBar
	};
	
}();

Perch.Apps.Content = function() {
	
	var contentOpenRows = [];
	var contentListCookie = 'cmscl';
	
	var init = function() {
		initContentList();
		initRegionReordering();
	};

	var initRegionReordering = function() {
		var list = $('ul.reorder');
		if (list.length) {
			list.sortable({
				stop: function() {
					list.find('input').each(function(i,o) {
						$(o).val(i+1000);
					});
				}
			});
		}
	}

	var initContentList = function() { 

		var contentList = $('#content-list');
		if (contentList.length) {
			var win = $(window);
			contentList.on('click', 'a.toggle', function(e){
				var link = $(e.target);
				link.attr('href', link.attr('href')+'#sc'+win.scrollTop());
			});
			
			var hash = window.location.hash;
			if (hash.length) {
				var pos = hash.replace('#sc', '');
				win.scrollTop(pos);
			}
		}
	};
	
	return {
		init: init
	};
}();


Perch.Lang	= function()
{
	var translations = {};
	
	var init = function(t) {
		translations = t;
	};
	
	var get = function(str) {
		if (translations[str]) {
			return translations[str];
		}
		return str;
	};
	
	return {
		init: init,
		get: get
	};
}();


Perch.Privs	= function()
{
	var privs = [];
	
	var init = function(p) {
		privs = p;
	};
	
	var has = function(priv) {
		return privs.indexOf(priv);
	};
	
	return {
		init: init,
		has: has
	};
}();


if (typeof(jQuery)!='undefined') {

	jQuery.fn.selfHealingRemove = function(settings, fn) {
		if (jQuery.isFunction(settings)){
			fn = settings;
			settings = {};
		}else{
			settings = jQuery.extend({
				speed: 'slow'
			}, settings);
		};
		
		return this.each(function(){
			var self = jQuery(this); 
			self.animate({
				opacity: 0
			}, settings.speed, function(){
				self.slideUp(settings.speed, function(){
					self.remove();
					if (jQuery.isFunction(fn)) fn();
				});
			});
		});
	};


	/**  * jQuery Cookie plugin  * Copyright (c) 2010 Klaus Hartl (stilbuero.de)  * Dual licensed under the MIT and GPL licenses:  * http://www.opensource.org/licenses/mit-license.php  * http://www.gnu.org/licenses/gpl.html  */
	jQuery.cookie=function(D,E,B){if(arguments.length>1&&String(E)!=="[object Object]"){B=jQuery.extend({},B);if(E===null||E===undefined){B.expires=-1;}if(typeof B.expires==="number"){var G=B.expires,C=B.expires=new Date();C.setDate(C.getDate()+G);}E=String(E);return(document.cookie=[encodeURIComponent(D),"=",B.raw?E:encodeURIComponent(E),B.expires?"; expires="+B.expires.toUTCString():"",B.path?"; path="+B.path:"",B.domain?"; domain="+B.domain:"",B.secure?"; secure":""].join(""));}B=E||{};var A,F=B.raw?function(H){return H;}:decodeURIComponent;return(A=new RegExp("(?:^|; )"+encodeURIComponent(D)+"=([^;]*)").exec(document.cookie))?F(A[1]):null;};


	jQuery(function($) { 
		Perch.UI.Global.init(); 
		for (var app in Perch.Apps) Perch.Apps[app].init();
	});
}else{
	// jQuery didn't load, so undo any JavaScript damage
	window.onload=function(){document.getElementById('appmenu').parentNode.style.display='block'};
}
