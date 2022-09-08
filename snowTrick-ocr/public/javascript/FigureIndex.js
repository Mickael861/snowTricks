$( document ).ready(function() {
    var elts_discussions = $('#discussionsContent').children();
    var nbrs_elts_disc = 10;
    var nbrs_more_elts_disc = 10;

    $.each(elts_discussions, function( key, value ) {
        if (key < nbrs_elts_disc) {
            $(elts_discussions[key]).removeClass('d-none');
        }
    });

    $('#loadMoreDiscussions').on('click', function() {
        nbrs_elts_disc += nbrs_more_elts_disc;

        $.each(elts_discussions, function( key, value ) {
            if (key < nbrs_elts_disc) {
                $(elts_discussions[key]).removeClass('d-none');
            }
        });

        if (nbrs_elts_disc >= $(elts_discussions).length) {
            $('#loadMoreDiscussions svg').attr('fill', '#EC7063');
            $('#loadMoreDiscussions').attr('onclick', 'return false');
            nbrs_elts_disc = $(elts_discussions).length;
        }
    })

    $('#btnViewMedia').on('click', function() {
        var elt = $('#containerViewMedia');
        if (elt.hasClass('d-none')) {
            elt.removeClass('d-none');
            elt.removeClass('d-sm-block');
        } else {
            elt.addClass('d-none');
            elt.addClass('d-sm-block');
        }
    });
});