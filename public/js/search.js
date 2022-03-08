function searchAjax(value) {
    $.ajax({
        method: "GET",
        url: "",
        data: {
            search: value
        },
        success: function (data) {
            $('#bodylist').html(data.content);
        },
    })

}