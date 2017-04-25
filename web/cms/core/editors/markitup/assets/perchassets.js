miu.PerchAssets = function(jQuery)
{
	var upload =  function(markItUp, language, file_upload) {
		var textareaID = markItUp.textarea.id;
		var textarea = jQuery('#'+textareaID);
		var output = '';

		var opts = {
			field: 	  textareaID,
			bucket:   textarea.attr('data-bucket'),
			type:     textarea.attr('data-type')
		};

		Perch.UI.Assets.choose(opts, function(result){
			var embed_tag  = result.embed;
			set_caret_position(textareaID, markItUp.caretPosition, 0);
			jQuery.markItUp({ target:'#'+textareaID, openWith:embed_tag, closeWith:' '} );
		});

		return true;
	};

	var set_caret_position = function(textareaID, start, len) {
		var textarea = jQuery('#'+textareaID).get(0);
		if (textarea.createTextRange){
			range = textarea.createTextRange();
			range.collapse(true);
			range.moveStart('character', start);
			range.moveEnd('character', len);
			range.select();
		}else if(textarea.setSelectionRange){
			textarea.setSelectionRange(start, start + len);
		}
		textarea.focus();
	};

	return {
		upload: upload
	};
}(jQuery);
