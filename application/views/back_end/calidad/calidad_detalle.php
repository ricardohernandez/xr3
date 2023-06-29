<style type="text/css">
  .ejemplo_planilla_calidad{
    display: inline;
    cursor: pointer;
    color: #17A2B8;
    margin-top:7px;
  }

  .modal-ejemplo{
    width:60%!important;
  }

  .actualizacion_calidad{
      display: inline-block;
      font-size: 11px;
  }
</style>

<script type="text/javascript">
  $(function(){

    const perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";
  
    $('#rut').Rut({
      on_error: function(){ alert('Rut incorrecto'); },
      format_on: 'keyup'
    });

  /*****DATATABLE*****/   
    var lista_detalle_calidad = $('#lista_detalle_calidad').DataTable({
       "aaSorting" : [[4,"desc"]],
       "iDisplayLength":50, 
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "responsive":false,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaCalidad",
          "dataSrc": function (json) {
            $(".btn_filtro_calidad").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_calidad").prop("disabled" , false);

            var desde_actual="<?php echo $desde_actual; ?>"
            var hasta_actual="<?php echo $hasta_actual; ?>"
            var desde_anterior="<?php echo $desde_anterior; ?>"
            var hasta_anterior="<?php echo $hasta_anterior; ?>"
            var periodo =$("#periodo_detalle").val()

            if(periodo=="actual"){
              $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
            }else if(periodo=="anterior"){
              $("#fecha_f").val(`${desde_anterior.substring(0,5)} - ${hasta_anterior.substring(0,5)}`);
            }

            return json;
          },       
          data: function(param){
            param.periodo = $("#periodo_detalle").val();
            param.jefe = $("#jefe_det").val();

            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
          }
        },    

       
                
        "columns": [
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "rut_tecnico" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "ot" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "descripcion" ,"class":"margen-td centered"},
          { "data": "cierre" ,"class":"margen-td centered"},
          { "data": "ot_2davisita" ,"class":"margen-td centered"},
          { "data": "fecha_2davisita" ,"class":"margen-td centered"},
          { "data": "descripcion_2davisita" ,"class":"margen-td centered"},
          { "data": "cierre_2davisita" ,"class":"margen-td centered"},
          { "data": "diferencia_dias" ,"class":"margen-td centered"},
          { "data": "tipo_red" ,"class":"margen-td centered"},
          { "data": "falla" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"}
        ]
      }); 
  
      $(document).on('keyup paste', '#buscador_calidad', function() {
        lista_detalle_calidad.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_calidad').on('click', '.btn_filtro_calidad',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_calidad").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_detalle_calidad.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_detalle_calidad = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_calidad.length > 0 ) {
            $(lista_detalle_calidad).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_detalle_calidad = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_calidad.length > 0 ) {
            $(lista_detalle_calidad).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_detalle_calidad = $.fn.dataTable.fnTables(true);
        if ( lista_detalle_calidad.length > 0 ) {
            $(lista_detalle_calidad).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_calidad').on('click', '.btn_nuevo_calidad',function(event) {
        $('#modal_calidad').modal('toggle'); 
        $(".btn_guardar_calidad").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_calidad").attr("disabled", false);
        $(".cierra_modal_calidad").attr("disabled", false);
        $('#formCalidad')[0].reset();
        $("#hash_calidad").val("");
        $("#formCalidad input,#formCalidad select,#formCalidad button,#formCalidad").prop("disabled", false);
    });     

    $(document).off('submit', '#formCalidad').on('submit', '#formCalidad',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formCalidad");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formCalidad').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_calidad").attr("disabled", true);
              $(".cierra_modal_calidad").attr("disabled", true);
              $("#formCalidad input,#formCalidad select,#formCalidad button,#formCalidad").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_calidad").attr("disabled", false);
                $(".cierra_modal_calidad").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formCalidad input,#formCalidad select,#formCalidad button,#formCalidad").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_calidad").attr("disabled", false);
                  $(".cierra_modal_calidad").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_calidad').modal("toggle");
                  lista_detalle_calidad.ajax.reload();
            }

            $(".btn_guardar_calidad").attr("disabled", false);
            $(".cierra_modal_calidad").attr("disabled", false);
            $("#formCalidad input,#formCalidad select,#formCalidad button,#formCalidad").prop("disabled", false);
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
                    $('#modal_calidad').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_calidad').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar_calidad').on('click', '.btn_modificar_calidad',function(event) {
      $("#hash_calidad").val("");
      hash_calidad = $(this).attr("data-hash_calidad");
      $("#hash_calidad").val(hash_calidad);
        
      $.ajax({
        url: "getDataCalidad"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_calidad : hash_calidad},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_calidad").attr("disabled", true);
          $(".cierra_modal_calidad").attr("disabled", true);
          $("#formCalidad input,#formCalidad select,#formCalidad button,#formCalidad").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_calidad").attr("disabled", false);
          $(".cierra_modal_calidad").attr("disabled", false);
          $("#formCalidad input,#formCalidad select,#formCalidad button,#formCalidad").prop("disabled", false);
        
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
                  $('#modal_calidad').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_calidad').modal("toggle");
          }
        },timeout:25000
      }); 
    });

  /********OTROS**********/
    
    $(document).off('click', '.excel_calidad').on('click', '.excel_calidad',function(event) {
      event.preventDefault();

      if(perfil<=3){
        trabajador = $("#trabajadores").val()
      }else{
        trabajador = $("#trabajador").val();
      }

      var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
      jefe = jefe=="" ? "-" : jefe;

      if(trabajador=="" || trabajador==undefined){
         trabajador="-"
      }

      var periodo = $("#periodo_detalle").val()=="" ? "actual" : $("#periodo_detalle").val()
      window.location="excel_calidad/"+periodo+"/"+trabajador+"/"+jefe;
    });

    $.getJSON(base + "listaTrabajadoresCalidad", { jefe : $("#jefe_det").val() } , function(data) {
      response = data;
    }).done(function() {
        $("#trabajadores").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
         allowClear:true,
        });
    });

    $(document).off('click', '.ejemplo_planilla_calidad').on('click', '.ejemplo_planilla_calidad',function(event) {
        $('#ejemplo_modal_calidad').modal('toggle'); 
    });     

    $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
      var myFormData = new FormData();
      myFormData.append('userfile', $('#userfile_calidad').prop('files')[0]);
      $.ajax({
          url: "formCargaMasivaCalidad"+"?"+$.now(),  
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
             $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base ').prop("disabled",false);
              if(data.res=="ok"){
                $.notify(data.msg, {
                    className:data.tipo,
                    globalPosition: 'top center',
                    autoHideDelay: 20000,
                });
                lista_detalle_calidad.ajax.reload();
                actualizacionCalidad()
              }else{
                $.notify(data.msg, {
                    className:data.tipo,
                    globalPosition: 'top center',
                    autoHideDelay: 10000,
                });
              }

              $("#userfile_calidad").val(null);

          },
          error : function(xhr, textStatus, errorThrown ) {
            $("#userfile_calidad").val(null);
            if (textStatus == 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.notify("Reintentando...", {
                      className:'info',
                      globalPosition: 'top center'
                    });
                    $.ajax(this);
                    $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base ').prop("disabled",false);
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
            $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base ').prop("disabled",false);
            }
        },timeout:520000
      });
    })

    actualizacionCalidad()

    function actualizacionCalidad(){
      $.ajax({
          url: "actualizacionCalidad"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          dataType:"json",
          beforeSend:function(){
          },
          success: function (data) {
            if(data.res=="ok"){
              $(".actualizacion_calidad").html("<b>Última actualización planilla : "+data.datos+"</b>");
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


    $(document).off('change', '#jefe_det').on('change', '#jefe_det', function(event) {
      lista_detalle_calidad.ajax.reload()
    }); 


    $(document).off('change', '#periodo_detalle , #jefe_det').on('change', '#periodo_detalle , #jefe_det', function(event) {
      lista_detalle_calidad.ajax.reload()
    }); 
      
  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <?php
        if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
          ?>
          <div class="col-6 col-lg-1">  
             <input type="file" id="userfile_calidad" name="userfile" class="file_cs" style="display:none;" />
             <button type="button"  class="btn-block btn btn-sm btn-primary btn_file_cs btn_xr3" onclick="document.getElementById('userfile_calidad').click();">
             <i class="fa fa-file-import"></i> Cargar base

          </div>
         
          <?php
        }
      ?>
      
      <div class="col-6 col-lg-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 10px;font-size:13px!important;"> Periodo <span></span> 
            </div>
              <select id="periodo_detalle" name="periodo" class="custom-select custom-select-sm">
                <option value="actual" selected>Actual - <?php echo $mes_actual ?> </option>
                <option value="anterior">Anterior - <?php echo $mes_anterior ?> </option>
             </select>
          </div>
        </div>
      </div>

      <div class="col-6  col-lg-1">
        <div class="form-group">
          <div class="input-group">
              <input type="text" disabled placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="fecha_f" id="fecha_f">
          </div>
        </div>
      </div>


      <?php  
        if($this->session->userdata('id_perfil')<3){
      ?>

        <div class="col-6  col-lg-2">
          <div class="form-group">
            <select id="jefe_det" name="jefe_det" class="custom-select custom-select-sm">
              <option value="" selected>Seleccione Jefe | Todos</option>
              <?php  
                foreach($jefes as $j){
                  ?>
                    <option value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                  <?php
                }
              ?>
            </select>
          </div>
        </div>

      <?php
        }elseif($this->session->userdata('id_perfil')==3){
          ?>
          <div class="col-6 col-lg-2">
            <div class="form-group">
              <select id="jefe_det" name="jefe_det" class="custom-select custom-select-sm">
                <?php  
                  foreach($jefes as $j){
                    ?>
                      <option selected value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                    <?php
                  }
                ?>
              </select>
            </div>
          </div>
          <?php
        }
      ?>

      <?php  
       if($this->session->userdata('id_perfil')<=3){
          ?>
            <div class="col-6  col-lg-2">  
              <div class="form-group">
                <select id="trabajadores" name="trabajadores" style="width:100%!important;">
                    <option value="">Seleccione Trabajador | Todos</option>
                </select>
              </div>
            </div>
          <?php
       }else{
        ?>
           <div class="col-6  col-lg-2">  
              <div class="form-group">
                <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" >
                    <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
                </select>
              </div>
            </div>
        <?php
       }
      ?>


      <div class="col-6 col-lg-2">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_calidad" class="buscador_calidad form-control form-control-sm">
       </div>
      </div>

      <!-- <div class="col-6 col-lg-1">
        <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_calidad btn_xr3">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
      </div> -->

      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary excel_calidad btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div>
      
      </div>            

<!-- LISTADO -->
  <div class="row">
    <div class="col-lg-6 offset-lg-3">
        <center><span class="titulo_fecha_actualizacion_dias">
          <div class="alert alert-primary actualizacion_calidad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;">
          </div>
        </span> <!-- <i class="fa-solid fa-circle-info ejemplo_planilla_calidad" title="Ver ejemplo" ></i> --></center>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <table id="lista_detalle_calidad" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
           <!--  <th class="centered" style="width: 50px;"></th>     -->
            <th class="centered">Técnico</th> 
            <th class="centered">RUT</th> 
            <th class="centered">Comuna</th> 
            <th class="centered">Orden</th> 
            <th class="centered">Fecha</th> 
            <th class="centered">Descripción</th> 
            <th class="centered">Cierre</th> 
            <th class="centered">Orden 2da vis.</th> 
            <th class="centered">Fecha 2da vis.</th> 
            <th class="centered">Descripción 2da vis.</th> 
            <th class="centered">Cierre 2da vis.</th> 
            <th class="centered">Diferencia Días</th> 
            <th class="centered">Tipo red</th> 
            <th class="centered">Falla</th> 
            <th class="centered">Última actualización</th>   
          </tr>
        </thead>

      </table>
    </div>
  </div>


<!-- MODAL EJEMPLO -->
  <div class="modal fade" id="ejemplo_modal_calidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <a href="./planilla_ejemplo/cabecera_calidad.png" target="_blank"><img src="./planilla_ejemplo/cabecera_calidad.png" width="800px"></a><br><br>

          <h6>*Finalmente subir archivo CSV , solo el detalle de datos.</h6>
          <a href="./planilla_ejemplo/campos_calidad2.png" target="_blank"><img src="./planilla_ejemplo/campos_calidad2.png" width="800px"></a>
        </div></center>
        <center><div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div></center>
      </div>
    </div>
  </div>