$('#boton-entrar').unbind('click').bind('click', function () {
    $.post("/login", function (data) {
        var usuario = $("#usuario").val();
        var password = $("#password").val();

        $.post("/login", { usuario: usuario, password: password })
            .done(function (data) {
                if (data == 0) {
                    dialog("El usuario no existe");
                } else if (data == -1) {
                    dialog("La contraseña es incorrecta");
                } else if (data == 1) {
                    window.location.replace("/");
                } else if (data == 2) {
                    window.location.replace("/pedidos");
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
            window.location.replace("/login");
        }
    });
}