 <tbody>
                          <?php
                          if($tabla[0]){
                          for($i = 0; $i < count($tabla[1]); $i++){
                                $t = "<tr>
                                      <td style='cursor:pointer'><i class='fa fa-plus-square' aria-hidden='true'></i></td>
                                      <td>".$tabla[1][$i]['id']."</td>
                                      <td>".$tabla[1][$i]['empleado']."</td>
                                      <td>".$tabla[1][$i]['razon']."</td>
                                      <td>".$tabla[1][$i]['cif']."</td>
                                      <td data-toggle='tooltip' title='".$tabla[1][$i]['direccion']."' >".$tabla[1][$i]['calle']."</td>
                                      <td>".$tabla[1][$i]['email']."</td>
                                      <td style='cursor:pointer'><span onclick=llama('".$tabla[1][$i]['tel']."')>".$tabla[1][$i]['tel']."</span> - <span onclick=llama('".$tabla[1][$i]['movil']."')>".$tabla[1][$i]['movil']."</span></td>
                                      <td>".$tabla[1][$i]['calle']."</td>
                                      <td>".$tabla[1][$i]['poblacion']."</td>
                                      <td>".$tabla[1][$i]['provincia']."</td>
                                      <td>".$tabla[1][$i]['cp']."</td>
                                      <td>".$tabla[1][$i]['tel']."</td>
                                      <td>".$tabla[1][$i]['movil']."</td>
                                      <td>".$tabla[1][$i]['cane']."</td>
                                      <td>".$tabla[1][$i]['cargo']."</td>
                                      <td>".$tabla[1][$i]['persona_contratante']."</td>
                                      <td>".$tabla[1][$i]['gestoria']."</td>
                                      <td>".$tabla[1][$i]['contacto_gestoria']."</td>
                                      <td>".$tabla[1][$i]['tlf_gestoria']."</td>
                                      <td>".$tabla[1][$i]['email_gestoria']."</td>
                                      <td>".$tabla[1][$i]['usuario_comercial']."</td>
                                      <td>".$tabla[1][$i]['dni']."</td>";
                                
                                      $prods = $datos->traeProductos($tabla[1][$i]['id'],$datosRed[0]['idredes'],$filtro);
                                      //print_r($prods);
                                      $x = 0;
                                      $t2 = "";
                                      for($a = 0; $a < count($prods); $a++){
                                          $estado = $prods[$a]['estado'];
                                          switch($estado){
                                              case "pendiente":
                                                  $pendiente = '<div id="prod'.$prods[$a]['iproductos'].'" style="cursor:pointer" class="badge badge-pill badge-outline-primary pendiente" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$pendiente;
                                                  break;
                                              case "hecho":
                                                  $hecho = '<div id="prod'.$prods[$a]['iproductos'].'" style="cursor:pointer" class="badge badge-pill badge-outline-success hecho" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$hecho;
                                                  break;
                                              case "incidencia":
                                                  $incidencia = '<div id="prod'.$prods[$a]['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-warning incidencia" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha_creacion'].'</div>';
                                                  $t2 = $t2.$incidencia;
                                                  break;
                                              case "cancelado":
                                                  $cancelado = '<div id="prod'.$prods[$a]['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-danger cancelado" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$cancelado;
                                                  break;
                                               case "generico":
                                                  $cancelado = '<div id="prod'.$prods[$a]['iproductos'].'" style="cursor:pointer" class="badge badge-pill badge-outline-dark generico" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$cancelado;
                                                  break;
                                                case "preincidenciacontactado":
                                                  $cancelado = '<div id="prod'.$prods[$a]['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-warning preincidencia" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$cancelado;
                                                  break;
                                                case "preincidencianocontactado":
                                                  $cancelado = '<div id="prod'.$prods[$a]['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-warning preincidencia" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$cancelado;
                                                  break;
                                                case "preincidenciaresuelta":
                                                  $cancelado = '<div id="prod'.$prods[$a]['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-info preincidencia_resuelta" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$cancelado;
                                                  break;
                                                case "gestionado":
                                                  $cancelado = '<div id="prod'.$prods[$a]['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-success gestionado" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$cancelado;
                                                  break;
                                                case "curso":
                                                  $cancelado = '<div id="prod'.$prods[$a]['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-primary curso" data-toggle="tooltip" title="'.$prods[$a]['nombre'].' | '.$prods[$a]['empresa_fiscal'].' | PRECIO: '.$prods[$a]['precio'].' COMERCIAL: '.$prods[$a]['usuario_comercial'].'" onclick=obtenerInformacion("'.$prods[$a]['tipo_producto'].'",'.$prods[$a]['iproductos'].','.$prods[$a]['clientes_idclientes'].')>('.($prods[$a]['llamada']-1).') - '.$prods[$a]['tipo_producto'].' | '.$prods[$a]['numcontrato'].' | '.$prods[$a]['fase'].' | '.$prods[$a]['fecha_creacion'].' | '.$prods[$a]['fecha'].'</div>';
                                                  $t2 = $t2.$cancelado;
                                                  break;
                                          }
                                          
                                          $x = $a;
                                      }
                                    $td = "<td style='display:contents'><br>".$t2."<br></td>";
                                    $t = $t.$td."</tr>";   
                                    echo $t;
                                }
                              }
                          ?>
                      </tbody>