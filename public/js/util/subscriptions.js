'use strict'

let subscriptions = {
    init : function() {
        this.initSubscriptionsCollapsible();
        this.resetFilterRadioButtons();
    },

    initSubscriptionsCollapsible : function() {
        M.Collapsible.init(document.querySelectorAll('.subscriptions > ul'));
    },

    resetFilterRadioButtons : function() {
        Array.from(document.querySelectorAll(".sub-radios-container input")).forEach( (elem, index) => {
            elem.checked = (index === 0);
        });
    },

    removeSubscriptionNode : function(subIndex) {
        document.querySelectorAll('subscriptions li').forEach( (elem, index) => {
            if (index === subIndex) {
                elem.remove();
            }
        })
    }
};
