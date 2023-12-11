var tablePersonal;

document.addEventListener('DOMContentLoaded', function() {
    tablePersonal = $('#tablePersonal').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Personal/getPersonals",
            "dataSrc": ""
        },
        "columns": [
            { "data": "idPersonal" },
            { "data": "nombre" },
            { "data": "paterno" },
            { "data": "materno" },
            { "data": "documento" },
            { "data": "codDocumento" },
            { "data": "telefono" },
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
    //Nuevo ROL
    var formPersonal = document.querySelector("#formPersonal");
    if (formPersonal != null) {
        formPersonal.onsubmit = function(e) {
            e.preventDefault();
            var strDocumento = document.querySelector('#txtDocumento').value;
            var strCodDocumento = document.querySelector('#txtCodDocumento').value;
            var strNombre = document.querySelector('#txtNombre').value;
            var strPaterno = document.querySelector('#txtPaterno').value;
            var strMaterno = document.querySelector('#txtMaterno').value;
            var strTelefono = document.querySelector('#txtTelefono').value;
            var strSexo = document.querySelector('#txtSexo').value;
            var strDireccion = document.querySelector('#txtDireccion').value;
            var strCorreo = document.querySelector('#txtCorreo').value;
            var strNacionalidad = document.querySelector('#txtNacionalidad').value;
            if (strNombre == '' || strPaterno == '' || strMaterno == '' || strDocumento == '' || strCodDocumento == '' || strTelefono == '' || strSexo == '' || strDireccion == '' || strCorreo == '' || strNacionalidad == 0) {
                swal("Atencion", "Todos los campos son obligatorios", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Personal/setPersonal';
            var formData = new FormData(formPersonal);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormPersonal').modal("hide");
                        formPersonal.reset();
                        swal("Personal de Tienda", objData.msg, "success");
                        removePhoto();
                        tablePersonal.api().ajax.reload(function() {});
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }
        }
    }
    if (document.querySelector("#foto")) {
        var foto = document.querySelector("#foto");
        foto.onchange = function(e) {
            var uploadFoto = document.querySelector("#foto").value;
            var fileimg = document.querySelector("#foto").files;
            var nav = window.URL || window.webkitURL;
            var contactAlert = document.querySelector('#form_alert');
            if (uploadFoto != '') {
                var type = fileimg[0].type;
                var name = fileimg[0].name;
                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.add("notBlock");
                    foto.value = "";
                    return false;
                } else {
                    contactAlert.innerHTML = '';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                    var objeto_url = nav.createObjectURL(this.files[0]);
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + objeto_url + ">";
                }
            } else {
                alert("No selecciono foto");
                if (document.querySelector('#img')) {
                    document.querySelector('#img').remove();
                }
            }
        }
    }
    if (document.querySelector(".delPhoto")) {
        var delPhoto = document.querySelector(".delPhoto");
        delPhoto.onclick = function(e) {
            removePhoto();
        }
    }
});

function removePhoto() {
    document.querySelector('#foto').value = "";
    document.querySelector('.delPhoto').classList.add("notBlock");
    document.querySelector('#img').remove();
}
$('#tablePersonal').DataTable();

function openModal() {
    document.querySelector('#idPersonal').value = "";
    document.querySelector('#foto').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Personal";
    document.querySelector("#formPersonal").reset();
    fechaActual();
    if (document.querySelector('#img') != null) {
        document.querySelector('#img').remove();
    }
    $('#modalFormPersonal').modal('show');
}

window.addEventListener('load', function() {
    fntCargarPaises();

}, false);

function fntEditPersonal(idPersonal) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Personal";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idPersonal = idPersonal;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Personal/getPersonal/' + idPersonal;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#idPersonal").value = objData.data.idPersonal;
                document.querySelector("#txtNombre").value = objData.data.nombre;
                document.querySelector("#txtPaterno").value = objData.data.paterno;
                document.querySelector("#txtMaterno").value = objData.data.materno;
                document.querySelector("#txtDocumento").value = objData.data.documento;
                document.querySelector("#txtCodDocumento").value = objData.data.codDocumento;
                document.querySelector("#txtSexo").value = objData.data.sexo;
                document.querySelector("#txtTelefono").value = objData.data.telefono;
                document.querySelector("#txtDireccion").value = objData.data.direccion;
                document.querySelector("#txtCorreo").value = objData.data.correo;
                document.querySelector("#txtNacionalidad").value = objData.data.nacionalidad;
                $('#txtNacionalidad').selectpicker('render');
                document.querySelector("#fechaModal").value = objData.data.fecha;

                var img = './Assets/images/personal/' + objData.data.imagen;
                document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + img + ">";
                ("#foto").value = objData.data.imagen;
                // if (objData.data.estado == 1) {
                //     var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
                // } else {
                //     var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';
                // }

                // var htmlSelect = `${optionSelect}
                //                         <option value="1">Activo</option>
                //                         <option value="2">Inactivo</option>
                //                         `;
                // document.querySelector("#listEstado").innerHTML = htmlSelect;
                $('#modalFormPersonal').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntViewMarca(idMarca) {
    var idMarca = idMarca;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Marca/getMarca/' + idMarca;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                var estadoMarca = objData.data.estado == 1 ?
                    '<span class="badge badge-success">Activo</span>' :
                    '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celEstado").innerHTML = estadoMarca;
                $('#modalViewMarca').modal('show');
            } else {
                swal("Error", objData.msg, "error")
            }
        }
    }
}

function fntActivarPersonal(idPersonal) {
    var idPersonal = idPersonal;
    swal({
        title: "Activar Personal",
        text: "¿Realmente quiere Activar el Personal?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Activar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Personal/activarPersonal/';
            var strData = "idPersonal=" + idPersonal;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Activada!", objData.msg, "success");
                        tablePersonal.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fntDesactivarPersonal(idPersonal) {
    var idPersonal = idPersonal;
    swal({
        title: "Desactivar Personal",
        text: "¿Realmente quiere Desactivar el Personal?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Personal/desactivarPersonal/';
            var strData = "idPersonal=" + idPersonal;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Desactivada!", objData.msg, "success");
                        tablePersonal.api().ajax.reload(function() {});
                    } else {
                        swal("Atencion", objData.msg, "error");
                    }
                }
            }
        }
    });
}

function fechaActual() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha').val(today);
    $('#fechaModal').val(today);
}

function fntCargarPaises() {
    var ajaxUrl = base_url + '/Personal/obtenerPaises';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#txtNacionalidad').innerHTML = request.responseText;
            document.querySelector('#txtNacionalidad').value = 1;
            $('#txtNacionalidad').selectpicker('render');
        }
    }
}