'use strict'

const videoDescriptionEditor = {
    init : function() {
        tinymce.init({
            selector : '#video-description',
            height : 500,
            formats : {
                bold : {inline : 'b' }
            }
        });
    },

    getHtmlValue : function() {
        return tinymce.activeEditor.getContent();
    },

    getTextValue : function() {
        return tinymce.activeEditor.getContent({format: 'text'});
    },

    setHtmlValue : function(value) {
        tinymce.activeEditor.setContent(value);
    }
};

videoDescriptionEditor.init();
