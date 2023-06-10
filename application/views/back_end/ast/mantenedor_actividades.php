<style type="text/css">
  @media(min-width: 768px){
    .borrar_mant_act{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    .btn_modificar_mant_act{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }

    .modal_mant_act{
      width: 34%!important;
    }
    .table_head{
      font-size: 12px!important;
    }

  }
  @media(max-width: 768px){
    .borrar_mant_act{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }
    .btn_modificar_mant_act{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }

    .modal_mant_act{
      width: 94%!important;
    }
    .table_head{
      font-size: 11px!important;
    }
  }

 </style>

<script type="text/javascript">
  $(function(){
    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_fm").val(desde);
    $("#hasta_fm").val(hasta);
    const id_perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

  /*****DATATABLE*****/   
    var tabla_mant_act = $('#tabla_mant_act').DataTable({
       "aaSorting" : [],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "responsive":false,
       "select" : true,
       "columnDefs" : [
          { orderable: false , targets: 0 }
       ],
       "ajax": {
          "url":"<?php echo base_url();?>listaMantActividades",
          "dataSrc": function (json) {
            $(".btn_filtro_mant_act").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_mant_act").prop("disabled" , false);
            return json;
          },       
          data: function(param){
         
          }
        },    
       "columns": [
          {
           "class":"centered center margen-td", "width": "2%" ,  "data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_mant_act" data-hash="'+row.hash+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_mant_act"></a>';
              
              if(id_perfil==1 || id_perfil==2){
                /*btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_mant_act" data-hash="'+row.hash+'"></a>';*/
              }

              return btn;
            }
          },
          { "data": "actividad"  ,"width": "5%" , "class":"margen-td centered"},
          { "data": "tipo" ,"width": "5%" , "class":"margen-td centered"},
          { "data": "descripcion" ,"width": "30%" , "class":"margen-td centered"},
          { "data": "aplica" ,"width": "10%" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_mant_act', function() {
        tabla_mant_act.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tabla_mant_act = $.fn.dataTable.fnTables(true);
        if ( tabla_mant_act.length > 0 ) {
            $(tabla_mant_act).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var tabla_mant_act = $.fn.dataTable.fnTables(true);
        if ( tabla_mant_act.length > 0 ) {
            $(tabla_mant_act).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var tabla_mant_act = $.fn.dataTable.fnTables(true);
        if ( tabla_mant_act.length > 0 ) {
            $(tabla_mant_act).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_mant_act').on('click', '.btn_nuevo_mant_act',function(event) {
      $('#modal_mant_act').modal('toggle'); 
      $(".btn_guardar_mant_act").html('<i class="fa fa-save"></i> Guardar');
      $(".btn_guardar_mant_act").attr("disabled", false);
      $(".cierra_modal_mant_act").attr("disabled", false);
      $('#formMantActividades')[0].reset();
      $("#hash_mant_act").val("");
      $(".btn_guardar_mant_act").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
      $("#formMantActividades input,#formMantActividades select,#formMantActividades button,#formMantActividades").prop("disabled", false);
    });     

    $(document).off('submit', '#formMantActividades').on('submit', '#formMantActividades',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formMantActividades");
      var formData = new FormData(formElement);
   
        $.ajax({
            url: $('#formMantActividades').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_mant_act").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').attr("disabled", true);
              $(".cierra_modal_mant_act").attr("disabled", true);
              $("#formMantActividades input,#formMantActividades select,#formMantActividades button,#formMantActividades").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){
                $(".btn_guardar_mant_act").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
                $(".cierra_modal_mant_act").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                }); 

                $("#formMantActividades input,#formMantActividades select,#formMantActividades button,#formMantActividades").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_mant_act").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
                  $(".cierra_modal_mant_act").attr("disabled", false);
                  $('#modal_mant_act').modal("toggle");
                  tabla_mant_act.ajax.reload();
                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:15000,
                  });
              }

            $(".btn_guardar_mant_act").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
            $(".cierra_modal_mant_act").attr("disabled", false);
            $("#formMantActividades input,#formMantActividades select,#formMantActividades button,#formMantActividades").prop("disabled", false);
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
                    $('#modal_mant_act').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_mant_act').modal("toggle");
            }
          },timeout:105000
        });
      return false; 
    });

    $(document).off('click', '.btn_modificar_mant_act').on('click', '.btn_modificar_mant_act',function(event) {
      $("#hash_mant_act").val("");
      hash = $(this).attr("data-hash");
      $("#hash_mant_act").val(hash);
      $(".btn_guardar_mant_act").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
      getDataMantActividades(hash)        
    });

    function getDataMantActividades(hash){

     $.ajax({
      url: base+"getDataMantActividades"+"?"+$.now(),  
      type: 'POST',
      cache: false,
      tryCount : 0,
      retryLimit : 3,
      data:{hash : hash},
      dataType:"json",
      beforeSend:function(){
        $(".btn_guardar_mant_act").attr("disabled", true);
        $(".cierra_modal_mant_act").attr("disabled", true);
        $("#formMantActividades input,#formMantActividades select,#formMantActividades button,#formMantActividades").prop("disabled", true);
      },
      success: function (data) {
        $(".btn_guardar_mant_act").attr("disabled", false);
        $(".cierra_modal_mant_act").attr("disabled", false);
        $("#formMantActividades input,#formMantActividades select,#formMantActividades button,#formMantActividades").prop("disabled", false);
        if(data.res=="ok"){
          for(dato in data.datos){
            $("#actividad_mant  option[value='"+data.datos[dato].id_actividad+"'").prop("selected", true);
            $("#tipo_mant  option[value='"+data.datos[dato].id_tipo+"'").prop("selected", true);
            $("#descripcion_mant").val(data.datos[dato].descripcion);
            $("#actividad_mant").prop("disabled", true);
            $("#tipo_mant").prop("disabled", true);

            $("#aplica  option[value='"+data.datos[dato].aplica+"'").prop("selected", true);
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
                $('#modal_mant_act').modal("toggle");
            }    
            return;
        }
        if (xhr.status == 500) {
            $.notify("Problemas en el servidor, intente más tarde.", {
              className:'warn',
              globalPosition: 'top right'
            });
            $('#modal_mant_act').modal("toggle");
        }
      },timeout:25000
      }); 
    }

  
    $(document).off('click', '.borrar_mant_act').on('click', '.borrar_mant_act',function(event) {
        var hash=$(this).attr("data-hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
          $.post('eliminaMantActividades'+"?"+$.now(),{"hash": hash}, function(data) {
            if(data.res=="ok"){
              $.notify(data.msg, {
                className:'success',
                globalPosition: 'top right'
              });
             tabla_mant_act.ajax.reload();
            }else{
              $.notify(data.msg, {
                className:'danger',
                globalPosition: 'top right'
              });
            }
          },"json");
        }
    });



  /********OTROS**********/
    
    $(document).off('click', '.btn_filtro_mant_act').on('click', '.btn_filtro_mant_act',function(event) {
      event.preventDefault();
       $(this).prop("disabled" , true);
       $(".btn_filtro_mant_act").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tabla_mant_act.ajax.reload();
    });

    $(".fecha_normal").datetimepicker({
        format: "DD-MM-YYYY",
        locale:"es",
        maxDate:"now"
    });
   

  })
</script>

<!-- FILTROS -->
  
  <div class="form-row">

      <!-- <div class="col-6 col-lg-2">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_mant_act btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
        </div>
      </div> -->
    
      <div class="col-12 col-lg-4">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_mant_act" class="buscador_mant_act form-control form-control-sm">
       </div>
      </div>
    
      <!--  <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary btn_excel btn_xr3">
         <i class="fa fa-save"></i>  Excel
         </button>
        </div>
      </div> -->
      
    </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="tabla_mant_act" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;"></th>    
            <th class="centered">Actividad</th>   
            <th class="centered">Tipo</th>   
            <th class="centered">Descripción</th>  
            <th class="centered">Aplica</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_mant_act" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formMantActividades",array("id"=>"formMantActividades","class"=>"formMantActividades"))?>
    <input type="hidden" name="hash" id="hash_mant_act">
    <div class="modal-dialog modal_mant_act modal-dialog-scrollable">
      <div class="modal-content">

       <div class="modal-header">
        <div class="col-xs-12 col-sm-12 col-lg-6 offset-lg-3 mt-0">
          <div class="form-row">
            <div class="col-4 col-lg-6">
                <button type="submit" class="btn-block btn btn-sm btn-success btn_guardar_mant_act">
                 <i class="fa fa-save"></i> Guardar
                </button>
            </div>
            <div class="col-4 col-lg-6">
              <button class="btn-block btn btn-sm btn-danger cierra_modal_mant_act" data-dismiss="modal" aria-hidden="true">
               <i class="fa fa-window-close"></i> Cerrar
              </button>
            </div>
          </div>
        </div>

       </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Mantenedor Actividades </legend>

            <div class="form-row">
              <div class="col-lg-12">  
                <div class="form-group">
                <label for="col-form-label-sm" class="col-sm-12 col-form-label col-form-label-sm">Actividad</label>
                  <select id="actividad_mant" name="actividad_mant" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($actividades as $a){
                          ?>
                            <option value="<?php echo $a["id"]; ?>"><?php echo $a["actividad"]; ?></option>
                          <?php
                        }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-12">               
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo</label>
                <div class="form-group">
                 <div class="input-group mb-3">
                    <select id="tipo_mant" name="tipo_mant" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($tipos as $t){
                          ?>
                            <option value="<?php echo $t["id"]; ?>"><?php echo $t["tipo"]; ?></option>
                          <?php
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-lg-12">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Descripción </label>
                  <input placeholder="Descripción" type="text" name="descripcion_mant"  id="descripcion_mant" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-12">               
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Aplica</label>
                <div class="form-group">
                 <div class="input-group mb-3">
                    <select id="aplica" name="aplica" class="custom-select custom-select-sm">
                    <option value="si" selected>Si</option>
                    <option value="no_ap">No</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>
          </fieldset> 

        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

