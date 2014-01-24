/**
 * Application javascript file
 */

(function ($) {
    // common functions section
    var send_message = function(msg) {
        console.log(msg);
    }

    // js for manual fetch
    var formContainer = $('#manual-fetch-form');
    var urlInput = formContainer.find('#url');
    var dateInput = formContainer.find('#date');
    formContainer.find('.btn-primary').click(function(e) {

        var url = urlInput.val();
        var date = dateInput.val();
        // validation
/*
        if (url == '') {
            urlInput.parent().addClass('has-error');
            e.preventDefault();
            return ;
        }
        if (!url.match(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/)) {
            urlInput.parent().addClass('has-error');
            e.preventDefault();
            return ;
        }

        if (date == '') {
            dateInput.parent().addClass('has-error');
            e.preventDefault();
            return ;
        }

        if (!date.match(/\d{4}\-\d{2}\-\d{2}/)) {
            dateInput.parent().addClass('has-error');
            e.preventDefault();
            return ;
        }
*/
        
        urlInput.parent().removeClass('has-error').addClass('has-success');
        dateInput.parent().removeClass('has-error').addClass('has-success');
        $(this).button('loading');
        // call fetch action by ajax
    });

})(jQuery);
