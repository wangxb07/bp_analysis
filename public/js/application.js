/**
 * Application javascript file
 */

(function ($) {
    // common functions section
    var send_message = function(msg) {
    }

    // js for manual fetch
    var formContainer = $('#manual-fetch-form');
    var urlInput = formContainer.find('#url');
    var dateInput = formContainer.find('#date');
    formContainer.find('.btn-primary').click(function(e) {

        var url = urlInput.val();
        var date = dateInput.val();
        
        urlInput.parent().removeClass('has-error').addClass('has-success');
        dateInput.parent().removeClass('has-error').addClass('has-success');
        $(this).button('loading');
        // call fetch action by ajax
    });

})(jQuery);
