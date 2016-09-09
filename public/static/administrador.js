$('#boton-cambiar').unbind('click').bind('click', function () {
    $.post("/cambiarpassword", function (data) {
        var passOriginal = $("#passOriginal").val();
        var passNueva = $("#passNueva").val();
        var passNueva2 = $("#passNueva2").val();

        $.post("/cambiarpassword", { passOriginal: passOriginal, passNueva: passNueva, passNueva2: passNueva2 })
            .done(function (data) {
                console.log(data);
                if (data == -1) {
                    dialog("La contraseña no es correcta");
                } else if (data == 0) {
                    dialog("Las contraseñas no coinciden");
                } else if (data == 1) {
                    dialog("Contraseña cambiada correctamente");
                } else {
                    dialog("Nada")
                }
            });
    });

});

function dialog(msg) {
    BootstrapDialog.show({
        title: 'Error',
        message: msg,
        type: BootstrapDialog.TYPE_WARNING,
        onhidden: function (dialogRef) {
            window.location.replace("/administrador");
        }
    });
}