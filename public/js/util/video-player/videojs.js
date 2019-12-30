'use strict'

const videoJS = {
    name : 'Video.js',
    jsSrc : [
        'https://vjs.zencdn.net/7.5.5/video.js'
    ],
    cssSrc : [
        "https://vjs.zencdn.net/7.5.5/video-js.css"
    ],

    bootstrap : function() {
        this._initPlayer();
        this.loadedSrcCount = 0;

        return new Promise((resolve) => {
            this.resolve = resolve;
            this._appendCssSources();
            this._appendJsSources();
        });
    },

    play : function() {
        player.play();
    },

    _initPlayer() {
        this.playerNode = document.querySelector('video');
        this.playerNode.setAttribute('id', 'videojs-player');
        this.playerNode.classList.add('video-js');
        this.playerNode.classList.add('vjs-default-skin');
    },

    _getSources : function() {
        const src = [];
        Array.from(document.querySelectorAll('video source')).forEach((node) => {
            src.push({
                src : node.getAttribute('src'),
                type : node.getAttribute('type')
            });
        });

        return src;
    },

    _appendJsSources : function() {
        this.jsSrc.forEach((src) => {
            const scriptNode = document.createElement('script');
            scriptNode.setAttribute('src', src);
            scriptNode.onload = this._onSrcLoad.bind(this);
            if (!this._checkIfNodeExists(scriptNode, 'script')) {
                document.body.appendChild(scriptNode);
            } else {
                this._onSrcLoad();
            }
        });
    },

    _appendCssSources : function() {
        const headNode = document.querySelector('head');

        this.cssSrc.forEach((src) => {
            const cssNode = document.createElement('link');
            cssNode.setAttribute('href', src);
            cssNode.setAttribute('rel', 'stylesheet');
            cssNode.onload = this._onSrcLoad.bind(this);
            if (!this._checkIfNodeExists(cssNode, 'link')) {
                headNode.appendChild(cssNode);
            } else {
                this._onSrcLoad();
            }
        });
    },

    _checkIfNodeExists : function(node, querySelectorName) {
        const nodes = document.querySelectorAll(querySelectorName);
        for (let i = 0 ; i < nodes.length ; i++) {
            if (node.isEqualNode(nodes[i])) {
                return true;
            }
        }

        return false;
    },

    _onSrcLoad : function() {
        if (++this.loadedSrcCount === this.jsSrc.length + this.cssSrc.length) {
            const player = videojs(this.playerNode.getAttribute('id'));
            player.src(this._getSources());
            this.resolve();
        }
    }
};
