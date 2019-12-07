'use strict'

let user = {
    fromJsonObject : function(data) {
        this.id = data.id;
        this.email = data.email;
        this.firstName = data.firstName;
        this.surname = data.surname;
    }
}
