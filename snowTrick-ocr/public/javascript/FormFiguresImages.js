$( document ).ready(function() {
    const collectionHolder = $('ul.figuresImages')[0];
    
    var with_delete = false;

    const addFormToCollection = () => {
        const item = document.createElement('li');
        $(item).addClass('d-flex align-items-center');
        
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

    const addTagFormDeleteLink = (item) => {
        const removeFormButton = document.createElement('button');
        $(removeFormButton).addClass('btn btn-danger mb-3 ms-2');
        $(removeFormButton).html('X');
        
        $(item).append(removeFormButton);

        $(removeFormButton).on('click', function(e) {
            e.preventDefault();
            
            $(item).prev().remove();
            $(item).remove();
        });
    }
    
    if ($(collectionHolder).data('index') == 0) {
        with_delete = true;
        addFormToCollection();
        with_delete = false;
    }

    $('.add_item_link_images').on("click", function() {
        addFormToCollection();
    });

    if ($(collectionHolder).data('index') != 0) {
        $.each($('ul.figuresImages li'), function( key, value ) {
            $('#figures_save').on("click", addTagFormDeleteLink(value));
        });
    }

    $('ul.figuresImages li').on('change', function() {
        var val_input = $(this).find('input').val();
        val_input = val_input.split("\\")[2];
        $(this).prev('p').text(val_input);
    })

    if ($('div.error_image').text().length == 0) {
        $('div.error_image').hide();
    }
});