$('.login-content [data-toggle="flip"]').click(function () {
    $('.login-box').toggleClass('flipped');
    return false;
});
document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector("#formLogin")) {
        let formLogin = document.querySelector("#formLogin");
        formLogin.onsubmit = function (e) {
            e.preventDefault();
            let strLoginn = document.querySelector('#txtLoginn').value;
            let strPass = document.querySelector('#txtPass').value;

            if (strLoginn == "" || strPass == "") {
                swal("Por favor", "Escribe usuario y contraseña", "error");
                return false;
            } else {
                var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url + '/Login/loginUser';
                var formData = new FormData(formLogin);
                request.open("POST", ajaxUrl, true);
                request.send(formData);
                
                request.onreadystatechange = function () {
                    if (request.readyState != 4) return;
                    if (request.status == 200) {
                        var objData = JSON.parse(request.responseText);
                        if (objData.status) {
                            window.location = base_url + '/dashboard';
                        } else {
                            swal("Atencion", objData.msg, "error");
                            document.querySelector('#txtPass').value = "";
                            document.querySelector('#txtLoginn').value = "";
                        }
                    } else {
                        swal("Atencion", "Error en el Proceso", "error");
                    }
                    return false;
                }
            }
        }
    }
}, false);