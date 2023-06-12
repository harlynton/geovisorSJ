<?php
class ControlRolUsuario{
	var $objRolUsuario;
        function __construct($objRolUsuario)
        {
            $this->objRolUsuario = $objRolUsuario;
		}


    function guardar() {
		$fkEma = $this->objRolUsuario->getFkEmail();
        $fkIdR = $this->objRolUsuario->getFkIdRol();
        
        $comandoSql = "INSERT INTO rol_usuario(fkEmail,fkIdRol) VALUES ('$fkEma','$fkIdR')";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $objControlConexion->ejecutarComandoSql($comandoSql);
        $objControlConexion->cerrarBd();
    }

        function consultarRoles_por_EmailUsuario()
        {
            $msg = "ok";
            $i=0;
            $matRolUsuario = null;
            $fkEma = $this->objRolUsuario->getFkEmail();
            $comandoSql  ="SELECT rol.id,rol.nombre " .
                        "FROM rol INNER JOIN rol_usuario ON rol.id=rol_usuario.fkIdRol" .
                        " WHERE rol_usuario.fkEmail='$fkEma'";
            $objControlConexion = new ControlConexionPostgreSQL();
            $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
            $recordSet = $objControlConexion->ejecutarSelect($comandoSql);
            try
            {
                if (pg_num_rows($recordSet) > 0)
                {
                    $i=0;
                    while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) 
                    {
                        $matRolUsuario[$i][0] = $row['id'];
                        $matRolUsuario[$i][1] = $row['nombre'];
                        $i++;
                    }
                    $objControlConexion->cerrarBd();
                }
            }
            catch (Exception $objExcetion)
            {
                $msg = $objExcetion->getMessage();
            }
            return $matRolUsuario;
        }
        function consultarRolesPorUsuario($ema)
        {
            $msg = "ok";
            $listadoIdRolesDelUsuario = [];
            $comandoSQL ="SELECT fkIdRol FROM rol_usuario WHERE fkEmail='$ema'";
            echo  $comandoSQL;
            $objControlConexion = new ControlConexionPostgreSQL();
            $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
            $recordSet = $objControlConexion->ejecutarSelect($comandoSQL);
            try
            {
                if (pg_num_rows($recordSet) > 0)
                {
                    $i=0;
                    while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) 
                    {
                        $listadoIdRolesDelUsuario[$i]= $row['id'];
                        $i++;
                    }
                    $objControlConexion->cerrarBd();
                }
            }
            catch (Exception $objExcetion)
            {
                $msg = $objExcetion->getMessage();
            }
            return $listadoIdRolesDelUsuario;
        }
    
        function borrarTodosEmailUsuario()
        {
            $fkEma = $this->objRolUsuario->getFkEmail();
            $comandoSql  ="DELETE FROM rol_usuario WHERE fkEmail='$fkEma'";
            $objControlConexion = new ControlConexionPostgreSQL();
            $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        	$objControlConexion->ejecutarComandoSql($comandoSql);
            $objControlConexion->cerrarBd();
        }
}
?>