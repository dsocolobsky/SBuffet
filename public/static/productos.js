$("#tabla_productos > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    var btnname = button.attr("name");

    if (typeof btnname != "string") {
        return;
    }

    var disponible = true;

    if (btnname.indexOf("no") >= 0) {
        disponible = false;
    }

    button.unbind('click').bind('click', function () {
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