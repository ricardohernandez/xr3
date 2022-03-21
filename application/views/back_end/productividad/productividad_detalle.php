<style type="text/css">
  .ejemplo_planilla{
    display: inline;
    cursor: pointer;
    color: #17A2B8;
    margin-top:7px;
  }

  .modal-ejemplo{
    width:60%!important;
  }

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }
</style>

<script type="text/javascript">
  $(function(){

    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";
    var fecha_hoy="<?php echo $fecha_hoy; ?>";
    var fecha_anio_atras="<?php echo $fecha_anio_atras; ?>";
    
    $("#desde_f").val(fecha_anio_atras);
    $("#hasta_f").val(fecha_hoy);

    $('#rut').Rut({
      on_error: function(){ alert('Rut incorrecto'); },
      format_on: 'keyup'
    });

    

  /*****DATATABLE*****/   
    var lista_detalle = $('#lista_detalle').DataTable({
       "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
       "iDisplayLength":100, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": true,
       "aaSorting" : [[1,"desc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaDetalle",
          "dataSrc": function (json) {
            $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_detalle").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.desde = $("#desde_f").val();
            param.hasta = $("#hasta_f").val();
            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
          }
        },    
        "footerCallback": function ( row, data, start, end, display ) {
          // var api = this.api(), data;
          // var largo = api.columns(':visible').count();

          // for (var i = 1; i <= (largo); i++) {
          //   if(i==7){
          //     var intVal = function ( i ) {
          //         return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
          //     };

          //     total = api .column( i )  .data()   .reduce( function (a, b) {
          //        return intVal(a) + intVal(b);
          //     },0);

          //     if(total==0){
          //     }else if(total==1){
          //       $( api.column( i ).footer() ).html("<center><b style='color:green;font-size:10px;text-align:center;'>"+total+"</b></center>");
          //     }else if(total>1){
          //       $( api.column( i ).footer() ).html("<center><b style='color:#CE3735;font-size:10px;text-align:center;'>"+total+"</b></center>");
          //     }
          //   }

          // }
        },

       "columns": [
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "direccion" ,"class":"margen-td centered"},
          { "data": "tipo_actividad" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "estado" ,"class":"margen-td centered"},
          { "data": "derivado" ,"class":"margen-td centered"},
          { "data": "puntaje" ,"class":"margen-td centered"},
          { "data": "ot" ,"class":"margen-td centered"},
          { "data": "estado_ot" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador_detalle', function() {
        lista_detalle.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_detalle.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_detalle = $.fn.dataTable.fnTables(true);
        if ( lista_detalle.length > 0 ) {
            $(lista_detalle).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_detalle = $.fn.dataTable.fnTables(true);
        if ( lista_detalle.length > 0 ) {
            $(lista_detalle).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_detalle = $.fn.dataTable.fnTables(true);
        if ( lista_detalle.length > 0 ) {
            $(lista_detalle).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


     

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_detalle').on('click', '.btn_nuevo_detalle',function(event) {
        $('#modal_detalle').modal('toggle'); 
        $(".btn_guardar_detalle").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_detalle").attr("disabled", false);
        $(".cierra_modal_detalle").attr("disabled", false);
        $('#formDetalle')[0].reset();
        $("#hash_detalle").val("");
        $("#formDetalle input,#formDetalle select,#formDetalle button,#formDetalle").prop("disabled", false);
    });     

    $(document).off('submit', '#formDetalle').on('submit', '#formDetalle',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formDetalle");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formDetalle').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_detalle").attr("disabled", true);
              $(".cierra_modal_detalle").attr("disabled", true);
              $("#formDetalle input,#formDetalle select,#formDetalle button,#formDetalle").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_detalle").attr("disabled", false);
                $(".cierra_modal_detalle").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formDetalle input,#formDetalle select,#formDetalle button,#formDetalle").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_detalle").attr("disabled", false);
                  $(".cierra_modal_detalle").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_detalle').modal("toggle");
                  lista_detalle.ajax.reload();
            }

            $(".btn_guardar_detalle").attr("disabled", false);
            $(".cierra_modal_detalle").attr("disabled", false);
            $("#formDetalle input,#formDetalle select,#formDetalle button,#formDetalle").prop("disabled", false);
          },
          error : function(xhr, textStatus, errorThrown ) {
            if (textStatus == 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.notify("Reintentando...", {
                      className:'info',
                      globalPosition: 'top right'
                    });
                    $.ajax(this);
                    return;
                } else{
                   $.notify("Problemas en el servidor, intente nuevamente.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });     
                    $('#modal_detalle').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_detalle').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar_usuario').on('click', '.btn_modificar_usuario',function(event) {
      $("#hash_detalle").val("");
      hash_detalle = $(this).attr("data-hash_detalle");
      $("#hash_detalle").val(hash_detalle);
        
      $.ajax({
        url: "getDataDetalle"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_detalle : hash_detalle},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_detalle").attr("disabled", true);
          $(".cierra_modal_detalle").attr("disabled", true);
          $("#formDetalle input,#formDetalle select,#formDetalle button,#formDetalle").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_detalle").attr("disabled", false);
          $(".cierra_modal_detalle").attr("disabled", false);
          $("#formDetalle input,#formDetalle select,#formDetalle button,#formDetalle").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              // $("#nombres").val(data.datos[dato].nombres);
              // $("#sexo  option[value='"+data.datos[dato].sexo+"'").prop("selected", true);
            } 
          }
        },

        error : function(xhr, textStatus, errorThrown ) {
          if (textStatus == 'timeout') {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.notify("Reintentando...", {
                    className:'info',
                    globalPosition: 'top right'
                  });
                  $.ajax(this);
                  return;
              } else{
                 $.notify("Problemas en el servidor, intente nuevamente.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });     
                  $('#modal_detalle').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_detalle').modal("toggle");
          }
        },timeout:25000
      }); 
    });

  /********OTROS**********/
    
    $(document).off('click', '.excel_detalle').on('click', '.excel_detalle',function(event) {
      event.preventDefault();
      var desde = $("#desde_f").val();
      var hasta = $("#hasta_f").val();  
      if(perfil==4){
        trabajador = $("#trabajador").val()
      }else{
        trabajador = $("#trabajadores").val();
      }

      if(desde==""){
         $.notify("Debe seleccionar una fecha de inicio.", {
             className:'error',
             globalPosition: 'top right'
         });  
         return false;
       }
       if(hasta==""){
         $.notify("Debe seleccionar una fecha de término.", {
             className:'error',
             globalPosition: 'top right'
         });  
        return false;
       }
      
       if(trabajador==""){
         trabajador="-"
       }

       window.location="excel_detalle/"+desde+"/"+hasta+"/"+trabajador;
    });

    $.getJSON("listaTrabajadores", function(data) {
      response = data;
    }).done(function() {
        $("#trabajadores").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
           allowClear:true,
        });
    });


    $(document).off('click', '.ejemplo_planilla').on('click', '.ejemplo_planilla',function(event) {
        $('#ejemplo_modal').modal('toggle'); 
    });     

    $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
        var myFormData = new FormData();
        myFormData.append('userfile', $('#userfile').prop('files')[0]);
        $.ajax({
            url: "formCargaMasivaDetalle"+"?"+$.now(),  
            type: 'POST',
            data: myFormData,
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            processData: false,
            contentType : false,
            dataType:"json",
            beforeSend:function(){
              $(".btn_file_cs").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').prop("disabled",true);
            },  
            success: function (data) {
               $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base productividad ').prop("disabled",false);
                if(data.res=="ok"){
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 20000,
                  });
                  lista_detalle.ajax.reload();
                  actualizacionProductividad()
                }else{
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 10000,
                  });
                }

                $("#userfile").val(null);

            },
            error : function(xhr, textStatus, errorThrown ) {
              $("#userfile").val(null);
              if (textStatus == 'timeout') {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.notify("Reintentando...", {
                        className:'info',
                        globalPosition: 'top center'
                      });
                      $.ajax(this);
                      $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base productividad').prop("disabled",false);
                      return;
                  } else{
                     $.notify("Problemas cargando el archivo, intente nuevamente.", {
                        className:'warn',
                        globalPosition: 'top center',
                        autoHideDelay: 10000,
                      });
                  }    
                  return;
              }

              if (xhr.status == 500) {
                 $.notify("Problemas cargando el archivo, intente nuevamente.", {
                    className:'warn',
                    globalPosition: 'top center',
                    autoHideDelay: 10000,
                 });
              $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base productividad').prop("disabled",false);
              }
          },timeout:120000
        });
      })

      actualizacionProductividad()

      function actualizacionProductividad(){
        $.ajax({
            url: "actualizacionProductividad"+"?"+$.now(),  
            type: 'POST',
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            dataType:"json",
            beforeSend:function(){
            },
            success: function (data) {
              if(data.res=="ok"){
                $(".actualizacion_productividad").html("<b>Última actualización planilla : "+data.datos+"</b>");
              }
            },
            error : function(xhr, textStatus, errorThrown ) {
              if (textStatus == 'timeout') {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.notify("Reintentando...", {
                        className:'info',
                        globalPosition: 'top right'
                      });
                      $.ajax(this);
                      return;
                  } else{
                     $.notify("Problemas en el servidor, intente nuevamente.", {
                        className:'warn',
                        globalPosition: 'top right'
                      });     
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
              }
            },timeout:5000
        }); 
      }
      



  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <?php
        if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
          ?>
          <div class="col-xs-6 col-sm-6 col-md-1 col-lg-2">  
             <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
             <button type="button"  class="btn-block btn btn-sm btn-primary btn_file_cs btn_xr3" onclick="document.getElementById('userfile').click();">
             <i class="fa fa-file-import"></i> Cargar base productividad 
          </div>
          <i class="fa-solid fa-circle-info ejemplo_planilla" title="Ver ejemplo" ></i>
          <?php
        }
      ?>
      

      <div class="col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span>Fecha <span></span> 
            </div>
              <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">
              <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div>

      <?php  
       if($this->session->userdata('id_perfil')<>4){
          ?>
            <div class="col-lg-2">  
              <div class="form-group">
                <select id="trabajadores" name="trabajadores" style="width:100%!important;">
                    <option value="">Seleccione Trabajador | Todos</option>
                </select>
              </div>
            </div>
          <?php
       }else{
        ?>
           <div class="col-lg-2">  
              <div class="form-group">
                <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" >
                    <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
                </select>
              </div>
            </div>
        <?php
       }
      ?>

      <div class="col-12 col-lg-2">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_detalle" class="buscador_detalle form-control form-control-sm">
       </div>
      </div>

      <div class="col-6 col-lg-1">
        <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_detalle btn_xr3">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
      </div>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary excel_detalle btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div>
      
      </div>            

<!-- LISTADO -->
  <div class="row">
    <div class="col-lg-6 offset-lg-3">
          <center><span class="titulo_fecha_actualizacion_dias">
            <div class="alert alert-primary actualizacion_productividad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;">
            </div>
          </span></center>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <table id="lista_detalle" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
           <!--  <th class="centered" style="width: 50px;"></th>     -->
            <th class="centered">Técnico</th> 
            <th class="centered">Fecha</th> 
            <th class="centered">Dirección</th> 
            <th class="centered">Tipo actividad</th> 
            <th class="centered">Comuna</th> 
            <th class="centered">Estado</th> 
            <th class="centered">Derivado</th> 
            <th class="centered">Puntaje</th> 
            <th class="centered">Orden de Trabajo</th> 
            <th class="centered">Digitalizacion OT</th>   
          </tr>
        </thead>

        <tfoot>
          <tr>    
            <th class="centered"></th> 
            <th class="centered"></th> 
            <th class="centered"></th> 
            <th class="centered"> </th> 
            <th class="centered"></th> 
            <th class="centered"></th> 
            <th class="centered"></th> 
            <th class="centered"></th> 
            <th class="centered"></th> 
            <th class="centered"></th>   
          </tr>
        </tfoot>

      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_detalle" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formDetalle",array("id"=>"formDetalle","class"=>"formDetalle"))?>

    <div class="modal-dialog modal_detalle modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-4 offset-lg-4 mt-0">
            <div class="form-row">
              <div class="col-9 col-lg-6">
                  <button type="submit" class="btn-block btn btn-sm btn-success btn_guardar_detalle">
                   <i class="fa fa-save"></i> Guardar
                  </button>
              </div>
              <div class="col-3 col-lg-6">
                <button class="btn-block btn btn-sm btn-danger cierra_modal_detalle" data-dismiss="modal" aria-hidden="true">
               <!--   <i class="fa fa-window-close"></i>  -->Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_detalle" id="hash_detalle">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro Usuarios </legend>

            <div class="form-row">
              
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombres </label>
                <input placeholder="Nombres"  type="text" name="nombres"  id="nombres" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Apellidos </label>
                <input placeholder="Apellidos"  type="text" name="apellidos"  id="apellidos" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Empresa </label>
                <input placeholder="Empresa"  type="text" name="empresa"  id="empresa" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">RUT </label>
                <input placeholder="RUT"  type="text" name="rut"  id="rut" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Sexo </label>
                  <select id="sexo" name="sexo" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                  </select>
                <!-- <input placeholder="Sexo"  type="text" name="sexo"  id="sexo" class="form-control form-control-sm" autocomplete="off" /> -->
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nacionalidad </label>
                <input placeholder="Nacionalidad"  type="text" name="nacionalidad"  id="nacionalidad" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>


            </div>
          </fieldset> 
         
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>


<!-- MODAL EJEMPLO -->
  <div class="modal fade" id="ejemplo_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-ejemplo">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <center><div class="modal-body">
           <h4 class="modal-title text-center" id="exampleModalLabel"><center>Instrucciones subida de planilla</center></h4><br>
          <h6>*El archivo debe ser tener la extensión CSV delimitado por comas en UTF-8, esto se obtiene en la opción "guardar como" desde la planilla excel </h6>
          <a href="./planilla_ejemplo/csv_planilla.png" target="_blank"><img src="./planilla_ejemplo/csv_planilla.png" width="500px"></a><br><br>

          <h6>*El orden de campos es el siguiente.</h6>
          <a href="./planilla_ejemplo/campos_planilla.png" target="_blank"><img src="./planilla_ejemplo/campos_planilla.png" width="800px"></a><br><br>

          <h6>*Finalmente subir archivo CSV , solo el detalle de datos.</h6>
          <a href="./planilla_ejemplo/ejemplo_planilla.png" target="_blank"><img src="./planilla_ejemplo/ejemplo_planilla.png" width="800px"></a>
        </div></center>
        <center><div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div></center>
      </div>
    </div>
  </div>