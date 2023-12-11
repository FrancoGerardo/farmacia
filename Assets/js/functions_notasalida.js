var tableNotaSalida;
var cont = 0;
var detalles = 0;
document.addEventListener('DOMContentLoaded', function() {
    tableNotaSalida = $('#tableNotaSalida').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Notasalida/getNotaSalidas",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idNotaSalida" },
            { "data": "idUsuario" },
            { "data": "Ntipo" },
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
    var formNotaSalida = document.querySelector("#formNotaSalida");
    if (formNotaSalida != null) {
        formNotaSalida.onsubmit = function(e) {
            e.preventDefault();
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Notasalida/setNotaSalida';
            var formData = new FormData(formNotaSalida);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormNotaSalida').modal("hide");
                        formNotaSalida.reset();
                        swal("Nota de Salida", objData.msg, "success");
                        tableNotaSalida.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableNotaSalida').DataTable();

//VISTA
function fntViewNotaSalida(idNotaSalida) {
    var idNotaSalida = idNotaSalida;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Notasalida/getViewNotaSalida/' + idNotaSalida;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idNotaSalidaV").innerHTML = objData.data.idNotaSalida;
                document.querySelector("#txtTipoV").innerHTML = ': ' + objData.data.tipo;
                document.querySelector("#txtLoginV").innerHTML = ': ' + objData.data.login;
                document.querySelector("#cargoV").innerHTML = ': ' + objData.data.cargo;
                document.querySelector("#txtNombreV").innerHTML = ': ' + objData.data.nombreP;
                document.querySelector("#dateFechaV").innerHTML = ': ' + objData.data.fecha;
                document.querySelector("#txtEstadoV").innerHTML = ': ' + objData.data.estado;

                $.post(base_url + '/Notasalida/getDetalleNota/' + idNotaSalida, function(r) {
                    $("#detalleView").html(r);
                });

                $('#modalViewNotaSalida').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function openModal() {
    document.querySelector('#idNotaSalida').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Nota de Salida";
    document.querySelector("#formNotaSalida").reset();
    fecha();
    $('#modalFormNotaSalida').modal('show');
}

window.addEventListener('load', function() {
    fntListaProductos();
}, false);


function fntAnularNota(idNotaSalida) {
    var idNotaSalida = idNotaSalida;
    swal({
        title: "ANULAR Nota de Salida",
        text: "¿Realmente quiere ANULAR la Nota de Salida?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, ANULAR",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Notasalida/anularNotaSalida/';
            var strData = "idNotaSalida=" + idNotaSalida;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("ANULADA!", objData.msg, "success");
                        tableNotaSalida.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntListaProductos() {
    var ajaxUrl = base_url + '/NotaSalida/getSelectProducto';
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

function cargarProducto() {
    var idProducto = document.querySelector('#idProducto').value;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/NotaSalida/getAgregarProducto/' + idProducto;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            var presentacion = objData.data.presentacion;
            var concentracion = objData.data.concentracion;
            var nombreGenerico = objData.data.nombreGenerico;
            var nombreComercial = objData.data.nombreComercial;
            var precio = objData.data.precioVenta;
            var cantidad = 1;


            if (idProducto != "") {
                var subtotal = cantidad * precio;
                var fila = '<tr class="filas" id="fila' + cont + '">' +
                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')" title="Eliminar"><i class="fas fa-times"></i></button></td>' +
                    '<td><input type="hidden" name="idProducto[]" value="' + idProducto + '">' + nombreGenerico + '</td>' +
                    '<td><input type="hidden" >' + nombreComercial + '</td>' +
                    '<td><input type="hidden" >' + presentacion + '</td>' +
                    '<td><input type="hidden">' + concentracion + '</td>' +
                    '<td><input type="number" style="width : 65px; heigth : 65px" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
                    '</tr>';
                cont++;
                detalles = detalles + 1;
                $('#detalles').append(fila);
                evaluar();
            }
        }
    }
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