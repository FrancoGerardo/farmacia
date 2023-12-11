var tableProducto;

document.addEventListener('DOMContentLoaded', function() {
    tableProducto = $('#tableProducto').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Producto/getProductos",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idProducto" },
            { "data": "nombreComercial" },
            { "data": "nombreGenerico" },
            { "data": "venta" },
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
    //Nuevo Producto
    var formProducto = document.querySelector("#formProducto");
    if (formProducto != null) {
        formProducto.onsubmit = function(e) {
            e.preventDefault();
            var nombreGenerico = document.querySelector('#txtNombreGenerico').value;
            var nombreComercial = document.querySelector('#txtNombreComercial').value;
            var cantidad = document.querySelector('#txtCantidad').value;
            var precioVenta = document.querySelector('#txtPrecioVenta').value;
            if (nombreGenerico == '' || nombreComercial == '' || cantidad == '' || precioVenta == 0) {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Producto/setProducto';
            var formData = new FormData(formProducto);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormProducto').modal("hide");
                        formProducto.reset();
                        swal("Producto", objData.msg, "success");
                        tableProducto.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableProducto').DataTable();

function fntViewUsuario(idUsuario) {
    var idUsuario = idUsuario;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Usuario/getViewUsuario/' + idUsuario;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#celRol").innerHTML = objData.data.rol;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celLogin").innerHTML = objData.data.login;
                document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                document.querySelector("#celCorreo").innerHTML = objData.data.correo;
                $('#modalViewUsuario').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function openModal() {
    document.querySelector('#idProducto').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Producto";
    document.querySelector("#formProducto").reset();

    $('#modalFormProducto').modal('show');
}

window.addEventListener('load', function() {
    fntCargarPresentacion();
    fntCargarConcentracion();
    fntCargarEstante();
    fntCargarLaboratorio();
}, false);

function fntEditProducto(idProducto) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Producto";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idProducto = idProducto;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Producto/getProducto/' + idProducto;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#idProducto").value = objData.data.idProducto;
                document.querySelector("#idPresentacion").value = objData.data.idPresentacion;
                $('#idPresentacion').selectpicker('render');

                document.querySelector("#idConcentracion").value = objData.data.idConcentracion;
                $('#idConcentracion').selectpicker('render');

                document.querySelector("#idEstante").value = objData.data.idEstante;
                $('#idEstante').selectpicker('render');

                document.querySelector("#idLaboratorio").value = objData.data.idLaboratorio;
                $('#idLaboratorio').selectpicker('render');

                document.querySelector("#txtNombreGenerico").value = objData.data.nombreGenerico;
                document.querySelector("#txtNombreComercial").value = objData.data.nombreComercial;
                document.querySelector("#txtVenta").value = objData.data.venta;
                document.querySelector("#txtCantidad").value = objData.data.cantidad;
                document.querySelector("#txtPrecioVenta").value = objData.data.precioVenta;

                $('#modalFormProducto').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntActivarProducto(idProducto) {
    var idProducto = idProducto;
    swal({
        title: "Activar Usuario",
        text: "¿Realmente quiere Activar el Producto?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Activar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Producto/activarProducto/';
            var strData = "idProducto=" + idProducto;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Activada!", objData.msg, "success");
                        tableProducto.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntDesactivarProducto(idProducto) {
    var idProducto = idProducto;
    swal({
        title: "Desactivar Producto",
        text: "¿Realmente quiere Desactivar el Producto?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Producto/desactivarProducto/';
            var strData = "idProducto=" + idProducto;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Desactivada!", objData.msg, "success");
                        tableProducto.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}


function fntCargarPresentacion() {
    var ajaxUrl = base_url + '/Producto/listPresentacion';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idPresentacion').innerHTML = request.responseText;
            document.querySelector('#idPresentacion').value = 1;
            $('#idPresentacion').selectpicker('render');
        }
    }
}

function fntCargarConcentracion() {
    var ajaxUrl = base_url + '/Producto/listConcentracion';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idConcentracion').innerHTML = request.responseText;
            document.querySelector('#idConcentracion').value = 1;
            $('#idConcentracion').selectpicker('render');
        }
    }
}

function fntCargarEstante() {
    var ajaxUrl = base_url + '/Producto/listEstante';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idEstante').innerHTML = request.responseText;
            document.querySelector('#idEstante').value = 1;
            $('#idEstante').selectpicker('render');
        }
    }
}

function fntCargarLaboratorio() {
    var ajaxUrl = base_url + '/Producto/listLaboratorio';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idLaboratorio').innerHTML = request.responseText;
            document.querySelector('#idLaboratorio').value = 1;
            $('#idLaboratorio').selectpicker('render');
        }
    }
}