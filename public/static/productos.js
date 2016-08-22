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
    console.log("CALLED");
    html_final = $.get("/agregarproducto", {})
        .done(function (data) {
            //console.log($("#boton-agregar").html());
            BootstrapDialog.show({
                title: 'Agregar Producto',
                message: 'hey',
                type: BootstrapDialog.TYPE_WARNING,
            });
        });

        BootstrapDialog.show({
                title: 'Agregar Producto',
                message: 'hey',
                type: BootstrapDialog.TYPE_WARNING,
            });
});