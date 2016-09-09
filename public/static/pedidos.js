$("#tabla_pendientes > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    button.unbind('click').bind('click', function () {
        var id = $(this).attr("name").replace("pedido", "");

        $.post("/listo", { id: id })
            .done(function (data) {
                window.location.replace("/pedidos");
            });
    });
});

$("#tabla_entregados > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    button.unbind('click').bind('click', function () {
        var id = $(this).attr("name").replace("pedido", "");

        $.post("/borrarpedido", { id: id })
            .done(function (data) {
                window.location.replace("/pedidos");
            });
    });
});

function cargarTabla() {
    $("#tabla_pendientes").load("/tablapedidos");
}

cargarTabla();
setInterval( function() {
    cargarTabla();
}, 8000);