$('input[type="radio"][name="input-limit"], #input-sort').on('change', function(e) {
    console.log('sdgdfg');
    location = e.target.value;
});

function fetch(nextHref) {
    if (nextHref) {
        $.ajax({
            url: nextHref,
            dataType: 'html',
            beforeSend: function () {
                $('#fetch').attr("disabled", "disabled");
            },
            success: function (html) {
                $('#competitor').before(html).remove();
                catalogProductCardController.__init();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    return false;
}