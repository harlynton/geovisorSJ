<?php
ob_start();
?>
<?php 
  session_start();
  if ($_SESSION["ema"] ==null){
    header('Location: ../index.php');
  }
?>
<?php include "../templates/baseEncabezado_ini_head.html" ?>
<?php include "../templates/baseEncabezado_ini_body.html" ?>
<?php
$permisoParaEntrar =false;
$matRolesDelUsuario = $_SESSION["matRolesDelUsuario"];
for($i=0;$i<count($matRolesDelUsuario); $i++) {
	if ($matRolesDelUsuario[$i][1] == "admin") $permisoParaEntrar = true;
}
if ($permisoParaEntrar == false)
{
		header('Location: ../index.php');
}


include '../modelo/Usuario.php';
include '../modelo/Rol.php';
include '../modelo/RolUsuario.php';
include '../control/configBdPostgreSQL.php';
include '../control/ControlConexionPostgreSQL.php';
include '../control/ControlUsuario.php';
include '../control/ControlRol.php';
include '../control/ControlRolUsuario.php';

$arregloRoles=array();
$objControlRol = new ControlRol(null);
$arregloRoles = $objControlRol->listar();

$bt="";
$id="0";
$nombre="";

if(isset($_POST['bt']))$bt=$_POST['bt'];
if(isset($_POST['txtId']))$id=$_POST['txtId'];
if(isset($_POST['txtNombre']))$nombre=$_POST['txtNombre'];
switch ($bt) {
    case "Guardar":
        try
        {
			$objRol= new Rol(0,$nombre);
			$objControlRol= new ControlRol($objRol);
			$objControlRol->guardar();
			header('Location: ../templates/vistaRoles.php');
		}
		catch (Exception $objException)
		{
			$msg = $objExcetion->getMessage();
		}			
		
        break;
    case "Consultar":
		try
		{
			$objRol= new Rol($id,"");
			$objControlRol= new ControlRol($objRol);
			$objRol=$objControlRol->consultar();
			$nombre=$objRol->getNombre();
		}
		catch (Exception $objException)
		{
			$mensaje = $objExcetion->getMessage();;
		}			      
        break;
    case "Modificar":
		try
		{
			$objRol= new Rol($id,$nombre);
			$objControlRol= new ControlRol($objRol);
			$objControlRol->modificar();
			header('Location: ../templates/vistaRoles.php');
		}
		catch (Exception $objException)
		{
			$msg = $objExcetion->getMessage();
		}			
		break;
    case "Borrar":
		try
		{
			$objRol= new Rol($id,"");
			$objControlRol= new ControlRol($objRol);
			$objControlRol->borrar();
			header('Location: ../templates/vistaRoles.php');
		}
		catch (Exception $objException)
		{
			$msg = $objExcetion->getMessage();
		}			
		break;
    case "Listar":
		try
		{
			$objRol= new Rol("","");
			$objControlRol= new ControlRol($objRol);
			$arregloRoles = $objControlRol->listar();
		}
		catch (Exception $objException)
		{
			$mensaje = $objExcetion->getMessage();;
		}			      
		break;           
} 

?>
<form id="form1" method="post" action="../templates/vistaRoles.php"> <!-- novalidate-->
	<div>

<div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2> <b>Usuarios</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#modalRoles" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Gestión Roles</span></a>
						<a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Borrar</span></a>						
					</div>
				</div>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
						</th>
						<th>id Rol</th>
						<th>Nombre</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php
                       $msg = "ok";
                        try{
                        
                            for ($i = 0; $i < count($arregloRoles); $i++)
                            {
					?>
					<tr>
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" id="checkbox1" name="options[]" value="1">
								<label for="checkbox1"></label>
							</span>
						</td>
						<td><?php echo $arregloRoles[$i]->getId(); ?></td>
						<td><?php echo $arregloRoles[$i]->getNombre(); ?></td>
						<td>
							<a href="#modalaAgregarRoles" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Agregar Roles">&#xE254;</i></a>					
							<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
						</td>
					</tr>
					<?php
					}
                     }
                    catch (Exception $objException)
                    {
                            echo "ERROR ".$objException->getMessage()."\n";
                    }
                     ?>
				</tbody>
			</table>
			<div class="clearfix">
				<div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
				<ul class="pagination">
					<li class="page-item disabled"><a href="#">Previous</a></li>
					<li class="page-item"><a href="#" class="page-link">1</a></li>
					<li class="page-item"><a href="#" class="page-link">2</a></li>
					<li class="page-item active"><a href="#" class="page-link">3</a></li>
					<li class="page-item"><a href="#" class="page-link">4</a></li>
					<li class="page-item"><a href="#" class="page-link">5</a></li>
					<li class="page-item"><a href="#" class="page-link">Next</a></li>
				</ul>
			</div>
		</div>
	</div>        
</div>

<!-- Crud Modal HTML -->
<div id="modalRoles" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">						
					<h4 class="modal-title">Usuarios</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<div class="form-group">
						<nav>
						  <div class="nav nav-tabs" id="nav-tab" role="tablist">
							<button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Rol</button>
						  </div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
						  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
								<div class="form-group">
									<label>id</label>
									<input type="text" id="txtId" name="txtId" class="form-control" required value="<?php echo $id; ?>">

								</div>
								<div class="form-group">
									<label>Contraseña</label>
									<input type="text" id="txtContrasena" name="txtNombre" class="form-control" value="<?php echo $nombre; ?>">
								</div>
								<div class="form-group">
									<button id="btnGuardar" name="bt" value="Guardar" class="btn btn-success">Guardar</button>
									<input type="submit" id="btnConsultar" name="bt" value="Consultar" class="btn btn-success"/>
									<input type="submit" id="btnModificar" name="bt" value="Modificar" class="btn btn-danger"/>
									<input type="submit" id="btnBorrar" name="bt" value="Borrar" class="btn btn-danger"/>
								</div>	
						  </div>
					</div>	
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>

					<input type="hidden" name="HiddenField1" id="HiddenField1" value="dato">

				</div>

		</div>
	</div>
</div>


    </div>
</form>
<?php
ob_end_flush();
?>