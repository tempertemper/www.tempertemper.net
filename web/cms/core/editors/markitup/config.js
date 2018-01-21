	// mIu nameSpace to avoid conflict.
	var miu = function(jQuery) {

		return {
			markdownTitle: function(markItUp, ch) {
				heading = '';
				n = $.trim(markItUp.selection||markItUp.placeHolder).length;
				for(i = 0; i < n; i++) {
					heading += ch;
				}
				return '\n'+heading;
			}
		};
	}(jQuery);

	

	textileSettings = {
		nameSpace: "textile",
		onTab: {keepDefault:false, openWith:'    '},
		previewParserPath:	'', // path to your Textile parser
		onShiftEnter:		{keepDefault:false, replaceWith:'\n\n'},
		markupSet: [
			{name:'Heading', key:'1', className:'fa fa-header', dropMenu: [
	            {name:'Heading 1', className:'fa fa-header', openWith:'h1(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
	            {name:'Heading 2', className:'fa fa-header', openWith:'h2(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
	            {name:'Heading 3', className:'fa fa-header', openWith:'h3(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
	            {name:'Heading 4', className:'fa fa-header', openWith:'h4(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
	            {name:'Heading 5', className:'fa fa-header', openWith:'h5(!(([![Class]!]))!). ', placeHolder:'Your title here...' },
	            {name:'Heading 6', className:'fa fa-header', openWith:'h6(!(([![Class]!]))!). ', placeHolder:'Your title here...' }
	            ]},
			{name:'Bold', className:'fa fa-bold', key:'B', closeWith:'*', openWith:'*'},
			{name:'Italic', className:'fa fa-italic', key:'I', closeWith:'_', openWith:'_'},
			{name:'Stroke through', className:'fa fa-strikethrough', closeWith:'-', openWith:'-'},
			{name:'Quotes', className:'fa fa-quote-left', openWith:'bq. '},
			{name:'Paragraph', className:'fa fa-paragraph', key:'P', openWith:'p(!(([![Class]!]))!). '},
			{name:'Bulleted list', className:'fa fa-list-ul', openWith:'(!(* |!|*)!)'},
			{name:'Numeric list', className:'fa fa-list-ol', openWith:'(!(# |!|#)!)'}, 
			{name:'Picture', className:'image-upload fa fa-picture-o', closeWith:function(markItUp){miu.PerchAssets.upload(markItUp,'textile');}}, 
			{name:'File', className:'file-upload fa fa-file-o', closeWith:function(markItUp){miu.PerchAssets.upload(markItUp,'textile',true);}}, 
			{name:'Link', className:'fa fa-link', openWith:'"', closeWith:'":[![Link:!:http://]!]', placeHolder:'Your text to link here...' }
		]
	};

	markdownSettings = {
		nameSpace: 'markdown',
		previewParserPath:	'',
		onTab: {keepDefault:false, openWith:'    '},
		onShiftEnter:		{keepDefault:false, openWith:'\n\n'},
		markupSet: [
			{name:'Heading', key:'1', className:'fa fa-header', dropMenu: [
	            {name:'Heading 1', className:'fa fa-header', openWith:'# ', 	 placeHolder:'Your title here...' },
	            {name:'Heading 2', className:'fa fa-header', openWith:'## ', 	 placeHolder:'Your title here...' },
	            {name:'Heading 3', className:'fa fa-header', openWith:'### ', 	 placeHolder:'Your title here...' },
	            {name:'Heading 4', className:'fa fa-header', openWith:'#### ', 	 placeHolder:'Your title here...' },
	            {name:'Heading 5', className:'fa fa-header', openWith:'##### ',  placeHolder:'Your title here...' },
	            {name:'Heading 6', className:'fa fa-header', openWith:'###### ', placeHolder:'Your title here...' }
	            ]},	
			{name:'Bold', className:'fa fa-bold', key:'B', openWith:'**', closeWith:'**'},
			{name:'Italic', className:'fa fa-italic', key:'I', openWith:'_', closeWith:'_'},
			{name:'Quotes', className:'fa fa-quote-left', openWith:'> '},
			{name:'Bulleted List', className:'fa fa-list-ul', openWith:'- ' },
			{name:'Numeric List', className:'fa fa-list-ol', openWith:function(markItUp) {
				return markItUp.line+'. ';
			}},
			{name:'Picture', className:'image-upload fa fa-picture-o', openWith:function(markItUp){miu.PerchAssets.upload(markItUp,'markdown');}}, 
			{name:'File', className:'file-upload fa fa-file-o', openWith:function(markItUp){miu.PerchAssets.upload(markItUp,'markdown',true);}},
			{name:'Link', className:'fa fa-link', key:'L', openWith:'[', closeWith:']([![URL:!:http://]!] "[![Title]!]")', placeHolder:'Your text to link here...' }
		]
	};

	htmlSettings = {
		nameSpace: 'html',
		onShiftEnter:	{keepDefault:false, replaceWith:'<br />\n'},
		onCtrlEnter:	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>\n'},
		onTab:			{keepDefault:false, openWith:'	 '},
		markupSet: [
			{name:'Heading', key:'1', className:'fa fa-header', dropMenu: [
	            {name:'Heading 1', className:'fa fa-header', openWith:'<h1(!( class="[![Class]!]")!)>', closeWith:'</h1>', placeHolder:'Your title here...' },
	            {name:'Heading 2', className:'fa fa-header', openWith:'<h2(!( class="[![Class]!]")!)>', closeWith:'</h2>', placeHolder:'Your title here...' },
	            {name:'Heading 3', className:'fa fa-header', openWith:'<h3(!( class="[![Class]!]")!)>', closeWith:'</h3>', placeHolder:'Your title here...' },
	            {name:'Heading 4', className:'fa fa-header', openWith:'<h4(!( class="[![Class]!]")!)>', closeWith:'</h4>', placeHolder:'Your title here...' },
	            {name:'Heading 5', className:'fa fa-header', openWith:'<h5(!( class="[![Class]!]")!)>', closeWith:'</h5>', placeHolder:'Your title here...' },
	            {name:'Heading 6', className:'fa fa-header', openWith:'<h6(!( class="[![Class]!]")!)>', closeWith:'</h6>', placeHolder:'Your title here...' }
	            ]},
			{name:'Bold', className:'fa fa-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
			{name:'Italic', className:'fa fa-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)' },
			{name:'Stroke through', className:'fa fa-strikethrough', openWith:'<del>', closeWith:'</del>' },
			{name:'Paragraph', className:'fa fa-paragraph', openWith:'<p(!( class="[![Class]!]")!)>', closeWith:'</p>' },
			{name:'UL', className:'fa fa-list-ul', openWith:'<ul>\n', closeWith:'</ul>\n' },
			{name:'OL', className:'fa fa-list-ol', openWith:'<ol>\n', closeWith:'</ol>\n' },
			{name:'LI', className:'fa fa-minus', openWith:'<li>', closeWith:'</li>' },
			{name:'Picture', className:'image-upload fa fa-picture-o', closeWith:function(markItUp){miu.PerchAssets.upload(markItUp,'html');}}, 
			{name:'File', className:'file-upload fa fa-file-o', closeWith:function(markItUp){miu.PerchAssets.upload(markItUp,'html',true);}},
			{name:'Link', className:'fa fa-link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' }
		]
	};


	var set_up_markitup = function(){

		if (typeof Perch.UserConfig.markitup != 'undefined') {
			Perch.UserConfig.markitup.load(function(){
				var editors = $('textarea.markitup:not(.markItUpEditor)');
				editors.each(function(i, o){
					var self = $(o);
					config = Perch.UserConfig.markitup.get(self.attr('data-editor-config'), markdownSettings, self);
					self.markItUp(config);
				});
			});
		} else {
			$('textarea.markitup.textile:not(.markItUpEditor)').markItUp(textileSettings);
			$('textarea.markitup.markdown:not(.markItUpEditor)').markItUp(markdownSettings);
			$('textarea.markitup.html:not(.markItUpEditor)').markItUp(htmlSettings);	
		}
	
	}

	$(window).on('Perch_Init_Editors', function(){
		   set_up_markitup();
	});

	set_up_markitup();

	$('body').on('focus', 'textarea.markitup', function(e){
		$('.markItUpContainer').removeClass('active');
		$(e.target).parents('.markItUpContainer').addClass('active');	
	});

	$('body').on('blur', 'textarea.markitup', function(e){
		$(e.target).parents('.markItUpContainer').removeClass('active');
	});

