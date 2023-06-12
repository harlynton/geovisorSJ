<?php
    class ControlUsuario{
	   var $objUsuario;
        function __construct($objUsuario){
         $this->objUsuario=$objUsuario;

        }

    function validarIngreso()
        {
            $msg = "ok";
            $validar=false;
            $ema= $this->objUsuario->getEmail(); 
            $con=$this->objUsuario->getContrasena();
            //$comandoSql ="SELECT * FROM usuario WHERE email='$ema' AND contrasena=crypt('$con', contrasena);";
            $comandoSql ="SELECT * FROM usuario WHERE email='$ema' AND contrasena='$con';";
            $objControlConexion = new ControlConexionPostgreSQL();
            $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
            
            $recordSet = $objControlConexion->ejecutarSelect($comandoSql);
            try
            {
                if (pg_num_rows($recordSet) > 0) 
                {
                    $validar = true;
                }
                $objControlConexion->cerrarBd();
            }
            catch (Exception $objExcetion)
            {
                $msg = $objExcetion->getMessage();
            }
            
            return $validar;
    }


    function guardar() {

        $ema= $this->objUsuario->getEmail(); 
        $con=$this->objUsuario->getContrasena();
            
        $comandoSql = "INSERT INTO usuario(email,contrasena) VALUES ('$ema', crypt('$con', gen_salt('md5')));";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $objControlConexion->ejecutarComandoSql($comandoSql);
        $objControlConexion->cerrarBd();

    }
    
    function consultar() {
        $ema= $this->objUsuario->getEmail(); 
    
        $comandoSql = "SELECT * FROM usuario WHERE email = '$ema'";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $recordSet = $objControlConexion->ejecutarSelect($comandoSql);
        if (pg_num_rows($recordSet) ==1) {
            $row = pg_fetch_array($recordSet, 0, PGSQL_BOTH);
            $this->objUsuario->setContrasena($row['contrasena']);
            
        }
        $objControlConexion->cerrarBd();
        return $this->objUsuario;
    }

    function modificar() {
        $ema= $this->objUsuario->getEmail(); 
        $con=$this->objUsuario->getContrasena();
        
        $comandoSql = "UPDATE usuario SET contrasena=crypt('$con', gen_salt('md5')) WHERE email = '$ema'";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $objControlConexion->ejecutarComandoSql($comandoSql);
        $objControlConexion->cerrarBd();
    }

    function borrar() {
        $ema= $this->objUsuario->getEmail(); 
        $comandoSql = "DELETE FROM usuario WHERE email = '$ema'";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $objControlConexion->ejecutarComandoSql($comandoSql);
        $objControlConexion->cerrarBd();
    }

    function listar() {
        $comandoSql = "SELECT * FROM usuario";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $recordSet = $objControlConexion->ejecutarSelect($comandoSql);
        if (pg_num_rows($recordSet) > 0) {
            $arregloUsuarios = array();
            $i=0;
            while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) {
                $objUsuario=new Usuario("","");
                $objUsuario->setEmail($row['email']);
                $objUsuario->setContrasena($row['contrasena']);
                $arregloUsuarios[$i]=$objUsuario;
                $i++;
            }
        }
        $objControlConexion->cerrarBd();
        return $arregloUsuarios;
    }
}
?>