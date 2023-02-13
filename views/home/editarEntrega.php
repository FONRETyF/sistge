<?php
    session_start();
?>

<div id="editarEntrega" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="" class="formulario" id="edita_Entrega">
                    <div class="modal-body">
                        <div class="modal-header">
                            <h4 id="modal-title" id="mdltitulo"></h4>
                        </div> 
                        <input type="hidden" id="identrega" name="identrega">      
                            <section id="ContenEntregas">
                                <section id="DatsEntrDetalle">
                                    <div class="DatsEntrNuv">
                                        <div id="DvNumEntrNuev">Num. Entrega:
                                            <input type="text" class="form-control" name="numentrega" id="numentrega">
                                        </div>
                                        <div id="DvAnioEntrNuev">Año: 
                                            <input type="text" class="form-control" name="Anioentrega" id="Anioentrega">
                                        </div>
                                    </div>
                                    <div class="DatsEntrNuv">
                                        <div id="DvDescEntrNuv">Descripcion:
                                            <input type="text" class="form-control" id="descentrega" name="descentrega">
                                        </div>
                                    </div>
                                    <div class="fechEntrEdit">Fecha Entrega:
                                        <input type="text" class="form-control" id="fechentrega" name="fechentrega">
                                    </div>
                                    <div class="obsrEntrEdit">Observaciones:
                                        <input type="text" class="form-control" id="observaciones" name="observaciones">
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
