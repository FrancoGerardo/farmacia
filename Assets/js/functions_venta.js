var tableVenta;
var cont = 0;
var detalles = 0;
document.addEventListener('DOMContentLoaded', function() {
    tableVenta = $('#tableVenta').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Venta/getVentas",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idVenta" },
            { "data": "idCliente" },
            { "data": "nombreCliente" },
            { "data": "fecha" },
            { "data": "estado" },
            { "data": "options" }
        ],
        'dom': 'lBfrtip',
        'buttons': [{
            "extend": "copyHtml5",
            "text": "<i class='far fa-copy'></i> Copiar",
            "titleAttr": "Copiar",
            "className": "btn btn-secondary"
        }, {
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Exel",
            "titleAttr": "Exportar a Excel",
            "className": "btn btn-success"
        }, {
            "extend": "pdfHtml5",
            "text": "<i class='fas fa-file-pdf'></i> PDF",
            "titleAttr": "Exportar a PDF",
            "className": "btn btn-danger"
        }, {
            "extend": "csvHtml5",
            "text": "<i class='fas fa-file-csv'></i> CSV",
            "titleAttr": "Exportar a CSV",
            "className": "btn btn-info"
        }],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [
            [0, "desc"]
        ]
    });
    //NUEVA VENTA INICIO
    var formVenta = document.querySelector("#formVenta");
    if (formVenta != null) {
        formVenta.onsubmit = function(e) {
            e.preventDefault();

            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Venta/setVenta';
            var formData = new FormData(formVenta);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormVenta').modal("hide");
                        formVenta.reset();
                        swal("Venta", objData.msg, "success");
                        tableVenta.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }

    var formFactura = document.querySelector("#formFactura");
    if (formFactura != null) {
        formFactura.onsubmit = function(e) {
            e.preventDefault();

            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Venta/setFactura';
            var formData = new FormData(formFactura);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormFactura').modal("hide");
                        formFactura.reset();
                        swal("Factura", objData.msg, "success");
                        tableVenta.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
    //NUEVA VENTA FIN//
});

$('#tableVenta').DataTable();

//ABRIR EL FORMULARIO MODAL
function openModal() {
    document.querySelector('#idVenta').value = "";
    document.querySelector("#idCliente").value = 1;
    $('#idCliente').selectpicker('render');
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Venta";
    document.querySelector("#formVenta").reset();

    cargarCliente();
    fecha();
    evaluar();
    detalles = 0;
    $("#total").html("Bs. " + 0)
    $(".filas").remove();
    $('#modalFormVenta').modal('show');
}

function fecha() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#txtFecha').val(today);
}

//LISTA DE FUNCIONES QUE CARGAN LA LISTA DE CLIENTES Y PRODCTOS
window.addEventListener('load', function() {
    fntListaClientes();
    fntListaProductos();
}, false);

//ANULAR VENTA INICIO
function fntAnularVenta(idVenta) {
    var idVenta = idVenta;
    swal({
        title: "ANULAR VENTA",
        text: "¿Realmente quiere Anular la Venta?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, ANULAR",
        cancelButtonText: "No, CANCELAR",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Venta/anularVenta/';
            var strData = "idVenta=" + idVenta;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("ANULADA!", objData.msg, "success");
                        tableVenta.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}
//ANULAR VENTA FIN

//CARGAR LISTA CLIENTE Y PRODUCTO INICIO
function fntListaClientes() {
    var ajaxUrl = base_url + '/Venta/getSelectCliente';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idCliente').innerHTML = request.responseText;
            document.querySelector('#idCliente').value = 1;
            $('#idCliente').selectpicker('render');
        }
    }
}

function fntListaProductos() {
    var ajaxUrl = base_url + '/Venta/getSelectProducto';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idProducto').innerHTML = request.responseText;
            document.querySelector('#idProducto').value = 1;
            $('#idProducto').selectpicker('render');
        }
    }
}
//CARGAR LISTA CLIENTE Y PRODUCTO FIN

//OBTENER DATOS DEL CLIENTE Y MOSTRARLO EN EL FORMULARIO MODAL INICIO
function cargarCliente() {
    var idCliente = document.querySelector('#idCliente').value;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Venta/getCliente/' + idCliente;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#documento").innerHTML = objData.data.documento;
                document.querySelector("#codDocumento").innerHTML = objData.data.codDocumento;
                document.querySelector("#nit").innerHTML = objData.data.nit;
                document.querySelector("#nivel").innerHTML = objData.data.nivel;
                document.querySelector('#descuento').value = objData.data.descuento;
                document.querySelector("#descuento").innerHTML = objData.data.descuento;

            }
        }
    }
}
//OBTENER DATOS DEL CLIENTE Y MOSTRARLO EN EL FORMULARIO MODAL FIN

//CARGAR LISTA DE PRODUCTOS EN EL DETALLE INICIO
function cargarProducto() {
    var idProducto = document.querySelector('#idProducto').value;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Venta/getAgregarProducto/' + idProducto;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            var presentacion = objData.data.presentacion;
            var concentracion = objData.data.concentracion;
            var estante = objData.data.estante;
            var nombreGenerico = objData.data.nombreGenerico;
            var nombreComercial = objData.data.nombreComercial;
            var venta = objData.data.venta;
            var cantidadTotal = objData.data.cantidad;
            var precioVenta = objData.data.precioVenta;
            var cantidad = 1;


            var impuesto = 13;
            var imp = (precioVenta / 100) * impuesto;
            var total = (Number(precioVenta) + Number(imp));
            total = total.toFixed(2);
            if (idProducto != "") {
                var subtotal = total;
                var fila = '<tr class="filas" id="fila' + cont + '">' +
                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')" title="Eliminar"><i class="fas fa-times"></i></button></td>' +
                    '<td><input type="hidden" name="idProducto[]" value="' + idProducto + '">' + nombreGenerico + '</td>' +
                    '<td><input type="hidden" >' + nombreComercial + '</td>' +
                    '<td><input type="hidden" >' + presentacion + '</td>' +
                    '<td><input type="hidden" >' + concentracion + '</td>' +
                    '<td><input type="hidden" >' + venta + '</td>' +
                    '<td><input type="hidden" >' + estante + '</td>' +
                    '<td><input type="number" style="width : 85px; heigth : 85px" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
                    '<td><input type="hidden" style="width : 110px; heigth : 110px" name="cantidadTotal[]" value="' + cantidadTotal + '">' + cantidadTotal + '</td>' +
                    '<td><input type="hidden" name="precioVenta[]" value="' + total + '">' + total + '</td>' +
                    '<td><span name="subtotal" name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
                    '</tr>';
                cont++;
                detalles = detalles + 1;
                $('#detalles').append(fila);
                evaluar();
            }
        }
    }
}

function calcularSubtotal() {
    var cantidad = document.getElementsByName("cantidad[]");
    var precio = document.getElementsByName("precioVenta[]");
    var sub = 0;
    for (var i = 0; i < cantidad.length; i++) {
        var can = cantidad[i];
        var pre = precio[i];
        sub = sub + can.value * pre.value;
        document.getElementsByName("subtotal")[i].innerHTML = (can.value * pre.value).toFixed(2);
    }
    var descuento = document.querySelector('#descuento').value;
    if (descuento != 0) {
        sub = sub - ((sub / 100) * descuento);
    }
    $("#total").html("Bs. " + sub.toFixed(2));
    $("#total_venta").val(sub.toFixed(2));
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    detalles = detalles - 1;
    evaluar()
}

function evaluar() {
    if (detalles > 0) {
        $("#btnActionForm").show();
    } else {
        $("#btnActionForm").hide();
        cont = 0;
    }
}
//CARGAR LISTA DE PRODUCTOS EN EL DETALLE FIN

function obtenerDescuento(idVenta) {
    var idVenta = idVenta;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Venta/getDescuento/' + idVenta;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#descuentoCompra").innerHTML = (objData.data.descuento).toFixed(2) + ' BS';
            }
        }
    }
}

function fntViewVenta(idVenta) {
    var idVenta = idVenta;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Venta/getViewCliente/' + idVenta;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idVentaV").innerHTML = '#' + idVenta;
                document.querySelector("#nombreClienteV").innerHTML = ': ' + objData.data.nombreCliente;
                document.querySelector("#nitV").innerHTML = ': ' + objData.data.nit;
                document.querySelector("#nivelV").innerHTML = ': ' + objData.data.nivel;
                document.querySelector("#descuentoV").innerHTML = ': ' + objData.data.descuento + '%';

                document.querySelector("#usuarioV").innerHTML = ': ' + objData.data.nombreUsuario;
                document.querySelector("#cargoV").innerHTML = ': ' + objData.data.cargo;
                document.querySelector("#fechaV").innerHTML = ': ' + objData.data.fecha;
                document.querySelector("#horaV").innerHTML = ': ' + objData.data.hora;

                $.post(base_url + '/Venta/getDetalleVenta/' + idVenta, function(r) {
                    $("#detalleView").html(r);
                });
                obtenerDescuento(idVenta);
                $('#modalViewVenta').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntGenerarFactura(idVenta) {
    var idVenta = idVenta;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Venta/getViewCliente/' + idVenta;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idVentaF").value = idVenta;
                document.querySelector("#txtNombre").value = objData.data.nombreCliente;
                document.querySelector("#txtNit").value = objData.data.nit;
                document.querySelector("#dateFecha").value = objData.data.fecha;
                $.post(base_url + '/Venta/getDetalleVentaF/' + idVenta, function(r) {
                    $("#detalleFactura").html(r);
                });
                $('#modalFormFactura').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}