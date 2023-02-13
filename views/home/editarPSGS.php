<?php
    session_start();
?>

<div id="editarPSGS" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="contenidoModal">
            <form method="post" action="" class="formulario" id="edita_PSGS">
                <div class="modal-body">
                    <div class="modal-header" id="modalHeader">
                        <h4 id="tituto_mod_psgs" id="mdltitulo"></h4>
                    </div>
                    <input type="hidden" id="fechBase" name="fechBase">
                    
                    <section id="ContenPSGS">
                        <section id="DatsPSGS">
                            <div id="DatPSGSEdit">
                                <div id="DivNumPSGS">Num. permisos: &nbsp<input type="text" id="numsPSGS" name="numsPSGS"><button id="addPSGS" onclick="" value="agregar"><img src="../../img/add-psgs.png" alt="Agregar PSGS" height="25" width="25"></button></div> 
                                <div id="DivFechsPSGS">
                                    <div id="DivFechsPSGSIni">Inicio:
                                        <div><input type="date" name="fechaIni[]"></div>
                                    </div>
                                    <div id="DivFechsPSGSFin">Fin:
                                        <div><input type="date" name="fechaFin[]"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </section>
                    <div id="modal-footer">
                        <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" name="action" id="#" value="add" class="btn btn-rounded btn-primary">Guardar</button>  
                    </div> 
                </div> 
            </form>
        </div>
    </div>
</div>