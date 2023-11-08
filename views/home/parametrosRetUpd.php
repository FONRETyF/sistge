<?php
    session_start();
    require_once("/var/www/html/sistge/views/head/header.php");
    /*if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }*/
?>

<section class="contenidoGral">
    <form class="FormcontenidoGral" action="" method="POST" name="" id="form_paramAct">
        <section class="sectNavegador">
            <div class="DivBotnsNav">
                <div id="DivBtnatras">
                    <button type="button" class="BtnsNav Btnregresar"  id="Btnregresar" name="Btnregresar">ATRAS</></button>
                </div>
                <div id="DivBtnInicio">
                    <button type="button" class="BtnsNav" id="Btnnicio" name="Btnnicio">INICIO</></button>
                </div>
                <div id="DivFechaActual">
                    <?php date_default_timezone_set('America/Mexico_City'); echo("Toluca, México a  " .date("d-m-y"));?>
                </div>
            </div>
        </section>
        <div>
            <div id="titParamRetAct">PARAMETROS PARA EL RETIRO DE MAESTROS ACTIVOS</div>
            <div id="contParamRetAct">
                <div id="datsParamRetAct">
                    <div id="DicEstatParamRet">Estatus: &nbsp
                        <input type="checkbox" id="checkStatParam" data-toggle="toggle" data-onstyle="outline-success" data-offstyle="outline-danger" >
                    </div>
                    <div>SUELDOS BASE</div>
                    <div id="DivsueldosBase"> 
                        <div class="datsSueldosBase" id="DivsueldosBaseSp">Supervisor: &nbsp
                            <input type="text" class="sueldosBase" id="sueldBaseSuperv" name="sueldBaseSuperv" placeholder="$000.00" >
                        </div>
                        <div class="datsSueldosBase" id="DivsueldosBasePt">Profr. Titulado:&nbsp
                            <input type="text" class="sueldosBase" id="sueldBaseProfrTit" name="sueldBaseProfrTit" placeholder="$000.00" ></br>
                            <div><button type="submit" id="BttnCalcProm" name="BttnCalcProm">...</button></div>
                        </div>
                    </div>
                    <div id="DivpromSueldos">Promedio: &nbsp
                        <input type="text" class="sueldosBase" id="promSueldos" name="promSueldos" disabled>
                    </div>
                    <div id="datsAdicinls">
                        <div id="DivaniosLey">Años ley:
                             <input type="text" id="aniosLey" name="aniosLey" value="30" disabled>
                        </div>
                    </div>
                    <div id="DivMontRetAn">Monto de retiro anual:
                        <input type="button" id="montRetAn" name="montRetAn" value="$0.00" disabled>
                    </div>
                    <div id="entregasApli">
                        <div class="DivEntrsApli" id="DivEntrApliIni">Entrega inicial de aplicacion:
                            <input type="text" class="EntrsApliIni" id="NumEntApliIni" name="NumEntApliIni" placeholder="Num" minlength="2" maxlength="2">
                            <input type="text" class="EntrsApliFin" id="AnioEntApliIni" name="AnioEntApliIni" placeholder="Año" minlength="4" maxlength="4">
                        </div>
                        <div class="DivEntrsApli" id="DivEntrApliFin">Entrega final de aplicacion:
                            <input type="text" class="EntrsApliIni" id="NumEntApliFin" name="NumEntApliFin" placeholder="Num" minlength="2" maxlength="2">
                            <input type="text" class="EntrsApliFin" id="AnioEntApliFin" name="AnioEntApliFin" placeholder="Año" minlength="4" maxlength="4">
                        </div>
                    </div>
                    <div id="divAgregaParam">
                        <button id="updtParam" name="updtParam">GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

<script type="text/javascript" src="../../asset/js/parametrosUpd.js"></script>

<?php require_once("/var/www/html/sistge/views/head/footer.php"); ?>
