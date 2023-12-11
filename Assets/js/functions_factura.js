var tableFactura;

document.addEventListener('DOMContentLoaded', function() {
    tableFactura = $('#tableFactura').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Factura/getFacturas",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idFactura" },
            { "data": "idVenta" },
            { "data": "nombre" },
            { "data": "nit" },
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

});

$('#tableNivel').DataTable();

//VISTA
function fntViewFactura(idFactura) {
    var idFactura = idFactura;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Factura/getViewFactura/' + idFactura;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idFacturaV").innerHTML = '#' + objData.data.idFactura;
                document.querySelector("#nombre").innerHTML = ': ' + objData.data.nombre;
                document.querySelector("#nit").innerHTML = ': ' + objData.data.nit;
                document.querySelector("#documento").innerHTML = ': ' + objData.data.documento;
                document.querySelector("#codDocumento").innerHTML = ': ' + objData.data.codDocumento;
                document.querySelector("#correo").innerHTML = ': ' + objData.data.correo;

                document.querySelector("#nivelV").innerHTML = ': ' + objData.data.nivel;
                document.querySelector("#descuentoV").innerHTML = ': ' + objData.data.descuento;
                document.querySelector("#usuarioV").innerHTML = ': ' + objData.data.nombreUsuario;
                document.querySelector("#cargoV").innerHTML = ': ' + objData.data.cargo;
                document.querySelector("#fechaV").innerHTML = ': ' + objData.data.fecha;
                document.querySelector("#estadoV").innerHTML = ': ' + objData.data.estado;
                var idVenta = objData.data.idVenta;
                $.post(base_url + '/Factura/getDetalleFactura/' + idVenta, function(r) {
                    $("#detalleViewFactura").html(r);
                });
                $('#modalViewFactura').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}


window.addEventListener('load', function() {

}, false);

function fntAnularFactura(idFactura) {
    var idFactura = idFactura;
    swal({
        title: "Anular Factura",
        text: "¿Realmente quiere ANULAR la Factura?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, ANULAR",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Factura/anularFactura/';
            var strData = "idFactura=" + idFactura;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("ANULADA!", objData.msg, "success");
                        tableFactura.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}