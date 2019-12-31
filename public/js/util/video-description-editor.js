'use strict'

const videoDescriptionEditor = {
    init : function() {
        tinymce.init({
            selector : '#video-description',
            height : 500
        });
    },

    getHtmlValue : function() {
        return tinymce.activeEditor.getContent();
    },

    getTextValue : function() {
        return tinymce.activeEditor.getContent({format: 'text'});
    }
};

videoDescriptionEditor.init();