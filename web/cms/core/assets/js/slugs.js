if (typeof(Perch) == 'undefined') {
	Perch	= {};
	Perch.UI	= {};
	Perch.Apps	= {};
}

Perch.UI.Slugs	= function()
{
	var init = function() {
		$('input[data-slug-for]').each(function(i, o){
			var field = $(o);
			var targets = field.attr('data-slug-for').split(' ');
			var form = field.parents('form');

			form.on('change', '#'+targets.join(', #'), function(e){

				var out = [], i, l;
				for(i=0,l=targets.length; i<l; i++) {
					out.push($('#'+targets[i]).val());
				}
				out = out.join(' ');
				if (out.length) {
					$.get(Perch.path+'/core/async/urlify.php?s='+encodeURIComponent(out), function(r){
						field.val(r);
					});
				}
			});
		});
	};

	return {
		init: init
	};
	
}();

jQuery(function($) { Perch.UI.Slugs.init(); });