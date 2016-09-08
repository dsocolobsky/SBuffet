/* Borrar usuario */
$("#tabla_usuarios > table > tbody > tr").each(function (i, row) {
    var button = $(this).find('button[name*="borrar"]')
    console.log(button);

    /* Cargar saldo */
    button.unbind('click').bind('click', function () {
        var id = $(this).attr("name").replace("borrar", "");

        BootstrapDialog.show({
            title: 'Confirmar',
            message: 'Esta seguro que desea eliminar al usuario ' + id,
            type: BootstrapDialog.TYPE_WARNING,
            buttons: [{
                label: 'Aceptar',
                action: function (dialog) {
                    $.post("/borrarusuario", { id: id }).done(function (data) {
                        dialog.close();
                        BootstrapDialog.show({
                            title: 'Usuario eliminado',
                            message: 'Usuario ' + id + ' eliminado correctamente.',
                            type: BootstrapDialog.TYPE_WARNING,
                            buttons: [{
                                label: 'Aceptar',
                                action: function (dialog) {
                                    dialog.close();
                                    window.location.replace("/usuarios");
                                }
                            }]
                        })
                    });
                }
            }, {
                    label: 'Cancelar',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
        });
    });

});

/* Cargar saldo */
$("#tabla_usuarios > table > tbody > tr").each(function (i, row) {
    var button = $(this).find('button[name*="carga"]')
    console.log(button);

    /* Cargar saldo */
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

/* Borrar codigos */
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

/* Generar codigo */
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