<?php
class UsuarioModel extends Mysql
{
    public $idUsuario;
    public $idPersonal;
    public $idRol;
    public $login;
    public $password;
    public $estado;
    public function __construct()
    {
        parent::__construct();
    }

    public function getPersonal(int $idPersonal)
    {
        $sql = "SELECT p.idPersonal,CONCAT(p.nombre,' ',p.paterno,' ',p.materno)as nombreP FROM personal as p WHERE idPersonal=$idPersonal";
        $request = $this->select($sql);
        return $request;
    }

    public function getRol(int $idRol)
    {
        $sql = "SELECT r.idRol,(r.nombre)as rol FROM rol as r WHERE idRol=$idRol";
        $request = $this->select($sql);
        return $request;
    }

    public function setBitacora(string $accion, string $detalle)
    {
        $idUsuario = $_SESSION['userData']['idUsuario'];
        $sql = "INSERT INTO bitacora(idUsuario, accion, detalle) VALUES(?,?,?)";
        $arrData = array($idUsuario, $accion, $detalle);
        $this->insert($sql, $arrData);
    }

    public function insertarPersonalBitacora(int $idUsuario)
    {
        $personal = $this->getPersonal($this->idPersonal);
        $rol = $this->getRol($this->idRol);
        $detalle = 'Se Registro al Usuario con ID: ' . $idUsuario . ',' . 'Login: ' . $this->login . ',' . 'Pertenece a :' . $personal['nombreP'] . ',' . 'Con el Rol: ' . $rol['rol'];
        return $detalle;
    }

    public function selectUsuarios()
    {
        $sql = "SELECT u.idUsuario,u.login,CONCAT(p.nombre,' ',p.paterno,' ',p.materno)as nombre,(r.nombre)as rol,u.estado FROM usuario as u, personal as p, rol as r WHERE u.idPersonal = p.idPersonal and u.idRol = r.idRol";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectUsuario(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        $sql = "SELECT * FROM usuario WHERE idUsuario = $this->idUsuario";
        $request = $this->select($sql);
        return $request;
    }

    public function selectViewUsuario(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        $sql = "SELECT u.idUsuario, u.login, (r.nombre)as rol, CONCAT(p.nombre,' ', p.paterno,' ',p.materno)as nombre, p.telefono, p.correo FROM usuario as u, personal as p, rol as r WHERE u.idPersonal = p.idPersonal and u.idRol = r.idRol and  u.idUsuario = $this->idUsuario";
        $request = $this->select($sql);
        return $request;
    }

    public function insertUsuario(int $idPersonal, int $idRol, string $login, string $password)
    {

        $return = "";
        $this->idPersonal = $idPersonal;
        $this->idRol = $idRol;
        $this->login = $login;
        $this->password = hash("SHA256", $password);

        $sql = "SELECT * FROM usuario WHERE login ='{$this->login}'";
        $request = $this->select_all($sql);

        $accion = 'Registrar Usuario';
        if (empty($request)) {
            $query_insert = "INSERT INTO usuario(idPersonal, idRol, login, password, estado) VALUES(?,?,?,?,?)";
            $arrData = array($this->idPersonal, $this->idRol, $this->login, $this->password, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            if ($request_insert > 0) {
                $detalle = $this->insertarPersonalBitacora($request_insert);
                $this->setBitacora($accion, $detalle);
            }
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateUsuario(int $idUsuario, int $idPersonal, int $idRol, string $login, string $password)
    {
        $this->idUsuario = $idUsuario;
        $this->idPersonal = $idPersonal;
        $this->idRol = $idRol;
        $this->login = $login;
        $this->password = $password;

        $sql = "SELECT* FROM usuario WHERE login = '$this->login' AND idUsuario != $this->idUsuario";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE usuario SET idPersonal = ?, idRol = ?, login = ?, password = ? WHERE idUsuario = $this->idUsuario";
            $arrData = array($this->idPersonal, $this->idRol, $this->login, $this->password);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarUsuario(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        $sql = "UPDATE usuario SET estado = ? WHERE idUsuario = $this->idUsuario";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarUsuario(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        $sql = "UPDATE usuario SET estado = ? WHERE idUsuario = $this->idUsuario";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    ///////////////////////////////////////////////////////////////////////
    public function selectPersonals()
    {
        $sql = "SELECT * FROM personal";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectRoles()
    {
        $sql = "SELECT * FROM rol";
        $request = $this->select_all($sql);
        return $request;
    }
}
