$("#tabla_codigos > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    button.unbind('click').bind('click', function () {
        var codigo = $(this).attr("name").replace("codigo", "");
        
        $.post("/borrarcodigo", {codigo: codigo})
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
            type: BootstrapDialog.TYPE_WARNING
        });
    });
});