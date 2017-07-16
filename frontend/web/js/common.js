$('.comment_form').submit(function(event) {
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            if(data.status == 200) {
                window.location.reload();
            }
        },
        beforeSend: function () {
        },
        complete: function () {
            $('.input_message').val('');
        }
    });
    return false;

});

$('.comment_delete a').click(function() {
    $.ajax({
        url: $(this).attr('href'),
        type: 'POST',
        //data: {'_csrf-frontend': $('meta[name=csrf-token]')},
        data: {'_csrf-frontend': phpVars._csrf},
        //data: {'_csrf-frontend': phpVars._csrf},
        dataType: 'json',
        success: function (data) {
            if(data.status == 200) {
                window.location.reload();
            }
        },
        beforeSend: function () {
        },
        complete: function () {
        }
    });
    return false;
});