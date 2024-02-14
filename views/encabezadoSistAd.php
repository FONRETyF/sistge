<?php
    session_start();
?>

<header>
    <div class="escudo_Ini">
        <img class="imagen1" src="../../img/escudosmsem.png" width="55" height="75">
        <img class="imagen2" src="../../img/planilla2021-2024.png" width="60" height="40">
    </div>
    <div class="texto_Ini">
        <div class="texfonretif_Ini">SMSEM - FONDO DE RETIRO Y FALLECIMIENTO</div>
        <div class="texSISTGE">SISTGE - FONRETyF</div>
    </div>
</header>

<div class="fondo_menu">
    <div class="container-fluid">
        <nav class="navbar fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">MENU</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle primermenu" aria-current="page" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="primermenu">RETIROS</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="entregas.php">ENTREGAS</a></li>
                                    <li><a class="dropdown-item" href="consultRetiros.php">CONSULTAS</a></li>
                                    <li><a class="dropdown-item" href="pendientes.php">PENDIENTES</a></li>
                                    <li><a class="dropdown-item" href="tramsProrrgs.php">PRORROGAS</a></li>
                                    <li><a class="dropdown-item" href="cancelCheqs.php">CANCELACIONES</a></li>
                                    <li><a class="dropdown-item" href="reposiciones.php">REPOSICIONES</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle primermenu" aria-current="page" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="primermenu">JUBILADOS</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="consultaEdosCtaMut.php">CONSULTAS</a></li>
                                    <li><a class="dropdown-item" href="emisionesMut.php">MUTUALIDAD</a></li>
                                    <li><a class="dropdown-item" href="emisionesFF.php">FONDO FALLECIMIENTO</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle primermenu" aria-current="page" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="primermenu">PROCESOS</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="parametros.php" >PARAMETROS RET</a></li>
                                    <li><a class="dropdown-item" href="#">NOMINAS ISSEMYM</a></li>
                                    <li><a class="dropdown-item" href="altMaestro.php">ALTA DE MAESTROS</a></li>
                                </ul>
                                <li><a class="dropdown-item" href="logout.php">CERRAR SESION</a></li>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            
            <!--<div class="btn-group btnDropRets">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">RETIROS</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="entregas.php">ENTREGAS</a></li>
                    <li><a class="dropdown-item" href="consultRetiros.php">CONSULTAS</a></li>
                    <li><a class="dropdown-item" href="pendientes.php">PENDIENTES</a></li>
                    <li><a class="dropdown-item" href="tramsProrrgs.php">PRORROGAS</a></li>
                    <li><a class="dropdown-item" href="cancelCheqs.php">CANCELACIONES</a></li>
                    <li><a class="dropdown-item" href="reposiciones.php">REPOSICIONES</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                </ul>
            </div>
            <div class="btn-group btnDropJubs">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">JUBILADOS</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="consultaEdosCtaMut.php">CONSULTAS</a></li>
                    <li><a class="dropdown-item" href="emisionesMut.php">MUTUALIDAD</a></li>
                    <li><a class="dropdown-item" href="emisionesFF.php">FONDO FALLECIMIENTO</a></li>                    
                </ul>
            </div>
            <div class="btn-group btnDropProcs">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">PROCESOS</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="parametros.php" >PARAMETROS RET</a></li>
                    <li><a class="dropdown-item" href="#">NOMINAS ISSEMYM</a></li>
                    <li><a class="dropdown-item" href="altMaestro.php">ALTA DE MAESTROS</a></li>
                </ul>
            </div>-->
           

        </nav>
    </div>
</div>
