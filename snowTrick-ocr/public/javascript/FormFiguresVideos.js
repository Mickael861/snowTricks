$( document ).ready(function() {
    const collectionHolder = $('ul.figuresVideos')[0];

    const addFormToCollection = (e) => {
         const item = document.createElement('li');
        $(item).addClass('d-flex align-items-center')
        
        item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );
    
        collectionHolder.appendChild(item);
    
        collectionHolder.dataset.index++;

        addTagFormDeleteLink(item);
    };

    $('.add_item_link_video').on("click", addFormToCollection);
    
    const addTagFormDeleteLink = (item) => {
        const removeFormButton = document.createElement('button');
        $(removeFormButton).addClass('btn btn-danger mb-3 ms-2');
        $(removeFormButton).html('X');
        
        $(item).append(removeFormButton);
    
        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            $(item).remove();
        });
    }

    if ($(collectionHolder).data('index') != 0) {
        $.each($('ul.figuresVideos li'), function( key, value ) {
            $('#figures_save').on("click", addTagFormDeleteLink(value));
        });
    }
});