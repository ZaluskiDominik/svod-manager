'use strict'

const videoPlayerBootstrapper = {
    allowedPlayers : [
        videoJS.name
    ],

    bootstrap : function(player) {
        if (!player in this.allowedPlayers) {
            throw "Player '" + player + "' is not allowed!";
        }

        switch (player) {
            case videoJS.name:
                videoJS.bootstrap().then(videoJS.play.bind(videoJS));
                break;
        }
    },
};
