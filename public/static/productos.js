/* Borrar producto */
$("#tabla_productos > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find('button[name*="borrar"]');

    button.unbind('click').bind('click', function () {
        var id = $(this).attr("name").replace("borrar", "");

        BootstrapDialog.show({
            title: 'Confirmar',
            message: 'Esta seguro que desea eliminar el producto?',
            type: BootstrapDialog.TYPE_WARNING,
            buttons: [{
                label: 'Aceptar',
                action: function (dialog) {
                    $.post("/borrarproducto", { id: id }).done(function (data) {
                        dialog.close();
                        BootstrapDialog.show({
                            title: 'Producto eliminado',
                            message: 'Producto eliminado correctamente.',
                            type: BootstrapDialog.TYPE_WARNING,
                            buttons: [{
                                label: 'Aceptar',
                                action: function (dialog) {
                                    dialog.close();
                                    window.location.replace("/productos");
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


$('#boton-agregar > button').unbind('click').bind('click', function () {
    html_final = $.get("/agregarproducto")
        .done(function (data) {
            BootstrapDialog.show({
                title: 'Agregar Producto',
                message: data,
                type: BootstrapDialog.TYPE_WARNING,
                onhidden: function (dialogRef) {
                    window.location.replace("/productos");
                }
            });
        });
    console.log($.get("/usuarios"), {});
});