var tableLote;

document.addEventListener('DOMContentLoaded', function() {
    tableLote = $('#tableLote').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Lote/getLotes",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idLote" },
            { "data": "idProducto" },
            { "data": "codigo" },
            { "data": "fechaVencimiento" },
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
    var formLote = document.querySelector("#formLote");
    if (formLote != null) {
        formLote.onsubmit = function(e) {
            e.preventDefault();
            var fabricante = document.querySelector('#txtFabricante').value;
            var codigo = document.querySelector('#txtCodigo').value;
            var cantidad = document.querySelector('#intCantidad').value;
            if (fabricante == '' || codigo == '' || cantidad == '') {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Lote/setLote';
            var formData = new FormData(formLote);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormLote').modal("hide");
                        formLote.reset();
                        swal("Lote", objData.msg, "success");
                        tableLote.api().ajax.reload(function() {

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
});

$('#tableLote').DataTable();

function fntViewLote(idLote) {
    var idLote = idLote;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Lote/getViewLote/' + idLote;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {

                document.querySelector("#celidProducto").innerHTML = objData.data.producto;
                document.querySelector("#celCodigo").innerHTML = objData.data.codigo;
                document.querySelector("#celFabricante").innerHTML = objData.data.fabricante;
                document.querySelector("#celCantidad").innerHTML = objData.data.cantidad;
                document.querySelector("#celFechaVencimiento").innerHTML = objData.data.fechaVencimiento;

                var estadoLote = objData.data.estado == 1 ?
                    '<span class="badge badge-success">Activo</span>' :
                    '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celEstado").innerHTML = estadoLote;

                $('#modalViewLote').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function openModal() {
    document.querySelector('#idLote').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Lote";
    document.querySelector("#formLote").reset();

    $('#modalFormLote').modal('show');
}

window.addEventListener('load', function() {
    fntListaProductos();
}, false);

function fntEditLote(idLote) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Lote";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idLote = idLote;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Lote/getLote/' + idLote;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#idLote").value = objData.data.idLote;
                document.querySelector("#idProducto").value = objData.data.idProducto;
                $('#idProducto').selectpicker('render');
                document.querySelector("#txtCodigo").value = objData.data.codigo;
                document.querySelector("#txtFabricante").value = objData.data.fabricante;
                document.querySelector("#intCantidad").value = objData.data.cantidad;
                document.querySelector("#dateFechaVencimiento").value = objData.data.fechaVencimiento;

                $('#modalFormLote').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntActivarLote(idLote) {
    var idLote = idLote;
    swal({
        title: "Activar Lote",
        text: "¿Realmente quiere Activar el Lote?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Activar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Lote/activarLote/';
            var strData = "idLote=" + idLote;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Activada!", objData.msg, "success");
                        tableLote.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntDesactivarLote(idLote) {
    var idLote = idLote;
    swal({
        title: "Desactivar Lote",
        text: "¿Realmente quiere Desactivar el Lote?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Lote/desactivarLote/';
            var strData = "idLote=" + idLote;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Desactivada!", objData.msg, "success");
                        tableLote.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntListaProductos() {
    var ajaxUrl = base_url + '/Lote/getSelectProducto';
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