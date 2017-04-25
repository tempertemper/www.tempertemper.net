$(function() {


	$.Redactor.prototype.perchassets = function()
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
	        	var textarea = this.core.textarea();
	        	var asset_type = textarea.attr('data-type');
	        	
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

				var this_redactor = this;

				Perch.UI.Assets.choose(opts, function(result){
					this_redactor.insert.html(result.embed);
				});      
	        }
	    };
	};


	var set_up_redactor = function() {
		$('textarea.redactor:not([data-init])').each(function(i,o){
			var self = $(o);
			var uploadFields = {
					'width'	 : 	self.attr('data-width')||'',
					'height' : 	self.attr('data-height')||'',
					'crop'	 : 	self.attr('data-crop')||'',
					'quality': 	self.attr('data-quality')||'',
					'sharpen': 	self.attr('data-sharpen')||'',
					'density': 	self.attr('data-density')||'',
					'bucket' : 	self.attr('data-bucket')||'default'

				};
			self.wrap('<div class="editor-wrap"></div>');
			self.redactor({
				//imageUpload: 'PERCH_LOGINPATH/addons/plugins/editors/redactor/perch/upload.php?filetype=image',
				//fileUpload: 'PERCH_LOGINPATH/addons/plugins/editors/redactor/perch/upload.php',
				//fileUploadFields: uploadFields,
				//imageUploadFields: uploadFields,
				plugins: ['perchassets']
			});
			self.attr('data-init', true);
		});
	};

	set_up_redactor();

	$(window).on('Perch_Init_Editors', function(){
		set_up_redactor();
	});

});