'use strict'

const videoPlayerBootstrapper = {
    allowedPlayers : [
        videoJS.name,
        'YouTube'
    ],

    bootstrappedPlayer : null,

    bootstrap : function(player) {
        if (!player in this.allowedPlayers) {
            throw "Player '" + player + "' is not allowed!";
        }

        switch (player) {
            case videoJS.name:
                this.bootstrappedPlayer = videoJS;
                videoJS.bootstrap();
                break;
        }
    },
};
