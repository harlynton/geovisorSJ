<?php
ob_start();
?>
<?php 
  session_start();
  if ($_SESSION["ema"] ==null){
    header('Location: ../index.php');
  }
$permisoParaEntrar =false;
$matRolesDelUsuario = $_SESSION["matRolesDelUsuario"];
for($i=0;$i<count($matRolesDelUsuario); $i++) {
	if ($matRolesDelUsuario[$i][1] == "invitado") $permisoParaEntrar = true;
}
if ($permisoParaEntrar == false)
{
		header('Location: ../index.php');
}
?>
<?php
include '../control/configBdPostgreSQL.php';
include '../control/ControlConexionPostgreSQL.php';
include '../control/ControlGeoJson.php';
include '../control/ControlArchivos.php';
$bt="";
$txtAreaConsulta="";
$cargado="";
$ruta="";
$listacampos=[];
$objControlConexion = new ControlConexionPostgreSQL();
$objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
$listaGeoTablas=$objControlConexion->obtenerListaGeoTablas();

if(!isset($_REQUEST['selTablas']))$_REQUEST['selTablas']=$listaGeoTablas[0];
$listaCampos=$objControlConexion->obtenerListaCampos($_REQUEST['selTablas']);
$objControlConexion->cerrarBd();
$consultaSQLparaSelect="DROP VIEW IF EXISTS vista;
CREATE VIEW vista as SELECT * FROM {$_REQUEST['selTablas']};";

if(isset($_POST['boton']))$bt=$_POST['boton'];
if(isset($_POST['txtAreaConsulta']))$txtAreaConsulta=$_POST['txtAreaConsulta'];
if(isset($_FILES['selecArchivo']))$cargado = $_FILES['selecArchivo']['name'];
if(isset($_FILES['selecArchivo']))$rutatmp = $_FILES['selecArchivo']['tmp_name'];
//if(isset($_FILES['selecArchivo']))$rutaReal = realpath($_FILES["selecArchivo"]["tmp_name"]);

if($bt=="Consultar"){
	$objControlGeoJson = new ControlGeoJson();
	$objControlArchivos = new ControlArchivos();
	$objControlArchivos->borrarUnArchivo("../templates/data/consulta.js");
	$objControlArchivos->crearArchivoBorrarContenido("../templates/data/consulta.js");
	$objControlArchivos->escribirUnaLineaYAlFrente("var consulta=");
	
	$textoGeoJson=$objControlGeoJson->obtenerGeoJson($_REQUEST['selTablas'],'gid');
	
	$objControlArchivos->escribirUnaLineaYDebajo($textoGeoJson);
	$objControlArchivos->cerrarArchivo();

	$consultaSQLparaSelect="DROP VIEW IF EXISTS vista;
	CREATE VIEW vista as SELECT * FROM {$_REQUEST['selTablas']};";

	//header("location:geovisor.php");
	}
if($bt=="Cargararchivo"){
	$objControlGeoJson = new ControlGeoJson();
	$objControlArchivos1 = new ControlArchivos();
	$objControlArchivos2 = new ControlArchivos();
	$objControlArchivos2->borrarUnArchivo("../templates/data/cargado.js");

	move_uploaded_file($_FILES["selecArchivo"]["tmp_name"], "../templates/data/tmp.json");

	$objControlArchivos1->abrirArchivoLecturaEscritura("../templates/data/tmp.json");
	$objControlArchivos2->crearArchivoBorrarContenido("../templates/data/cargado.js");
	

	$linea=$objControlArchivos1->leerUnaLinea();
	$objControlArchivos2->escribirUnaLineaYAlFrente('var cargado=');
	while($linea){
		$objControlArchivos2->escribirUnaLineaYAlFrente($linea);
		$linea=$objControlArchivos1->leerUnaLinea();
	}
	$objControlArchivos1->borrarUnArchivo("../templates/data/tmp.json");
	$objControlArchivos2->cerrarArchivo();

	header("location:geovisor.php");
}
if($bt=="CrearVista"){
	$objControlConexion = new ControlConexionPostgreSQL();
	$objControlConexion->abrirBd($GLOBALS['serv'],$GLOBALS['usua'],$GLOBALS['pass'],$GLOBALS['bdat'],$GLOBALS['port']);
	$objControlConexion->ejecutarComandoSql($txtAreaConsulta);
	$objControlConexion->cerrarBd();
	$objControlGeoJson = new ControlGeoJson();
	$objControlArchivos = new ControlArchivos();
	$objControlArchivos->borrarUnArchivo("../templates/data/vista.js");
	$objControlArchivos->crearArchivoBorrarContenido("../templates/data/vista.js");
	$objControlArchivos->escribirUnaLineaYAlFrente("var vista=");
	$textoGeoJson=$objControlGeoJson->obtenerGeoJson('vista','gid');
	$objControlArchivos->escribirUnaLineaYDebajo($textoGeoJson);
	$objControlArchivos->cerrarArchivo();

	header("location:geovisor.php");
}
?>
<?php include "../templates/baseEncabezado_ini_head.html" ?>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

	<script type="text/javascript" src="data/consulta.js"></script>
	<script type="text/javascript" src="data/cargado.js"></script>
	<script type="text/javascript" src="data/vista.js"></script>

<?php include "../templates/baseEncabezado_ini_body.html" ?>
<form id="frmGeovisor" method="post" action="../templates/geovisor.php" enctype="multipart/form-data">
<div class="container">
  	<!-- <div class="row"> -->
		<!-- <div class="col-sm-1" style="text-align: left;">
			<a href="http://www.usbmed.edu.co">Universidad de San Buenaventura-Medellín</a>
		</div> -->
		<!-- <div class="col-sm-10"> -->
		    <div id='map' style="width: 100%; height: 450px;"></div>
		<!-- </div> -->
		<!-- <div class="col-sm-1" style="text-align: right;">
		</div> -->
	<!-- </div> -->
</div>
<div class="container">
  	<div class="row">
		<div class="col-sm-1" >
					
		</div>
		<div class="col-sm-5">
			<div class="form-group">
				<input type="file" id="selecArchivo" value="selecArchivo" name="selecArchivo" accept=".json,.geojson">
			</div>
		</div>
		<div class="col-sm-5">
			<div class="form-group">
				<input type="submit" value="Cargararchivo" name="boton">
			</div>
		</div>
		<div class="col-sm-1"  >

		</div>
	</div>
</div>
<div class="container">
	<div class="row">
	  <div class="col-sm-1" >

	  </div>
	  <div class="col-sm-5">
		  <div class="form-group">
			  <select class="form-control" id="selTablas" name="selTablas">
				  <option>Seleccione una Capa</option>
			  <?php for($i=0;$i<count($listaGeoTablas);$i++){ ?>
					<option><?php echo $listaGeoTablas[$i]; ?></option>
			  <?php } ?>
			  </select>
			</div>
	  </div>
	  <div class="col-sm-4">
		  <div class="form-group">
			  <select class="form-control" id="selCampos" name="selCampos">
				  <option>Seleccione un Campo de la Capa</option>
			  <?php for($i=0;$i<count($listaCampos);$i++){ ?>
					<option><?php echo $listaCampos[$i]; ?></option>
			  <?php } ?>
			  </select>
			</div>	
	  </div>
	  <div class="col-sm-1"  >
		<input type="submit"  name ="boton" value="Consultar"/>
	  </div>
	  <div class="col-sm-1"  >

	  </div>
  </div>
</div>
<div class="container">
	<div class="row">
	  <div class="col-sm-1" >
	  </div>
	  <div class="col-sm-9">
		  <textarea  id="txtAreaConsulta" name="txtAreaConsulta" rows="5" style="width: 100%;text-align: left;">
			<?php echo $consultaSQLparaSelect ?>
		  </textarea>
	  </div>
	  <div class="col-sm-1"  >
		<input type="submit"  name ="boton" value="CrearVista"/>
	  </div>
	  <div class="col-sm-1"  >

	  </div>
  </div>
</div>
</form>
<br>
<script>
	L.Icon.Default.imagePath = 'circulo-negro.png';

	var myIcon = L.icon({
		iconUrl: 'circulo-negro.png',
		iconSize: [5, 5],
		popupAnchor: [0, -5]
	});

	L.Marker.prototype.options.icon = myIcon;

	function popUpInfoConsulta(feature, layer) {
		if (feature.properties) {
			var properties = [];
			for (var property in feature.properties) {
				properties.push(property + ': ' + feature.properties[property]);
			}
			layer.bindPopup(properties.join('<br>'));
		}
	}
	 
	function popUpInfoCargado(feature, layer) {
		if (feature.properties) {
			var properties = [];
			for (var property in feature.properties) {
				properties.push(property + ': ' + feature.properties[property]);
			}
			layer.bindPopup(properties.join('<br>'));
		}
	}
	function popUpInfoVista(feature, layer) {
		if (feature.properties) {
			var properties = [];
			for (var property in feature.properties) {
				properties.push(property + ': ' + feature.properties[property]);
			}
			layer.bindPopup(properties.join('<br>'));
		}
	}
	var marcadores = L.layerGroup();

	var myIcon = L.icon({
		iconUrl: 'ubicacion1.jpg',
		iconSize: [20, 10],
	});

	L.marker([8.762442,-76.5286442],
		{
			icon:myIcon
		}
	).bindPopup('Alcaldía San Juan de Urabá-Antioquia').addTo(marcadores);

	var capaOSM = new L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png');
	var capaRelieve = new L.tileLayer('https://stamen-tiles.a.ssl.fastly.net/terrain/{z}/{x}/{y}.png');

	var map = L.map('map', {
		center: [8.7612927,-76.5306177],
		zoom: 11,
		layers: [capaOSM, marcadores]
	});

	var baseLayers = {
		"capaOSM" : capaOSM,
		"capaRelieve" : capaRelieve
	};

	var geolayerConsulta=L.geoJson(consulta,{
		onEachFeature: popUpInfoConsulta
		});
	var geolayerCargado=L.geoJson(cargado,{
		onEachFeature: popUpInfoCargado
		});
	var geolayerVista=L.geoJson(vista,{
		onEachFeature: popUpInfoVista
		});
	var overlays = {
		"consulta"	: geolayerConsulta,
		"cargado"	: geolayerCargado,
		"vista"		: geolayerVista
	};

	L.control.layers(baseLayers, overlays).addTo(map);

	// Estilos CSS para la ventana estática
	var ventanaEstilo = 'position:absolute; background-color:white; width:300px; height:200px; border-radius:10px; overflow:auto; bottom:10px; left:10px;';

	// Crear la ventana estática como una capa de superposición personalizada
	var ventanaEstatica = L.control({ position: 'bottomleft' });
	ventanaEstatica.onAdd = function(map) {
	this._div = L.DomUtil.create('div', 'ventana-estatica');
	this._div.innerHTML = 'Contenido de la ventana estática';
	this._div.style = ventanaEstilo;
	return this._div;
	};
	ventanaEstatica.addTo(map);

</script>
<?php
ob_end_flush();
?>