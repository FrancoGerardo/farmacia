<?php
    $controller = ucwords($controller);
    $controllerFile = "Controllers/".$controller.".php";
    if (file_exists($controllerFile)) {
        require_once($controllerFile);
        $controller= new $controller();
        if (method_exists($controller,$method)) {
            $controller->{$method}($params);
        }else {
            //echo "No existe el metodo";
            require_once("Controllers/Error.php");
        }
    }else {
        //echo "no existe controlador";
        //$buscar = strpos($controller,'Exel');
        if(strpos($controller,'Exel') !== false){
            require_once("Views/Exel/".$controller.".php");
        }else{
            require_once("Controllers/Error.php");
        }
    }
?>