$("#boton").unbind('click').bind('click', function () {
    var datos = new Object();
    datos.codigo = $("#codigo").val();
    datos.usuario = $("#usuario").val();
    datos.nombre = $("#nombre").val();
    datos.password = $("#password").val();
    datos.password2 = $("#password2").val();

    if (datos.usuario.indexOf(' ') >= 0) {
        BootstrapDialog.show({
            title: 'Error',
            message: 'El nombre de usuario no puede contener espacios',
            type: BootstrapDialog.TYPE_WARNING
        });
    } else if (datos.password !== datos.password2) {
        BootstrapDialog.show({
            title: 'Error',
            message: 'Las contraseñas no coinciden',
            type: BootstrapDialog.TYPE_WARNING
        });
    } else {
        $.post("/registro", { datos: datos })
            .done(function (data) {
                BootstrapDialog.show({
                    title: 'Informacion',
                    message: data,
                    type: BootstrapDialog.TYPE_WARNING,
                    onhidden: function (dialogRef) {
                        window.location.replace("/registro");
                    }
                });
            });
    }
});
