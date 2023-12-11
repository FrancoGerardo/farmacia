var tableCompra;
var cont = 0;
var detalles = 0;
document.addEventListener('DOMContentLoaded', function() {
    tableCompra = $('#tableCompra').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Compra/getCompras",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idCompra" },
            { "data": "nombreEmpresa" },
            { "data": "nombreVendedor" },
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
    //Nuevo Presentacion///
    var formCompra = document.querySelector("#formCompra");
    if (formCompra != null) {
        formCompra.onsubmit = function(e) {
            e.preventDefault();
            var tipoPago = document.querySelector('#txtTipoPago').value;
            if (tipoPago == 0) {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Compra/setCompra';
            var formData = new FormData(formCompra);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormCompra').modal("hide");
                        formCompra.reset();
                        swal("Compra", objData.msg, "success");
                        tableCompra.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableCompra').DataTable();

window.addEventListener('load', function() {
    fntListaProveedores();
    fntListaProductos();
}, false);


function openModal() {
    document.querySelector('#idCompra').value = "";
    document.querySelector("#idProveedor").value = 1;
    $('#idProveedor').selectpicker('render');
    document.querySelector("#idProducto").value = 1;
    $('#idProducto').selectpicker('render');
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Compra";
    document.querySelector("#formCompra").reset();
    cargarProveedor();
    detalles = 0;
    fecha();
    $("#total").html("Bs. " + 0)
    $(".filas").remove();
    evaluar();
    $('#modalFormCompra').modal('show');
}

function fntEditPresentacion(idPresentacion) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Presentacion";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idPresentacion = idPresentacion;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Presentacion/getPresentacion/' + idPresentacion;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#idPresentacion").value = objData.data.idPresentacion;
                document.querySelector("#txtNombre").value = objData.data.nombre;

                $('#modalFormPresentacion').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntAnularCompra(idCompra) {
    var idCompra = idCompra;
    swal({
        title: "Anular Compra",
        text: "¿Realmente quiere Anular la Compra?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Anular",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Compra/anularCompra/';
            var strData = "idCompra=" + idCompra;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Anulada!", objData.msg, "success");
                        tableCompra.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntRecibirCompra(idCompra) {
    var idCompra = idCompra;
    swal({
        title: "Recibir la Compra",
        text: "¿Realmente quiere Recibir la Compra?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Recibir",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Compra/recibirCompra/';
            var strData = "idCompra=" + idCompra;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Recibida!", objData.msg, "success");
                        tableCompra.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntListaProveedores() {
    var ajaxUrl = base_url + '/Compra/getSelectProveedor';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idProveedor').innerHTML = request.responseText;
            document.querySelector('#idProveedor').value = 1;
            $('#idProveedor').selectpicker('render');
        }
    }
}

function fntListaProductos() {
    var ajaxUrl = base_url + '/Compra/getSelectProducto';
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

function cargarProveedor() {
    var idProveedor = document.querySelector('#idProveedor').value;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Compra/getProveedor/' + idProveedor;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#txtNombreVendedor").innerHTML = objData.data.nombreVendedor;
                document.querySelector("#nit").innerHTML = objData.data.nit;
                document.querySelector("#txtTelefono").innerHTML = objData.data.telefono;
                document.querySelector("#txtDireccion").innerHTML = objData.data.direccion;

            }
        }
    }
}

function cargarProducto() {
    var idProducto = document.querySelector('#idProducto').value;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Compra/getAgregarProducto/' + idProducto;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            var presentacion = objData.data.presentacion;
            var concentracion = objData.data.concentracion;
            var nombreGenerico = objData.data.nombreGenerico;
            var nombreComercial = objData.data.nombreComercial;
            var precioCompra = objData.data.precioVenta;
            var cantidad = 1;
            var laboratorio = '';

            if (idProducto != "") {
                var subtotal = cantidad * precioCompra;
                var fila = '<tr class="filas" id="fila' + cont + '">' +
                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')" title="Eliminar"><i class="fas fa-times"></i></button></td>' +
                    '<td><input type="hidden" name="idProducto[]" value="' + idProducto + '">' + nombreGenerico + '</td>' +
                    '<td><input type="hidden" >' + nombreComercial + '</td>' +
                    '<td><input type="hidden" >' + presentacion + '</td>' +
                    '<td><input type="hidden">' + concentracion + '</td>' +
                    '<td><input type="number" style="width : 65px; heigth : 65px" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
                    '<td><input type="float" style="width : 55px; heigth : 55px id="precioCompra[]" name="precioCompra[]" value="' + precioCompra + '"></td>' +
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
    var precio = document.getElementsByName("precioCompra[]");
    var sub = 0;
    for (var i = 0; i < cantidad.length; i++) {
        var can = cantidad[i];
        var pre = precio[i];
        sub = sub + can.value * pre.value;
        document.getElementsByName("subtotal")[i].innerHTML = can.value * pre.value;
    }
    $("#total").html("Bs. " + sub);
    $("#total_venta").val(sub);
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

function fecha() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#dateFecha').val(today);
}

function fntViewCompra(idCompra) {
    var idCompra = idCompra;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Compra/getViewCompra/' + idCompra;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idCompraV").innerHTML = '#' + idCompra;
                document.querySelector("#nombreEmpresaV").innerHTML = ': ' + objData.data.nombreEmpresa;
                document.querySelector("#nombreVendedorV").innerHTML = ': ' + objData.data.nombreVendedor;
                document.querySelector("#nitV").innerHTML = ': ' + objData.data.nit;
                document.querySelector("#telefonoV").innerHTML = ': ' + objData.data.telefono;
                document.querySelector("#direccionV").innerHTML = ': ' + objData.data.direccion;

                document.querySelector("#usuarioV").innerHTML = ': ' + objData.data.nombreUsuario;
                document.querySelector("#cargoV").innerHTML = ': ' + objData.data.cargo;
                document.querySelector("#fechaV").innerHTML = ': ' + objData.data.fecha;
                document.querySelector("#tipoPagoV").innerHTML = ': ' + objData.data.tipoPago;
                document.querySelector("#estadoV").innerHTML = ': ' + objData.data.estado;

                $.post(base_url + '/Compra/getDetalleCompra/' + idCompra, function(r) {
                    $("#detalleView").html(r);
                });

                $('#modalViewCompra').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}