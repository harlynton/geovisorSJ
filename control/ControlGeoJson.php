<?php
class ControlGeoJson{
    var $msg;
    function __construct(){
        $this->msg="ok";
    }
    function obtenerGeoJson($geoTabla,$campo){
        $textoGeoJson="";
        $comandoSql ="SELECT row_to_json(fc) 
                        FROM (SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features 
                        FROM (SELECT 'Feature' As type, ST_AsGeoJSON(lg.geom)::json As geometry, row_to_json(lp) As properties 
                        FROM public.{$geoTabla} As lg INNER JOIN 
                        (SELECT * FROM public.{$geoTabla}) As lp ON lg.{$campo} = lp.{$campo}) As f ) As fc;";
        $objControlConexion = new ControlConexionPostgreSQL();
        $objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
        $cursor=$objControlConexion->ejecutarSelect($comandoSql);
        if ($registro= pg_fetch_row($cursor)) {
            $textoGeoJson=$registro[0];
        }
        $objControlConexion->cerrarBd();
        return $textoGeoJson;
    }        
}
?>