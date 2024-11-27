let dataProductos;
let table = "";


    
     table = $('#order-listing').DataTable({
      "processing": true,
      "serverSide": true,
      "serverMethod": 'post',
      "ajax": "../php/datatables/server_tabla.php?idEmp="+idEmp+"&idRol="+idRol+"&idRed="+idred,
      "drawCallback": function(settings) {
        $('[data-toggle="tooltip"]').tooltip();
        //alert("dataSRC done");
      },
      "aLengthMenu": [
        [5, 10, 15, -1],
        [5, 10, 15, "All"]
      ],
      "iDisplayLength": 10,
      "autoWidth": true,
      "searching": true,
      "info": true,
      "lengthChange": false,
    //"deferRender": true,
      "responsive": true,
      "language": {
        url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
      },
      "columns": [
                
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": '',
                    "render": function () {
                         return '<i class="fa fa-plus-square" aria-hidden="true"></i>';
                     },
                     width:"15px"
                },
                { "data": "id" },
                { "data": "empleado" },
                { "data": "razon" },
                { "data": "cif" },
                { "data": "direccion" },
                { "data": "email" },
                { "data": "telefonos" },
                { "data": "calle" },
                { "data": "poblacion" },
                { "data": "provincia" },
                { "data": "cp" },
                { "data": "tel" },
                { "data": "movil" },
                { "data": "cane" },
                { "data": "cargo" },
                { "data": "persona_contratante" },
                { "data": "gestoria" },
                { "data": "contacto_gestoria" },
                { "data": "tlf_gestoria" },
                { "data": "email_gestoria" },
                { "data": "usuario_comercial" },
                { "data": "dni" },
                { "data": "productos" }
            ],
      "columnDefs": [
            {
                "targets": [1,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22],
                "visible": false,
                "searchable": false
            },
        ]
    });
      
    $('#order-listing tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
             var tdi = tr.find("i.fa");
             var row = table.row(tr);

             if (row.child.isShown()) {
                 // This row is already open - close it
                 row.child.hide();
                 tr.removeClass('shown');
                 tdi.first().removeClass('fa-minus-square');
                 tdi.first().addClass('fa-plus-square');
             }
             else {
                 // Open this row
                 if ( table.row( '.shown' ).length ) {
                  $('.details-control', table.row( '.shown' ).node()).click();
                 }
                 row.child(format(row.data())).show();
                 tr.addClass('shown');
                 tdi.first().removeClass('fa-plus-square');
                 tdi.first().addClass('fa-minus-square');
             }
    });
    function format ( d ) {
        return  '<ul class="nav nav-pills nav-pills-success" id="pills-tab" role="tablist">'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Datos</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeObs(this.id,"'+d.id+'") id="pills-profile-tab'+d.id+'" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Comentarios</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeEstado(this.id,"'+d.id+'") id="pills-estado-tab'+d.id+'" data-bs-toggle="pill" href="#pills-estado" role="tab" aria-controls="pills-estado" aria-selected="false">Estados y Llamadas</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeArchivos(this.id,"'+d.id+'","'+d.cif+'") id="pills-contact-tab'+d.id+'" data-bs-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Archivos</a>'+
                        '</li>'+
                    '</ul>'+
                    '<div class="tab-content" id="pills-tabContent">'+
                        '<div class="tab-pane fade show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">'+
                            '<form id="formCliente'+d.id+'" class="forms-sample">'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="razon" placeholder="Razón Social" value="'+d.razon+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="cif" placeholder="Cif" value="'+d.cif+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="email" class="form-control" name="email" placeholder="E-mail" value="'+d.email+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="direccion" placeholder="Dirección" value="'+d.calle+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="localidad" placeholder="Localidad" value="'+d.poblacion+'">'+
                                    '</div>'+
                                    '<div class="col-md-3">'+
                                        '<input type="text" class="form-control" name="provincia" placeholder="Provincia" value="'+d.provincia+'">'+
                                    '</div>'+
                                    '<div class="col-md-1">'+
                                        '<input type="text" class="form-control" name="cp" placeholder="C.Postal" value="'+d.cp+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="telFijo" placeholder="Tel Fijo" value="'+d.tel+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="telMovil" placeholder="Tel Móvil" value="'+d.movil+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="cnae" placeholder="CNAE" value="'+d.cane+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="contratante" placeholder="Contratante" value="'+d.persona_contratante+'">'+
                                    '</div>'+
                                    '<div class="col-md-1">'+
                                        '<input type="text" class="form-control" name="cargo" placeholder="Cargo" value="'+d.cargo+'">'+
                                    '</div>'+
                                    '<div class="col-md-1">'+
                                        '<input type="text" class="form-control" name="dni" placeholder="DNI" value="'+d.dni+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="gestoria" placeholder="Gestoría" value="'+d.gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="contactoGestoria" placeholder="Contacto" value="'+d.contacto_gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="email" class="form-control" name="emailGestoria" placeholder="E-mail" value="'+d.email_gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="tlfGestoria" placeholder="Teléfono" value="'+d.tlf_gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="usuarioComercial" placeholder="Usuario" value="'+d.usuario_comercial+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row"><div class="col-md-12" align="right"><button type="button" onclick=actualizaCliente("'+d.id+'") class="btn btn-success btn-rounded btn-fw">Editar cliente</button></div>'+
                            '</form>'+ 
                         '</div>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">'+
                            '<form id="formComentarios'+d.id+'" class="forms-sample">'+
                                '<select id="selectProductos'+d.id+'" onchange=cargaComentarios(this.value,"'+d.id+'") name="producto" style="width:100%" class="select2 js-example-basic-single w-100"></select>'+
                                '<div id="obs'+d.id+'" class="comentarios">'+
                                '</div>'+
                                    '<div id="summernoteDiv'+d.id+'" style="display:none">'+
                                        '<div class="form-group">'+
                                            '<textarea id="summernote'+d.id+'" name="comentarios" ></textarea>'+
                                        '</div>'+
                                        '<div class="form-group">'+
                                            '<button type="button" onclick=actualizaComentarios("'+d.id+'") class="btn btn-success btn-rounded btn-fw">Añadir comentario</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</form>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-estado" role="tabpanel" aria-labelledby="pills-estado-tab">'+
                           '<form id="formEstados'+d.id+'" class="forms-sample">'+
                               '<div class="form-group">'+
                                    '<select id="selectProductosEstados'+d.id+'" onchange=rellenaSelectEstadosYllamadas(this.value,"'+d.id+'") name="producto" style="width:100%" class="select2 js-example-basic-single w-100"></select>'+
                                '</div>'+
                            '</form>'+
                            '<div class="form-group">'+
                                '<p>Estado actual - Selección nuevo estado</p>'+
                                '<select id="selectEstados'+d.id+'" name="estado" style="width:100%" class="select2 js-example-basic-single w-100" onchange=actualizaEstado(this.value,"'+d.id+'",null,"select")>'+
                                '</select>'+
                           '</div>'+
                           '<div class="form-group">'+
                                '<p>Llamada actual - Selección nueva llamada</p>'+
                                '<select id="selectLlamadas'+d.id+'" name="llamadas" style="width:100%" class="select2 js-example-basic-single w-100" onchange=actualizaLlamada(this.value,"'+d.id+'")>'+
                                '</select>'+
                           '</div>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">'+
                            '<div>'+
                                '<iframe style="overflow:hidden" id="iframeDoc'+d.id+'" frameBorder="0" width="100%" height="450px"></iframe>'+
                            '</div>'+
                    '</div>';
                    
            
        }
    $('#navbar-search-input').keyup(function(){
      //console.log(table);
      table.search($(this).val()).draw();
    });
