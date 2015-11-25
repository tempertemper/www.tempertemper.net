this["Handlebars"] = this["Handlebars"] || {};
this["Handlebars"]["templates"] = this["Handlebars"]["templates"] || {};
this["Handlebars"]["templates"]["asset-badge"] = Handlebars.template({"1":function(container,depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=container.escapeExpression;

  return "		<img src=\""
    + alias3(((helper = (helper = helpers.thumburl || (depth0 != null ? depth0.thumburl : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"thumburl","hash":{},"data":data}) : helper)))
    + "\" width=\""
    + alias3(((helper = (helper = helpers.thumbwidth || (depth0 != null ? depth0.thumbwidth : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"thumbwidth","hash":{},"data":data}) : helper)))
    + "\" height=\""
    + alias3(((helper = (helper = helpers.thumbheight || (depth0 != null ? depth0.thumbheight : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"thumbheight","hash":{},"data":data}) : helper)))
    + "\" alt=\"Preview\" />\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=container.escapeExpression;

  return "<div class=\"asset-badge\" data-for=\""
    + alias3(((helper = (helper = helpers.asset_field || (depth0 != null ? depth0.asset_field : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"asset_field","hash":{},"data":data}) : helper)))
    + "\">\n	<div class=\"asset-badge-thumb\">\n"
    + ((stack1 = helpers["if"].call(depth0,(depth0 != null ? depth0.has_thumb : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "	</div>\n	<div class=\"asset-badge-meta\">\n		<div class=\"asset-badge-remove\">\n			<label for=\""
    + alias3(((helper = (helper = helpers.input_id || (depth0 != null ? depth0.input_id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"input_id","hash":{},"data":data}) : helper)))
    + "_remove\" class=\"inline\">Remove</label> \n			<input type=\"checkbox\" class=\"check \" id=\""
    + alias3(((helper = (helper = helpers.input_id || (depth0 != null ? depth0.input_id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"input_id","hash":{},"data":data}) : helper)))
    + "_remove\" name=\""
    + alias3(((helper = (helper = helpers.input_id || (depth0 != null ? depth0.input_id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"input_id","hash":{},"data":data}) : helper)))
    + "_remove\" value=\"1\" />\n		</div>\n		<ul class=\"meta\">\n			<li class=\"title\">"
    + alias3(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "</li>\n			<li>"
    + alias3(((helper = (helper = helpers.mime_display || (depth0 != null ? depth0.mime_display : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"mime_display","hash":{},"data":data}) : helper)))
    + "</li>\n			<li>"
    + alias3(((helper = (helper = helpers.width || (depth0 != null ? depth0.width : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"width","hash":{},"data":data}) : helper)))
    + " x "
    + alias3(((helper = (helper = helpers.height || (depth0 != null ? depth0.height : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"height","hash":{},"data":data}) : helper)))
    + " px @ "
    + alias3(((helper = (helper = helpers.display_filesize || (depth0 != null ? depth0.display_filesize : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"display_filesize","hash":{},"data":data}) : helper)))
    + "</li>\n		</ul>\n	</div>\n</div>";
},"useData":true});
this["Handlebars"]["templates"]["asset-chooser"] = Handlebars.template({"1":function(container,depth0,helpers,partials,data) {
    return "<a href=\"#\" class=\"add button\">"
    + container.escapeExpression((helpers.Lang || (depth0 && depth0.Lang) || helpers.helperMissing).call(depth0,"Add Asset",{"name":"Lang","hash":{},"data":data}))
    + "</a>";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2=container.escapeExpression;

  return "<div class=\"asset-chooser\">\n	<div class=\"asset-topbar\">\n		<div class=\"actions\">\n			<a href=\"#\" class=\"close icon asset-icon\"><span class=\"hidden\">"
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Close",{"name":"Lang","hash":{},"data":data}))
    + "</span></a>\n			"
    + ((stack1 = (helpers.hasPriv || (depth0 && depth0.hasPriv) || alias1).call(depth0,"assets.create",{"name":"hasPriv","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\n			<a href=\"#\" class=\"select button\">"
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Use Selected",{"name":"Lang","hash":{},"data":data}))
    + "</a>\n		</div>\n		<h2>"
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Select an Asset",{"name":"Lang","hash":{},"data":data}))
    + "</h2>\n	</div>\n	<div class=\"asset-drop\">\n		<form action=\""
    + alias2(((helper = (helper = helpers.upload_url || (depth0 != null ? depth0.upload_url : depth0)) != null ? helper : alias1),(typeof helper === "function" ? helper.call(depth0,{"name":"upload_url","hash":{},"data":data}) : helper)))
    + "\" id=\"asset-dropzone\" method=\"post\" enctype=\"multipart/form-data\">\n			<div class=\"fallback\">\n			    <input name=\"file\" type=\"file\" multiple />\n			    <input type=\"submit\" value=\""
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Upload",{"name":"Lang","hash":{},"data":data}))
    + "\" />\n			 </div>\n		</form>\n	</div>\n	<div class=\"asset-field\">\n		<div id=\"asset-filter\" class=\"asset-filter\">\n\n		</div>\n		<div class=\"inner\">\n\n		</div>\n	</div>\n</div>";
},"useData":true});
this["Handlebars"]["templates"]["asset-grid"] = Handlebars.template({"1":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=container.escapeExpression;

  return "	<div class=\"grid-asset asset-"
    + alias3(((helper = (helper = helpers.type || (depth0 != null ? depth0.type : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"type","hash":{},"data":data}) : helper)))
    + ((stack1 = helpers.unless.call(depth0,(depth0 != null ? depth0.has_thumb : depth0),{"name":"unless","hash":{},"fn":container.program(2, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\" tabindex=\"0\" data-id=\""
    + alias3(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"id","hash":{},"data":data}) : helper)))
    + "\">\n"
    + ((stack1 = helpers["if"].call(depth0,(depth0 != null ? depth0.has_thumb : depth0),{"name":"if","hash":{},"fn":container.program(4, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "		<div class=\"asset-meta"
    + ((stack1 = helpers["if"].call(depth0,(depth0 != null ? depth0.has_thumb : depth0),{"name":"if","hash":{},"fn":container.program(6, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\"><span class=\"title\">"
    + alias3(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "</span></div>\n		<span class=\"ind "
    + alias3(((helper = (helper = helpers.orientation || (depth0 != null ? depth0.orientation : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"orientation","hash":{},"data":data}) : helper)))
    + "\"></span>\n	</div>\n";
},"2":function(container,depth0,helpers,partials,data) {
    return " asset-icon";
},"4":function(container,depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=container.escapeExpression;

  return "			<img class=\"thumb "
    + alias3(((helper = (helper = helpers.orientation || (depth0 != null ? depth0.orientation : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"orientation","hash":{},"data":data}) : helper)))
    + "\" src=\""
    + alias3(((helper = (helper = helpers.thumburl || (depth0 != null ? depth0.thumburl : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"thumburl","hash":{},"data":data}) : helper)))
    + "\" alt=\""
    + alias3(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "\" data-width=\""
    + alias3(((helper = (helper = helpers.thumbwidth || (depth0 != null ? depth0.thumbwidth : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"thumbwidth","hash":{},"data":data}) : helper)))
    + "\" data-height=\""
    + alias3(((helper = (helper = helpers.thumbheight || (depth0 != null ? depth0.thumbheight : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"thumbheight","hash":{},"data":data}) : helper)))
    + "\" />\n";
},"6":function(container,depth0,helpers,partials,data) {
    return " with-thumb";
},"8":function(container,depth0,helpers,partials,data) {
    return "	<p class=\"alert notice\">"
    + container.escapeExpression((helpers.Lang || (depth0 && depth0.Lang) || helpers.helperMissing).call(depth0,"No matching assets found",{"name":"Lang","hash":{},"data":data}))
    + "</p>\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1;

  return ((stack1 = helpers.each.call(depth0,(depth0 != null ? depth0.assets : depth0),{"name":"each","hash":{},"fn":container.program(1, data, 0),"inverse":container.program(8, data, 0),"data":data})) != null ? stack1 : "");
},"useData":true});
this["Handlebars"]["templates"]["asset-list"] = Handlebars.template({"1":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=container.escapeExpression;

  return "	<tr>\n		<td class=\"primary\"><a class=\"list-asset-title\" href=\"#\" data-id=\""
    + alias3(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"id","hash":{},"data":data}) : helper)))
    + "\">"
    + alias3(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "</a></td>\n		<td class=\"asset-icon-cell\"><span class=\"icon asset-icon asset-"
    + alias3(((helper = (helper = helpers.type || (depth0 != null ? depth0.type : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"type","hash":{},"data":data}) : helper)))
    + "\"></span></td>\n		<td>"
    + alias3(((helper = (helper = helpers.mime_display || (depth0 != null ? depth0.mime_display : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"mime_display","hash":{},"data":data}) : helper)))
    + "</td>\n		<td>"
    + ((stack1 = helpers["if"].call(depth0,(depth0 != null ? depth0.width : depth0),{"name":"if","hash":{},"fn":container.program(2, data, 0),"inverse":container.program(4, data, 0),"data":data})) != null ? stack1 : "")
    + "</td>\n		<td>"
    + alias3(((helper = (helper = helpers.display_filesize || (depth0 != null ? depth0.display_filesize : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"display_filesize","hash":{},"data":data}) : helper)))
    + "</td>\n	</tr>\n";
},"2":function(container,depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=container.escapeExpression;

  return alias3(((helper = (helper = helpers.width || (depth0 != null ? depth0.width : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"width","hash":{},"data":data}) : helper)))
    + " x "
    + alias3(((helper = (helper = helpers.height || (depth0 != null ? depth0.height : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"height","hash":{},"data":data}) : helper)));
},"4":function(container,depth0,helpers,partials,data) {
    return " - ";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, alias1=helpers.helperMissing, alias2=container.escapeExpression;

  return "<table class=\"list-asset\">\n    <thead>\n        <tr>\n            <th class=\"first\">"
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Name",{"name":"Lang","hash":{},"data":data}))
    + "</th>\n            <th></th>\n            <th>"
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Type",{"name":"Lang","hash":{},"data":data}))
    + "</th>\n            <th>"
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Dimensions",{"name":"Lang","hash":{},"data":data}))
    + "</th>\n            <th>"
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Size",{"name":"Lang","hash":{},"data":data}))
    + "</th>\n        </tr>\n    </thead>\n    <tbody>\n"
    + ((stack1 = helpers.each.call(depth0,(depth0 != null ? depth0.assets : depth0),{"name":"each","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "	</tbody>\n</table>";
},"useData":true});
this["Handlebars"]["templates"]["asset-static-drop"] = Handlebars.template({"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2=container.escapeExpression;

  return "<div class=\"asset-drop static\">\n	<form action=\""
    + alias2(((helper = (helper = helpers.upload_url || (depth0 != null ? depth0.upload_url : depth0)) != null ? helper : alias1),(typeof helper === "function" ? helper.call(depth0,{"name":"upload_url","hash":{},"data":data}) : helper)))
    + "\" id=\"asset-dropzone\" method=\"post\" enctype=\"multipart/form-data\">\n		<div class=\"fallback\">\n		    <input name=\"file\" type=\"file\" multiple />\n		    <input type=\"submit\" value=\""
    + alias2((helpers.Lang || (depth0 && depth0.Lang) || alias1).call(depth0,"Upload",{"name":"Lang","hash":{},"data":data}))
    + "\" />\n		 </div>\n	</form>\n</div>\n";
},"useData":true});