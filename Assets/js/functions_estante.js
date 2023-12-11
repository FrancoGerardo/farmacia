var tableEstante;

document.addEventListener('DOMContentLoaded', function() {
    tableEstante = $('#tableEstante').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Estante/getEstantes",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idEstante" },
            { "data": "nombre" },
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

    var formEstante = document.querySelector("#formEstante");
    if (formEstante != null) {
        formEstante.onsubmit = function(e) {
            e.preventDefault();
            var nombre = document.querySelector('#txtNombre').value;
            if (nombre == '') {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Estante/setEstante';
            var formData = new FormData(formEstante);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormEstante').modal("hide");
                        formEstante.reset();
                        swal("Estante", objData.msg, "success");
                        tableEstante.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableEstante').DataTable();

function openModal() {
    document.querySelector('#idEstante').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Estante";
    document.querySelector("#formEstante").reset();

    $('#modalFormEstante').modal('show');
}

window.addEventListener('load', function() {

}, false);

function fntEditEstante(idEstante) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Estante";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idEstante = idEstante;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Estante/getEstante/' + idEstante;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#idEstante").value = objData.data.idEstante;
                document.querySelector("#txtNombre").value = objData.data.nombre;

                $('#modalFormEstante').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntActivarEstante(idEstante) {
    var idEstante = idEstante;
    swal({
        title: "Activar Estante",
        text: "¿Realmente quiere Activar el Estante?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Activar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Estante/activarEstante/';
            var strData = "idEstante=" + idEstante;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Activada!", objData.msg, "success");
                        tableEstante.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntDesactivarEstante(idEstante) {
    var idEstante = idEstante;
    swal({
        title: "Desactivar Estante",
        text: "¿Realmente quiere Desactivar el Estante?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Estante/desactivarEstante/';
            var strData = "idEstante=" + idEstante;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Desactivada!", objData.msg, "success");
                        tableEstante.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}