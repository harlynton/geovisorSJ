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

$arregloUsuarios=array();
$objControlUsuario = new ControlUsuario(null);
$arregloUsuarios = $objControlUsuario->listar();

$arregloRoles=array();
$objControlRol = new ControlRol(null);
$arregloRoles = $objControlRol->listar();

$ListBox1="";
$bt="";
$ema="";
$con="";

if(!empty($_POST['txtEmail'])){
	$ema=$_POST['txtEmail'];
	$objRolUsuario = new RolUsuario($ema, 0);
	$objControlRolUsuario = new ControlRolUsuario($objRolUsuario);
	$matRolesDelUsuario = $objControlRolUsuario->consultarRoles_por_EmailUsuario();
}

if(isset($_POST['bt']))$bt=$_POST['bt'];
if(isset($_POST['txtContrasena']))$con=$_POST['txtContrasena'];
if(isset($_POST['ListBox1']))$ListBox1=$_POST['ListBox1'];
switch ($bt) {
    case "Guardar":
        try
            {
			$objUsuario= new Usuario($ema,$con);
			$objControlUsuario= new ControlUsuario($objUsuario);
			$objControlUsuario->guardar();
			$arregloRolUsuario=array();
				
			if($ListBox1!=""){
				for ($i = 0; $i < count($ListBox1); $i++)
				{
					$id = explode(";", $ListBox1[$i])[0];
					//explode($separador, $cadena); //devuelve un arreglo
					$objRolUsuario = new RolUsuario($ema, $id);
					$arregloRolUsuario[$i] = $objRolUsuario;
					$objControlRolUsuario = new ControlRolUsuario($objRolUsuario);
					$objControlRolUsuario->guardar(); //Guarda los datos de las claves foráneas en la tabla intermedia tblrol_usuario
				}
			}
			else{
				$id=1;
				$objRolUsuario = new RolUsuario($ema, $id);
				$arregloRolUsuario[$i] = $objRolUsuario;
				$objControlRolUsuario = new ControlRolUsuario($objRolUsuario);
				$objControlRolUsuario->guardar();
			}
		}
		catch (Exception $objException)
		{
			$msg = $objExcetion->getMessage();
		}			
			header('Location: ../templates/vistaUsuarios.php');

        break;
    case "Consultar":
		try
		{
			$objUsuario= new Usuario($ema,"");
			$objControlUsuario= new ControlUsuario($objUsuario);
			$objUsuario=$objControlUsuario->consultar();
			$con=$objUsuario->getContrasena();
			$objRolUsuario = new RolUsuario($ema, 0);
			$objControlRolUsuario = new ControlRolUsuario($objRolUsuario);
			$matRolesDelUsuario = $objControlRolUsuario->consultarRoles_por_EmailUsuario();
			if($matRolesDelUsuario==null){$matRolesDelUsuario[0][0]="1";$matRolesDelUsuario[0][1]="invitado";}
		}
		catch (Exception $objException)
		{
			$mensaje = $objExcetion->getMessage();;
		}			
        
        break;
    case "Modificar":
        try
            {
			//nota: Para esto debería hacerse en un procedimiento almacenado con manejo de transacciones
			//1. modifica en la tabla Usuario
			$objUsuario= new Usuario($ema,$con);
			$objControlUsuario= new ControlUsuario($objUsuario);
			$objControlUsuario->modificar();

			//2. borra los registros asociados del usuario en la tabla intermedia
			$objRolUsuario = new RolUsuario($ema, 0);
			$objControlRolUsuario = new ControlRolUsuario($objRolUsuario);
			$objControlRolUsuario->borrarTodosEmailUsuario();

			//3. guarda de nuevo en la tabla intermedia
			$arregloRolUsuario=array();
			if($ListBox1 !=""){
				for ($i = 0; $i < count($ListBox1); $i++)
				{
					$id = explode(";", $ListBox1[$i])[0];
					$objRolUsuario = new RolUsuario($ema, $id);
					$arregloRolUsuario[$i] = $objRolUsuario;
					$objControlRolUsuario = new ControlRolUsuario($objRolUsuario);
					$objControlRolUsuario->guardar(); //Guarda los datos de las claves foráneas en la tabla intermedia tblrol_usuario
				}
			}
			else{
				$objRolUsuario = new RolUsuario($ema, 1);
				$arregloRolUsuario[$i] = $objRolUsuario;
				$objControlRolUsuario = new ControlRolUsuario($objRolUsuario);
				$objControlRolUsuario->guardar(); //Guarda los datos de las claves foráneas en la tabla intermedia tblrol_usuario
			}

		}
		catch (Exception $objException)
		{
			$msg = $objExcetion->getMessage();
		}			
			header('Location: ../templates/vistaUsuarios.php');

        break;
    case "Borrar":
        $objUsuario= new Usuario($ema,"");
        $objControlUsuario= new ControlUsuario($objUsuario);
		//en este caso considero que está bien aplicar ON DELETE CASCADE 
		//al contraint de clave foránea con_fkEmail de la tabla rol_usuario
        $objControlUsuario->borrar();
        header('Location: ../templates/vistaUsuarios.php');
        break;  
    case "Listar":
        $objUsuario= new Usuario("","");
        $objControlUsuario= new ControlUsuario($objUsuario);
        $arregloUsuarios=$objControlUsuario->listar();
        header('Location: ../templates/vistaUsuarios.php');
        break;            
} 

?>
    <form id="form1" method="post" action="../templates/vistaUsuarios.php"> <!-- novalidate-->
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
						<a href="#modalUsuarios" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Gestión Usuarios</span></a>
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
						<th>Nombre Usuario</th>
						<th>Contraseña</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php
                       $msg = "ok";
                        try{
                        
                            for ($i = 0; $i < count($arregloUsuarios); $i++)
                            {
					?>
					<tr>
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" id="checkbox1" name="options[]" value="1">
								<label for="checkbox1"></label>
							</span>
						</td>
						<td><?php echo $arregloUsuarios[$i]->getEmail(); ?></td>
						<td><?php echo $arregloUsuarios[$i]->getContrasena(); ?></td>
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
<div id="modalUsuarios" class="modal fade">
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
							<button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Usuarios</button>
							<button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Roles por Usuario</button>
						  </div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
						  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
								<div class="form-group">
									<label>Nombre Usuario</label>
									<input type="text" id="txtEmail" name="txtEmail" class="form-control" required value="<?php echo $ema; ?>">

								</div>
								<div class="form-group">
									<label>Contraseña</label>
									<input type="text" id="txtContrasena" name="txtContrasena" class="form-control" value="<?php echo $con; ?>">
								</div>
								<div class="form-group">
									<button id="btnGuardar" name="bt" value="Guardar" class="btn btn-success">Guardar</button>
									<input type="submit" id="btnConsultar" name="bt" value="Consultar" class="btn btn-success"/>
									<input type="submit" id="btnModificar" name="bt" value="Modificar" class="btn btn-danger"/>
									<input type="submit" id="btnBorrar" name="bt" value="Borrar" class="btn btn-danger"/>
								</div>	
						  </div>
						  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
								<div class="form-group">
									<select name="combo1[]" id="combo1" class="form-control">
										<?php for($i=0;$i<count($arregloRoles);$i++){ ?>
											<option>
												<?php echo $arregloRoles[$i]->getId().";".$arregloRoles[$i]->getNombre(); ?>
											</option>
										<?php } ?>	
									</select>															
								</div>     
								<div class="form-group">
									<select name="ListBox1[]" id="ListBox1" class="form-control" size="4" multiple="multiple" >
										<?php for($i=0;$i<count($matRolesDelUsuario);$i++){ ?>
											<option>
												<?php echo $matRolesDelUsuario[$i][0].";".$matRolesDelUsuario[$i][1]; ?>
											</option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">									
									<button id="btnAgregarRol" name="bt" value="Agregar Rol" class="btn btn-success" onclick="agregarItem('combo1','ListBox1')">Agregar Rol</button>
									<input type="submit" id="btnBorrarRol" name="bt" value="Borrar Rol" class="btn btn-success" onclick="removerItem('ListBox1')"/>
								</div>
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