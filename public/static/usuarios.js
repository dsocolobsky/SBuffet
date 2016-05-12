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