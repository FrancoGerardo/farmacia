var tableBitacora;

document.addEventListener('DOMContentLoaded', function() {
    tableBitacora = $('#tableBitacora').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Bitacora/getBitacoras",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idBitacora" },
            { "data": "idUsuario" },
            { "data": "login" },
            { "data": "rol" },
            { "data": "nombrePersonal" },
            { "data": "accion" },
            { "data": "options" }
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [
            [0, "desc"]
        ]
    });
});

$('#tableBitacora').DataTable();

function openModal() {
    document.querySelector('#idBitacora').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Bitacora";
    document.querySelector("#formBitacora").reset();

    $('#modalFormBitacora').modal('show');
}

window.addEventListener('load', function() {}, false);

function fntViewBitacora(idBitacora) {
    var idBitacora = idBitacora;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Bitacora/getBitacora/' + idBitacora;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#celLogin").innerHTML = objData.data.login;
                document.querySelector("#celNombre").innerHTML = objData.data.nombrePersonal;
                document.querySelector("#celCargo").innerHTML = objData.data.rol;
                document.querySelector("#celAccion").innerHTML = objData.data.accion;
                document.querySelector("#celDetalle").innerHTML = objData.data.detalle;
                $('#modalViewBitacora').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}