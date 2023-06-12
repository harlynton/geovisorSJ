<?php

class ControlConexionPostgreSQL{
	
	var $conn;
	function __construct(){
		$this->conn=null;
	}
    function abrirBd($servidor, $usuario, $password,$db,$puerto){
    	try	{
			 
			$this->conn = pg_connect("host=".$servidor." port=".$puerto." dbname=".$db." user=".$usuario." password=".$password);
      	}
      	catch (Exception $e){
          	echo "ERROR AL CONECTARSE AL SERVIDOR ".$e->getMessage()."\n";
      	}

    }

    function cerrarBd() {
		try{
    if(!pg_close($this->conn)) {
        print "Failed to close connection to " . pg_host($this->conn) . ": " .
       pg_last_error($this->conn) . "<br/>\n";
    } else {
        //print "Successfully disconnected from database";
    }
		}
      	catch (Exception $e){
          	echo "ERROR AL CONECTARSE AL SERVIDOR ".$e->getMessage()."\n";
      	}		
    }

    function ejecutarComandoSql($sql) {
    	try	{
			//$sql = pg_escape_string($cadena);
			pg_query($this->conn, $sql);
			}
		catch (Exception $e) {
				echo " NO SE AFECTARON LOS REGISTROS: ". $e->getMessage()."\n";
		  }	
		}

	function ejecutarSelect($sql) {
			try	{
				//$sql = pg_escape_string($sql);
				 $recordSet=pg_query($this->conn, $sql);
				}
			catch (Exception $e) {
					echo " ERROR: ". $e->getMessage()."\n";
			  }	
		return $recordSet;
			
	}
    function obtenerListaTablas(){
		$sql="SELECT table_name
			FROM information_schema.tables
			WHERE table_schema='public'
			AND table_type='BASE TABLE';";
        $recordSet = $this->ejecutarSelect($sql);
		$listaTablas = [];
		$i=0;
		while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) {
			$listaTablas[$i]=$row['table_name'];
			$i++;
		}
        return $listaTablas;
    }
	function obtenerListaGeoTablas(){
		$sql="SELECT table_name
			FROM information_schema.tables
			WHERE table_schema='public'
			AND table_type='BASE TABLE';";
        $recordSet = $this->ejecutarSelect($sql);
		$listaGeoTablas = [];
		$i=0;
		while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) {
			$listaCampos=$this->obtenerListaCampos($row['table_name']);
			for($j=0;$j<count($listaCampos);$j++){
				if($listaCampos[$j]=='geom'){
					$listaGeoTablas[$i]=$row['table_name'];
					$i++;
				}
			}
		}
		$sql="SELECT table_name
			FROM information_schema.views
			WHERE table_schema='public';";
        $recordSet = $this->ejecutarSelect($sql);
		while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) {
			$listaCampos=$this->obtenerListaCampos($row['table_name']);
			for($j=0;$j<count($listaCampos);$j++){
				if($listaCampos[$j]=='geom'){
					$listaGeoTablas[$i]=$row['table_name'];
					$i++;
				}
			}
		}		
        return $listaGeoTablas;
    }
    function obtenerListaCampos($tabla){
		$sql="SELECT column_name
			FROM information_schema.columns
			WHERE table_name = '$tabla';";
        $recordSet = $this->ejecutarSelect($sql);
		$listaCampos = [];
		$i=0;
		while($row = pg_fetch_array($recordSet, NULL, PGSQL_BOTH)) {
			$listaCampos[$i]=$row['column_name'];
			$i++;
		}
        return $listaCampos;
    }  
}
?>
