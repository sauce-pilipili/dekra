window.onload = () => {
    const FiltersForm = document.getElementById('filters');

    FiltersForm.addEventListener("keyup", () => {
        var data = document.getElementById('members-search').value;

        // On récupère les données du formulaire
        // On récupère l'url active
        const Url = new URL(window.location.href);

        // On lance la requête ajax
        fetch(Url.pathname, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            dataType: "json",
            data: {"search":"data"}
        }).then(response =>
            // console.log(response)
            response.json(),
        )
            .then(data => {

                const content = document.querySelector("#content");
                content.innerHTML = data.content;
                console.log(data.content)
            }).catch(e => alert(e));

    });
}

