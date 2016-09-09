var productos = [];

var p_precio = $("#total").find("p");
var total = precioReal(p_precio.html());

$("#tabla > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    button.unbind('click').bind('click', function () {
        var nombre = $(this).parent().parent().find("td").eq(0).html();
        var precio = $(this).parent().parent().find("td").eq(2).html();
        var id = $(this).attr("name").replace("producto", "");

        var elem = [
            "<tr>",
            "<td>", nombre, "</td>",
            "<td>", precio, "</td>",
            '<td><button type="button"' + ' name="rproducto' + id + '"',
            'class="btn btn-info btn-sm"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></td>',
            "</tr>"
        ].join(" ");

        $('#tabla2 > table > tbody:last-child').append(
            elem
        );

        cambiarTotal(true, precioReal(precio));
    });
});

$('body').on('click', function () {
    $("#tabla2 > table > tbody > tr").each(function (i, row) {
        if (i != 0) {
            var button = $(this).find("td").find("button");
            button.unbind('click').bind('click', (function () {
                var tr = $(this).parent().parent();

                var precio = precioReal(tr.find("td").eq(1).html());

                cambiarTotal(false, precio);

                tr.remove();
            }));
        }
    });
});

$('#boton-comprar > button').unbind('click').bind('click', function () {
    if (total > 0) {

        $("#tabla2 > table > tbody > tr").each(function (i, row) {
            if (i != 0) {
                var button = $(this).find("td").find("button");
                var id = button.attr("name").replace("rproducto", "");

                productos.push(id);
            }
        });

        var jproductos = JSON.stringify(productos);

        var horario = $('input[name=horario]:checked').val();

        $.post("/compra", { productos: productos, horario: horario })
            .done(function (data) {
                console.log(data);
                if (data == -1) {
                    BootstrapDialog.show({
                        title: 'Informacion',
                        message: 'No hay saldo suficiente',
                        type: BootstrapDialog.TYPE_WARNING,
                        onhidden: function (dialogRef) {
                            window.location.replace("/");
                        }
                    });
                } else {
                    BootstrapDialog.show({
                        title: 'Informacion',
                        message: 'Compra realizada correctamente',
                        type: BootstrapDialog.TYPE_WARNING,
                        onhidden: function (dialogRef) {
                            window.location.replace("/");
                        }
                    });
                }
                
            });

    }
});

function cambiarTotal(mas, valor) {
    var saldo = precioReal($('#saldo').find("p").html());

    if (mas) {
        total = total + valor;
        saldo = saldo - valor;
    } else {
        total = total - valor;
        saldo = saldo + valor;
    }

    p_precio.html("$" + total);
    $('#saldo').find("p").html("$" + saldo);
}

function precioReal(precio) {
    return parseFloat(precio.replace("$", ""));
}
