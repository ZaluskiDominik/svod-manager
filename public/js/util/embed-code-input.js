'use strict'

const embedCodeInput = {
    setValue : function(value) {
        const embedCodeNode = document.querySelector('#embed-code');
        embedCodeNode.value = value;
        M.textareaAutoResize(embedCodeNode);
        if (value !== '') {
            document.querySelector('label[for="embed-code"]').classList.add('active');
        }
    }
};
