if (typeof(Perch) == 'undefined') {
	Perch	= {};
	Perch.UI	= {};
	Perch.Apps	= {};
}

Perch.UI.Repeaters	= function()
{
	var oldIE;

	var init	= function() {
		$('body').addClass('js');

		if (document.all && document.querySelector && !document.addEventListener) oldIE = true;

		if ($('.repeater').length) {
			hide_spares();
			init_plusone_links();
			init_sorting();
			init_delete_links();
			init_submit_handler();
		}	
	};


 	var init_submit_handler = function() {
 		$('.repeater:first').parents('form').on('submit', function(){
 			$('.repeater').each(function(i, o){
 				var repeater = $(o);
 				var count_field = repeater.find('.repeater-footer input.count');
 				count_field.val(repeater.find('.repeated-item:not(.spare)').length);

 				repeater.find('.spare').remove();

 			});
 		});
 	};


	var init_delete_links = function() {
		$('.repeated .rm').each(function(i, o){
			var cont = $(o);
			var link = $('<a href="#" class="icon"><span>'+Perch.Lang.get('Delete this item?')+'</span></a>');
			link.appendTo(cont);
		});
		$('.repeater').on('click', '.rm a', function(e){
			e.preventDefault();
			var self = $(this);
			var repeater = self.parents('.repeater');
			self.parents('.repeated-item').selfHealingRemove({speed:'fast'}, function(){
				renumber_repeater(repeater);
			});
		});
	};


	var init_sorting = function() {
		$('.repeated').sortable({
			items: '> .repeated-item',
			handle: '.index',
			axis: 'y',
			helper: function(e, tr) {
			    var $originals = tr.children();
			    var $helper = tr.clone();
			    $helper.children().each(function(index) {
			      // Set helper cell sizes to match the original sizes
			      $(this).width($originals.eq(index).width());
			    });
			    return $helper;
			},
			update: function(e, ui) {
				renumber_repeater($(this).parents('.repeater'));
			},
			sort: function(e, ui) {
				$(this).parents('.repeater').find('.ui-sortable-helper').siblings('.repeated-item').addClass('sorting');
			},
			stop: function(e, ui) {
				$(this).parents('.repeater').find('.repeated-item').removeClass('sorting');
			},
		});
	};

	var hide_spares = function() {
		var spare = $('.repeated-item.spare');
		spare.hide();
		spare.find('*[required]').attr('required', false).attr('data-required', 'true');
	};

	var init_plusone_links = function() {
		var repeaters = $('.repeater');
		repeaters.find('.repeater-footer').append($('<a href="#" class="icon plusone add">'+Perch.Lang.get('Add Another')+'</a>'));

		verify_plusone_links();		

		repeaters.on('click', 'a.plusone', function(e){
			e.preventDefault();
			var link = $(e.target);
			if (link.hasClass('disabled')) return false;
			var repeater = link.closest('.repeater');
			if (repeater) {
				var spare = repeater.find('.spare');
				if (spare.length) {
					spare.find('*[data-required]').attr('data-required', false).attr('required', true);
					spare.removeClass('spare').fadeIn(function(){
						var items = repeater.find('.repeated-item');
						var new_item = clone_item(spare, items.length, repeater.attr('data-prefix'));
						var repeated = repeater.find('.repeated');
						repeated.append(new_item);
						repeated.animate({ scrollTop: repeated.prop("scrollHeight") - repeated.height() }, 1000);
						new_item.addClass('spare');
						hide_spares();
						renumber_repeater(repeater);
						verify_plusone_links();
					});
				}
			} 
			$(window).trigger('Perch.FieldTypes.redraw');
		});
	};

	var verify_plusone_links = function() {
		$('.repeater[data-max]').each(function(i,o){
			var repeater = $(o);
			var items = repeater.find('.repeated-item:not(.spare)');
			var max = parseInt(repeater.attr('data-max')); 
			if (items.length >= max) {
				repeater.find('a.plusone').addClass('disabled');
			}else{
				repeater.find('a.plusone').removeClass('disabled');
			}
		});
	};

	var clone_item = function(item, item_count, prefix) {
		var new_item = item.clone();
		var match_count = item_count-1;
		var match_str = prefix+'_'+match_count+'_';

		new_item.find('*[id*="'+match_str+'"]').each(function(i, o){
			var self = $(o);
			self.attr('id', self.attr('id').replace(match_str, prefix+'_'+item_count+'_'));
		});

		new_item.find('*[name*="'+match_str+'"]').each(function(i, o){
			var self = $(o);
			self.attr('name', self.attr('name').replace(match_str, prefix+'_'+item_count+'_'));
		});

		new_item.find('label[for*="'+match_str+'"]').each(function(i, o){
			var self = $(o);
			self.attr('for', self.attr('for').replace(match_str, prefix+'_'+item_count+'_'));
		});

		// catch all the data-* attributes for images etc. If an attribute matches, replace it.		
		var elements = new_item.get(0).getElementsByTagName("*");
		for(var i=0;i<elements.length;i++){
		    var element = elements[i];
		    var attr = element.attributes;
		    for(var j=0;j<attr.length;j++){
		        if(attr[j].value.indexOf(match_str)!=-1){
		            attr[j].value = attr[j].value.replace(match_str, prefix+'_'+item_count+'_');
		        }
		    }
		}

		new_item.find('.index span:not(.icon)').text(item_count+1);

		return new_item;
	};

	var renumber_repeater = function(repeater) {

		var items  = repeater.find('.repeated-item, .repeater-footer');
		var prefix = repeater.attr('data-prefix');

		items.each(function(i, obj){

			var new_item = $(obj);
			var item_count = i;
			var match_str = prefix+'_';
			var re = new RegExp(match_str+'([0-9]+)_', 'i');

			new_item.find('*[id^="'+match_str+'"]').each(function(i, o){
				var self = $(o);
				self.attr('id', self.attr('id').replace(re, prefix+'_'+item_count+'_'));
			});

			new_item.find('*[name^="'+match_str+'"]').each(function(i, o){
				var self = $(o);
				self.attr('name', self.attr('name').replace(re, prefix+'_'+item_count+'_'));
			});

			new_item.find('label[for^="'+match_str+'"]').each(function(i, o){
				var self = $(o);
				self.attr('for', self.attr('for').replace(re, prefix+'_'+item_count+'_'));
			});

			new_item.find('.index span:not(.icon)').text(item_count+1);

		});

		verify_plusone_links();
	};


	
	return {
		init: init
	};
	
}();

if (typeof(jQuery)!='undefined') {
	jQuery(function($) { 
		Perch.UI.Repeaters.init(); 
	});
}
