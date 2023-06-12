<?php
    class ControlRol{
	   var $objRol;
        function __construct($objRol){
         $this->objRol=$objRol;

        }
    function guardar() {
        $nom= $this->objRol->getNombre();        
        $comandoSql = "INSERT INTO Rol(nombre) VALUES ('$nom')";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $objControlConexion->ejecutarComandoSql($comandoSql);
        $objControlConexion->cerrarBd();
    }
    
    function consultar() {
        $id= $this->objRol->getId(); 
    
        $comandoSql = "SELECT * FROM Rol WHERE id = '$id'";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $recordSet = $objControlConexion->ejecutarSelect($comandoSql);
        if ($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) {
            $this->objRol->setNombre($row['nombre']);
        }
        $objControlConexion->cerrarBd();
        return $this->objRol;
    }

    function modificar() {
        $id= $this->objRol->getId(); 
        $nom= $this->objRol->getNombre(); 
        
        $comandoSql = "UPDATE Rol SET nombre='$nom' WHERE id = $id";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $objControlConexion->ejecutarComandoSql($comandoSql);
        $objControlConexion->cerrarBd();
    }

    function borrar() {
        $id= $this->objRol->getId(); 
        $comandoSql = "DELETE FROM Rol WHERE id = '$id'";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $objControlConexion->ejecutarComandoSql($comandoSql);
        $objControlConexion->cerrarBd();
    }

    function listar() {
        $comandoSql = "SELECT * FROM Rol";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $recordSet = $objControlConexion->ejecutarSelect($comandoSql);
        if (pg_num_rows($recordSet) > 0) {
            $arregloRoles = array();
            $i=0;
            while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)){
                $objRol=new Rol(0,"");
                $objRol->setId($row['id']);
                $objRol->setNombre($row['nombre']);
                $arregloRoles[$i]=$objRol;
                $i++;
            }
        }
        $objControlConexion->cerrarBd();
        return $arregloRoles;
    }

}
?>