<?php 
  session_start();
  if ($_SESSION["ema"] ==null){
    header('Location: ../index.php');
  }
?>
<?php include "../templates/baseEncabezado_ini_head.html" ?>
<?php include "../templates/baseEncabezado_ini_body.html" ?>
<!--
  https://www.w3schools.com/bootstrap4/tryit.asp?filename=trybs_img_circle&stacked=h
-->
<style>
    .gallery {
      display: flex;
      flex-wrap: wrap;
    }

    .gallery img {
      width: 25%;
      height: auto;
      box-sizing: border-box;
      padding: 5px;
    }
  </style>

<div class="container">         
  <!-- <img src="../static/img/cinqueterre.jpg" class="rounded-circle" alt="San Juann de urabá" width="100%" height="500">      -->
  <div class="gallery">
    <img src="../static/img/imagen1.jpeg" alt="Imagen 1">
    <img src="../static/img/imagen2.jpeg" alt="Imagen 2">
    <img src="../static/img/imagen3.jpeg" alt="Imagen 3">
    <img src="../static/img/imagen4.jpeg" alt="Imagen 4">
    <img src="../static/img/imagen5.jpeg" alt="Imagen 5">
    <img src="../static/img/imagen6.jpeg" alt="Imagen 6">
    <img src="../static/img/imagen7.jpeg" alt="Imagen 7">
    <img src="../static/img/imagen8.jpeg" alt="Imagen 8">
  </div> 
  <div class="container">
    <h2 style="color:#000000; text-align: center;">Bienvenidos al Sistema de Información Geográfico de San Juan de Urabá</h2>
    <br>
    <hr>
    <p style="color:#000000; text-align: center;">En coordinación con la Secretaría de Planeación, Vivienda, Infraestructura y Servicios Públicos se diseña el Geoportal Municipal, 
    administrado por la ingeniera <strong> Delyana Pajaro Morales</strong>, como proyecto de grado para optar al título de  Especialista en Sistemas de Información geográfica - Universidad San Buenaventura - Medellín</p>
    <hr>
  </div>
</div>
<?php include "../templates/basePie.html" ?>
