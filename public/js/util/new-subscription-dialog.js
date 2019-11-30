'use strict'

let newSubscriptionDialog = {
    dialogNode : null,

    init : function() {
        this.initDialog();
    },

    refreshVideosSelect : function() {
        setTimeout(newSubscriptionDialog.initVideosSelect.bind(newSubscriptionDialog), 100);
    },

    initVideosSelect : function() {
        M.FormSelect.init(this.dialogNode.querySelector('select'));
    },

    initDialog : function() {
        this.dialogNode = document.querySelector('#new-subscription-dialog');
        M.Modal.init(this.dialogNode);
    },

    open : function() {
        M.Modal.getInstance(this.dialogNode).open();
    },

    getSelectedVideoIndex : function() {
        return Array.from(this.dialogNode.querySelectorAll('.select-wrapper ul > li')).findIndex( (elem) => {
            return elem.className.indexOf('selected') !== -1;
        });
    }
};
