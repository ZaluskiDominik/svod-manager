'use strict'

let user = {
    fromJsonObject : function(data) {
        this.id = data.id;
        this.email = data.email;
        this.firstName = data.firstName;
        this.surname = data.surname;
    },

    updateAccountBalance : function(value) {
        this.accountBalance = Math.round(value * 100) / 100;
        document.querySelector(".account-balance-val").innerHTML = this.accountBalance;
    }
};
