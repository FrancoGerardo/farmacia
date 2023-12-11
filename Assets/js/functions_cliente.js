var tableCliente;

document.addEventListener('DOMContentLoaded', function() {
    tableCliente = $('#tableCliente').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Cliente/getClientes",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idCliente" },
            { "data": "nombre" },
            { "data": "paterno" },
            { "data": "materno" },
            { "data": "nivel" },
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
    var formCliente = document.querySelector("#formCliente");
    if (formCliente != null) {
        formCliente.onsubmit = function(e) {
            e.preventDefault();

            var documento = document.querySelector('#txtDocumento').value;
            var codDocumento = document.querySelector('#txtCodDocumento').value;
            var nit = document.querySelector('#nit').value;
            var nombre = document.querySelector('#txtNombre').value;
            var paterno = document.querySelector('#txtPaterno').value;
            var materno = document.querySelector('#txtMaterno').value;
            var correo = document.querySelector('#txtCorreo').value;

            if (documento == 0 || codDocumento == '' || nit == '' || nombre == '' || paterno == '' || materno == '' || correo == '') {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Cliente/setCliente';
            var formData = new FormData(formCliente);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormCliente').modal("hide");
                        formCliente.reset();
                        swal("Cliente", objData.msg, "success");
                        tableCliente.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableCliente').DataTable();

//VISTA
function fntViewNivel(idCliente) {
    var idCliente = idCliente;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Nivel/getViewCliente/' + idCliente;
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
    document.querySelector('#idCliente').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Cliente";
    document.querySelector("#formCliente").reset();

    $('#modalFormCliente').modal('show');
}

window.addEventListener('load', function() {
    fntCargarNivel();
}, false);

function fntEditCliente(idCliente) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Cliente";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idCliente = idCliente;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Cliente/getCliente/' + idCliente;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#idCliente").value = objData.data.idCliente;
                document.querySelector("#idNivel").value = objData.data.idNivel;
                $('#idNivel').selectpicker('render');
                document.querySelector("#txtDocumento").value = objData.data.documento;
                document.querySelector("#txtCodDocumento").value = objData.data.codDocumento;
                document.querySelector("#nit").value = objData.data.nit;
                document.querySelector("#txtNombre").value = objData.data.nombre;
                document.querySelector("#txtPaterno").value = objData.data.paterno;
                document.querySelector("#txtMaterno").value = objData.data.materno;
                document.querySelector("#txtCorreo").value = objData.data.correo;
                document.querySelector("#txtCuenta").value = objData.data.cuenta;

                $('#modalFormCliente').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntActivarCliente(idCliente) {
    var idCliente = idCliente;
    swal({
        title: "Activar Cliente",
        text: "¿Realmente quiere Activar el Cliente?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Activar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Cliente/activarCliente/';
            var strData = "idCliente=" + idCliente;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Activada!", objData.msg, "success");
                        tableCliente.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntDesactivarCliente(idCliente) {
    var idCliente = idCliente;
    swal({
        title: "Desactivar Cliente",
        text: "¿Realmente quiere Desactivar el Cliente?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Cliente/desactivarCliente/';
            var strData = "idCliente=" + idCliente;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Desactivada!", objData.msg, "success");
                        tableCliente.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntCargarNivel() {
    var ajaxUrl = base_url + '/Cliente/getNivel';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#idNivel').innerHTML = request.responseText;
            document.querySelector('#idNivel').value = 2;
            $('#idNivel').selectpicker('render');
        }
    }
}