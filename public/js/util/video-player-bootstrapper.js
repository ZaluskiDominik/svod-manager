'use strict'

const videoPlayerBootstrapper = {
    allowedPlayers : [
        videoJS.name,
        youTube.name
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
            case youTube.name:
                this.bootstrappedPlayer = youTube;
                break;
        }
    },
};
