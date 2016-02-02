global.jQuery = require('jquery');

require('fancybox/dist/js/jquery.fancybox.pack');

(function ($) {

    $('a[rel=external]').on('click', function() {
        window.open($(this).prop('href'));
        return false;
    });

    //var form = $('#form-download');
    //form.submit(function() {
    //    var $form = $(this),
    //        $target = $('.alert');
    //
    //    var request = $.ajax({
    //        url: $form.prop('action'),
    //        beforeSend: function( xhr ) {
    //            $.fancybox.showLoading();
    //        },
    //        method: 'POST',
    //        data: $form.serialize(),
    //        dataType: 'json'
    //    });
    //
    //    request.done(function( data ) {
    //        if (data.error) {
    //            $target.addClass('alert-danger');
    //        } else {
    //            $target.addClass('alert-success');
    //        }
    //
    //        $target.html(data.mensagem).parent().css('display', 'block');
    //
    //        $.fancybox.hideLoading();
    //    });
    //
    //    request.fail(function( jqXHR, textStatus ) {
    //        $target.html( "Request failed: " + textStatus );
    //    });
    //
    //    return false;
    //});
})(jQuery);
