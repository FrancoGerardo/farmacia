var tableReposicion;
var cont = 0;
var detalles = 0;
document.addEventListener('DOMContentLoaded', function() {
    tableReposicion = $('#tableReposicion').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Reposicion/getReposiciones",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idReposicion" },
            { "data": "nombreEmpresa" },
            { "data": "descripcion" },
            { "data": "fechaEntrega" },
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

    var formReposicion = document.querySelector("#formReposicion");
    if (formReposicion != null) {
        formReposicion.onsubmit = function(e) {
            e.preventDefault();
            var descripcion = document.querySelector('#txtDescripcion').value;
            if (descripcion == '') {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Reposicion/setReposicion';
            var formData = new FormData(formReposicion);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormReposicion').modal("hide");
                        formReposicion.reset();
                        swal("Reposicion", objData.msg, "success");
                        tableReposicion.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableReposicion').DataTable();

function openModal() {
    document.querySelector('#idReposicion').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Reposicion";
    document.querySelector("#formReposicion").reset();
    fecha();
    $('#modalFormReposicion').modal('show');
}

window.addEventListener('load', function() {
    fntListaProveedores();
    fntListaLotes();
}, false);

function fntRecibirReposicion(idReposicion) {
    var idReposicion = idReposicion;
    swal({
        title: "Recibir Reposicion",
        text: "¿Realmente quiere Recibir la Reposicion?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Recibir",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Reposicion/recibirReposicion/';
            var strData = "idReposicion=" + idReposicion;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Recibida!", objData.msg, "success");
                        tableReposicion.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntAnularReposicion(idReposicion) {
    var idReposicion = idReposicion;
    swal({
        title: "ANULAR Reposicion",
        text: "¿Realmente quiere Anular la Reposicion?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, ANULAR",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Reposicion/anularReposicion/';
            var strData = "idReposicion=" + idReposicion;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("ANULADA!", objData.msg, "success");
                        tableReposicion.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntListaProveedores() {
    var ajaxUrl = base_url + '/Reposicion/getSelectProveedor';
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

function cargarProveedor() {
    var idProveedor = document.querySelector('#idProveedor').value;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Reposicion/getProveedor/' + idProveedor;
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

function fntListaLotes() {
    var ajaxUrl = base_url + '/Reposicion/getSelectLotes';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idLote').innerHTML = request.responseText;
            document.querySelector('#idLote').value = 1;
            $('#idLote').selectpicker('render');
        }
    }
}

function cargarLote() {
    var idLote = document.querySelector('#idLote').value;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Reposicion/getAgregarLote/' + idLote;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            var idLote = objData.data.idLote;
            var codigo = objData.data.codigo;
            var nombreGenerico = objData.data.nombreGenerico;
            var nombreComercial = objData.data.nombreComercial;
            var presentacion = objData.data.presentacion;
            var concentracion = objData.data.concentracion;
            var precioVenta = objData.data.precioVenta;
            var cantidad = 0;
            var fechaVencimiento = objData.data.fechaVencimiento;
            var cantidadT = 1;
            var laboratorio = objData.data.fabricante;

            if (idLote != "") {
                var subtotal = cantidadT * precioVenta;
                var fila = '<tr class="filas" id="fila' + cont + '">' +
                    '<td style="text-align: center"><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')" title="Eliminar"><i class="fas fa-times"></i></button></td>' +
                    '<td><input type="hidden" name="idLote[]" value="' + idLote + '">' + codigo + '</td>' +
                    '<td><input type="hidden" >' + nombreGenerico + ' - ' + nombreComercial + '</td>' +
                    '<td><input type="hidden" >' + presentacion + ' - ' + concentracion + '</td>' +
                    '<td><input type="hidden">' + laboratorio + '</td>' +
                    '<td><input type="hidden" >' + fechaVencimiento + '</td>' +
                    '<td><input type="number" style="width : 65px; heigth : 65px" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
                    '<td><input type="hidden" style="width : 55px; heigth : 55px id="precioVenta[]" name="precioVenta[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
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
    $("#total").html("Bs. " + (sub).toFixed(2));
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

function fecha() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#dateFecha').val(today);
}

function fntViewReposicion(idReposicion) {
    var idReposicion = idReposicion;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Reposicion/getViewReposicion/' + idReposicion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idReposicionV").innerHTML = '#' + idReposicion;
                document.querySelector("#nombreEmpresaV").innerHTML = ': ' + objData.data.nombreEmpresa;
                document.querySelector("#nombreVendedorV").innerHTML = ': ' + objData.data.nombreVendedor;
                document.querySelector("#nitV").innerHTML = ': ' + objData.data.nit;
                document.querySelector("#telefonoV").innerHTML = ': ' + objData.data.telefono;
                document.querySelector("#direccionV").innerHTML = ': ' + objData.data.direccion;

                document.querySelector("#usuarioV").innerHTML = ': ' + objData.data.nombreUsuario;
                document.querySelector("#cargoV").innerHTML = ': ' + objData.data.cargo;
                document.querySelector("#fechaCreacionV").innerHTML = ': ' + objData.data.fechaCreacion;
                document.querySelector("#fechaEntregaV").innerHTML = ': ' + objData.data.fechaEntrega;
                document.querySelector("#estadoV").innerHTML = ': ' + objData.data.estado;

                $.post(base_url + '/Reposicion/getDetalleReposicion/' + idReposicion, function(r) {
                    $("#detalleView").html(r);
                });

                $('#modalViewReposicion').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}