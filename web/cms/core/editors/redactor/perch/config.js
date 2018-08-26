jQuery(function() {

    (function($R) {
        $R.add('plugin', 'perchassets', {
            init: function(app)
            {
                this.app     = app;
                this.toolbar = app.toolbar; 
            },
            start: function()
            {
                var source    = this.app.source.getElement();
                this.textarea = source.nodes[0];

                var img_button = this.toolbar.addButton('perchassets_img', {
                        title: 'Image',
                        api: 'plugin.perchassets.chooser',
                        icon: '<i class="re-icon-image"></i>',
                        args: {
                            type: 'img',
                        }
                    });
                var file_button = this.toolbar.addButton('perchassets_file', {
                        title: 'File',
                        api: 'plugin.perchassets.chooser',
                        icon: '<i class="re-icon-file"></i>',
                        args: {
                            type: 'file',
                        }
                    });
            },
            chooser: function(args)
            {
                var this_redactor = this.app;
                
                var opts = {
                    field:    this.textarea.getAttribute('id'),
                    bucket:   this.textarea.getAttribute('data-bucket'),
                    type:     args.type
                };

                this_redactor.selection.save();
                Perch.UI.Assets.choose(opts, function(result){    
                    this_redactor.selection.restore();                                      
                    this_redactor.insertion.insertHtml(result.embed);
                });
            }
        });
    })(Redactor);
        

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
                plugins: ['perchassets'],
            };

            
        jQuery('textarea.redactor:not([data-init])').each(function(i,o){
            var self = $(o);

            
            if (!self.parents('.spare').length) {

                self.wrap('<div class="editor-wrap"></div>');   

                if (typeof Perch.UserConfig.redactor != 'undefined') {
                    config = Perch.UserConfig.redactor.get(self.attr('data-editor-config'), config, self);
                    $R(self.get(0), config);
                } else {
                    $R(self.get(0), config);
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