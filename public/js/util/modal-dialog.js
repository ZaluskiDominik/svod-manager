'use strict'

class ModalDialog
{
    constructor(dialogId)
    {
        this.dialogNode = document.querySelector('#' + dialogId);
        M.Modal.init(this.dialogNode);
    }

    open()
    {
        M.Modal.getInstance(this.dialogNode).open();
    }
}
