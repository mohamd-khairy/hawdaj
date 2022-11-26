$(document).ready(function () {
    $('.select2').select2({});

    $('.nice-select').select2({
        minimumResultsForSearch: -1
    });

    var d = Date(Date.now());
    a = d.toString()
    document.getElementsByClassName("date").min = a;

    $('.add_loading').on('click', function (e) {

        $(this)
            .addClass('spinner spinner-white spinner-right')
            .text(lang[LANG].please_wait)
            .prop('disabled', true);

        $(".form").submit();
    });
});

function logout() {
    $("#LogoutForm").submit();
}

$('.file').on('change', function () { //on file input change
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        $(this).parent().parent().siblings('.image').find('.thumb-output').html('');
        var data = $(this)[0].files;
        var self = $(this);
        $.each(data, function (index, file) {
            if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) {
                var fRead = new FileReader();
                fRead.onload = (function (file) {
                    return function (e) {
                        var img = $('<img/>').addClass('thumb').attr('src', e.target.result);
                        // console.log(  $(this).parent().parent());
                        self.parent().parent().siblings('.image').children('.thumb-output').append(img);
                    };
                })(file);
                fRead.readAsDataURL(file);
            }
        });
    } else {
        alert("Your browser doesn't support File API!");
    }
});

$('.add_loading').on('click', function (e) {
    if ($(this).hasClass('disabled')) {
        return;
    }
    let form = $(this).closest('form');

    $(this)
        .addClass('spinner spinner-white spinner-right')
        .text(langs[LANG].please_wait)
        .prop('disabled', true);

    form.submit();
});
