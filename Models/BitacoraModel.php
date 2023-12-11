<?php 
    class BitacoraModel extends Mysql
    {
        public $idBitacora;

        public function __construct()
        {
            parent::__construct();
        }
        public function selectBitacoras()
        {
            $sql = "SELECT b.idBitacora,b.accion,u.idUsuario,u.login,(r.nombre)as rol,CONCAT(p.nombre,' ',p.paterno,' ',p.materno)as nombrePersonal FROM bitacora as b, usuario as u, rol as r, personal as p WHERE u.idRol=r.idRol and u.idPersonal=p.idPersonal and b.idUsuario=u.idUsuario";
            $request = $this->select_all($sql);
            return $request;
        }

        public function selectBitacora(int $idBitacora)
        {
            $this->idBitacora = $idBitacora;
            $sql = "SELECT b.idBitacora,b.accion,b.detalle,u.idUsuario,u.login,(r.nombre)as rol,CONCAT(p.nombre,' ',p.paterno,' ',p.materno)as nombrePersonal FROM bitacora as b, usuario as u, personal as p, rol as r WHERE b.idUsuario=u.idUsuario and u.idRol=r.idRol and u.idPersonal=p.idPersonal and idBitacora = $this->idBitacora";
            $request = $this->select($sql);
            return $request;
        }
    }
?>