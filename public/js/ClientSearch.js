$(document).ready(function () {
    $('#members-search').keyup(function () {
        search = $('#members-search').val();
            $('#members-search')
            searchAjax(search);
    });

    function searchAjax(data) {
        $.ajax({
            method: "POST",
            url: "/client",
            data: {
                search: data
            },
            dataType: "html",
            success: function (response) {
                remplacement = $.parseJSON(response)
                $('#response-content').html(remplacement.content);
            },

        })
    }
});