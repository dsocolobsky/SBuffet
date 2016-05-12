$("#tabla_pendientes > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    console.log(button.html());
    button.unbind('click').bind('click', function () {
        var id = $(this).attr("name").replace("pedido", "");
        
        $.post("/listo", {id: id})
            .done(function (data) {
                window.location.replace("/pedidos");
        });
    });
});