(function() {
var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['asset-badge'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, helper, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n		<img src=\"";
  if (helper = helpers.thumburl) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.thumburl); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" width=\"";
  if (helper = helpers.thumbwidth) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.thumbwidth); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" height=\"";
  if (helper = helpers.thumbheight) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.thumbheight); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" alt=\"Preview\" />\n		";
  return buffer;
  }

  buffer += "<div class=\"asset-badge\" data-for=\"";
  if (helper = helpers.asset_field) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.asset_field); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\">\n	<div class=\"asset-badge-thumb\">\n		";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.has_thumb), {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n	</div>\n	<div class=\"asset-badge-meta\">\n		<div class=\"asset-badge-remove\">\n			<label for=\"";
  if (helper = helpers.input_id) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.input_id); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "_remove\" class=\"inline\">Remove</label> \n			<input type=\"checkbox\" class=\"check \" id=\"";
  if (helper = helpers.input_id) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.input_id); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "_remove\" name=\"";
  if (helper = helpers.input_id) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.input_id); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "_remove\" value=\"1\" />\n		</div>\n		<ul class=\"meta\">\n			<li class=\"title\">";
  if (helper = helpers.title) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.title); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</li>\n			<li>";
  if (helper = helpers.mime_display) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.mime_display); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</li>\n			<li>";
  if (helper = helpers.width) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.width); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + " x ";
  if (helper = helpers.height) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.height); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + " px @ ";
  if (helper = helpers.display_filesize) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.display_filesize); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</li>\n		</ul>\n	</div>\n</div>";
  return buffer;
  });

templates['asset-chooser'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, helper, options, helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression, self=this, functionType="function";

function program1(depth0,data) {
  
  var buffer = "", helper, options;
  buffer += "<a href=\"#\" class=\"add button\">"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Add Asset", options) : helperMissing.call(depth0, "Lang", "Add Asset", options)))
    + "</a>";
  return buffer;
  }

  buffer += "<div class=\"asset-chooser\">\n	<div class=\"asset-topbar\">\n		<div class=\"actions\">\n			<a href=\"#\" class=\"close icon asset-icon\"><span class=\"hidden\">"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Close", options) : helperMissing.call(depth0, "Lang", "Close", options)))
    + "</span></a>\n			";
  stack1 = (helper = helpers.hasPriv || (depth0 && depth0.hasPriv),options={hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data},helper ? helper.call(depth0, "assets.create", options) : helperMissing.call(depth0, "hasPriv", "assets.create", options));
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n			<a href=\"#\" class=\"select button\">"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Use Selected", options) : helperMissing.call(depth0, "Lang", "Use Selected", options)))
    + "</a>\n		</div>\n		<h2>"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Select an Asset", options) : helperMissing.call(depth0, "Lang", "Select an Asset", options)))
    + "</h2>\n	</div>\n	<div class=\"asset-drop\">\n		<form action=\"";
  if (helper = helpers.upload_url) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.upload_url); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" id=\"asset-dropzone\" method=\"post\" enctype=\"multipart/form-data\">\n			<div class=\"fallback\">\n			    <input name=\"file\" type=\"file\" multiple />\n			    <input type=\"submit\" value=\""
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Upload", options) : helperMissing.call(depth0, "Lang", "Upload", options)))
    + "\" />\n			 </div>\n		</form>\n	</div>\n	<div class=\"asset-field\">\n		<div id=\"asset-filter\" class=\"asset-filter\">\n\n		</div>\n		<div class=\"inner\">\n\n		</div>\n	</div>\n</div>";
  return buffer;
  });

templates['asset-grid'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var stack1, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n	<div class=\"grid-asset asset-";
  if (helper = helpers.type) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.type); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1);
  stack1 = helpers.unless.call(depth0, (depth0 && depth0.has_thumb), {hash:{},inverse:self.noop,fn:self.program(2, program2, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\" tabindex=\"0\" data-id=\"";
  if (helper = helpers.id) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.id); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\">\n		<div class=\"asset-meta";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.has_thumb), {hash:{},inverse:self.noop,fn:self.program(4, program4, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\"><span class=\"title\">";
  if (helper = helpers.title) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.title); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</span></div>\n		";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.has_thumb), {hash:{},inverse:self.noop,fn:self.program(6, program6, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n	</div>\n";
  return buffer;
  }
function program2(depth0,data) {
  
  
  return " asset-icon";
  }

function program4(depth0,data) {
  
  
  return " with-thumb";
  }

function program6(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n			<img class=\"thumb\" src=\"";
  if (helper = helpers.thumburl) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.thumburl); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" alt=\"";
  if (helper = helpers.title) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.title); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" width=\"";
  if (helper = helpers.thumbwidth) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.thumbwidth); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" height=\"";
  if (helper = helpers.thumbheight) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.thumbheight); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\" />\n		";
  return buffer;
  }

function program8(depth0,data) {
  
  var buffer = "", helper, options;
  buffer += "\n	<p class=\"alert notice\">"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "No matching assets found", options) : helperMissing.call(depth0, "Lang", "No matching assets found", options)))
    + "</p>\n";
  return buffer;
  }

  stack1 = helpers.each.call(depth0, (depth0 && depth0.assets), {hash:{},inverse:self.program(8, program8, data),fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { return stack1; }
  else { return ''; }
  });

templates['asset-list'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, helper, options, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1, helper;
  buffer += "\n	<tr>\n		<td class=\"primary\"><a class=\"list-asset-title\" href=\"#\" data-id=\"";
  if (helper = helpers.id) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.id); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (helper = helpers.title) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.title); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</a></td>\n		<td class=\"asset-icon-cell\"><span class=\"icon asset-icon asset-";
  if (helper = helpers.type) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.type); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "\"></span></td>\n		<td>";
  if (helper = helpers.mime_display) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.mime_display); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</td>\n		<td>";
  stack1 = helpers['if'].call(depth0, (depth0 && depth0.width), {hash:{},inverse:self.program(4, program4, data),fn:self.program(2, program2, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</td>\n		<td>";
  if (helper = helpers.display_filesize) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.display_filesize); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + "</td>\n	</tr>\n";
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = "", stack1, helper;
  if (helper = helpers.width) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.width); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1)
    + " x ";
  if (helper = helpers.height) { stack1 = helper.call(depth0, {hash:{},data:data}); }
  else { helper = (depth0 && depth0.height); stack1 = typeof helper === functionType ? helper.call(depth0, {hash:{},data:data}) : helper; }
  buffer += escapeExpression(stack1);
  return buffer;
  }

function program4(depth0,data) {
  
  
  return " - ";
  }

  buffer += "<table class=\"list-asset\">\n    <thead>\n        <tr>\n            <th class=\"first\">"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Name", options) : helperMissing.call(depth0, "Lang", "Name", options)))
    + "</th>\n            <th></th>\n            <th>"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Type", options) : helperMissing.call(depth0, "Lang", "Type", options)))
    + "</th>\n            <th>"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Dimensions", options) : helperMissing.call(depth0, "Lang", "Dimensions", options)))
    + "</th>\n            <th>"
    + escapeExpression((helper = helpers.Lang || (depth0 && depth0.Lang),options={hash:{},data:data},helper ? helper.call(depth0, "Size", options) : helperMissing.call(depth0, "Lang", "Size", options)))
    + "</th>\n        </tr>\n    </thead>\n    <tbody>\n";
  stack1 = helpers.each.call(depth0, (depth0 && depth0.assets), {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n	</tbody>\n</table>";
  return buffer;
  });
}());