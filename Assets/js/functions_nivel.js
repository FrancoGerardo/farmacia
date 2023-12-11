var tableNivel;

document.addEventListener('DOMContentLoaded', function() {
    tableNivel = $('#tableNivel').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Nivel/getNiveles",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idNivel" },
            { "data": "nombre" },
            { "data": "descuento" },
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
    var formNivel = document.querySelector("#formNivel");
    if (formNivel != null) {
        formNivel.onsubmit = function(e) {
            e.preventDefault();
            var nombre = document.querySelector('#txtNombre').value;
            var descripcion = document.querySelector('#txtDescripcion').value;
            var cuentaRequerida = document.querySelector('#txtCuentaRequerida').value;
            var descuento = document.querySelector('#txtDescuento').value;
            if (nombre == '' || descripcion == '' || cuentaRequerida == '' || descuento == '') {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Nivel/setNivel';
            var formData = new FormData(formNivel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormNivel').modal("hide");
                        formNivel.reset();
                        swal("Nivel", objData.msg, "success");
                        tableNivel.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableNivel').DataTable();

//VISTA
function fntViewNivel(idNivel) {
    var idNivel = idNivel;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Nivel/getViewNivel/' + idNivel;
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
    document.querySelector('#idNivel').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Nivel";
    document.querySelector("#formNivel").reset();

    $('#modalFormNivel').modal('show');
}

window.addEventListener('load', function() {

}, false);

function fntEditNivel(idNivel) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Nivel";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idNivel = idNivel;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Nivel/getNivel/' + idNivel;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#idNivel").value = objData.data.idNivel;
                document.querySelector("#txtNombre").value = objData.data.nombre;
                document.querySelector("#txtDescripcion").value = objData.data.descripcion;
                document.querySelector("#txtCuentaRequerida").value = objData.data.cuentaRequerida;
                document.querySelector("#txtDescuento").value = objData.data.descuento;

                $('#modalFormNivel').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntActivarNivel(idNivel) {
    var idNivel = idNivel;
    swal({
        title: "Activar Nivel",
        text: "¿Realmente quiere Activar el Nivel?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Activar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Nivel/activarNivel/';
            var strData = "idNivel=" + idNivel;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Activada!", objData.msg, "success");
                        tableNivel.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntDesactivarNivel(idNivel) {
    var idNivel = idNivel;
    swal({
        title: "Desactivar Nivel",
        text: "¿Realmente quiere Desactivar el Nivel?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Nivel/desactivarNivel/';
            var strData = "idNivel=" + idNivel;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Desactivada!", objData.msg, "success");
                        tableNivel.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}