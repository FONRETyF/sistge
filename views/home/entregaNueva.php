<?php
    require_once("/var/www/html/sistge/views/head/header.php");
    
    if(empty($_SESSION['usuario'])){
        header("Location:login.php");
    }
?>

<section class="contenidoGral">
    <form action="" class="formulario">
        <section id="ContenEntregas">
            <section id="DatsEntrNueva">
                <div class="DatsEntrNuv">
                    <div id="DvNumEntrNuev">Num. Entrega:
                        <input type="text" name="numEntrega" id="numEntrega">
                    </div>
                    <div id="DvAnioEntrNuev">Año: 
                        <select name="AnioEntrNuv" id="AnioEntrNuv">
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>
                    </div>
                </div>
                <div id="DvStatEntrNuv">Estatus: 
                    <div class="radio">
                        <input type="radio" name="Estatus" id="StatActiva">
                        <label for="StatActiva">Activa</label>
                        <input type="radio" name="Estatus" id="StatDesactivada">
                        <label for="StatDesactivada">Descativada</label>
                        <input type="radio" name="Estatus" id="StatCerrada">
                        <label for="StatCerrada">Cerrada</label>
                    </div> 
                </div>
                <div class="DatsEntrNuv">
                    <div id="DvDescEntrNuv">
                        Descripcion:
                        <div>
                            <input type="text" id="NomEntrNuv" name="NomEntrNuv">
                        </div>
                        <div id="BtnAgrega">
                            <button class="BtnAgrega">AGREGAR</button>  
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </form>
</section>

<?php
    require_once("/var/www/html/sistge/views/head/footer.php");
?>