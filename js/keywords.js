document.addEventListener('DOMContentLoaded', () => {
    const inputElm = document.querySelector('#keywords');
    
    if (!inputElm) {
        console.error('Keywords input not found');
        return;
    }

    // Initialize Tagify
    var tagify = new Tagify(inputElm, {
        whitelist: [], // will be loaded via AJAX
        enforceWhitelist: false, // allow new tags
        dropdown: {
            enabled: 1, // show suggestions after 1 character
            maxItems: 10,
            classname: 'tags-look',
            fuzzySearch: true,
            closeOnSelect: false
        },
        placeholder: 'Type and press Enter to add keywords...',
        addTagOnBlur: true
    });

    // Expose tagify instance globally so product.js can access it
    window.keywordTagify = tagify;


    // "remove all tags" button event listener
    const removeBtn = document.querySelector('.removeTagsBtn');
    if (removeBtn) {
        removeBtn.addEventListener('click', tagify.removeAllTags.bind(tagify));
    }

    
    // Chainable event listeners
    tagify.on('add', onAddTag)
          .on('remove', onRemoveTag)
          .on('input', onInput)
          .on('change', onChange)
          .on('edit', onTagEdit)
          .on('blur', onTagifyFocusBlur)
          .on('click', onTagClick)
          .on('focus', onTagifyFocusBlur)
          .on('blur', onTagifyFocusBlur)
          .on('dropdown:hide dropdown:show', e => console.log(e.type))
          .on('dropdown:select', onDropdownSelect)

         




    // Fetch existing keywords from the backend
    fetch('../../actions/fetch_keywords_action.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                tagify.whitelist = data.keywords; // update whitelist
                console.log('Keywords loaded:', data.keywords.length);
            } else {
                console.error('Failed to load keywords:', data.message);
            }
        })
        .catch(err => {
            console.error('Error fetching keywords:', err);
        });


    // Event handler functions
    // tag added callback
    function onAddTag(e) {
        console.log("Current Tags: ", e.detail);
        console.log("original input value: ", inputElm.value)
    }

    function onRemoveTag(e) {
        console.log('Tag removed:', e.detail, 'Removed tags:', tagify.value);
    }

   function onInput(e) {
        tagify.loading(true)    
        var value = e.detail.value;
        const url = `../../actions/fetch_keywords_action.php?term=${encodeURIComponent(value)}`;
        console.log("Fetch URL:", url);
         // Show a "loading" dropdown item
        tagify.dropdown.hide(); // hide previous suggestions
        tagify.dropdown.show.call(tagify); // open dropdown
        tagify.settings.whitelist = ["Loading..."];
        tagify.loading(true); // show the loading animation


        fetch(url)
            .then(res => res.text()) // get raw text first
            .then(text => {
                console.log("Server response:", text);
                try {
                    const data = JSON.parse(text); // manually parse
                    if (data.status === 'success') {
                        tagify.whitelist = data.keywords
                        tagify.loading(false)    
                        tagify.dropdown.show.call(tagify, value);
                    }
                } catch (err) {
                    console.error("JSON parse error:", err);
                }
            })
            .catch(err => console.error("Fetch error:", err));
    };


    function onChange(e) {
        console.log('Current tags:', tagify.value.map(t => t.value));
    }

    function onTagEdit(e){
    console.log("onTagEdit: ", e.detail);
    }

    // invalid tag added callback
    function onInvalidTag(e){
        console.log("onInvalidTag: ", e.detail);
    }

    // invalid tag added callback
    function onTagClick(e){
        console.log(e.detail);
        console.log("onTagClick: ", e.detail);
    }

    function onTagifyFocusBlur(e){
        console.log(e.type, "event fired")
    }

    function onDropdownSelect(e){
        console.log("onDropdownSelect: ", e.detail)
    }

});