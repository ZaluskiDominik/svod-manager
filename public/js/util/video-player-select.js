'use strict'

const videoPlayerSelect = {
    players : [],

    init : function() {
       M.FormSelect.init(document.querySelector('#video-player-select'));
    },

    setPlayers : function(players) {
        this.players = players;
        const selectNode = document.querySelector('#video-player-select');
        this.clearPlayersNodes();
        players.forEach((player) => {
            const option = document.createElement('option');
            option.setAttribute('value', player.name);
            option.innerHTML = player.name;
            selectNode.appendChild(option);
        });

        this.init();
    },

    clearPlayersNodes : function() {
        Array.from(document.querySelectorAll('#video-player-select option:enabled')).forEach((node) => {
            node.parentNode.removeChild(node);
        });
    },

    selectPlayer : function(playerName) {
        Array.from(document.querySelectorAll('#video-player-select option')).forEach((elem) => {
            elem.removeAttribute("selected");
        });

        document.querySelector('#video-player-select option[value="' + playerName + '"]')
            .setAttribute("selected", "");
    }
};
