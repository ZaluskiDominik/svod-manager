'use strict'

const formFieldsToObjectConverter = {
    convert : function(formNode) {
        const inputs = formNode.getElementsByTagName('input');
        let resultObject = {};

        for (let i = 0 ; i < inputs.length ; i++) {
            const name = inputs[i].getAttribute('name');
            if (name) {
                resultObject[name] = inputs[i].value;
            }
        }

        return resultObject;
    }
};
