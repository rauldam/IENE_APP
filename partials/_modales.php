<div class="modal fade" id="modalTecnicos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"> 
                    <?php
                        echo '<select id="tecnicosSelect" class="form-control" name="tecnicosSelect" onchange="insertaTecnicoHecho(this.value)">';
                            for($i = 0; $i < count($tecnicos); $i++){
                                if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                    echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                }else{
                                    echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                }
                            }
                        echo '</select>';
                    ?>
                </div>
            </div>
        </div>
    </div>

<!-- Modal envases -->
      <div class="modal fade" id="modalenvases" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnEnvases" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formenvases">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosenvases" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                   1.	¿Conoce usted las prácticas de reciclaje de separación de los residuos para destinarlo a los contenedores adecuados o puntos de recogida establecidos?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="envases2" id="envases2" class="check_datos" value="si">
                                    <label for="envases2">Si</label><br>
                                    <input type="radio" name="envases2" id="envases3" class="check_datos" value="no">
                                    <label for="envases3">No. Recuerde que los residuos y envases deben ser separados y destinados a contenedores adecuados.
                                        <ul>
                                            <li>Contenedor Gris: desechos en general</li>
                                            <li>Contenedor Naranja/Marrón: orgánico</li>
                                            <li>Contenedor Verde: Vidrio</li>
                                            <li>Contenedor amarillo: plásticos y envases metálicos</li>
                                            <li>Contenedor azul: papel y cartón</li>
                                            <li>Contenedor rojo: desechos peligrosos</li>
                                        </ul>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    2.	¿Promociona usted en su establecimiento la utilización de bolsas de más de un uso?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="envases3" id="envases4" class="check_datos" value="si">
                                    <label for="envases4">Si</label><br>
                                    <input type="radio" name="envases3" id="envases5" class="check_datos" value="no">
                                    <label for="envases5">No. En tal caso, debe promover el uso de bolsas reutilizables para reducir el uso innecesario de estos envases.</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    3.	¿Sabe que, los clientes que quieran pueden llevar sus propios envases (tuppers, fiambreras…) para transportar y conservar los alimentos que le compren a granel?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="envases4" id="envases6" class="check_datos" value="si">
                                    <label for="envases6">a.	Si. Perfecto. Recuerde que también puede ofrecer usted este tipo de envases reutilizables de forma gratuita o a cambio de un precio. 
Asimismo, debe tener en cuenta que los recipientes pueden ser rechazados para el servicio si están sucios o no son adecuados
</label>
                                    <input type="radio" name="envases4" id="envases7" class="check_datos" value="no">
                                    <label for="envases7">b.	No. En tal caso, informarle de que podrá aceptar el uso de recipientes reutilizables, siempre y cuando sean adecuados para la naturaleza del producto adquirido y esté debidamente higienizado, siendo el consumidor el responsable de su acondicionamiento o limpieza. Recuerde que también puede ofrecer usted este tipo de envases reutilizables de forma gratuita o a cambio de un precio.</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    4.	¿Conoce la obligación para los establecimientos de venta de alimentación de más de 400 m2 de destinar el 20% de su área de ventas a la oferta de productos a granel o con envases reutilizables?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="envases5" id="envases8" class="check_datos" value="si">
                                    <label for="envases8">a.	Sí. Perfecto. Igualmente, aunque no superen los 400 m2 han de fomentar la venta a granel de frutas y verduras frescas.</label>
                                    <input type="radio" name="envases5" id="envases9" class="check_datos" value="no">
                                    <label for="envases9">b.	No. En tal caso, informarle de que esta es la obligación impuesta por la nueva normativa sobre envases y residuos de envases (Real Decreto 1055/2022, de 27 de diciembre).
Igualmente, aunque no superen los 400 m2 han de fomentar la venta a granel de frutas y verduras frescas.
</label>
                                </div>
                            </div>
                            <hr>
                            <h3>SOLO HOSTELERÍA</h3>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                        ¿Saben que si un cliente les solicita agua no embotellada se la deben facilitar de forma gratuita?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="envases6" id="envases10" class="check_datos" value="si" checked>
                                    <label for="envases10">Si</label><br>
                                    <input type="radio" name="envases6" id="envases11" class="check_datos" value="no">
                                    <label for="envases11">No. En tal caso, le informo de que todos los establecimientos de hostelería y restauración ofrecerán siempre a los clientes de sus servicios la posibilidad de consumo de agua no envasada de manera gratuita</label>
                                </div>

                            </div>
                            
                            <div class="form-group row">
                                 <div align="right">
                                    <button class="btn btn-success" type="button" onclick="enviarInformacion('envases')">Enviar Cuestionario</button>
                                </div>
                         </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal Digitales -->
    <div class="modal fade" id="modaldigitales" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnDigitales" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formdigitales">
				            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosdigitales" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                        ¿Qué miembro de la empresa/organización se encarga de gestionar la Protección de Datos y las Garantías de Derechos Digitales?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="digitales1" id="digitales1" class="form-control" >
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                   ¿Disponen de personal en Teletrabajo?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="digitales2" id="digitales2" class="check_datos" value="si">
                                    <label for="digitales2">Si</label>
                                    <input type="radio" name="digitales2" id="digitales3" class="check_datos" value="no">
                                    <label for="digitales3">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    ¿Realizan Geolocalización de los trabajadores?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="digitales3" id="digitales4" class="check_datos" value="si">
                                    <label for="digitales4">Si</label>
                                    <input type="radio" name="digitales3" id="digitales5" class="check_datos" value="no">
                                    <label for="digitales5">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    ¿Han informado a los trabajadores del derecho a la desconexión digital?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="digitales4" id="digitales6" class="check_datos" value="si">
                                    <label for="digitales6">Si</label>
                                    <input type="radio" name="digitales4" id="digitales7" class="check_datos" value="no">
                                    <label for="digitales7">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    ¿Existe una política en la empresa de pantalla y escritorio limpios?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" name="digitales5" id="digitales8" class="check_datos" value="si">
                                    <label for="digitales8">Si</label>
                                    <input type="radio" name="digitales5" id="digitales9" class="check_datos" value="no">
                                    <label for="digitales9">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                        ¿Cuál es la dirección de correo que pueden usar los trabajadores para solicitar información sobre sus derechos digitales?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="digitales6" id="digitales10" class="form-control" >
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                        ¿Cómo se va a publicitar el cartel informativo de derechos a todos los trabajadores?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="digitales7" id="digitales11" class="form-control" >
                                </div>

                            </div>
                            <div class="form-group row">
                                 <div align="right">
                                    <button class="btn btn-success" type="button" onclick="enviarInformacion('digitales')">Enviar Cuestionario</button>
                                </div>
                         </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Desperdicio -->
<div class="modal fade" id="modaldesperdicio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnDesperdicio" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formdesperdicio">
				            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosdesperdicio" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 mb-12">
                                        Para implantar esta nueva norma, es necesario que se establezca en la empresa un responsable de prevenir y controlar las pérdidas y desperdicios alimentarios.
                                        En el caso de su empresa, ¿Quién va a ser este responsable? 
                                        Tener en cuenta que en caso de una empresa mediana/grande (+10 trabajadores) debe ser más de 1 persona.

                                </div>
                                <div class="col-md-12 mb-12">
                                    <input type="text" name="desperdicio1" id="desperdicio1" class="form-control" >
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 mb-12">
                                   ¿Conoce usted las prácticas de higiene y seguridad que debe cumplir para el tratamiento de los alimentos? Tales como la temperatura de conservación, el periodo durante el cual lo puede conservar, como almacenarlo…
                                   Independientemente de su respuesta, le diremos: Le adjuntamos una serie de anexos en la documentación en la que podrá comprobar estas correctas prácticas de higiene y seguridad

                                </div>
                                <div class="col-md-12 mb-12">
                                    <input type="radio" name="desperdicio2" id="desperdicio2" class="check_datos" value="si">
                                    <label for="desperdicio2">Si</label>
                                    <input type="radio" name="desperdicio2" id="desperdicio3" class="check_datos" value="no">
                                    <label for="desperdicio3">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 mb-12">
                                  ¿Conoce la propuesta de la nueva normativa de impulsar en su establecimiento la donación de excedentes de productos alimentarios a entidades sociales, sin ánimo de lucro, ong’s o bancos de alimentos?
                                   SI: Perfecto.
                                   NO: En tal caso, le informamos que es recomendable incorporar en su establecimiento un acuerdo con alguna entidad de las que hemos mencionado con el objetivo de donar los excedentes alimentarios que aun sean aptos para el consumo humano

                                </div>
                                <div class="col-md-12 mb-12">
                                    <input type="radio" name="desperdicio3" id="desperdicio4" class="check_datos" value="si">
                                    <label for="desperdicio7">Si</label>
                                    <input type="radio" name="desperdicio3" id="desperdicio5" class="check_datos" value="no">
                                    <label for="desperdicio8">No</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <a class="btn btn-primary" onclick="collapse('collapse1')" style="width:50%">HOSTELERÍA</a>
                                <a class="btn btn-primary" onclick="collapse('collapse2')" style="width:50%">COMERCIO AL POR MENOR</a>
                                 <div class="collapse" id="collapse1">
                                    <div class="card card-body">
                                        <div class="col-md-6 mb-6">
                                            ¿Dispone en su establecimiento de buffet libre?
                                        </div>
                                        <div class="col-md-12 mb-12">
                                            <input type="radio" name="desperdicio4" id="desperdicio6" class="check_datos" value="si">
                                            <label for="desperdicio4">Si</label>
                                            <input type="radio" name="desperdicio4" id="desperdicio7" class="check_datos" value="no" checked>
                                            <label for="desperdicio5">No</label>
                                        </div>
                                        <div class="col-md-6 mb-6">
                                            ¿Ofrece venta de alimentos crudos como sushi?
                                        </div>
                                        <div class="col-md-12 mb-12">
                                            <input type="radio" name="desperdicio5" id="desperdicio8" class="check_datos" value="si">
                                            <label for="desperdicio4">Si</label>
                                            <input type="radio" name="desperdicio5" id="desperdicio9" class="check_datos" value="no" checked>
                                            <label for="desperdicio5">No</label>
                                        </div>
                                        <div class="col-md-12 mb-12">
                                            ¿En su establecimiento da la posibilidad a sus clientes de poder elegir el tamaño de la ración o diferentes guarniciones?
                                        </div>
                                        <div class="col-md-12 mb-12">
                                            <input type="radio" name="desperdicio6" id="desperdicio10" class="check_datos" value="si">
                                            <label for="desperdicio6">Si</label>
                                            <input type="radio" name="desperdicio6" id="desperdicio11" class="check_datos" value="no" checked>
                                            <label for="desperdicio7">No</label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="collapse" id="collapse2">
                                      <div class="card card-body">
                                        <div class="col-md-12 mb-12">
                                            ¿Disponen en su establecimiento de una línea de venta de productos feos, imperfectos o poco estéticos? Son los productos que están con alguna rayita o frutas feas…
                                        </div>
                                        <div class="col-md-12 mb-12">
                                            <input type="radio" name="desperdicio7" id="desperdicio12" class="check_datos" value="si">
                                            <label for="desperdicio4">Si</label>
                                            <input type="radio" name="desperdicio7" id="desperdicio13" class="check_datos" value="no" checked>
                                            <label for="desperdicio5">No</label>
                                        </div>
                                        <div class="col-md-12 mb-12">
                                            ¿Su establecimiento tiene una superficie de venta superior a 1.300m2? 
                                        </div>
                                        <div class="col-md-12 mb-12">
                                            <input type="radio" name="desperdicio8" id="desperdicio14" class="check_datos" value="si">
                                            <label for="desperdicio6">Si</label>
                                            <input type="radio" name="desperdicio8" id="desperdicio15" class="check_datos" value="no" checked>
                                            <label for="desperdicio7">No</label>
                                        </div>
                                      </div>
                                    </div>
                            </div>
                            <div class="form-group row">
                                 <div align="right">
                                    <button class="btn btn-success" type="button" onclick="enviarInformacion('desperdicio')">Enviar Cuestionario</button>
                                </div>
                         </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <!-- Modal Libertad Sexual -->
    <div class="modal fade" id="modallibertadsex" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnLibertadsex" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formlibertadsex">
				            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicoslibertadsex" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 mb-3">
                                        1.	Es necesario que, para la correcta implantación de las garantías integrales de la libertad sexual en la empresa, se disponga de un responsable que lleve a cabo esta acción, 
                                        En su empresa, ¿quién será el cargo o persona responsable de la implantación?

                                </div>
                                <div class="col-md-12 mb-3">
                                    <input type="text" name="libertadsex1" id="libertadsex1" class="form-control" >
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 mb-3">
                                   2.	Para establecer las garantías de la libertad sexual en la empresa, hay que tener en cuenta dos medidas, las preventivas y las formativas.
                                    -	¿Han adoptado o piensan adoptar medidas formativas para sus empleados?
                                    -	¿Qué medidas preventivas han considerado incorporar en su organización? Por ejemplo: incorporar un protocolo de prevención de acoso sexual y por razón de sexo, revisar el plan de prevención de riesgos laborales…

                                </div>
                                <div class="col-md-12 mb-3">
                                    <input type="text" name="libertadsex2" id="libertadsex2" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-3">
                                    3.	Es importante que todos los miembros de la empresa estén informados de estas garantías a fin de poder prevenir y proteger a las personas afectadas por la violencia sexual. ¿Cómo van a trasladar esta información a los miembros de su empresa?
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="libertadsex3" id="libertadsex3" class="form-control" >
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                 <div align="right">
                                    <button class="btn btn-success" type="button" onclick="enviarInformacion('libertadsex')">Enviar Cuestionario</button>
                                </div>
                         </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal LOPD -->
    <div class="modal fade" id="modallopd" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnLopd" class="btn btn-danger btn-block">Cancelar produto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formlopd">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicoslopd" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-6 control-label">
                                    <input type="text" class="form-control" name="repLegal" id="repLegal" value="Representante legal">
                                </div>
                                <div class="col-md-6 col-sm-6 control-label">
                                    <input type="text" class="form-control" name="cuestionario" id="cuestionario" value="Nombre del responsable en LOPD">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3 col-sm-3 control-label">
                                    <label>1 - ¿La entidad dispone de encargado de tratamiento de datos personales?  </label>
                                </div>
                                <div class="col-sm-9">
                                    <label>Recuerde que en el mapa de riesgos que el preparamos, podrá completar la relación de encargados que dispone su organización.</label>
                                        <input type="radio" id="encargado1" name="subcontrata" value="si" class="check_datos">
                                        La entidad dispone de encargado de tratamiento de datos personales<br>
                                        <input type="radio" id="encargado2" name="subcontrata" value="no" checked class="check_datos">
                                        La entidad no dispone de encargado de tratamiento de datos personales 
                                        
                                </div>
                            </div>
							<div class="form-group row">
                                <div class="col-md-4 col-sm-4 control-label">
                                    <label>2 - ¿Qué tipo de datos trata internamente?</label>
                                </div>
                                <div class="col-sm-4">
                                     <label>Clientes</label> 
                                        Si
                                        <input type="radio" id="clientes1" name="clientes" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="clientes2" name="clientes" value="no" checked class="check_datos"><br>
                                    <label>Associados</label>
                                        Si
                                        <input type="radio" id="asociados1" name="asociados" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="asociados2" name="asociados" value="no" checked class="check_datos"><br>
                                    <label>Proprietarios</label>
                                        Si
                                        <input type="radio" id="propietarios1" name="propietarios" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="propietarios2" name="propietarios" value="no" checked class="check_datos"><br>
                                     <label>Pacientes</label>
                                        Si
                                        <input type="radio" id="pacientes1" name="pacientes" value="si" class="check_datos" >
                                        No 
                                        <input type="radio" id="pacientes2" name="pacientes" value="no" checked class="check_datos" ><br>
                                </div>
                                <div class="col-sm-4">
                                    <label>Alumnos</label>
                                        Si
                                        <input type="radio" id="alumnos1" name="alumnos" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="alumnos2" name="alumnos" value="no" checked class="check_datos"><br>
                                    <label>Proveedores</label>
                                        Si
                                        <input type="radio" id="proveedores1" name="proveedores" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="proveedores2" name="proveedores" value="no" checked class="check_datos"><br>
                                    <label>Trabajadores</label>
                                        Si
                                        <input type="radio" id="trabajadores1" name="trabajadores" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="trabajadores2" name="trabajadores" value="no" checked class="check_datos">
                                </div>
                            </div>
							<div class="form-group row">
                                    <div class="col-sm-3 col-sm-3 control-label">
                                        <label>3 - ¿Qué tipo de datos trata? </label>
                                    </div>
									<div class="col-sm-3">
								    <label>Identificativos</label>
                                        Si
                                        <input type="radio" id="identificativos1" name="identificativos" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="identificativos2" name="identificativos" value="no" checked class="check_datos">
                                    <label>Académicos</label>
                                        Si
                                        <input type="radio" id="aacademicos1" name="academicos" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="aacademicos2" name="academicos" value="no" checked class="check_datos">
                                    <label>Profesionales</label>
                                        Si
                                        <input type="radio" id="profesionales1" name="profesionales" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="profesionales2" name="profesionales" value="no" checked class="check_datos">
                                    </div>
                                    <div class="col-sm-3">
                                    <label>Menores</label>
                                        Si
                                        <input type="radio" id="menores1" name="menores" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="menores2" name="menores" value="no" checked class="check_datos"><br>
                                        <label>Salud</label>
                                        Si
                                        <input type="radio" id="salud1" name="salud" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="salud2" name="salud" value="no" checked class="check_datos"><br>
                                    <label>Vida Sexual</label>
                                        Si
                                        <input type="radio" id="sexual1" name="sexual" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="sexual2" name="sexual" value="no" checked class="check_datos">
                                    </div>
                                    <div class="col-sm-3">
                                    <label>Religión/Creencias</label>
                                        Si
                                        <input type="radio" id="religion1" name="religion" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="religion2" name="religion" value="no" checked class="check_datos">
                                        <label>Afiliación sindical</label>
                                        Si
                                        <input type="radio" id="afiliacion1" name="afiliacion" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="afiliacion2" name="afiliacion" value="no" checked class="check_datos">
                                    <label>Origen racial o étnicos</label>
                                        Si
                                        <input type="radio" id="racial1" name="racial" value="si" class="check_datos">
                                        No 
                                        <input type="radio" id="racial2" name="racial" value="no" checked class="check_datos">
                                    </div>
                                    
				            </div>
							<div class="form-group row">
								<label class="col-sm-6 col-sm-6 control-label">4 - Realizan transferencias de datos personales con carácter internacional?  </label>
								    <div class="col-sm-6">
								        <input type="radio" id="transferencia1" name="transferencia" value="si">
								        <label>Realizan transferencias de datos personales con carácter internacional</label><br>
								        <input type="radio"  checked id="transferencia2" name="transferencia" value="No">
								        <label>No realizan transferencias de datos personales con carácter internacional</label>
                                    </div>
				            </div>
							<div class="form-group row">
											<label class="col-sm-6 col-sm-6 control-label">5 - ¿Realiza algún tipo de notificación o comunicación con los usuarios de su organización?</label>
											<div class="col-sm-6">
													<input type="radio" id="encargado1" name="encargado" value="si">
													<label>Si</label>
													<input type="radio" checked id="encargado2" name="encargado" value="No">
													<label>No</label>
											</div>
										</div>
							<div class="form-group row">
											<label class="col-sm-6 col-sm-6 control-label">6 - ¿Dispone en sus instalaciones de algún dispositivo de videovigilancia?</label>
											<div class="col-sm-6">
													<input type="radio" id="camaras1" name="camaras" value="si">
													<label>Si</label>
													<input type="radio" checked id="camaras2" name="camaras" value="No">
													<label>No</label>
											</div>
										</div>
                            <div class="form-group row">
											<label class="col-sm-6 col-sm-6 control-label">7 - Si tiene trabajadores. ¿registran su jornada? (Desaconsejar el uso de registro con huella dactilar)</label>
											<div class="col-sm-6">
													<input type="radio" id="biometricos4" name="biometricos" value="no">
													<label>Sin trabajadores </label><br>
													<input type="radio" id="biometricos1" name="biometricos" value="Huella digital">
													<label>Huella digital (registro biométrico)</label><br>
													<input type="radio" id="biometricos2" name="biometricos" value="Aplicación Móvil">
													<label>App móvil</label><br> 
                                                    <input type="radio" id="biometricos3" name="biometricos" value="Manual">
													<label>Registro jornada manual </label><br>
                                                    <input type="radio" id="biometricos5" name="biometricos" value="otros">
                                                    <input type="text" placeholder="Otro tipo de registro" id="biometricos6" name="biometricosOtros" value=""> 
													
											</div>
										</div>
                            <div class="form-group row">
											<label class="col-sm-6 col-sm-6 control-label">8 - ¿Recogen curriculums de trabajadores potenciales?</label>
											<div class="col-sm-6">
													<input type="radio" id="curriculums1" name="curriculums" value="si">
													<label>Si</label>
													<input type="radio" checked id="curriculums2" name="curriculums" value="No">
													<label>No</label>
											</div>
										</div>
							<div class="form-group row">
											<label class="col-sm-6 col-sm-6 control-label">9 - ¿Va a tomar fotos de sus clientes/empleados/usuarios para publicarlas en sus redes sociales o con fines publicitarios?</label>
											<div class="col-sm-6">
													<input type="radio" id="fotos1" name="fotos" value="si">
													<label>Si</label>
													<input type="radio" checked id="fotos2" name="fotos" value="No">
													<label>No</label>
											</div>
										</div>
							
							<div class="form-group row">
											<label class="col-sm-6 col-sm-6 control-label">10 - ¿Dispone de página web? En caso de tenerla, ¿es informativa o tiene venta online?</label>
											<div class="col-sm-6">
													<input type="radio" id="web1" name="web" value="Web informativa">
													<label>Página web informativa</label><br>
                                                    <input type="radio" id="web1" name="web" value="Web con venta online">
													<label>Dispone de página web con venta online</label><br>
													<input type="radio" checked id="web2" name="web" value="No dispone de web">
													<label>No dispone de página web</label><br>
											</div>
										</div>
							<div class="form-group row"> 
								     <label class="col-sm-6 col-sm-6 control-label">11 - Medidas que utilizan para proteger sus datos personales</label>
								     <div class="col-sm-6">
								        <input type="radio" id="proteccion1" name="proteccion" value="1" checked>
								        <label>Programa informático protegido por contraseña</label><br>
								        <input type="radio" id="proteccion2" name="proteccion" value="2">
								        <label>Nube, protegido por contraseña</label><br>
                                         <input type="radio" id="proteccion3" name="proteccion" value="3">
								        <label>Control de acceso de los datos</label><br>
								        <input type="radio" id="proteccion4" name="proteccion" value="4">
								        <label>En papel protegido por llave</label><br>
                                         <input type="radio" id="proteccion5" name="proteccion" value="5">
								        <label>En dispositivos portátiles protegidos por contraseña </label><br>
                                         <input type="radio" id="proteccion6" name="proteccion" value="6">
								        <label>Intranet protegida por contraseña</label><br>
                                        <input type="radio" id="proteccion7" name="proteccion" value="otros">
								        <input type="text" id="proteccion8" name="proteccionOtros" value="Añada otros aquí"> 
                                     </div>
                            </div>
                                <div class="form-group row">
                                <div align="right">
                                    <button class="btn btn-success" type="button" onclick="enviarInformacion('lopd')">Enviar </button>
                                </div>
				            </div>
				        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
         
    <!-- Modal LSSI -->
    <div class="modal fade" id="modallssi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnlssi" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formlssi">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicoslssi" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
				            <div class="form-group row">
                                
                                <div class="col-md-6 mb-6">
                                     <label>Página Web</label>
                                        <input type="text" id="lssiWeb" name="web" class="form-control">
                                </div>
                                <div class="col-md-6 mb-6">
                                     <label>Mail Web</label>
                                        <input type="email" id="lssiMail" name="mail" class="form-control">
                                </div>
                                
				            </div>
                            <div class="form-group row">
                                <div class="col-md-4 mb-4">
                                    <h4>Bloque 1</h4>
                                        <textarea class="form-control" id="bloque1a" rows="3" name="bloque1a" placeholder="¿Se informa al usuario de la denominación social, NIF, domicilio y dirección de correo electrónico?"></textarea>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h4>Bloque 1</h4>
                                        <textarea class="form-control" id="bloque1b" rows="3" name="bloque1b" placeholder="¿Se ha comunicado el nombre del domino de la empresa al Registro Mercantil, Cooperativas, Asociaciones u otro registro público al que esté inscrita?"></textarea>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h4>Bloque 1</h4>
                                        <textarea class="form-control" id="bloque1c" rows="3" name="bloque1c" placeholder="¿Se informa de los precios de los productos o servicios que se ofrecen?"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 2</h4>
                                        <textarea class="form-control" id="bloque2a" rows="3" name="bloque2a" placeholder="¿Se hace publicidad por correo electrónico?"></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 2</h4>
                                        <textarea class="form-control" id="bloque2b" rows="3" name="bloque2b" placeholder="¿Se indica claramente la identificación del anunciante?"></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 2</h4>
                                        <textarea class="form-control" id="bloque2c" rows="3" name="bloque2c" placeholder="¿Se identifica el mensaje publicitario con la palabra “Publicidad”?"></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 2</h4>
                                        <textarea class="form-control" id="bloque2c" rows="3" name="bloque2c" placeholder="¿Obtiene con carácter previo el consentimiento del destinatario para envío de publicidad y/o uso de cookies?"></textarea>
                                </div>
                                <div class="col-md-12 mb-12">
                                    <h4>Bloque 2</h4>
                                        <textarea class="form-control" id="bloque2d" rows="3" name="bloque2d" placeholder="¿Tiene establecidos los procedimientos para facilitar la revocación del consentimiento del usuario del envío de publicidad?"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 mb-6">
                                    <h4>Bloque 3</h4>
                                        <textarea class="form-control" id="bloque3a" rows="3" name="bloque3a" placeholder="¿Si se realizan concursos, ofertas y promociones están identificadas como tales?"></textarea>
                                </div>
                                <div class="col-md-6 mb-6">
                                    <h4>Bloque 3</h4>
                                        <textarea class="form-control" id="bloque3b" rows="3" name="bloque3b" placeholder="¿Se indican claramente las condiciones de participaciones?"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 4</h4>
                                        <textarea class="form-control" id="bloque4a" rows="3" name="bloque4a" placeholder="¿Están indicados los trámites que deben seguirse para realizar el contrato online?"></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 4</h4>
                                        <textarea class="form-control" id="bloque4b" rows="3" name="bloque4b" placeholder="¿Están indicadas las condiciones generales del contrato en su web?"></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 4</h4>
                                        <textarea class="form-control" id="bloque4c" rows="3" name="bloque4c" placeholder="¿Se realiza la confirmación del contrato por vía electrónica mediante el envío de un acuse de recibo del pedido realizado?"></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h4>Bloque 4</h4>
                                        <textarea class="form-control" id="bloque4d" rows="3" name="bloque4d" placeholder="¿Informan de la posibilidad de desistir de la contratación?"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                 <div align="right">
                                    <button class="btn btn-success" type="button" onclick="enviarInformacion('lssi')">Enviar Cuestionario</button>
                                </div>
                         </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Modal Manual -->
    <div class="modal fade" id="modalmanual" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnmanual" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formmanual">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosmanual" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <h4>RIESGO 1 : <h6>LEGITIMACIÓN DE LOS TRATAMIENTOS Y LAS CESIONES DE DATOS PERSONALES</h6></h4>
                                    <small>¿Se cuenta con el consentimiento libre, específico, inequívoco e informado de los afectados para el tratamiento de sus datos personales?
                                    En caso contrario, ¿Está autorizado por una ley? ¿Proceden los datos de fuentes accesibles al público? ¿Está asociado al ejercicio de un derecho fundamental (libertad de expresión, libertad de información, tutela judicial efectiva, libertad sindical, etc.)?
                                    ¿Se informa de la finalidad a la que se destinarán los datos?
                                    ¿Se han habilitado procedimientos para gestionar la revocación del consentimiento del afectado?</small>
                                    <textarea class="form-control" name="riesgo1" id="manual1" rows="5" class="ob_lopd1" placeholder="Para tratar los riesgos detectados se deberán aplicar las medidas presentes en la página 10 del Manual de Protección de Datos para el Responsable del Tratamiento
No se observa ningún tipo de riesgo relevante"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <h4>RIESGO 2 : <h6>TRANSFERENCIAS INTERNACIONALES</h6></h4>
                                    <small>¿Se van a transferir datos personales fuera de España?
                                        ¿Es el país de destino un miembro del Espacio Económico Europeo?</small>
                                    <textarea class="form-control" name="riesgo2" id="manual2" cols="60" rows="10" class="ob_lopd2" placeholder="Las transferencias internacionales de datos suponen un riesgo que tendrá que afrontarse con las medidas presentes en la página 14 del Manual de Protección de Datos para el Responsable del Tratamiento.
No se transfieren datos personales a ningún otro país."></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <h4>RIESGO 3 : <h6>TRATAMIENTOS DE DATOS</h6></h4>
                                    <small>¿ Cuenta con un registro de actividades de tratamiento de datos personales? o ¿Tienes copia de las de los ficheros inscritos en la agencia española de protección de datos?</small>
                                    <textarea class="form-control" name="riesgo3" id="manual3" rows="13" class="ob_lopd1" placeholder="Para tratar los riesgos detectados se deberán aplicar las medidas presentes en la página 10 del Manual de Protección de Datos para el Responsable del Tratamiento
No se observa ningún tipo de riesgo relevante"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <h4>RIESGO 4 : <h6>TRANSPARENCIA DE LOS TRATAMIENTOS</h6></h4>
                                    <small>¿Los datos se recaban directamente de los afectados?
                                        Si los datos no se recaban directamente de los afectados, ¿se informa a los mismos, en el plazo de tres meses desde el registro de los datos personales, de forma expresa e inequívoca de la existencia de un tratamiento de datos personales, de la identidad y dirección del responsable del tratamiento, de su finalidad, de los destinatarios de los datos y de la posibilidad de ejercer los derechos ARCO?</small>
                                    <textarea class="form-control" name="riesgo4" id="manual4" cols="60" rows="10" class="ob_lopd2" placeholder="Como los datos no son recabados directamente de los afectados se procederá a informarles de que sus datos están siendo tratados y de sus derechos.
Los datos se recaban de los afectados."></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <h4>RIESGO 5 : <h6>CALIDAD DE LOS DATOS</h6></h4>
                                    <small>¿Se recogen solo los datos personales estrictamente necesarios para las finalidades de que se trate?
                                        ¿Se recaban los datos de forma leal y transparente?
                                        ¿Se usan los datos para finalidades distintas o incompatibles con las establecidas y comunicadas al afectado?
                                        ¿Se contempla la posibilidad de cancelar los datos personales de oficio, cuando ya no sean necesarios para la finalidad o finalidades para las que se recogieron?</small>
                                    <textarea class="form-control" name="riesgo5" id="manual5" rows="13" class="ob_lopd1" placeholder="Para afrontar adecuadamente estos riesgos será necesario consultar la página 16 del Manual de Protección de Datos para el Responsable del Tratamiento.
No se observa ningún tipo de riesgo relevante"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <h4>RIESGO 6 : <h6>DATOS ESPECIALMENTE PROTEGIDOS</h6></h4>
                                    <small>¿Se tratan con datos especialmente protegidos de ideología, religión, creencias o afiliación sindical ¿se cuenta con el consentimiento expreso y por escrito?
                                        ¿Se tratan con datos especialmente protegidos de salud, vida sexual u origen racial o étnico, ¿se cuenta con el consentimiento expreso?
                                        En el caso de tratamientos de datos especialmente protegidos para la prestación o gestión de servicios sanitarios, ¿se garantiza adecuadamente el deber de secreto de todas las personas que tienen acceso a ellos? ¿Se limita el acceso a los datos de salud a los estrictamente necesarios para cada una de las diferentes funciones (sanitarias, administrativas, investigadoras, docentes, etc.) que se llevan a cabo?</small>
                                    <textarea class="form-control" name="riesgo6" id="manual6" cols="60" rows="10" class="ob_lopd2" placeholder="Será necesario revisar las medidas establecidas en la página 18 del Manual de Protección de Datos para el Responsable del Tratamiento.
Los datos se recaban de los afectados."></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <h4>RIESGO 7 : <h6>DEBER DE SECRETO</h6></h4>
                                    <small>¿El personal de la empresa está debidamente informado de la forma de tratar los datos de carácter personal?
                                    ¿Queda alguna constancia de la información?</small>
                                    <textarea class="form-control" name="riesgo7" id="manual7" rows="13" class="ob_lopd1" placeholder="Para evitar este tipo de riesgos deberán de adoptarse las medidas presentes en la página 19 del Manual de Protección de Datos para el Responsable del Tratamiento.
No se observa ningún tipo de riesgo relevante"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <h4>RIESGO 8 : <h6>TRATAMIENTOS POR ENCARGO</h6></h4>
                                    <small>¿En el caso de que se externalice algún proceso de la empresa, ¿La empresa subcontratada cumple con las exigencias legales de la agencia española de protección de datos?
                                    ¿Se estipula que el encargado solo podrá tratar los datos personales conforme a las instrucciones del responsable y no los aplicará a fines distintos?
                                    ¿Se estipula que los datos no serán comunicados a otras personas?</small>
                                    <textarea class="form-control" name="riesgo8" id="manual8" cols="60" rows="10" class="ob_lopd2" placeholder="Revisar página 20 del Manual de Protección de Datos para el Responsable del Tratamiento.Para minimizar los riesgos relacionados con los tratamientos de datos por encargo.
Los datos se recaban de los afectados."></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h4>RIESGO 9 : <h6>DERECHOS DE ACCESO, RECTIFICACIÓN, CANCELACIÓN Y OPOSICIÓN (DERECHOS ARCO)</h6></h4>
                                    <small>¿Existen procedimientos necesarios para el acceso, la rectificación, cancelación u oposición de los datos personales?
                                    ¿Se han adoptado medidas para acreditar la representación en casos de incapacidad o minoría de edad?
                                    ¿Se conservan los datos personales de tal forma que permitan el fácil y rápido ejercicio de los derechos?</small>
                                    <textarea class="form-control" name="riesgo9" id="manual9" rows="10" class="ob_lopd1" placeholder="Adoptar las medidas presentes en la página 21 del Manual de Protección de Datos para el Responsable del Tratamiento para minimizar los riesgos en este ámbito.
No se observa ningún tipo de riesgo relevante"></textarea>
                                
                                     <div align="right">
                                        <button class="btn btn-success" type="button" onclick="enviarInformacion('manual')" style="margin-right:5px">Enviar Cuestionario</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Modal BLANQUEO -->
    <div class="modal fade" id="modalblanqueo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnblanqueo" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formblanqueo">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosblanqueo" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
				            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <h6>¿Cuál es la actividad principal de la empresa? </h6>
                                    <textarea class="form-control" name="preg1" id="preg1" rows="5" class="ob_lopd1" placeholder="Debe escribir aquí la actividad de la empresa"></textarea>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <h6>¿Cuántos trabajadores tiene la empresa?</h6>
                                    <textarea class="form-control" name="preg2" id="preg2" cols="60" rows="5" class="ob_lopd2" placeholder="Indique aquí cuantos trabajadores tiene la empresa ya sean autónomos o por cuenta ajena"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <h6>¿Cuántos departamentos tiene la empresa?</h6>
                                    <textarea class="form-control" name="preg3" id="preg3" rows="5" class="ob_lopd1" placeholder="Indique los departamentos que tiene la empresa (Escriba el nombre)"></textarea>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <h6>¿Cuántos centros de trabajo tiene la empresa? </h6>
                                    <textarea class="form-control" name="preg4" id="preg4" cols="60" rows="5" class="ob_lopd2" placeholder="Indique aquí los centros de trabajo que dispone la empresa (Dirección)"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <h6>¿Cuál es el órgano de mayor jerarquía de la empresa? </h6>
                                    <textarea class="form-control" name="preg5" id="preg5" rows="5" class="ob_lopd1" placeholder="Indique cuál es el organo de mayor jerarquía, (Gerencia, dirección, consejo delegado…)"></textarea>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <h6>¿Quién es la persona responsable de la Prevención de Blanqueo de capitales en la empresa?</h6>
                                    <textarea class="form-control" name="preg6" id="preg6" cols="60" rows="5" class="ob_lopd2" placeholder="Indica el nombre completo y dni"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 mb-3">
                                    <h6>¿La empresa tiene 10 trabajadores o más? ¿La empresa factura 2 Millones de Euros o más? </h6>
                                    <textarea class="form-control" name="preg7" id="preg7" rows="5" class="ob_lopd1" placeholder="Poner Si en el caso de que al menos una de las dos preguntas sea positiva, si la respuesta a las dos preguntas es que no, poner NO."></textarea>
                                </div>
                                 <div align="right">
                                    <button class="btn btn-success" type="button" onclick="enviarInformacion('blanqueo')">Enviar Cuestionario</button>
                                </div>
                         </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
     </div>
        
    <!-- Modal SEGURO -->
    <div class="modal fade" id="modalseguro" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnseguro" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <textarea name="" id="" class="form-control" cols="30" rows="10" disabled>
                            Seguro asistencia LOPD
                            Atención telefónica ilimitada, de Lunes a Viernes de 9 h a 14 h.
                            Llamada de asistencia a la implantación, y de seguimiento a los 6 meses.
                            Creación de un mail específico para la comunicación con AGPD y los clientes.
                            Asistencia y elaboración de la documentación en caso de consulta o reclamación de un cliente.
                            Asistencia y elaboración de la documentación en caso de inspección de la AGPD.
                            50% Descuento en caso de necesitar la asistencia presencial de un abogado o consultor.
                       </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Modal COVID -->
    <div class="modal fade" id="modalcovid" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btncovid" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                     <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6"><button onclick="ocultarForm('covidform')" class="btn btn-primary btn-block">Servicios</button></div>
                                <div class="col-lg-6"><button onclick="ocultarForm('formcovid')" class="btn btn-success btn-block">Turismo</button></div>
                            </div>
                            <br>
                             <hr>
                            <form class="forms-sample" class="forms-sample" id="formcovid">
                                <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicoscovid" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';   
                                ?>
                            </div>
                                <h4 style="text-align:center">Servicios</h4>
                                <br>
                                <div class="form-group row"> 
                                    <div class="col-md-6"><input type="text" class="form-control" id="covidRep" placeholder="Responsable " name="resp"></div>
                                    <div class="col-md-6"><input type="text" class="form-control" id="covidRepAus" placeholder="Responsable Ausente" name="respAus"></div>
                                </div>
                                <hr>
                                <input type="hidden" name="tipo" value="servicios">
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Es posible mantener una distancia de seguridad de 2 metros con las zonas comunes, como son la caja, los probadores…?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid0" name="preg1" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid1" name="preg1" value="No">
                                                <label>No</label>
                                        </div>

                                        <div class="col-sm-12">
                                                <p>Si –) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.</p>
                                               <p>No -) Si no se puede alcanzar la distancia de 2 metros, es necesario aplicar una barrera física que permita reducir a la mitad esa distancia. Hablamos de mamparas o elementos distanciadores físicos, o de no existir esta separación, la inclusión de una pantalla de protección adicional a la mascarilla para el trabajador.</p>
                                        </div>

                                </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de jabón o gel hidroalcohólico para uso de los trabajadores y los clientes?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid2" name="preg2" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid3" name="preg2" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.</p>
                                               <p>No -) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.</p>
                                            </div>

                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Tiene previsto obligar al uso de guantes en su establecimiento?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid4" name="preg3" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid5" name="preg3" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.</p>
                                               <p>No -) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.</p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Tiene previsto obligar al uso de mascarilla a sus clientes en su establecimiento? Recuerde que el uso de mascarilla será obligatorio en la vía pública, en espacios al aire libre y en cualquier espacio cerrado de uso público que se encuentre abierto al público, siempre que no sea posible mantener una distancia de seguridad interpersonal de al menos dos metros.</strong></label>
                                        <div class="col-sm-2">
                                               <input type="radio" id="covid6" name="preg4" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid7" name="preg4" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Sí -) Siempre que en su negocio sea posible una distancia mayor a los dos metros, si usted obliga a sus clientes a portar mascarilla deberá proporcionársela. Mientras que si, por las características del espacio, no son posibles esos dos metros, no es su obligación proporcionarla, aunque recomendamos tener mascarillas desechables a disposición de los clientes.</p>
                                               <p>No -) Usted solo podrá permitir el NO uso de la mascarilla en su negocio, siempre que se pueda garantizar EN TODO MOMENTO la distancia de seguridad mínima de 2 metros. Recomendamos que al menos se recomiende el uso de mascarilla a los clientes.</p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de un protocolo de limpieza con un registro de las veces y los lugares que se han limpiado?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid8" name="preg5" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid9" name="preg5" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.</p>
                                               <p>No -) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.</p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de elementos de visualización con indicaciones para el Covid-19?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid10" name="preg6" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid11" name="preg6" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p>
                                               <p>No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
                                               Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Llevan uniformes sus trabajadores?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid12" name="preg7" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid13" name="preg7" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme</p>
                                               <p>No -) Nada que añadir</p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Disponemos de sistemas de climatización? NOTA: Los ventiladores no son aparatos de climatización, ya que solo mueven el aire que ya está en el interior, y quedan totalmente PROHIBIDOS</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid14" name="preg8" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid15" name="preg8" value="No">
                                                <label>No</label>
                                        </div>
                                         <div class="col-sm-12">
                                                <p>Si -) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.</p>
                                               <p>No -) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.</p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de formación y un plan específico de Prevención de Riesgos Laborales para el Covid-19?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid16" name="preg9" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid17" name="preg9" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</p>
                                               <p>No -) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas. </p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Conocen el protocolo de actuación en caso de sospechar que uno de sus trabajadores o clientes pueda estar infectado por coronavirus?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid18" name="preg10" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid19" name="preg10" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.</p>
                                               <p>No -) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas. </p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Sus instalaciones disponen de más de una puerta de acceso?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid20" name="preg11" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid21" name="preg11" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.
                                                Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.
                                            </p>
                                               <p>No -) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores. </p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone usted de muestras gratuitas o de prueba en su negocio?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid22" name="preg12" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid23" name="preg12" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.
                                            </p>
                                               <p>No -) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas. </p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Existen medidas concretas en los puntos de caja?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid24" name="preg13" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid25" name="preg13" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                Si -) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
    <pre>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.
    2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé
    3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar
    4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</pre>


                                               No -) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
    <pre>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.
    2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé
    3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar
    4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</pre>

                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Está su negocio en el interior de un Centro Comercial?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid26" name="preg14" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid27" name="preg14" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.
                                            </p>
                                               <p>No -) Nada que añadir</p>
                                            </div>
                                    </div>
                                <div class="form-group">
                                        <label class="col-sm-12 col-sm-12 control-label"><strong>¿Pertenece su negocio a uno de estos sectores?</strong></label>
                                        <div class="col-sm-12">
                                          <div class="checkbox d-inline-block mr-3">
                                              <label for="28">Alimentación</label>
                                              SI<input type="radio" name="alimentacion" value="Si" class="checkbox-input" id="covid28"> | 
                                              NO<input type="radio" name="alimentacion" value="no" checked class="checkbox-input" id="covid29">
                                           </div>
                                           <div class="checkbox d-inline-block mr-3">
                                              <label for="29">Textil</label>
                                              SI<input type="radio" name="textil" value="Si" class="checkbox-input" id="covid30"> | 
                                              NO<input type="radio" name="textil" value="no" checked class="checkbox-input" id="covid31">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="30">Calzado, Joyería</label>
                                              <input type="radio" name="calzado" value="Si" class="checkbox-input" id="covid32"> | 
                                              <input type="radio" name="calzado" value="no" checked class="checkbox-input" id="covid33">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="31">Relojería o similares</label>
                                              SI<input type="radio" name="relojeria" value="Si" class="checkbox-input" id="covid34"> | 
                                              NO<input type="radio" name="relojeria" value="no" checked class="checkbox-input" id="covid35">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="32">Tecnología – Telefonía – Cultura</label>
                                              SI<input type="radio" name="tecnologia" value="Si" class="checkbox-input" id="covid36"> | 
                                              NO<input type="radio" name="tecnologia" value="no" checked class="checkbox-input" id="covid37">
                                           </div>
                                           <div class="checkbox d-inline-block mr-3">
                                              <label for="33">Muebles</label>
                                              SI<input type="radio" name="muebles" value="Si" class="checkbox-input" id="covid38"> | 
                                              NO<input type="radio" name="muebles" value="no" checked class="checkbox-input" id="covid39">
                                           </div>
                                           <div class="checkbox d-inline-block mr-3">
                                              <label for="34">Cerámica – Baños – Cocina – Reformas en General</label>
                                              SI<input type="radio" name="ceramica" value="Si" class="checkbox-input" id="covid40"> | 
                                              NO<input type="radio" name="ceramica" value="no" checked class="checkbox-input" id="covid41">
                                              
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="35">Sombreros o Tocados</label>
                                              SI<input type="radio" name="sombreros" value="Si" class="checkbox-input" id="covid42"> | 
                                              NO<input type="radio" name="sombreros" value="no" checked class="checkbox-input" id="covid43">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <input type="radio" name="gasolinera" value="Si" class="checkbox-input" id="covid44"> | 
                                              SI<input type="radio" name="gasolinera" value="no" checked class="checkbox-input" id="covid45">
                                              NO<label for="36">Gasolineras</label>
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="37">Puestos de Venta al Público</label>
                                              SI<input type="radio" name="ventaPublico" value="Si" class="checkbox-input" id="covid46"> | 
                                              NO<input type="radio" name="ventaPublico" value="no" checked class="checkbox-input" id="covid47">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="38">Vehículos de Transporte y/o Venta Ambulante</label>
                                              SI<input type="radio" name="vehiculos" value="Si" class="checkbox-input" id="covid48"> | 
                                              NO<input type="radio" name="vehiculos" value="no" checked class="checkbox-input" id="covid49">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="39">Salones de Peluquería</label>
                                              SI<input type="radio" name="peluqueria" value="Si" class="checkbox-input" id="covid50"> | 
                                              NO<input type="radio" name="peluqueria" value="no" checked class="checkbox-input" id="covid51">
                                           </div>

                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="40">Centros de asistencia, Terapia y Logopedia</label>
                                              SI<input type="radio" name="asistencia" value="Si" class="checkbox-input" id="covid52"> | 
                                              NO<input type="radio" name="asistencia" value="no" checked class="checkbox-input" id="covid53">
                                           </div>
                                        </div>
                                        <br>
                                        <div align="right">
                                            <button class="btn btn-success" type="button" onclick="enviarInformacion('covid','servicios')">Enviar Cuestionario</button>
                                        </div>
                                    </div>
                            </form>
                            <form class="forms-sample" id="covidform" style="display:none">
                                    <h4 style="text-align:center">Turismo</h4>
                                <br>
                                     <div class="form-group row">
                                         <div class="form-group row">
                                <?php
                                    echo '<select class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                                    <div class="col-md-6"><input type="text" class="form-control" id="covidRepa" placeholder="Responsable" name="respA"></div>
                                    <div class="col-md-6"><input type="text" class="form-control" id="covidRepAusa" placeholder="Responsable Ausente" name="respAusA"></div>
                                </div>
                                    <input type="hidden" name="tipo" value="turismo">
                                <hr>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Es posible mantener una distancia de seguridad de 2 metros con las zonas comunes, como son la caja, los probadores…?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid0a" name="preg1" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid1a" name="preg1" value="No">
                                                <label>No</label>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                                <p>Si –) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.</p>
                                               <p>No -) Si no se puede alcanzar la distancia de 2 metros, es necesario aplicar una barrera física que permita reducir a la mitad esa distancia. Hablamos de mamparas o elementos distanciadores físicos, o de no existir esta separación, la inclusión de una pantalla de protección adicional a la mascarilla para el trabajador.</p>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de jabón o gel hidroalcohólico para uso de los trabajadores y los clientes?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid2a" name="preg2" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid3a" name="preg2" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.</p>
                                               <p>No -) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.</p>
                                            </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Tiene previsto obligar al uso de guantes en su establecimiento?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid4a" name="preg3" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid5a" name="preg3" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.</p>
                                               <p>No -) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.</p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Tiene previsto obligar al uso de mascarilla a sus clientes en su establecimiento? Recuerde que el uso de mascarilla es obligatorio para los trabajadores y usted debe ser responsable de proporcionar mascarillas homologadas a cada trabajador.?</strong></label>
                                        <div class="col-sm-2">
                                               <input type="radio" id="covid6a" name="preg4" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid7a" name="preg4" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) El uso de mascarilla no es obligatorio para los clientes. Pero recuerde que si obliga al uso de mascarilla debe ser responsable de proporcionar mascarilla desechable a cada cliente que acceda a su comercio y esté obligado a llevarla puesta.</p>
                                               <p>No -) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.</p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de un protocolo de limpieza con un registro de las veces y los lugares que se han limpiado?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid8a" name="preg5" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid9a" name="preg5" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.</p>
                                               <p>No -) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.</p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de elementos de visualización con indicaciones para el Covid-19?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid10a" name="preg6" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid11a" name="preg6" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p>
                                               <p>No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
                                               Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Llevan uniformes sus trabajadores?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid12a" name="preg7" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid13a" name="preg7" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme</p>
                                               <p>No -) Nada que añadir</p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Disponemos de sistemas de climatización? NOTA: Los ventiladores no son aparatos de climatización, ya que solo mueven el aire que ya está en el interior, y quedan totalmente PROHIBIDOS</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid14a" name="preg8" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid15a" name="preg8" value="No">
                                                <label>No</label>
                                        </div>
                                         <div class="col-sm-12">
                                                <p>Si -) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.</p>
                                               <p>No -) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.</p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone de formación y un plan específico de Prevención de Riesgos Laborales para el Covid-19?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid16a" name="preg9" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid17a" name="preg9" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</p>
                                               <p>No -) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas. </p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Conocen el protocolo de actuación en caso de sospechar que uno de sus trabajadores o clientes pueda estar infectado por coronavirus?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid18a" name="preg10" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid19a" name="preg10" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.</p>
                                               <p>No -) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas. </p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Sus instalaciones disponen de más de una puerta de acceso?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid20a" name="preg11" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid21a" name="preg11" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.
                                                Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.
                                            </p>
                                               <p>No -) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores. </p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Dispone usted de muestras gratuitas o de prueba en su negocio?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid22a" name="preg12" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid23a" name="preg12" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.
                                            </p>
                                               <p>No -) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas. </p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Existen medidas concretas en los puntos de caja?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid24a" name="preg13" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid25a" name="preg13" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                Si -) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
<pre>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.
2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé
3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar
4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</pre>

                                            
                                               No -) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
<pre>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.
2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé
3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar
4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</pre>

                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Está su negocio en el interior de un Centro Comercial?</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="covid26a" name="preg14" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="covid27a" name="preg14" value="No">
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <p>Si -) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.
                                            </p>
                                               <p>No -) Nada que añadir</p>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 col-sm-12 control-label"><strong>¿Pertenece su negocio a uno de estos sectores?</strong></label>
                                        <div class="col-sm-12">
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="28a">Alojamiento Turístico</label>
                                                SI <input type="radio" name="alojamiento" value="Si" class="checkbox-input" id="covid28a"> |
                                                 NO<input type="radio" name="alojamiento" value="no" checked class="checkbox-input" id="covid29a">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="29a">Restauración</label>
                                               SI <input type="radio" name="restauracion" value="Si" class="checkbox-input" id="covid30a"> |
                                                NO<input type="radio" name="restauracion" value="no" checked class="checkbox-input" id="covid31a">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                              <label for="30a">Actividades Turísticas</label>
                                                SI <input type="radio" name="turistico" value="Si" class="checkbox-input" id="covid32a"> |
                                                NO<input type="radio" name="turistico" value="no" checked class="checkbox-input" id="covid33a">
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                                <label for="31a">Puestos de Comida</label>
                                              SI <input type="radio" name="comida" value="Si" class="checkbox-input" id="covid34a"> |
                                              NO<input type="radio" name="comida" value="no" checked class="checkbox-input" id="covid35a">
                                              
                                           </div>
                                            <div class="checkbox d-inline-block mr-3">
                                                <label for="32a">Vehículos de Comida Ambulante</label>
                                              SI <input type="radio" name="comidaAmbulante" value="Si" class="checkbox-input" id="covid36a"> |
                                              NO<input type="radio" name="comidaAmbulante" value="no" checked class="checkbox-input" id="covid37a">
                                              
                                           </div>
                                        </div>
                                        <br>
                                        <div align="right">
                                            <button class="btn btn-success" type="button" onclick="enviarInformacion('covid','turismo')">Enviar Cuestionario</button>
                                        </div>
                                    </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Modal ALERGENOS -->
    <div class="modal fade" id="modalalergenos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnalergenos" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <br>
                        <input type="hidden" id="tecnicoIdProdAlergenos">
                        <?php
                        echo '<select id="tecnicosSelect" class="form-control" name="tecnicosSelect" onchange="insertaTecnicoHecho(this.value)">';
                            echo '<option>Seleccione un técnico</option>';
                            for($i = 0; $i < count($tecnicos); $i++){
                                
                                    echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                
                            }
                        echo '</select>';
                    ?>
                    <br>
                        <textarea name="" id="" class="form-control" cols="50" rows="3" disabled>
 Para realizar este producto debemos solicitar una copia de la carta del establecimiento.
 Puede enviarle un correo electrónico solicitándosela haciendo click en el siguiente botón.
                       </textarea>
                        <hr>
                       <button type="button" id="btnEmailCarta" class="btn btn-info btn-block">Pedir carta al establecimiento por correo electrónico</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal LOPD_PLATAF -->
    <div class="modal fade" id="modallopd_plataf" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnalergenos" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <br>
                        <input type="hidden" id="tecnicoIdProdLopdPlataf">
                        <?php
                        echo '<select id="tecnicosSelect" class="form-control" name="tecnicosSelect" onchange="insertaTecnicoHecho(this.value)">';
                        echo '<option>Seleccione un técnico</option>';
                            for($i = 0; $i < count($tecnicos); $i++){
                                
                                    echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                
                            }
                        echo '</select>';
                    ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal EXPLICACION -->
    <div class="modal fade" id="modalexplicacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnalergenos" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <br>
                        <input type="hidden" id="tecnicoIdProdExplicacion">
                        <?php
                        echo '<select id="tecnicosSelect" class="form-control" name="tecnicosSelect" onchange="insertaTecnicoHecho(this.value)">';
                        echo '<option>Seleccione un técnico</option>';
                            for($i = 0; $i < count($tecnicos); $i++){
                                
                                    echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                
                            }
                        echo '</select>';
                    ?>
                    
                </div>
            </div>
        </div>
        </div></div>
    <!-- Modal ACOSO -->
    <div class="modal fade" id="modalacoso" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnacoso" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formacoso">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosacoso" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-sm-12 control-label"><strong>¿Quién es el Cargo o Departamento encargado de informar (y en su caso de formar) a los trabajadores de la organización en materia de igualdad? </strong></label>
                                        <div class="col-sm-12">
                                                <input class="form-control" type="text" id="acoso1" name="acoso1" value="Indicar solo el cargo o departamento.">
                          
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-sm-12 control-label"><strong>¿Cómo van a compartir o difundir el protocolo con los trabajadores? </strong></label>
                                        <div class="col-sm-12">
                                                <input class="form-control" type="text" id="acoso2" name="acoso2" value="Enviándolo por correo, subiéndolo en la intranet, en mano, en el tablón de anuncios…">
                                                
                                       
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Quién es la persona o personas responsables de solucionar o intermediar en los casos de acoso que se denuncien en la organización? </strong></label>
                                        <div class="col-sm-2">
                                                <input type="text" id="acoso3" name="acoso3" placeholder="Indicar departamentos y cargos.">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>¿Cuál es el canal que han habilitado para recibir las denuncias?</strong></label>
                                        <div class="col-sm-2">
                                               <input  type="text" id="acoso4" name="acoso4" placeholder="Puede ser un correo electrónico o un canal interno">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-sm-12 control-label"><strong>¿Cuál es el Convenio colectivo de la organización?</strong></label>
                                        <div class="col-sm-12">
                                                  <input class="form-control" type="text" id="acoso5" name="acoso5" value="">
                                       
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-sm-12 control-label"><strong>¿Tienen más de 50 empleados? En caso de que así sea, ¿disponen de Plan de Igualdad y Plan de igualdad de las personas LGTBI? ¿Dispone de Canal de Denuncias?</strong></label>
                                        <div class="col-sm-12">
                                                <input class="form-control" type="text" id="acoso6" name="acoso6" value="">
                                        </div>
                                    </div>
                                    <br>
                                <div class="form-group row">
                                        <div class="btn-group pull-right">
                                            <button class="btn btn-success" type="button" onclick="enviarInformacion('acoso')">Enviar Cuestionario</button>
                                        </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal SEGURIDAD -->
    <div class="modal fade" id="modalseguridad" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnacoso" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                       <form class="forms-sample" id="formseguridad">
                           <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosseguridad" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="nomLocal" id="nomLocalSeg" placeholder="Nombre del local" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="numTrabsSeg" id="numTrabsSeg" placeholder="Trabajadores" value="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Alérgenos</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg0" name="preg1" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg1" name="preg1" value="No" checked>
                                                <label>No</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Planes simplificados de higiene</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg2" name="preg2" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg3" name="preg2" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan de control de temperaturas</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg4" name="preg3" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg5" name="preg3" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan limpieza y desinfección</strong></label>
                                        <div class="col-sm-2">
                                               <input type="radio" id="seg6" name="preg4" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg7" name="preg4" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan control de plagas</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg8" name="preg5" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg9" name="preg5" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan de formación de manipuladores</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg10" name="preg6" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg11" name="preg6" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan trazabilidad</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg12" name="preg7" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg13" name="preg7" value="No" checked>
                                                <label>No</label>
                                        </div>
                                       
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan eliminación de residuos</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg14" name="preg8" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg15" name="preg8" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan aguas</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg16" name="preg9" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg17" name="preg9" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan de mantenimiento de instalaciones</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg18" name="preg10" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg19" name="preg10" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Compromiso con la cultura de seguridad alimentaria Nuevo Reglamento (UE) 2021/382 de 03 de marzo de 2021</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg20" name="preg11" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg21" name="preg11" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                    </div>
                                        <br>
                                <div class="form-group row">
                                        <div class="btn-group pull-right">
                                            <button class="btn btn-success" type="button" onclick="enviarInformacion('seguridad')">Enviar Cuestionario</button>
                                        </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Modal COMPLIANCE -->
    <div class="modal fade" id="modalcompliance" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btncompliance" class="btn btn-danger btn-block">Cancelar producto</button>';
                                }
                        ?>
                        <form class="forms-sample" id="formcompliance">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicoscompliance" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                          <details>
                          <summary>Organigrama - Haga click aquí para verlo y rellenarlo</summary>
                          <div id="organigrama" align="center" style="text-align=center;justify-content:center">
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        Primer Escalón
                                        <input type="text" class="form-control" name="orga1" id="orga1">
                                    </div>
                                    <div class="col-sm-2">
                                        Primer Escalón
                                        <input type="text" class="form-control" name="orga2" id="orga2">
                                    </div>
                                    <div class="col-sm-2">
                                        Primer Escalón
                                        <input type="text" class="form-control" name="orga3" id="orga3">
                                    </div>
                                    <div class="col-sm-2">
                                        Primer Escalón
                                        <input type="text" class="form-control" name="orga4" id="orga4">
                                    </div>
                                    <div class="col-sm-3">
                                        Primer Escalón
                                        <input type="text" class="form-control" name="orga5" id="orga5">
                                    </div>  
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        Segundo Escalón
                                        <input type="text" class="form-control" name="orga6" id="orga6">
                                    </div>
                                    <div class="col-sm-2">
                                        Segundo Escalón
                                        <input type="text" class="form-control" name="orga7" id="orga7">
                                    </div>
                                    <div class="col-sm-2">
                                        Segundo Escalón
                                        <input type="text" class="form-control" name="orga8" id="orga8">
                                    </div>
                                    <div class="col-sm-2">
                                        Segundo Escalón
                                        <input type="text" class="form-control" name="orga9" id="orga9">
                                    </div>
                                    <div class="col-sm-3">
                                        Segundo Escalón
                                        <input type="text" class="form-control" name="orga10" id="orga10">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        Tercer Escalón
                                        <input type="text" class="form-control" name="orga11" id="orga11">
                                    </div>
                                    <div class="col-sm-2">
                                        Tercer Escalón
                                        <input type="text" class="form-control" name="orga12" id="orga12">
                                    </div>
                                    <div class="col-sm-2">
                                        Tercer Escalón
                                        <input type="text" class="form-control" name="orga13" id="orga13">
                                    </div>
                                    <div class="col-sm-2">
                                        Tercer Escalón
                                        <input type="text" class="form-control" name="orga14" id="orga14">
                                    </div>
                                    <div class="col-sm-3">
                                        Tercer Escalón
                                        <input type="text" class="form-control" name="orga15" id="orga15">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        Cuarto Escalón
                                        <input type="text" class="form-control" name="orga16" id="orga16">
                                    </div>
                                    <div class="col-sm-2">
                                        Cuarto Escalón
                                        <input type="text" class="form-control" name="orga17" id="orga17">
                                    </div>
                                    <div class="col-sm-2">
                                        Cuarto Escalón
                                        <input type="text" class="form-control" name="orga18" id="orga18">
                                    </div>
                                    <div class="col-sm-2">
                                        Cuarto Escalón
                                        <input type="text" class="form-control" name="orga19" id="orga19">
                                    </div>
                                    <div class="col-sm-3">
                                        Cuarto Escalón
                                        <input type="text" class="form-control" name="orga20" id="orga20">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        Quinto Escalón
                                        <input type="text" class="form-control" name="orga21" id="orga21">
                                    </div>
                                    <div class="col-sm-2">
                                        Quinto Escalón
                                        <input type="text" class="form-control" name="orga22" id="orga22">
                                    </div>
                                    <div class="col-sm-2">
                                        Quinto Escalón
                                        <input type="text" class="form-control" name="orga23" id="orga23">
                                    </div>
                                    <div class="col-sm-2">
                                        Quinto Escalón
                                        <input type="text" class="form-control" name="orga24" id="orga24">
                                    </div>
                                    <div class="col-sm-3">
                                        Quinto Escalón
                                        <input type="text" class="form-control" name="orga25" id="orga25">
                                    </div>
                                </div>
                            </div>
                            </details>
                          <hr>
										              <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h4>DELITO : <h6>Daños informáticos, y revelación de secretos</h6></h4>
                                                            <small>¿La empresa trabaja con servidores propios? 
Si - ¿Conoce y emplea los mecanismos de seguridad reflejados en la LSSI?
No - ¿La empresa contratada proporciona garantías de cumplimiento normativo?
¿La empresa puede acceder a servidores públicos? 
¿Se disponen de un sistema de copias de seguridad del sistema informático de la empresa? 
¿Se disponen de contratos o firmas de cláusulas de confidencialidad con los Profesionales y/o Empleados que incluyan la información obtenida sobre terceras personas?  </small>
                                                                <textarea class="form-control" id="cmp1" rows="3" name="riesgo1" placeholder=""></textarea>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <h4>DELITO : <h6>Prevención de Blanqueo de Capitales y de financiación del terrorismo</h6></h4>
                                                            <small>¿Su empresa se encuadra en algunos de los siguientes sectores o desarrolla alguna de las actividades de riesgo descritas  en la Ley 10/2010 de PBC y financiación del terrorismo?:
Considerando lo anterior ¿su empresa tiene más de 10 trabajadores y más de 2 millones de facturación anual?
¿Los trabajadores son conocedores de la imposibilidad de realizar operaciones en efectivo de  más de 999 €? 
</small>
                                                                <textarea class="form-control" id="cmp2" rows="3" name="riesgo2" placeholder=""></textarea>
                                                        </div>

                                                    </div>
										
										<div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <h4>DELITO : <h6>Estafas e Insolvencias punibles</h6></h4>
                                                            <small>¿Se lleva un correcto mantenimiento de libros, registro,  asientos contables que, reflejen de forma precisa y fiel las transacciones y disposiciones de activos de la Empresa?
¿Se lleva un control continuo de la documentación anteriormente indicada?
¿Existen registro    de    las    operaciones,   contratos    y negocios jurídicos cuyo importe sea superior a 1.000euros? 
</small>
                                                                <textarea class="form-control" id="cmp3" rows="3" name="riesgo3" placeholder=""></textarea>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h4>DELITO : <h6>Infracción de derechos de Propiedad Intelectual, Industrial, el mercado y los consumidores</h6></h4>
                                                            <small>¿Posee la empresa medios para interferir en el mercado? 
¿Los programas de gestión y software empleados en la empresa disponen de las licencias correspondientes? 
¿La empresa dispone de medidas que garanticen la propiedad intelectual de terceros (descargas de películas, música, etc.)? 
¿Se tienen en cuenta medidas de protección para los consumidores (hija de reclamaciones, arbitrajes…)?  
En caso de que se empleen marcas comerciales, logotipos, etc. ¿se dispone de las autorizaciones correspondientes evitando de esta manera problemas con otras empresas? 
</small>
                                                                <textarea class="form-control" id="cmp4" rows="3" name="riesgo4" placeholder=""></textarea>
                                                        </div>

                                                    </div>
										<div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <h4>DELITO : <h6>Construcción, edificación o urbanización ilegal</h6></h4>
                                                            <small>¿La empresa tiene actividad ligada a la construcción? Si la respuesta es NO, no continuar
¿Se revisa dispone de arquitectos cualificados para el estudio de la normativa de suelo y urbanización?
¿Se revisa dispone de apoyo legal cualificado para el estudio de la normativa de suelo y urbanización?
</small>
                                                                <textarea class="form-control" id="cmp5" rows="3" name="riesgo5" placeholder=""></textarea>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h4>DELITO : <h6>Defraudaciones tributarias  y  a la Seguridad Social</h6></h4>
                                                            <small>¿Se lleva de un sistema de libros, cuentas y registros que reflejen exactamente toda operación y disposición de efectivo en la empresa? 
¿Lleva personalmente la relación laboral de su empresa con Hacienda y SS o a través de gestor?
¿La empresa realiza auditorías de estados financieros anualmente?
</small>
                                                                <textarea class="form-control" id="cmp6" rows="3" name="riesgo6" placeholder=""></textarea>
                                                        </div>

                                                    </div>
                                                      <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h4>DELITO : <h6>Cohecho, Tráfico de Influencias y corrupción del funcionario extranjero</h6></h4>
                                                            <small>¿Se realizan trabajos por y para la administración en España o en el extranjero? En caso afirmativo, ¿Han resultado sospechosos de tratos de favor?
¿Se permiten los regalos a ejecutivos de la empresa? ¿Hay un límite en los importes?</small>
                                                                <textarea class="form-control" id="cmp7" rows="3" name="riesgo7" placeholder=""></textarea>
                                                        </div>
                                                        

                                                    </div>
										 <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h4>DELITO : <h6>Contra los derechos  de los Trabajadores y ciudadanos extranjeros</h6></h4>
                                                            <small>¿Todos los trabajadores de la empresa (nacionales y extranjeros) están contratados conforme la legislación vigente? 
¿Se dispone de un Plan de Prevención de Riesgos Laborales que garantice la identificación y evaluación de los todos los riesgos asociados a cada una de la actividades de la empresa así como la entrega de EPIS?
¿La maquinaría de la empresa cumple con las exigencias legales en cuanto a medios de protección (autorizaciones OCA, Marcado CE, etc.)?
¿Dispone de un registro retributivo?
¿Existe más de un centro de trabajo? Indicar comunidades y países en caso de ser en el extranjero</small>
                                                                <textarea class="form-control" id="cmp8" rows="3" name="riesgo8" placeholder=""></textarea>
                                                        </div>
                                                        

                                                    </div>
                                                      <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h4>DELITO : <h6>Contra la Protección de Datos Personales</h6></h4>
                                                            <small>¿Se lleva un control de los datos personales que maneja la empresa (copias de seguridad, acceso archivos, etc.)?
¿La empresa ha informado a terceros de sus derechos en cuanto a datos personales se refiere (clientes, proveedores, trabajadores, etc.)?
¿La empresa cuenta con la figura de un DPO?
¿Se realizan auditorías internas para garantizar el correcto funcionamiento de la política de datos personales que maneja la empresa?</small>
                                                                <textarea class="form-control" id="cmp9" rows="3" name="riesgo9" placeholder=""></textarea>
                                                        </div>
                                                        

                                                    </div>
										<div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h4>DELITO : <h6>Contra el Medioambiente</h6></h4>
                                                            <small>¿Los residuos que se generan en la empresa son gestionados por empresas/gestores autorizados?
¿La empresa dispone de Licencia Ambiental/Actividad?
¿Se dispone de Seguro de Responsabilidad Ambiental?</small>
                                                                <textarea class="form-control" id="cmp10" rows="3" name="riesgo10" placeholder=""></textarea>
                                                        </div>
                                                        

                                                    </div>
										<div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h4>DELITO : <h6>Otros Delitos</h6></h4>
                                                            <small>¿La empresa maneja órganos humanos que puedan verse afectados por un mercado ilegal?
¿La empresa puede verse implicada en casos de corrupción relacionados con el tráfico de seres humanos o explotación de personas en contra de su voluntad? 
En caso de manejar material explosivo ¿la empresa dispone de los medios e instalaciones de seguridad legales para su manejo y almacenamiento?
¿La empresa puede verse implicada en casos de corrupción relacionados con el tráfico de drogas o contrabando?
¿La empresa puede verse implicada en casos de terrorismo o recolectar fondos con fines terroristas?
¿La empresa puede verse implicada en casos de falsificación de dinero, tarjetas de crédito, cheques, etc.?</small>
                                                                <textarea class="form-control" id="cmp11" rows="3" name="riesgo11" placeholder=""></textarea>
                                                        </div>
                                                        

                                                    </div>
                            <br>
                            <div class="form-group">
                                <div class="col-md-12"><input type="text" id="trab" class="form-control" name="trab" placeholder="Número de trabajadores"></div>
                                <br>
                            <div align="right">
                                   <button class="btn btn-success" onclick="enviarInformacion('compliance')" type="button">Enviar Cuestionario</button>
                            </div>
                         </div>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Seg_alim -->
    <div class="modal fade" id="modalseg_alim" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnseg_alim" class="btn btn-danger btn-block">Cancelar producto</button>';
                                }
                        ?>
                            <form class="forms-sample" id="formseg_alim">
                                <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosseg_alim" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="nomLocal" id="seg_alimnomLocal" placeholder="Nombre del local">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="number" class="form-control" name="numTrabs" id="seg_alimnumTrabs" placeholder="Trabajadores">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Alérgenos</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim0" name="preg1" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim1" name="preg1" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion1" name="preg1a" rows="3">Acciones Correctivas</textarea>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Planes simplificados de higiene</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim2" name="preg2" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim3" name="preg2" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion2" name="preg2a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan de control de temperaturas</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim4" name="preg3" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim5" name="preg3" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion3" name="preg3a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan limpieza y desinfección</strong></label>
                                        <div class="col-sm-2">
                                               <input type="radio" id="seg_alim6" name="preg4" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim7" name="preg4" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion4" name="preg4a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan control de plagas</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim8" name="preg5" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim9" name="preg5" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion5" name="preg5a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan de formación de manipuladores</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim10" name="preg6" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim11" name="preg6" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion6" name="preg6a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan trazabilidad</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim12" name="preg7" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim13" name="preg7" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion7" name="preg7a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan eliminación de residuos</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim14" name="preg8" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim15" name="preg8" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion8" name="preg8a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan aguas</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim16" name="preg9" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim17" name="preg9" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion9" name="preg9a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Plan de mantenimiento de instalaciones</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim18" name="preg10" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim19" name="preg10" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion10" name="preg10a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-10 col-sm-10 control-label"><strong>Compromiso con la cultura de seguridad alimentaria Nuevo Reglamento (UE) 2021/382 de 03 de marzo de 2021</strong></label>
                                        <div class="col-sm-2">
                                                <input type="radio" id="seg_alim20" name="preg11" value="Si">
                                                <label>Si</label>
                                                <input type="radio" id="seg_alim21" name="preg11" value="No" checked>
                                                <label>No</label>
                                        </div>
                                        <div class="col-sm-12">
                                                <textarea style="display:none" class="form-control" id="seg_alimaccion11" name="preg11a" rows="3">Acciones Correctivas</textarea>
                                        </div>
                                    </div>
                                        <br>
                                <div class="form-group row">
                                        <div class="btn-group pull-right">
                                            <button class="btn btn-success" onclick="enviarInformacion('seg_alim')" type="button">Enviar Cuestionario</button>
                                        </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal APPCC -->
    <div class="modal fade" id="modalappcc" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnappcc" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <form class="forms-sample" id="formappcc">
                            <div class="form-group row">
                                <?php
                                    echo '<select id="tecnicosappcc" class="form-control" name="tecnicosForm">';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        if($tecnicos[$i]['nombre'] == $dataEmpleado[0]['nombre']){
                                            echo "<option selected value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }else{
                                            echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
					       <div id="wizard">
                                <h6>Temperaturas</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-2">Vitrina frío</div>
                                        <div class="col-md-1"><select name="appcc1" id="appcc1"><option value="si">Si</option><option value="no">No</option></select></div>
                                        <div class="col-md-9"><input type="text" size="85" placeholder="Añade alimentos" name="appcc55" id="appcc55"></div>
                                        <div class="col-md-2">Vitrina congelados</div>
                                        <div class="col-md-1"><select name="appcc2" id="appcc2"><option>Si</option><option value="no">No</option></select></div>
                                        <div class="col-md-9"><input size="85" type="text" placeholder="Añade alimentos" name="appcc56" id="appcc56"></div>
                                        
                                        <div class="col-md-2">Cámara frigorífica</div>
                                        <div class="col-md-1"><select name="appcc3" id="appcc3"><option value="si">Si</option><option value="no">No</option></select></div>
                                        <div class="col-md-9"><input size="85" type="text"  placeholder="Añade alimentos" name="appcc57" id="appcc57"></div>
                                        <div class="col-md-2">Botellero</div>
                                        <div class="col-md-1"><select name="appcc4" id="appcc4"><option value="si">Si</option><option value="no">No</option></select></div>
                                        <div class="col-md-9"><input size="85" type="text"  placeholder="Añade alimentos" name="appcc58" id="appcc58"></div>
                                        
                                        <div class="col-md-2">Cámara congelados</div>
                                        <div class="col-md-1"><select name="appcc5" id="appcc5"><option value="si">Si</option><option value="no">No</option></select></div>
                                        <div class="col-md-9"><input size="85" type="text" placeholder="Añade alimentos" name="appcc59" id="appcc59"></div>
                                        <div class="col-md-2">Mesa fria</div>
                                        <div class="col-md-1"><select name="appcc6" id="appcc6"><option value="si">Si</option><option value="no">No</option></select></div>
                                        <div class="col-md-9"><input size="85" type="text" placeholder="Añade alimentos" name="appcc60" id="appcc60"></div>
                                        
                                        <div class="col-md-2">Mesa caliente</div>
                                        <div class="col-md-1"><select name="appcc7" id="appcc7"><option value="si">Si</option><option value="no">No</option></select></div>
                                        <div class="col-md-9"><input size="85" type="text" placeholder="Añade alimentos" name="appcc61" id="appcc61"></div>
                                        <div class="col-md-1">Otro</div>
                                        <div class="col-md-2"><input name="appcc8" id="appcc8" type="text"></div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-5">
                                            <p>¿Cocinan alimentos en el establecimiento?</p>
                                        </div>
                                        <div class="col-md-1">
                                            <select name="appcc9" id="appcc9"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                        <div class="col-md-5">
                                            <p>¿Sirven alimentos con algún tratamiento de frío?</p>
                                        </div>
                                        <div class="col-md-1">
                                            <select name="appcc10" id="appcc10"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                        
                                        <div class="col-md-5">
                                            <p>¿Calientan alimentos que estuvieran ya enfriados?</p>
                                        </div>
                                        <div class="col-md-1">
                                            <select name="appcc11" id="appcc11"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                        <div class="col-md-5">
                                            <p>¿Tiene sala de elaboración de fríos?</p>
                                        </div>
                                        <div class="col-md-1">
                                            <select name="appcc12" id="appcc12"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                        <div class="col-md-11">
                                            <p>¿Presenta platos preparados a base de pescados que van a ser consumidos crudos?</p>
                                        </div>
                                        <div class="col-md-1">
                                            <select name="appcc13" id="appcc13"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-4">Responsable temperatura</div>
                                        <div class="col-md-2"><input name="appcc14" style="margin-left:-45px" type="text" id="appcc14"></div>
                                        <div class="col-md-4">Suplente responsable</div>
                                        <div class="col-md-2"><input name="appcc15" type="text" id="appcc15"></div>
                                    </div>
                                </section>
                                <h6>Limpieza</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-5">
                                            Platos, cubiertos, vasos, etc... (que no son de un solo uso)
                                        </div>
                                        <div class="col-md-1">
                                            <select class="form-control" name="appcc16" id="appcc16"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                        <div class="col-md-5">
                                            ¿Utiliza lavavajillas?
                                        </div>
                                        <div class="col-md-1">
                                            <select class="form-control" name="appcc17" id="appcc17"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            Máquinas (marque las máquinas disponibles en el centro)
                                        </div>
                                        <div class="col-md-1">
                                            <select class="form-control" name="appcc18" id="appcc18"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                        <div class="col-md-5">
                                            <select class="selectpicker" data-width="100%"  id="appcc19" multiple title="Seleccione las máquinas">
                                              <option selected value="batidora">Batidoras</option>
                                              <option selected value="picadora">Picadoras</option>
                                              <option value="cortadora">Cortadoras</option>
                                              <option value="otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            Personal de limpieza máquinas
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text"  name="appcc19" id="appcc19" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            ¿Todas poseen su ficha de datos?
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                             <select class="form-control"  name="appcc20" id="appcc20"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="Explique procedimiento limpieza/desinfección máquinas">
                                              <option value="Desconexion del aparato">Desconexión del aparato</option>
                                              <option selected value="Eliminación previa de la suciedad mas visible">Eliminación previa de la suciedad mas visible</option>
                                              <option selected value="Enjuage previo con agua caliente">Enjuage previo con agua caliente</option>
                                              <option selected value="Aplicar detergente o desengrasante">Aplicar detergente o desengrasante</option>
                                              <option selected value="Aclarado con agua">Aclarado con agua</option>
                                              <option selected value="Aplicación del desinfectante">Aplicación del desinfectante</option>
                                              <option value="Aclarado final">Aclarado final</option>
                                              <option selected value="Secado">Secado</option>
                                              <option value="Otros">Otros</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%"  multiple title="Seleccione frecuencia limpieza de máquinas">
                                              <option selected value="Después de cada uso">Después de cada uso</option>
                                              <option selected value="Diaria">Diaria</option>
                                              <option selected value="Cuando sea necesario">Cuando sea necesario</option>
                                              <option value="Semanal">Semanal</option>
                                              <option value="Mensual">Mensual</option>
                                              <option value="Bimensual">Bimensual</option>
                                              <option value="Trimestral">Trimestral</option>
                                              <option value="Semestral">Semestral</option>
                                              <option value="Anual">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            Indique el personal encargado de la limpieza de utensilios
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <input type="text" id="appcc21" name="appcc21" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" id="appcc22" name="appcc22" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" id="appcc23" name="appcc23" class="form-control" placeholder="Marca">
                                        </div>
                                        <div class="col-md-4">
                                            ¿Tienen la ficha de datos de seguridad?
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <select class="form-control" name="appcc24" id="appcc24"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                        <div class="col-md-12">
                                        <select class="selectpicker " data-width="100%" multiple title="Seleccione frecuencia utensilios">
                                              <option selected value="Después de cada uso">Después de cada uso</option>
                                              <option selected value="Diaria">Diaria</option>
                                              <option selected value="Cuando sea necesario">Cuando sea necesario</option>
                                              <option value="Semanal">Semanal</option>
                                              <option value="Mensual">Mensual</option>
                                              <option value="Bimensual">Bimensual</option>
                                              <option value="Bimensual">Trimestral</option>
                                              <option value="Semestral">Semestral</option>
                                              <option value="Anual">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            Personal limpieza superfícies de trabajo
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" id="appcc25" name="appcc25" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            ¿Dispone ficha de seguridad?
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <select class="form-control" name="appcc26" id="appcc26"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-12 input-group-sm">
                                            <select class="selectpicker " data-width="100%"  multiple title="Explique procedimiento limpieza/desinfección de superfícies de trabajo">
                                              <option value="Desconexión del aparato">Desconexión del aparato</option>
                                              <option selected value="Eliminación previa de la suciedad mas visible">Eliminación previa de la suciedad mas visible</option>
                                              <option selected value="Enjuage previo con agua caliente">Enjuage previo con agua caliente</option>
                                              <option selected value="otros">Aplicar detergente o desengrasante</option>
                                              <option selected value="Aplicar detergente o desengrasante">Aclarado con agua</option>
                                              <option selected value="Aplicación del desinfectante">Aplicación del desinfectante</option>
                                              <option value="Aclarado final">Aclarado final</option>
                                              <option selected value="Secado">Secado</option>
                                              <option value="Otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            Personal limpieza de cámaras o vitrinas
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" id="appcc27" name="appcc27" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            ¿Dispone ficha de seguridad?
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <select class="form-control" name="appcc28" id="appcc28"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%"  multiple title="Explique procedimiento limpieza/desinfección cámaras, vitrinas...">
                                              <option value="Desconexión del aparato">Desconexión del aparato</option>
                                              <option selected value="Eliminación previa de la suciedad mas visible">Eliminación previa de la suciedad mas visible</option>
                                              <option selected value="Enjuage previo con agua caliente">Enjuage previo con agua caliente</option>
                                              <option selected value="Aplicar detergente o desengrasante">Aplicar detergente o desengrasante</option>
                                              <option selected value="Aclarado con agua">Aclarado con agua</option>
                                              <option selected value="Aplicación del desinfectante">Aplicación del desinfectante</option>
                                              <option value="Aclarado final">Aclarado final</option>
                                              <option selected value="Secado">Secado</option>
                                              <option value="Otros">Otros</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%"  multiple title="Seleccione frecuencia limpieza de cámaras, vitrinas...">
                                              <option selected value="Después de cada uso">Después de cada uso</option>
                                              <option selected value="Diaria">Diaria</option>
                                              <option selected value="Cuando sea necesario">Cuando sea necesario</option>
                                              <option value="Semanal">Semanal</option>
                                              <option value="Mensual">Mensual</option>
                                              <option value="Bimensual">Bimensual</option>
                                              <option value="Trimestral">Trimestral</option>
                                              <option value="Semestral">Semestral</option>
                                              <option value="Anual">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            Personal limpieza de suelos,paredes,techos..
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" id="appcc29" name="appcc29" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            ¿Dispone ficha de seguridad?
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <select class="form-control" name="appcc30" id="appcc30"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="Explique procedimiento limpieza/desinfección suelos,techos,paredes...">
                                              <option value="Desconexión del aparato">Desconexión del aparato</option>
                                              <option selected value="Eliminación previa de la suciedad mas visible">Eliminación previa de la suciedad mas visible</option>
                                              <option selected value="Enjuage previo con agua caliente">Enjuage previo con agua caliente</option>
                                              <option selected value="Aplicar detergente o desengrasante">Aplicar detergente o desengrasante</option>
                                              <option selected value="Aclarado con agua">Aclarado con agua</option>
                                              <option selected value="Aplicación del desinfectante">Aplicación del desinfectante</option>
                                              <option value="Aclarado final">Aclarado final</option>
                                              <option selected value="Secado">Secado</option>
                                              <option value="Otros">Otros</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%"  multiple title="Seleccione frecuencia limpieza de uelos,techos,paredes...">
                                              <option selected value="Después de cada uso">Después de cada uso</option>
                                              <option selected value="Diaria">Diaria</option>
                                              <option selected value="Cuando sea necesario">Cuando sea necesario</option>
                                              <option value="Semanal">Semanal</option>
                                              <option value="Mensual">Mensual</option>
                                              <option value="Bimensual">Bimensual</option>
                                              <option value="Trimestral">Trimestral</option>
                                              <option value="Semestral">Semestral</option>
                                              <option value="Anual">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-4 input-group-sm">
                                            Responsable control limpieza
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <input type="text" class="form-control" id="appcc31" name="appcc31">
                                        </div>
                                        <div class="col-md-4 input-group-sm">
                                            Responsable ausente control limpieza
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <input type="text" class="form-control" id="appcc32" name="appcc32">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4 input-group-sm">
                                            Almacén de productos y útiles de limpieza
                                        </div>
                                        <div class="col-md-8 input-group-sm">
                                            <input type="text" class="form-control" id="appcc33" name="appcc33">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4 input-group-sm">
                                            Medidas correctoras previstas
                                        </div>
                                        <div class="col-md-8 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="Seleccione medidas correctivas">
                                                  <option selected value="limpiezaOtrasMedidas1">Si se detectan zonas concretas sin limpiar o no lo suficientemente limpias, volver a realizar la limpieza y realizar una llamada de atención a la persona que realizó la limpieza para formarla de nuevo en su tarea.</option>
                                                  <option selected value="limpiezaOtrasMedidas2">Si se detectan restos de suciedad visible, debido a que el limpiador no actúa bien, volver a limpiar y revisar la concentración, dosis, tiempo de actuación recomendadas en la etiqueta y/o en la Ficha de Seguridad del Producto.</option>
                                                  <option value="limpiezaOtrasMedidas3">Otras medidas</option>
                                                  
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                </section>
                                <h6>Plagas</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-3 input-group-sm">
                                            Responsable control plagas
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc34" name="appcc34">
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            Resp. ausente control plagas
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc35" name="appcc35">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select  class="selectpicker " data-width="100%" multiple title="Actuación en caso de presencia de plagas">
                                              <option selected value="Cerrar pasos colocando barreras antiinsectos">Cerrar pasos colocando barreras antiinsectos</option>
                                              <option selected value="comprobar si existe una adecuada limpieza y mantenimiento">comprobar si existe una adecuada limpieza y mantenimiento</option>
                                              <option selected value="Usar repelentes">Usar repelentes</option>
                                              <option selected value="Usar insecticidas/raticidas">Usar insecticidas/raticidas</option>
                                              <option  value="Otros">Otros</option>
                                              
                                            </select>
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="Identificación de las zonas críticas">
                                              <option selected value="Motores de las cámaras">Motores de las cámaras (zonas a mayor temperatura, favorecen su cobijo)</option>
                                              <option selected value="Bajo los fregaderos">Bajo los fregaderos</option>
                                              <option selected value="Zonas de almacenaje">Zonas de almacenaje</option>
                                              <option selected value="Zonas de basuras">Zonas de basuras</option>
                                              <option value="Otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select  class="selectpicker " data-width="100%" multiple title="Si ha tenido que realizar algún tratamiento, índique cual">
                                              <option selected value="DESINSECTACIÓN">DESINSECTACIÓN</option>
                                              <option selected value="DESRATIZACIÓN">DESRATIZACIÓN</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                           <input type="text" class="form-control" id="appcc36" name="appcc36" value="Empresa aplicadora">
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                           <input type="date" class="form-control" id="appcc37" name="appcc37" value="Fecha">
                                        </div>
                                    </div>
                                </section>
                                <h6>Formación</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-3 input-group-sm">
                                            Responsable formación
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc38" name="appcc38">
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            Resp. ausente formación
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc39" name="appcc39">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select  class="selectpicker" data-width="100%"multiple title="Imparte acciones formativas en el establecimiento">
                                              <option value="Si">Si</option>
                                              <option selected value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="¿Qué prácticas se higiénicas se controlan?">
                                              <option selected value="Manos limpias">Manos limpias</option>
                                              <option selected value="Uñas cortadas y sin pintar">Uñas cortadas y sin pintar</option>
                                              <option value="Uso adecuado gorro o cubrecabezas">Uso adecuado gorro o cubrecabezas</option>
                                              <option value="Barba aseada">Barba aseada</option>
                                              <option value="Posibles enfermedades">Posibles enfermedades</option>
                                              <option value="Heridas tapadas">Heridas tapadas</option>
                                              <option selected value="No fumar en áreas prohibidas">No fumar en áreas prohibidas</option>
                                              <option selected value="No usar joyas o complementos">No usar joyas o complementos</option>
                                              <option selected value="Ropa de trabajo adecuada">Ropa de trabajo adecuada</option>
                                              <option value="Otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-12 input-group-sm">
                                            <select  class="selectpicker " data-width="100%" multiple title="Medidas correctivas previstas">
                                              <option selected value="Amonestar al trabajador para que actúe conforme a las buenas prácticas.">Amonestar al trabajador para que actúe conforme a las buenas prácticas.</option>
                                              <option selected value="Ofrecer nueva formación.">Ofrecer nueva formación.</option>
                                              <option selected value="En caso de reincidencia proponer una sanción económica.">En caso de reincidencia proponer una sanción económica .</option>
                                              <option  value="Otras medidas">Otras medidas</option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <h6>Trazabilidad</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-3 input-group-sm">
                                            Responsable trazabilidad
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc40" name="appcc40">
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            Resp. ausente trazabilidad
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc41" name="appcc41">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select  class="selectpicker " data-width="100%" multiple title="Destino de los productos">
                                              <option selected value="Consumidor final">Consumidor final</option>
                                              <option value="Otro establecimiento">Otro establecimiento</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <select class="form-control" name="appcc42" id="appcc42"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-4 input-group-sm">
                                            <select  class="selectpicker " data-width="100%" multiple title="Registro de proveedores">
                                              <option value="Si">Si</option>
                                              <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="¿Qué recoge?">
                                              <option value="Fecha">Fecha</option>
                                              <option value="Producto">Producto</option>
                                              <option value="Proveedor">Proveedor</option>
                                              <option value="Cantidad">Cantidad</option>
                                              <option value="Lote">Lote</option>
                                              <option value="Otros">Otros</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 input-group-sm">
                                            <select class="form-control" name="appcc43" id="appcc43"><option value="si">Si</option><option value="no">No</option></select>
                                        </div>
                                    </div>
                                </section>
                                <h6>Agua</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-12 input-group-sm">
                                           <select onchange="despliegaPlanAgua(this.value)" class="selectpicker " data-width="100%" multiple title="Origen del agua">
                                              <option selected value="1">Agua procede directamente de la red de abastecimiento público (no hay depósito intermedio)</option>
                                              <option value="2">Agua procede de la red de abastecimiento público pero cuenta con un depósito intermedio.</option>
                                              <option value="3">Agua procede de un lugar de captación propia (pozo)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                            <div class="col-md-8">1-Capacidad del depósito en litros</div>
                                            <div class="col-md-4 input-group-sm"><input type="text" class="form-control" id="appcc44"></div>
                                            <div class="col-md-8">2-¿El deposito se ubica por encima del alcantarillado?</div>
                                            <div class="col-md-4 input-group-sm"><select class="form-control"><option value="si">si</option><option value="no">No</option></select></div>
                                            <div class="col-md-8">3-¿El depósito está tapado?</div>
                                            <div class="col-md-4 input-group-sm"><select class="form-control"><option value="si">si</option><option value="no">No</option></select></div>
                                            <div class="col-md-8">4-¿Dispone de desagüe para garantizar un vaciado completo?</div>
                                            <div class="col-md-4 input-group-sm"><select class="form-control"><option value="si">si</option><option value="no">No</option></select></div>
                                            <div class="col-md-8">1-¿Se limpia periódicamente?</div>
                                            <div class="col-md-4 input-group-sm"><select class="form-control"><option value="si">si</option><option value="no">No</option></select></div>
                                            <div class="col-md-8">1-¿Incluye dosificador automático de cloro?</div>
                                            <div class="col-md-4 input-group-sm"><select class="form-control"><option value="si">si</option><option value="no">No</option></select></div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-3 input-group-sm">
                                            Responsable tratamiento agua
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc44" name="appcc44">
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            Resp. medición cloro agua
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc45" name="appcc45">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-3 input-group-sm">
                                            Equipo medidor cloro
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc46" name="appcc46">
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            ¿Dónde toma la muestra?
                                        </div>
                                        <div class="col-md-3 input-group-sm">
                                            <input type="text" class="form-control" id="appcc47" name="appcc47">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="Medidas correctivas mínimo">
                                              <option selected value="Aumentar dosi">Aumentar dosi de cloro</option>
                                              <option value="dejar de usar el agua hasta que los valores de cloro vuelvan a ser aceptables">Dejar de usar el agua hasta que los valores de cloro vuelvan a ser aceptables</option>
                                              <option value="otras">Otras</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 input-group-sm">
                                            <select class="selectpicker " data-width="100%" multiple title="Medidas correctivas máximo">
                                              <option selected value="Disminuir dosi">Disminuir dosi de cloro</option>
                                              <option value="dejar de usar el agua hasta que los valores de cloro vuelvan a ser aceptables">dejar de usar el agua hasta que los valores de cloro vuelvan a ser aceptables</option>
                                              <option value="otras">Otras</option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <h6>Mantenimiento</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-12 input-group-sm">
                                           <select class="selectpicker " data-width="100%" multiple title="Origen del agua">
                                              <option selected value="publica">Red pública</option>
                                              <option selected value="gas">Instalación de gas</option>
                                              <option value="aire comprimido">Instalación de aire comprimido</option>
                                              <option selected value="electrica">Instalación eléctrica</option>
                                              <option value="otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-8 input-group-sm">
                                            Responsable mantenimiento y conservación
                                        </div>
                                        <div class="col-md-4 input-group-sm">
                                            <input type="text" class="form-control" id="appcc48" name="appcc48">
                                        </div>
                                    </div>
                                </section>
                                <h6>Residuos</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-12 input-group-sm">
                                           <select class="selectpicker " data-width="100%" multiple title="Tipo de residuo que genera">
                                              <option selected value="organicos">Residuos orgánicos</option>
                                              <option selected value="inorganicos">Residuos inorgánicos</option>
                                              <option value="origen animal">Residuos de origen animal</option>
                                              <option selected value="aceites">Aceites fritos</option>
                                              <option value="otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-12 input-group-sm">
                                           <select class="selectpicker " data-width="100%" multiple title="Almacenamiento de residuo que genera">
                                              <option selected value="basura">Cubo para basura</option>
                                              <option value="carnePescado">Cubo para restos de carnes y pescados</option>
                                              <option selected value="cuboAceite">Recipientes para aceites fritos</option>
                                              <option selected value="cuboInorganicos">Cubos para inorgánicos</option>
                                              <option value="vidrios">Cubo para vidrios</option>
                                              <option value="otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-12 input-group-sm">
                                           <select class="selectpicker " data-width="100%" multiple title="Eliminación de los residuos">
                                              <option selected value="las basuras se eliminan depositándolas en los contenedores de la vía pública.">las basuras se eliminan depositándolas en los contenedores de la vía pública.</option>
                                              <option value="los residuos de origen animal se eliminan mediante recogida por el gestor autorizado">los residuos de origen animal se eliminan mediante recogida por el gestor autorizado</option>
                                              <option value="los aceites usados se eliminan depositándolos con un contenedor de aceites usados.">los aceites usados se eliminan depositándolos con un contenedor de aceites usados.</option>
                                              <option selected value="los aceites usados se eliminan mediante recogida por el gestor autorizado">los aceites usados se eliminan mediante recogida por el gestor autorizado</option>
                                           </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                       <div class="col-md-4 input-group-sm">
                                            Indique razón social si procede
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <input type="text" class="form-control" id="appcc49" name="appcc49" placeholder="Gestor origen animal">
                                        </div>
                                        <div class="col-md-4 input-group-sm">
                                            Indique razón social si procede
                                        </div>
                                        <div class="col-md-2 input-group-sm">
                                            <input type="text" class="form-control" id="appcc50" name="appcc50" placeholder="Gestor de aceites">
                                        </div>
                                    </div>
                                </section>
                               <h6>Info adicional</h6>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input type="text" onchange="trabajadores(this.value)" class="form-control" id="appcc51" name="appcc51" placeholder="Número de trabajadores">
                                        </div>
                                    </div>
                                    <details>
                                    <summary>Trabajadores - Haga click aquí para verlo y rellenarlo</summary>
                                      <div class="form-group row" id="trabs"></div>
                                    </details>
                                    <hr>
                                    <h5>Información del local</h5>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="appcc52" name="appcc52" placeholder="Nombre establecimiento">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="appcc53" name="appcc53" placeholder="Tipo establecimiento">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="appcc54" name="appcc54" placeholder="Número autorización si procede">
                                        </div>
                                    </div>
                                </section>
                            </div>
				        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal UPLOAD FASE -->
    <div class="modal fade" id="modalupload" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body">
                        <iframe style="overflow:hidden" id="iframeUpload" frameBorder="0" width="100%" height="550px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Modal Registro Retributivo -->
    <div class="modal fade" id="modalregistro" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                  <h4 class="modal-title"></h4>                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <?php if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<button id="btnregistro" class="btn btn-danger btn-block">Cancelar producto</button><hr>';
                                }
                        ?>
                        <br>
                        <?php
                        echo '<select id="tecnicosSelect" class="form-control" name="tecnicosSelect" onchange="insertaTecnicoHecho(this.value)">';
                        echo '<option>Seleccione un técnico</option>';
                            for($i = 0; $i < count($tecnicos); $i++){
                                
                                    echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                
                            }
                        echo '</select>';
                    ?>
                        <br>
                        <div align="center">
                            <button id="btnregistroDoc" class="btn btn-danger btn-block">Enviar E-mail con documentación para Registro Retributivo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registro Retributivo -->
    <div class="modal fade" id="modalgenerico" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body">
                        <div align="center">
                            <button id="btngenerico" class="btn btn-danger btn-block">Realizar genérico</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     <!-- Modal NOTIFICAR -->
    <div class="modal fade" id="modalnotificar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body">
                        <form class="forms-sample" id="formnotificar">
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12 control-label">
                                    <select name="clienteNotificar" id="clienteNotificar" style="width:100%" class="form-control">
                                        <option value="0">Buscar cliente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12 control-label">
                                    <select name="empleadoNotificar" id="empleadoNotificar" style="width:100%" class="form-control">
                                        <option value="0">Buscar empleado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12 control-label">
                                    <input type="date" class="form-control" id="fechaNotificar" name="fechaNotificar">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12 control-label">
                                    <textarea class="form-control" rows="5" name="msg" id="msg"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12" align="right">
                                    <button class="btn btn-success" type="button" onclick="insertaNotificacion()">Mandar notificación</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- context menu -->
    <div id="contextMenu" class="context-menu" style="display: none"> 
        <ul class="menu"> 
            <li class="share"><a id="precio"><i class="fa fa-share" aria-hidden="true"></i> </a></li> 
            <li class="rename"><a id="comercial"><i class="fa fa-pencil" aria-hidden="true"></i> </a></li> 
            <li class="link"><a href="#"><i class="fa fa-link" aria-hidden="true"></i> Copy Link Address</a></li> 
            <li class="copy"><a href="#"><i class="fa fa-copy" aria-hidden="true"></i> Copy to</a></li> 
            <li class="paste"><a href="#"><i class="fa fa-paste" aria-hidden="true"></i> Move to</a></li> 
            <li class="download"><a href="#"><i class="fa fa-download" aria-hidden="true"></i> Download</a></li> 
            <li class="trash"><a href="#"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li> 
        </ul> 
    </div> 
    <!-- Modal Version -->
    <div class="modal fade" id="modalVersion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div align="center">Se ha detectado una nueva versión: <?php echo $ver[0]['nombre']; ?></div>
                            <hr>
                        </div>
                        <div class="col-lg-12">
                            <p><?php echo $ver[0]['descripcion']; ?></p>
                            <hr>
                        </div>
                        <div align="center">
                            <button id="btnmodalversion" onclick="actualizarVersion(<?php echo $dataEmpleado[0]['idempleado'] ?>)" class="btn btn-danger btn-block">Actualizar la versión</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Prod sin asignar -->
    <div class="modal fade" id="modalSinAsignar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div align="center">Se han detectado productos sin asignar</div>
                            <hr>
                        </div>
                        <div class="col-lg-12">
                            <p>Hola, le sacamos esta alerta para advertirle que hay <?php echo $prodSinAsignar ?> productos sin asignar ahora mismo, ¿lo quieres resolver ahora?</p>
                            <hr>
                        </div>
                        <div align="center">
                            <button id="btnmodalprodsinasignar" onclick="prodSinAsignarResolver()" class="btn btn-danger btn-block">Resolver productos</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

