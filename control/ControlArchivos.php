<?php
//https://www.php.net/manual/es/function.fopen.php
class ControlArchivos {
var $gestor;
    function __construct(){
        
    }
    function crearArchivoBorrarContenido($rutaYNombre){
        try{
            $this->gestor = fopen($rutaYNombre, "w+");
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    } 
    function abrirArchivoLecturaEscritura($rutaYNombre){
        try{
            $this->gestor = fopen($rutaYNombre, "r+");
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    }
    function cerrarArchivo(){
        try{
            fclose($this->gestor);
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    }
    function leerUnaLinea(){
        $lineaTexto=null;
        try{
            $lineaTexto=fgets($this->gestor);
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
        return $lineaTexto;
    }

     function escribirUnaLineaYAlFrente($lineaTexto){
        try{
        fwrite($this->gestor,$lineaTexto);
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    }
     function escribirUnaLineaYDebajo($lineaTexto){
        try{ 
        fwrite($this->gestor,$lineaTexto."\n");
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    }
     function crearCarpeta($ruta){
        try{
        mkdir($ruta);
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    }
    
    function borrarUnArchivo($rutaYNombre){
        try{
            unlink($rutaYNombre);
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    }
    
    function renombrarUnArchivo($rutaYNombreViejo,$rutaYNombreNuevo){
        try{
            rename($rutaYNombreViejo,$rutaYNombreNuevo);
        }
        catch (Exception $e){
            echo $e->getMessage()."\n";
        }
    }
}
?>