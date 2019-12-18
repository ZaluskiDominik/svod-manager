'use strict'

const purchaseSubDialog = {
    dialogNode : null,

    init : function() {
        this.dialogNode = document.querySelector("#sub-purchase-dialog");
        M.Modal.init(this.dialogNode);
    },

    open : function() {
        M.Modal.getInstance(this.dialogNode).open();
    }
};

purchaseSubDialog.init();
