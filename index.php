<?php
session_start();
include 'control/configBdPostgreSQL.php';
include 'modelo/Usuario.php';
include 'modelo/RolUsuario.php';
include 'control/ControlConexionPostgreSQL.php';
include 'control/ControlUsuario.php';
include 'control/ControlRolUsuario.php';
$ema="";
$con="";
$bot="";
$matRolesDelUsuario =[];
//$cadena = pg_escape_string($cadena);
//if(isset($_POST['txtEmail']))$ema=pg_escape_string($_POST['txtEmail']);
if(isset($_POST['txtEmail']))$ema=$_POST['txtEmail'];
if(isset($_POST['txtContrasena']))$con=$_POST['txtContrasena'];
if(isset($_POST['btnLogin']))$bot=$_POST['btnLogin'];
if($bot=='Login'){
    $validar = false;
    $objUsuario = new Usuario($ema, $con);
    $objControlUsuario = new ControlUsuario($objUsuario);
    $validar = $objControlUsuario->validarIngreso();
    if ($validar)
    {
        $objRolUsuario=new RolUsuario($ema,0);
        $objControlRolUsuario= new ControlRolUsuario($objRolUsuario);
        $matRolesDelUsuario = $objControlRolUsuario->consultarRoles_por_EmailUsuario();
        $_SESSION['ema']  = $ema;
        $_SESSION["matRolesDelUsuario"] = $matRolesDelUsuario;
        header('Location: templates/menu.php'); 
    }
    else
    {
        header('Location: index.php');
    }
}
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
            body {
                color: #000;
                overflow-x: hidden;
                height: 100%;
                background-color: #E3F2FD;
                background-repeat: no-repeat;
            }

            .card0 {
                box-shadow: 0px 4px 8px 0px #757575;
                border-radius: 0px;
            }

            .card2 {
                margin: 0px 40px;
            }

            .logo {
                width: 400px;
                height: 400px;
                margin-top: 20px;
                margin-left: 240px;
                margin-right: 100px;
                margin-bottom: 20px;
            }

            .image {
                width: 360px;
                height: 280px;
            }

            .border-line {
                border-right: 1px solid #EEEEEE;
            }

            .facebook {
                background-color: #3b5998;
                color: #fff;
                font-size: 18px;
                padding-top: 5px;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                cursor: pointer;
            }

            .twitter {
                background-color: #1DA1F2;
                color: #fff;
                font-size: 18px;
                padding-top: 5px;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                cursor: pointer;
            }

            .linkedin {
                background-color: #2867B2;
                color: #fff;
                font-size: 18px;
                padding-top: 5px;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                cursor: pointer;
            }

            .line {
                height: 1px;
                width: 45%;
                background-color: #E0E0E0;
                margin-top: 10px;
            }

            .or {
                width: 10%;
                font-weight: bold;
            }

            .text-sm {
                font-size: 14px !important;
            }

            ::placeholder {
                color: #BDBDBD;
                opacity: 1;
                font-weight: 300
            }

            :-ms-input-placeholder {
                color: #BDBDBD;
                font-weight: 300
            }

            :-ms-input-placeholder {
                color: #BDBDBD;
                font-weight: 300
            }

            input, textarea {
                padding: 10px 12px 10px 12px;
                border: 1px solid lightgrey;
                border-radius: 2px;
                margin-bottom: 5px;
                margin-top: 2px;
                width: 100%;
                box-sizing: border-box;
                color: #2C3E50;
                font-size: 14px;
                letter-spacing: 1px;
            }

            input:focus, textarea:focus {
                -moz-box-shadow: none !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                border: 1px solid #304FFE;
                outline-width: 0;
            }

            button:focus {
                -moz-box-shadow: none !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                outline-width: 0;
            }

            a {
                color: inherit;
                cursor: pointer;
            }

            .btn-blue {
                background-color: #1A237E;
                width: 150px;
                color: #fff;
                border-radius: 2px;
            }

            .btn-blue:hover {
                background-color: #000;
                cursor: pointer;
            }

            .bg-blue {
                color: #fff;
                background-color: #1A237E;
            }

            @media screen and (max-width: 991px) {
                .logo {
                    margin-left: 0px;
                }

                .image {
                    width: 300px;
                    height: 220px;
                }

                .border-line {
                    border-right: none;
                }

                .card2 {
                    border-top: 1px solid #EEEEEE !important;
                    margin: 0px 15px;
                }
            }
    </style>
    <script>

    </script>
</head>
<body>

    <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
    <form id="form1" method="post" action="index.php">
            <div class="container-fluid">
                <div class="row d-flex">
                    <div class="col-lg-6">
                        <div class="card1 pb-5">
                            <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
                                <img src="./static/img/Escudo.png" class="logo"/>
                            </div>
                            <!-- <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
                                <img src="./static/img//cinqueterre.jpg" class="image"/>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card2 card border-0 px-4 py-5">
                        <div class="row mb-4 px-3">
                            <h2>Bienvenido al Geovisor Municipal de San Juan de Urabá</h2>
                        </div>
                            <!-- <div class="row mb-4 px-3">
                                <h6 class="mb-0 mr-4 mt-2">Sign in with</h6>
                                <div class="facebook text-center mr-3"><div class="fa fa-facebook"></div></div>
                                <div class="twitter text-center mr-3"><div class="fa fa-twitter"></div></div>
                                <div class="linkedin text-center mr-3"><div class="fa fa-linkedin"></div></div>
                            </div> -->
                            <!-- <div class="row px-3 mb-4">
                                <div class="line"></div>
                                <small class="or text-center">Or</small>
                                <div class="line"></div>
                            </div> -->
                            <div class="row px-3">
                                <label class="mb-1">Email del Usuario</label>
                                <input type="email" id="txtEmail" name="txtEmail" class="mb-4" value="<?php echo $ema ?>">
                            </div>
                            <div class="row px-3">
                                <label class="mb-1">Contraseña</label>
                                <input id="txtContrasena" name="txtContrasena" class="mb-1" type="password" value="<?php echo $con ?>">
                            </div>
                            <!-- <div class="row px-3 mb-4">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input id="chk1" type="checkbox" name="chk" class="custom-control-input"> 
                                    <label for="chk1" class="custom-control-label text-sm">Remember me</label>
                                </div>
                                <a href="#" class="ml-auto mb-0 text-sm">Forgot Password?</a>
                            </div> -->
                            <div class="row mb-3 px-3">
                                <input type="submit" id="btnLogin" name="btnLogin" value="Login" class="btn btn-blue text-center" />
                            </div>
                            <!-- <div class="row mb-4 px-3">
                                <small class="font-weight-bold">Don't have an account? <a class="text-danger ">Register</a></small>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="bg-blue py-4" style="width:100%">
            <div class="row px-3">
                <small class="ml-4 ml-sm-5 mb-2">Derechos Reservados &copy; 2023.</small>
                <div class="social-contact ml-4 ml-sm-auto">
                    <a class="fa fa-facebook mr-4 text-sm" href="https://www.facebook.com/msanjuandeuraba"></a>
                    <a class="fa fa-twitter mr-4 mr-sm-5 text-sm" href="https://twitter.com/msanjuandeuraba"></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

