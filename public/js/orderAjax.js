function orderBy(order, direction){
    $.ajax(
        {
            url: "",
            type: "GET",
            data: {
                'order': order,
                'direction': direction,
            },
            success: function (data) {
                console.log(data.content)
                let span = document.getElementById('order'+order)
                let chevron = document.getElementById('chevron'+order)

                var up = document.createElement('i', )
                up.id = "chevron"+order
                up.className = "fa fa-chevron-up fa-1x"

                var down = document.createElement('i', )
                down.id = "chevron"+order
                down.className = "fa fa-chevron-down fa-1x"

                if (direction === 'DESC'){
                    span.removeChild(chevron)
                    span.onclick = function (){
                        orderBy(order,'ASC')
                    }
                    span.append(up)
                }
                else {
                    span.removeChild(chevron)
                    span.onclick = function (){
                        orderBy(order,'DESC')
                    }
                    span.append(down)
                }
                $("#bodylist").html(data.content);
            }
        }
    )

}
