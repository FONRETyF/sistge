<?php 
  session_start();
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>   SMSEM - FONRETyF   </title>
    <link rel="shortcut icon" href="./../../img/smsem_icono.png">
    <link rel="stylesheet" type="text/css" href="./../../css/Estilos.css">
    <link rel="stylesheet" type="text/css" href="./../../css/Estilos_Inic.css">
    <link rel="stylesheet" type="text/css" href="./../../css/Estilos.scss">
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://kit.fontawesome.com/fae5672c64.js" crossorigin="anonymous"></script>
    
    <script src="../../libs/datatables/jquery-3.6.0.js"></script>
    <script src="../../libs/datatables/jquery-3.6.0.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>  

    <link href="../../libs/datatables/jquery.dataTables.css" rel="stylesheet">
    <link href="../../libs/datatables/responsive.dataTables.min.css" rel="stylesheet"/>
    <link href="../../libs/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="../../libs/datatables/select2.min.css" rel="stylesheet">

  </head>
  <body>
    
<?php 
  if ($_SESSION['rol'] == 2 || $_SESSION['rol'] == 1) {
    include("../../views/encabezadoSistAd.php");
  } elseif ($_SESSION['rol'] == 3) {
    include("../../views/encabezadoSist.php");
  } 
?>

