function searchAjax(value) {
    $.ajax({
        method: "GET",
        url: "",
        data: {
            search: value
        },
        success: function (data) {
            console.log(data.result)
            $('#bodylist').html(data.result);
        },
    })

}