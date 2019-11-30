'use strict'

let subscriptions = {
    init : function() {
        this.initSubscriptionsCollapsible();
    },

    initSubscriptionsCollapsible : function() {
        M.Collapsible.init(document.querySelectorAll('.subscriptions > ul'));
    }
};
