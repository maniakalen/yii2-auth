$(document).ready(function() {
    $('body').on('click','.ajax-run', function(e) {
        var modal = $('#modal_update');
        setModalContent(modal);
        modal.modal();
        var $url = $(this).attr('href');

        $.get($url).done(function(data) {
            setModalContent(modal, data);
        }).fail(function() {
            setModalContent(modal, $('<div class="alert alert-danger">').html('Failed to finnish'));
        });

        return false;
    }).on('submit', '.ajax-submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var modal = $('#modal_update');
        setModalContent(modal);
        modal.modal();
        var action = $(this).attr('action');
        var data = new FormData($(this)[0]);
        $.ajax({
            'url':action,
            'data':data,
            'method':'POST',
            'processData': false,
            'contentType': false
        }).done(function(result) {
            setModalContent(modal, result);
        }).fail(function() {
            setModalContent(modal, $('<div class="alert alert-danger">').html('Failed to finnish'));
        });
    }).on('click', 'button.ajax-submit', function() {
        var form = $(this).data('formId');
        $('form#' + form).submit();
    });
});

window.setModalContent = function(modal, content) {
    modal = modal || false;
    if (!modal) return;
    if (typeof modal == 'string') { modal = $(modal); }
    if (!content) {
        $('div.modal-dialog', modal).addClass('spinner_width');
    } else {
        $('div.modal-dialog', modal).removeClass('spinner_width');
    }
    content = content || $('<span/>').append($('<img/>').attr({'src' : '/img/green_spinner.gif', 'style' : 'margin: 0 15px; width: 85px; height: 90px'}));
    $('div.modal-content', modal).html(content);
    return modal;
};