var Handlebars = require('handlebars/runtime');

module.exports = function(path, size, custom_class) {
	var parts  = path.split('/');
	if (typeof(size)=='object') size = 16;
	if (typeof(custom_class)=='object') custom_class = null;

	if (custom_class) {
		custom_class = ' icon-' + custom_class;
	} else {
		custom_class = '';
	}



	return new Handlebars.SafeString('<svg width="'+size+'" height="'+size+'" class="icon icon-'+(parts[1]+custom_class)+'"><use xlink:href="'+Perch.path+'/core/assets/svg/'+parts[0]+'.svg#'+parts[1]+'" /></svg>');
};