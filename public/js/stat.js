function stat(cdp, id) {
    $.ajax(
        {
            url: "",
            type: "GET",
            data: {
                'cdp': cdp,
            },
            success: function (data) {
                console.log(data.result, data.content)
                $("#version" + id).html(data.content);
                $("#satisfait" + id).html(data.result);
                let val = (100*data.result)/data.content;
                $("#pourcent" + id).val(val.toPrecision(2));
                $("#value" + id).html(val.toPrecision(3)+"%");
            }
        }
    )
}
function statCdp(cdp,fiche) {
    $.ajax(
        {
            url: "",
            type: "GET",
            data: {
                'cdp': cdp,
                'fiche':fiche,
            },
            success: function (data) {
                $("#version" + fiche).html(data.content);
                $("#satisfait" + fiche).html(data.result);
                let val = (100*data.result)/data.content;
                $("#pourcent" + fiche).val(val.toPrecision(2));
                $("#value" + fiche).html(val.toPrecision(3)+"%");
            }
        }
    )
}
function statCdpPreca(cdp,fiche, preca) {
    $.ajax(
        {
            url: "",
            type: "GET",
            data: {
                'cdp': cdp,
                'fiche':fiche,
                'preca':preca,
            },
            success: function (data) {
                $("#version" + preca).html(data.content);
                $("#satisfait" + preca).html(data.result);
                let val = (100*data.result)/data.content;
                $("#pourcent" + preca).val(val.toPrecision(2));
                $("#value" + preca).html(val.toPrecision(3)+"%");
            }
        }
    )
}
