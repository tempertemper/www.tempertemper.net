if (typeof(Perch) == 'undefined') {
	Perch	= {};
	Perch.UI	= {};
	Perch.Apps	= {};
}

Perch.UI.Categories	= function()
{
	var init = function() {
		$('head').append('<link rel="stylesheet" href="'+Perch.path+'/core/assets/css/chosen.min.css'+'" type="text/css" />');
		$(window).load(function() {
			$('select.categories').chosen();
		});
	};
	
	return {
		init: init
	};
	
}();

jQuery(function($) { Perch.UI.Categories.init(); });