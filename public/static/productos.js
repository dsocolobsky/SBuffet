$("#tabla_productos > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    var btnname = button.attr("name");

    if (typeof btnname != "string") {
        return;
    } else {
        if (btnname.indexOf("disponible") < 0) {
            return;
        }
    }

    var disponible = true;

    if (btnname.indexOf("no") >= 0) {
        disponible = false;
    }

    button.unbind('click').bind('click', function () {
        console.log(row);
        var id = ""
        if (disponible) {
            id = btnname.replace("disponible", "");

            $.post("/productonodisponible", { id: id })
                .done(function (data) {
                    window.location.replace("/productos");
                });
        } else {
            id = btnname.replace("nodisponible", "");

            $.post("/productodisponible", { id: id })
                .done(function (data) {
                    window.location.replace("/productos");
                });
        }
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