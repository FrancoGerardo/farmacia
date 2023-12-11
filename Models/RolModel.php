<?php
class RolModel extends Mysql
{
    public $idRol;
    public $nombre;
    public $descripcion;
    public $estado;

    public $listAcceso;
    public $listVer;
    public $listCrear;
    public $listModificar;
    public $listEliminar;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectRoles()
    {
        $sql = "SELECT * FROM rol";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectRol(int $idRol)
    {
        $this->idRol = $idRol;
        $sql = "SELECT * FROM rol WHERE idRol = $this->idRol";
        $request = $this->select($sql);
        return $request;
    }

    public function selectModulos(int $idRol)
    {
        $sql = "SELECT p.idPrivilegio,p.idAcceso,(a.nombre)as modulo,p.ver,p.crear,p.modificar,p.eliminar FROM privilegio as p, acceso as a WHERE p.idAcceso = a.idAcceso and idRol = $idRol";
        $request = $this->select_all($sql);
        return $request;
    }
    public function insertRol(string $nombre, string $descripcion)
    {
        $return = "";
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;

        $sql = "SELECT * FROM rol WHERE nombre ='{$this->nombre}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO rol(nombre,descripcion,estado) VALUES(?,?,?)";
            $arrData = array($this->nombre, $this->descripcion, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;

            $cant = $this->select_all("SELECT idAcceso  FROM acceso");
            for ($i = 0; $i < count($cant); $i++) {
                $query_insert = "INSERT INTO privilegio(idRol,idAcceso,ver,crear,modificar,eliminar) VALUES(?,?,?,?,?,?)";
                $arrData = array(intval($return), $cant[$i]['idAcceso'], 0, 0, 0, 0);
                $this->insert($query_insert, $arrData);
            }
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateRol(int $idRol, string $nombre, string $descripcion)
    {
        $this->idRol = $idRol;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $sql = "SELECT* FROM rol WHERE nombre = '$this->nombre' AND idRol != $this->idRol";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE rol SET nombre = ?, descripcion = ? WHERE idRol = $this->idRol";
            $arrData = array($this->nombre, $this->descripcion);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updatePrivilegios(int $idRol, array $idAcceso, array $ver, array $crear, array $modificar, array $eliminar)
    {
        $this->listAcceso = $idAcceso;
        $this->listVer = $ver;
        $this->listCrear = $crear;
        $this->listModificar = $modificar;
        $this->listEliminar = $eliminar;

        for ($i = 0; $i < count($this->listAcceso); $i++) {
            $acceso = $this->listAcceso[$i];
            $buscar = array_search($acceso, $this->listVer, true);
            if (strlen($buscar) > 0) {
                $ver = 1;
            } else {
                $ver = 0;
            }

            $buscar = array_search($acceso, $this->listCrear, true);
            if (strlen($buscar) > 0) {
                $crear = 1;
            } else {
                $crear = 0;
            }

            $buscar = array_search($acceso, $this->listModificar, true);
            if (strlen($buscar) > 0) {
                $modificar = 1;
            } else {
                $modificar = 0;
            }

            $buscar = array_search($acceso, $this->listEliminar, true);
            if (strlen($buscar) > 0) {
                $eliminar = 1;
            } else {
                $eliminar = 0;
            }
            $sql = ("UPDATE privilegio SET ver=?, crear=?, modificar=?, eliminar=? WHERE idRol = $idRol AND idAcceso = $acceso");
            $arrData = array($ver, $crear, $modificar, $eliminar);

            $request = $this->update($sql, $arrData);
        }
        if ($request>0) {
            $this->actualizarMisPrivilegios();
        }
        return $request;
        // print_r($this->listAcceso);
        // print_r($this->listVer);
        // print_r($this->listCrear);
        // print_r($this->listModificar);
        // print_r($this->listEliminar);
        // $sql = "UPDATE cargo SET nombre = ?, descripcion = ? WHERE idCargo = $this->idCargo";
        // $arrData = array($this->nombre, $this->descripcion);
        // $request = $this->update($sql, $arrData);
    }
    public function updateActivarRol(int $idRol)
    {
        $this->idRol = $idRol;
        $sql = "UPDATE rol SET estado = ? WHERE idRol = $this->idRol";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarRol(int $idRol)
    {
        $this->idRol = $idRol;
        $sql = "SELECT* FROM usuario WHERE idRol = $this->idRol and estado = 1";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE rol SET estado = ? WHERE idRol = $this->idRol";
            $arrData = array(2);
            $request = $this->update($sql, $arrData);
            if ($request) {
                $request = 'ok';
            } else {
                $request = 'error';
            }
        } else {
            $request = 'exist';
        }
        return $request;
    }
    //Acceder a funciones dentro de otras clases
    public function actualizarMisPrivilegios()
    {
        $idUsuario = $_SESSION['idUsuario'];
        require_once 'LoginModel.php';
        $login = new LoginModel();
        $listPrivilegios = $login->obtenerPrivilegios($idUsuario);
        $_SESSION['privilegios'] = $listPrivilegios;
        return;
    }
}
?>