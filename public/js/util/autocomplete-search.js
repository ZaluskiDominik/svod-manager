'use strict'

class AutocompleteSearch
{
    constructor(inputNode, data)
    {
        this.inputNode = inputNode;

        M.Autocomplete.init(inputNode, {
            onAutocomplete : () => {
                inputNode.blur();
            },

            data : data
        });
        inputNode.onkeydown = (e) => {
            if (e.code === 'Enter') {
                inputNode.blur();
            }
        }
    }

    static transformVideosToData(videos)
    {
        const data = {};
        videos.forEach( (video) => {
            data[this.composeSearchKey(video)] = video.posterUrl;
        });

        return data;
    }

    static composeSearchKey(video)
    {
        return video.title + ' (' + video.company + ')';
    }

    getValue()
    {
        return this.inputNode.value;
    }
}
