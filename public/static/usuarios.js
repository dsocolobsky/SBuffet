$("#tabla_usuarios > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    button.unbind('click').bind('click', function () {
        var id = $(this).attr("name").replace("carga", "");

        html_final = $.post("/obtenersaldo", { id: id })
            .done(function (data) {
                console.log($("#boton-cargar").html());
                BootstrapDialog.show({
                    title: 'Cargar Saldo',
                    message: data,
                    type: BootstrapDialog.TYPE_WARNING,
                });
            });
    });
});

$("#tabla_codigos > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    button.unbind('click').bind('click', function () {
        var codigo = $(this).attr("name").replace("codigo", "");

        $.post("/borrarcodigo", { codigo: codigo })
            .done(function (data) {
                console.log(data);
                window.location.replace("/usuarios");
            });
        console.log(codigo);
    });
});


$('#boton-codigo > button').unbind('click').bind('click', function () {
    $.post("/codigo", function (data) {
        var html = '<h1 class="text-center">' + data + '</h1>';

        BootstrapDialog.show({
            title: 'Codigo',
            message: html,
            type: BootstrapDialog.TYPE_WARNING,
            onhidden: function (dialogRef) {
                window.location.replace("/usuarios");
            }
        });
    });
});