$(function() {

	var set_up_simplemde = function() {

		var timer = null;
		var prev_preview = '...';
		var sizes = ['xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl'];

		var debounce = function(fn, delay) 
		{
			return function () {
				var context = this, args = arguments;
				clearTimeout(timer);
				timer = setTimeout(function () {
					fn.apply(context, args);
				}, delay);
			};
		};

		$('textarea.simplemde:not([data-init])').each(function(i,o){
			var self = $(o);

			if (!self.parents('.spare').length) {
				
				self.wrap('<div class="editor-wrap simplemde-wrap"></div>');
				for (var i in sizes) {
					if (self.is('.'+sizes[i])) {
						self.parents('.editor-wrap').addClass(sizes[i]);
					}
				}

				var config = { 
						element: self[0],
						status: false,
						spellChecker: false,
						autoDownloadFontAwesome: false,
						toolbar: [
							{
								name: "bold",
								action: SimpleMDE.toggleBold,
								className: "icon-bold",
								title: "Bold"
							},
							{
								name: "italic",
								action: SimpleMDE.toggleItalic,
								className: "icon-italic",
								title: "Italic"
							},
							{
								name: "heading",
								action: SimpleMDE.toggleHeadingSmaller,
								className: "icon-header",
								title: "Heading"
							},
							'|',
							{
								name: "quote",
								action: SimpleMDE.toggleBlockquote,
								className: "icon-quote-left",
								title: "Quote"
							},
							{
								name: "unordered-list",
								action: SimpleMDE.toggleUnorderedList,
								className: "icon-list-bullet",
								title: "Generic List"
							},
							{
								name: "ordered-list",
								action: SimpleMDE.toggleOrderedList,
								className: "icon-list-numbered",
								title: "Numbered List"
							},
							'|',
							{
								name: "link",
								action: SimpleMDE.drawLink,
								className: "icon-link",
								title: "Create Link"
							},
							{
								name: "image",
								action: function(editor){
									var textarea = $(editor.element);
									var opts = {
										field: 	  textarea.attr('id'),
										bucket:   textarea.attr('data-bucket'),
										type:     textarea.attr('data-type') || 'img',
									};

									Perch.UI.Assets.choose(opts, function(result){
										var cm = editor.codemirror;
										cm.replaceSelection(result.embed);
									});  
								},
								className: "icon-picture",
								title: "Insert Image"
							},
							{
								name: "file",
								action: function(editor){
									var textarea = $(editor.element);
									var opts = {
										field: 	  textarea.attr('id'),
										bucket:   textarea.attr('data-bucket'),
										type:     textarea.attr('data-type') || 'file',
									};

									Perch.UI.Assets.choose(opts, function(result){
										var cm = editor.codemirror;
										cm.replaceSelection(result.embed);
									});  
								},
								className: "icon-doc",
								title: "Insert File"
							},
							'|',
							{
								name: "preview",
								action: SimpleMDE.togglePreview,
								className: "icon-eye no-disable",
								title: "Toggle Preview"
							},
							{
								name: "side-by-side",
								action: SimpleMDE.toggleSideBySide,
								className: "icon-columns no-disable no-mobile",
								title: "Toggle Side by Side"
							},
							{
								name: "fullscreen",
								action: SimpleMDE.toggleFullScreen,
								className: "icon-resize-full-alt no-disable no-mobile",
								title: "Toggle Fullscreen"
							}
					    ],
					    previewRender: function(plainText, preview) { // Async method
					    	
					    	var textarea = $(this.element);
					    	var url = Perch.path+'/core/apps/content/async/preview.php';

					    	debounce(function(){
								$.post(url, {
										text: plainText,
										tag64: textarea.attr('data-source'),
									}, function(r){
										if (r!=prev_preview) {
											preview.innerHTML = r;
											prev_preview = r;	
										}
									});
					    	}, 500)();

							return prev_preview;
						},
					};


				var simplemde = new SimpleMDE(config);

				simplemde.codemirror.on("focus", function(){
				    $(simplemde.element).parents('.simplemde-wrap').addClass('active');
				});
				simplemde.codemirror.on("blur", function(){
				    $(simplemde.element).parents('.simplemde-wrap').removeClass('active');
				});
				self.attr('data-init', true);

			}
		});


	};

	set_up_simplemde();

	$(window).on('Perch_Init_Editors', function(){
		set_up_simplemde();
	});

	$(window).on('Perch.FieldTypes.redraw', function(){
		set_up_simplemde();
	});

});