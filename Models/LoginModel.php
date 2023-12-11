<?php
class LoginModel extends Mysql
{
    public $idUsuario;
    private $login;
    private $idCargo;
    private $password;
    private $strToken;

    public function __construct()
    {
        parent::__construct();
    }
    public function loginUser(string $login, string $pass)
    {
        $this->login = $login;
        $this->password = hash("SHA256",$pass);
        
        $sql = "SELECT * FROM usuario WHERE login='$this->login' and estado!=0"; // and password='$this->password'
        $request = $this->select($sql);
        return $request;
    }
    public function obtenerdatos(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        $sql = "SELECT u.idUsuario, u.login, u.idPersonal, CONCAT(p.nombre,' ',p.paterno,' ',p.materno)as nombre, p.imagen, u.idRol, (r.nombre)as cargo, p.correo, u.estado FROM usuario as u, personal as p, rol as r WHERE u.idPersonal=p.idPersonal and u.idRol=r.idRol and u.idUsuario=$this->idUsuario";
        $request = $this->select($sql);
        return $request;
    }
    public function obtenerPrivilegios(int $idCargo)
    {
        $this->idCargo = $idCargo;
        $sql = "SELECT p.idAcceso,p.ver,p.crear,p.modificar,p.eliminar FROM privilegio as p  WHERE p.idRol=$this->idCargo";
        $request = $this->select_all($sql);
        return $request;
    }
}
