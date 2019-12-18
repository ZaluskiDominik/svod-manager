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
    },

    filterSubscriptions : function(callback) {
        Array.from(document.querySelectorAll('.subscriptions > ul > li')).forEach( (elem, index) => {
            elem.style.display = callback(elem, index) ? 'block' : 'none';
        });
    },

    removeSubFilters : function() {
        this.filterSubscriptions(() => { return true; });
    }
};
