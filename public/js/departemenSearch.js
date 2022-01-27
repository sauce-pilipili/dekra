$(document).ready(function () {
   let data= $('#members-search')
    data.keyup(function () {
        let search = data.val();
        searchAjax(search);
    });
    function searchAjax(data) {
        $.ajax({
            method: "POST",
            url: "/departements/",
            data: {
                search: data
            },
            dataType: "html",
            success: function (response) {

                let remplacement = $.parseJSON(response)
                console.log(remplacement)
                $('#response-content').html(remplacement.content);
            },
        })

    }

});