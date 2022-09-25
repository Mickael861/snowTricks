$( document ).ready(function() {
    var elts_figures = $('#figures').children();
    var nbrs_elts_figure = 12;
    var nbrs_more_elts_figure = 12;

    $.each(elts_figures, function( key, value ) {
        if (key < nbrs_elts_figure) {
            $(elts_figures[key]).removeClass('d-none');
        }
    });

    $('#loadMoreFigures').on('click', function() {
        nbrs_elts_figure += nbrs_more_elts_figure;

        $.each(elts_figures, function( key, value ) {
            if (key < nbrs_elts_figure) {
                $(elts_figures[key]).removeClass('d-none');
            }
        });

        if (nbrs_elts_figure >= $(elts_figures).length) {
            $('#loadMoreFigures svg').attr('fill', '#EC7063');
            $('#loadMoreFigures').attr('onclick', 'return false');
            nbrs_elts_figure = $(elts_figures).length;
        }

        if (nbrs_elts_figure >= 15) {
            $('#arrowUpFigures').removeClass('d-none');
        }
    })
});