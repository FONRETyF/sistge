

<section class="contenidoGral">        
    <section id="SecCapturaTram">
        <section id="SecDatPerson">
            <div class="DatosCaptura">
                <p>D A T O S &nbsp &nbsp P E R S O N A L E S</p>                  
                <div id="DivCveServPub">
                    <div>C.S.P: &nbsp<input type="text" id="cspMaeBusq" name="cspMaeBusq" placeholder="999999999"></div>
                    <div>&nbsp &nbsp &nbsp Clave de ISSEMyM: &nbsp<input type="text" id="cveIMaeBusq" name="cveIMaeBusq" placeholder="000000"></div>
                    <div id="EstatLaboral">Estatus: &nbsp<input type="text" id="estLaboral" name="estLaboral" placeholder="Estatus laboral"></div>
                </div>  
                <div id="DivNomMae">Nombre del maestro: 
                    <div id="DivDatsNomMae">
                        <input type="text" class="DatsNomMae" id="apePatMae" name="apePatMae">
                        <input type="text" class="DatsNomMae" id="apeMatMae" name="apeMatMae">
                        <input type="text" class="DatsNomMae" id="nombreMae" name="nombreMae">
                        <a id="EditaNombre" href="#"><img src="../../img/lapiz.png" alt="Editar nombre" title="Editar nombre" height="20" width="20"></a>
                    </div>    
                    <div id="textnommae">
                        <div class="nomMaestro" id="TextApePat">Apellido Paterno</div>
                        <div class="nomMaestro" id="TextApeMat">Apellido Materno</div>
                        <div class="nomMaestro" id="TextNom">Nombre (s)</div>
                    </div>
                    <div>
                        <input type="text" class="nomComplMae" id="nomComplMae" name="nomComplMae">
                    </div> 
                    <div id="curpRfcMae">
                        <div class="curpRfcMae">CURP: <input type="text" id="CURPMae" name="CURPMae" placeholder="000000000000000000"></div>
                        <div class="curpRfcMae">RFC: <input type="text" id="RFCMae" name="RFCMae" placeholder="0000000000000"></div>
                        <div class="curpRfcMae">Region: <input type="text" id="RegionMae" name="RegionMae" placeholder=""></div>
                    </div>   
                </div>
            </div>
        </section>
        <section id="SecDatTramite">
            <div class="DatosCapTram">
                <p>D A T O S &nbsp D E L &nbsp T R A M I T E &nbsp D E &nbsp R E T I R O</p>
                <div id="DivDatSolic">
                    <div class="divNomSolic">Solicitante: <input type="text" id="nomSolic" name="nomSolic"></div>
                    <div class="divFechSolic">Fecha de recibido: <input type="date" id="fechBaseMae" name="fechBaseMae" required pattern="\d{4}-\d{2}-\d{2}"></div>
                </div>
                <div id="DivDictBasePsgs">
                    <div id="DivDictamen"><p>DICTAMEN</p> 
                        <div >Fecha: <input type="date" id="fechDictamen" name="fechDictamen" placeholder="AAAA-MM-DD"></div></br>
                        <div>Folio: &nbsp&nbspCP<input type="text" id="folioDictamen" name="folioDictamen" placeholder="00000/00"></div>
                    </div>
                    <div id="DivBaseBaja"><p>BASE Y BAJA</p> 
                        <div>Base: <input type="date" id="fechBaseMae" name="fechBaseMae" placeholder="AAAA-MM-DD" required pattern="\d{4}-\d{2}-\d{2}"></div></br>   
                        <div>Baja: <input type="date" id="fechBaseMae" name="fechBaseMae" placeholder="AAAA-MM-DD" required pattern="\d{4}-\d{2}-\d{2}"></div>
                    </div>
                    <div id="DivPsgs"><p>P.S.G.S:</p> 
                        <div>Agregar: <a id="EditaNombre" href="#"><img src="../../img/editar.png" alt="Editar nombre" title="Editar nombre" height="25" width="25"></a> </div>
                        <div id="divPeriLaboral">
                            <div class="DatsPeriodLab"><input type="text" id="numPsgs" name="numPsgs"></div>
                            <div class="DatsPeriodLab"><input type="text" id="tiempoPsgs" name="tiempoPsgs"></div>
                            <div class="DatsPeriodLab"><input type="text" id="aniosServMae" name="aniosServMae" ></div>
                        </div>
                        <div id="textPrioLaboral">
                            <div class="textPeriodLab">num psgs</div>
                            <div class="textPeriodLab">dias / años</div>
                            <div class="textPeriodLab">años de servicio</div>
                        </div>
                    </div>
                </div>              
            </div>
        </section>
        <section id="SecDatModRet">
            <p>M O D A L I D A D &nbsp D E L &nbsp R E T I R O</p>
            <div Id="tipModRetiro">Tipo de retiro: &nbsp
                <select name="ModoRetiro" id="ModoRetiro">
                    <option value="C">COMPLETO</option>
                    <option value="P">PARCIAL</option>
                    <option value="FF">FONDO FALLECIMIENTO</option>
                </select>
            </div>
            <div id="divCalcRetiro">
                <div class="montsRet" id="montRetiro">Monto del retiro: &nbsp $<input type="text" id="montRet" name="montRet"></div></br>
                <div class="montsRet" id="montRetFondFall">Monto de retiro para Fondo de Fallecimiento:&nbsp $<input type="text" id="montRetFondFall" name="montRetFondFall"></div>
            </div>
            <div id="divAgregaTram">
                <button id="agregaTramite" name="agregaTramite" class="">AGREGAR</button>
            </div>
        </section>
    </section>
</section>


