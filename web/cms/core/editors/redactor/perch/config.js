jQuery(function() {

	jQuery.Redactor.prototype.perchassets = function()
	{
	    return {
	        init: function ()
	        {
	            var img_button = this.button.add('perchassets_img', 'Image');
	            this.button.setIcon(img_button, '<i class="re-icon-image"></i>');
	            this.button.addCallback(img_button, this.perchassets.chooser);

	            var file_button = this.button.add('perchassets_file', 'File');
	            this.button.setIcon(file_button, '<i class="re-icon-file"></i>');
	            this.button.addCallback(file_button, this.perchassets.chooser);
	            
	        },
	        chooser: function(buttonName)
	        {
				var this_redactor = this;
				var textarea      = this_redactor.core.textarea();
				var asset_type    = textarea.attr('data-type');
	        	
	        	if (!asset_type) {
	        		asset_type = 'file';
	        		if (buttonName == 'perchassets_img') {
	        			asset_type = 'img';
	        		}
	        	}

	        	var opts = {
					field: 	  textarea.attr('id'),
					bucket:   textarea.attr('data-bucket'),
					type:     asset_type
				};

				Perch.UI.Assets.choose(opts, function(result){
					this_redactor.insert.html(result.embed);
				});      

	        }
	    };
	};

	var set_up_redactor = function() {
		
		if (typeof Perch.UserConfig.redactor != 'undefined') {
			Perch.UserConfig.redactor.load(function(){	
				create_editors();
			});
		} else {
			create_editors();
		}
	};

	var create_editors = function() {

		var config = {
				plugins: ['perchassets']
			};

		jQuery('textarea.redactor:not([data-init])').each(function(i,o){
			var self = $(o);

			if (!self.parents('.spare').length) {

				self.wrap('<div class="editor-wrap"></div>');	

				if (typeof Perch.UserConfig.redactor != 'undefined') {
					config = Perch.UserConfig.redactor.get(self.attr('data-editor-config'), config, self);
					self.redactor(config);
				} else {
					self.redactor(config);	
				}
				
				self.attr('data-init', true);
			};
			
		});

	};

	set_up_redactor();

	jQuery(window).on('Perch_Init_Editors', function(){
		set_up_redactor();
	});

	jQuery(window).on('Perch.FieldTypes.redraw', function(){
		set_up_redactor();
	});

});