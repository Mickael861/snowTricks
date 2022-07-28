$( document ).ready(function() {
    const addFormToCollection = (e) => {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
    
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

    $('ul.figuresVideos li').on("click", addTagFormDeleteLink('ul.figuresVideos li'));
});