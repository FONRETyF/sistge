<?php  

    require_once("/var/www/html/sistge/controller/homeController.php");
    session_start();
    $obj = new homeController();
    $usuario = $obj->limpiarusuario($_POST['usuario']);
    $contraseña = $obj->limpiarcadena($_POST['contraseña']);
    $bandera = $obj->verificarusuario($usuario,$contraseña);
    $rolUsuario = $obj->verificaRol($usuario);   

    if($bandera){
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $rolUsuario[0]['idrol'];
        header('Location:Inicio.php');        
    }else{
        $error = "<li>Las claves son incorrectas</li>";
        header("Location:login.php?error=".$error);
    }
    
?>