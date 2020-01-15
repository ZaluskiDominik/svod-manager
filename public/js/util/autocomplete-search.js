'use strict'

class AutocompleteSearch
{
    constructor(inputNode, data, changeCallback)
    {
        this.inputNode = inputNode;
        this.changeCallback = changeCallback;
        this.setData(data);
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
        return video.title + ' (' + video.publisherCompany + ')';
    }

    setData(data)
    {
        const inputNode = this.inputNode;
        const changeCallback = this.changeCallback;
        M.Autocomplete.init(inputNode, {
            limit : 7,
            onAutocomplete : changeCallback,

            data : data
        });
        inputNode.onkeydown = (e) => {
            if (e.code === 'Enter') {
                changeCallback();
            }
        };
        inputNode.onblur = changeCallback;
    }

    getValue()
    {
        return this.inputNode.value;
    }

    setValue(value)
    {
        this.inputNode.value = value;
    }
}
