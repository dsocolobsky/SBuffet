var p_precio = $("#total").find("p");
var total = precioReal(p_precio.html());

$("#tabla > table > tbody > tr").each(function (i, row) {
    var button = $(this).find("td").find("button");
    button.unbind('click').bind('click', function () {
        var nombre = $(this).parent().parent().find("td").eq(0).html();
        var precio = $(this).parent().parent().find("td").eq(1).html();
        var id = $(this).attr("name").replace("producto", "");

        var elem = [
            "<tr>",
            "<td>", nombre, "</td>",
            "<td>", precio, "</td>",
            '<td><button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></td>',
            "</tr>"
        ].join(" ");

        $('#tabla2 > table > tbody:last-child').append(
            elem
        );

        cambiarTotal(true, precioReal(precio));
        console.log("called1");
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

function cambiarTotal(mas, valor) {
    if (mas) {
        total = total + valor;
    } else {
        total = total - valor;
    }

    p_precio.html("$" + total);
}

function precioReal(precio) {
    return parseFloat(precio.replace("$", ""));
}