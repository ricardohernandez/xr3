<style type="text/css">
  .red{
    background-color: #DC3545;
    color: #fff;
  }
 
  @media(min-width: 768px){
    .borrar_ftth{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }
    .btn_modificar_ftth{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }
    .modal_ftth{
      width: 94%!important;
    }
    .table_head{
      font-size: 12px!important;
    }
  }
  @media(max-width: 768px){
    .borrar_ftth{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }
    .btn_modificar_ftth{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }
    .modal_ftth{
      width: 94%!important;
    }
    .table_head{
      font-size: 11px!important;
    }
  }
</style>

<script type="text/javascript">
  $(function(){
    var fecha_hoy="<?php echo $fecha_hoy; ?>";
    var fecha_anio_atras="<?php echo $fecha_anio_atras; ?>";
    $("#desde_f").val(fecha_anio_atras);
    $("#hasta_f").val(fecha_hoy);
    const id_perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

  /*****DATATABLE*****/   
    var listaChecklistFTTH = $('#listaChecklistFTTH').DataTable({
       "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
       "iDisplayLength":-1, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       "aaSorting" : [[11,"desc"]],
       "scrollY": "60vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       columnDefs: [
          { orderable: false, targets: 0 }
       ],

       "ajax": {
          "url":"<?php echo base_url();?>listaChecklistFTTH",
          "dataSrc": function (json) {
            $(".btn_filtro_ftth").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_ftth").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.desde = $("#desde_f").val();
            param.hasta = $("#hasta_f").val();
          }
        },    

       "columns": [
         
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_ftth" data-hash="'+row.hash+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_ftth"></a>';
              if(id_perfil==1 || id_perfil==2){
                btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_ftth" data-hash="'+row.hash+'"></a></center>';
              }
              return btn;
            }
          },

          { "data": "auditor" ,"class":"margen-td centered"},
          { "data": "cargo" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "area" ,"class":"margen-td centered"},
          { "data": "codigo" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "n_ot" ,"class":"margen-td centered"},
          { "data": "tipo_actividad" ,"class":"margen-td centered"},
          { "data": "direccion" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"}
        ]
      }); 

      $(document).on('keyup paste', '#buscador_ftth', function() {
        listaChecklistFTTH.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var listaChecklistFTTH = $.fn.dataTable.fnTables(true);
        if ( listaChecklistFTTH.length > 0 ) {
            $(listaChecklistFTTH).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var listaChecklistFTTH = $.fn.dataTable.fnTables(true);
        if ( listaChecklistFTTH.length > 0 ) {
            $(listaChecklistFTTH).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var listaChecklistFTTH = $.fn.dataTable.fnTables(true);
        if ( listaChecklistFTTH.length > 0 ) {
            $(listaChecklistFTTH).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_ftth').on('click', '.btn_nuevo_ftth',function(event) {
        $('#modal_ftth').modal('toggle'); 
        $(".btn_guardar_ftth").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_ftth").attr("disabled", false);
        $(".cierra_modal_ftth").attr("disabled", false);
        $('#formChecklistFTTH')[0].reset();
        $("#hash_ftth").val("");
        $("#formChecklistFTTH input,#formChecklistFTTH select,#formChecklistFTTH button,#formChecklistFTTH").prop("disabled", false);
        $(".estado").removeClass("red");
    });     

    $(document).off('submit', '#formChecklistFTTH').on('submit', '#formChecklistFTTH',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formChecklistFTTH");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formChecklistFTTH').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_ftth").attr("disabled", true);
              $(".cierra_modal_ftth").attr("disabled", true);
              $("#formChecklistFTTH input,#formChecklistFTTH select,#formChecklistFTTH button,#formChecklistFTTH").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_ftth").attr("disabled", false);
                $(".cierra_modal_ftth").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formChecklistFTTH input,#formChecklistFTTH select,#formChecklistFTTH button,#formChecklistFTTH").prop("disabled", false);


              }else if(data.res == "ok"){
                  $(".btn_guardar_ftth").attr("disabled", false);
                  $(".cierra_modal_ftth").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $("#hash_ftth").val(data.hash);
                  // $('#modal_ftth').modal("toggle");
                  listaChecklistFTTH.ajax.reload();
            }

            $(".btn_guardar_ftth").attr("disabled", false);
            $(".cierra_modal_ftth").attr("disabled", false);
            $("#formChecklistFTTH input,#formChecklistFTTH select,#formChecklistFTTH button,#formChecklistFTTH").prop("disabled", false);
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
                    $('#modal_ftth').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_ftth').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar_ftth').on('click', '.btn_modificar_ftth',function(event) {
      $("#hash_ftth").val("");
      hash = $(this).attr("data-hash");
      $("#hash_ftth").val(hash);
      $(".estado").removeClass("red");
        
      $.ajax({
        url: "getDataChecklistFTTH"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash : hash},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_ftth").attr("disabled", true);
          $(".cierra_modal_ftth").attr("disabled", true);
          $("#formChecklistFTTH input,#formChecklistFTTH select,#formChecklistFTTH button,#formChecklistFTTH").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_ftth").attr("disabled", false);
          $(".cierra_modal_ftth").attr("disabled", false);
          $("#formChecklistFTTH input,#formChecklistFTTH select,#formChecklistFTTH button,#formChecklistFTTH").prop("disabled", false);
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#hash_ftth").val(data.datos[dato].hash);
              $("#auditor  option[value='"+data.datos[dato].auditor_id+"'").prop("selected", true);
              $("#comuna").val(data.datos[dato].tecnico_comuna);
              $("#tecnico  option[value='"+data.datos[dato].tecnico_id+"'").prop("selected", true);
              $("#fecha").val(data.datos[dato].fecha);
              $("#cargo").val(data.datos[dato].auditor_cargo);
              $("#tecnico_zona").val(data.datos[dato].area);
              $("#tecnico_codigo").val(data.datos[dato].codigo);
              $("#tecnico_comuna").val(data.datos[dato].comuna);
              $("#check_"+data.datos[dato].id_check).val(data.datos[dato].id_check);
              $("#estado_"+data.datos[dato].id_check+" option[value='"+data.datos[dato].estado+"'").prop("selected", true);

              $("#n_ot").val(data.datos[dato].n_ot);
              $("#tipo_actividad").val(data.datos[dato].tipo_actividad);
              $("#direccion").val(data.datos[dato].direccion);

              if($("#estado_"+data.datos[dato].id_check).val()=="1"){
                $("#estado_"+data.datos[dato].id_check).addClass("red");
              }

              $("#observacion_"+data.datos[dato].id_check).val(data.datos[dato].observacion);
            } 

            listaChecklistFTTH.ajax.reload();
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
                  $('#modal_ftth').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_ftth').modal("toggle");
          }
        },timeout:25000
      }); 
    });

    $(document).off('click', '.borrar_ftth').on('click', '.borrar_ftth',function(event) {
        var hash=$(this).attr("data-hash");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaChecklistFTTH'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               listaChecklistFTTH.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
        }
    });

    $(document).off('change', '#auditor').on('change', '#auditor',function(event) {
        var auditor=$(this).val();
        if(auditor!=""){
          $.post('datosAuditorChecklistFTTH'+"?"+$.now(),{"auditor": auditor}, function(data) {
            for(dato in data.datos){
             $("#cargo").val(data.datos[dato].cargo);
          }
          },"json");
        }
    });
  
    $(document).off('change', '#tecnico').on('change', '#tecnico',function(event) {
        var tecnico=$(this).val();

        if(tecnico!=""){
            $.post('datosTecnicoChecklistFTTH'+"?"+$.now(),{"tecnico": tecnico}, function(data) {
            for(dato in data.datos){
               $("#tecnico_zona").val(data.datos[dato].area);
               $("#tecnico_codigo").val(data.datos[dato].codigo);
               $("#tecnico_comuna").val(data.datos[dato].comuna);
            }
            },"json");
        }
        
    });  



  /********OTROS**********/
    
    $(document).off('click', '.btn_filtro_ftth').on('click', '.btn_filtro_ftth',function(event) {
      event.preventDefault();
       $(this).prop("disabled" , true);
       $(".btn_filtro_ftth").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       listaChecklistFTTH.ajax.reload();
    });


    $(".fecha_normal").datetimepicker({
        format: "DD-MM-YYYY",
        locale:"es",
        maxDate:"now"
    });

    /********OTROS**********/


      $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
        var myFormData = new FormData();
        myFormData.append('userfile', $('#userfile').prop('files')[0]);

        $.ajax({
            url: "formCargaMasivaChecklistFTTH"+"?"+$.now(),  
            type: 'POST',
            data: myFormData,
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            processData: false,
            contentType : false,
            dataType:"json",
            beforeSend:function(){
              $(".btn_file_cs").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
            },  
            success: function (data) {
               $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> CSV');
               //activarTodo();
                if(data.res=="ok"){
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top right',
                      autoHideDelay: 20000,
                  });

                  table_mat.ajax.reload();


                }else{
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top right',
                      autoHideDelay: 5000,
                  });
                }
            },
            error : function(xhr, textStatus, errorThrown ) {
              //activarTodo();
              if (textStatus == 'timeout') {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.notify("Reintentando...", {
                        className:'info',
                        globalPosition: 'top right'
                      });
                      $.ajax(this);
                      $(".btn_file_cs").attr('disabled', false);      
                      $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> Importar');
                      return;
                  } else{
                     $.notify("Problemas cargando el archivo, intente nuevamente.", {
                        className:'warn',
                        globalPosition: 'top right'
                      });
                  }    
                  return;
              }

              if (xhr.status == 500) {
                 $.notify("Problemas cargando el archivo, intente nuevamente.", {
                    className:'warn',
                    globalPosition: 'top right'
                 });
              $(".btn_file_cs").attr('disabled', false); 
              $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> Importar series');
              }
          },timeout:120000
        });
      })


      $(document).off('click', '.btn_excel').on('click', '.btn_excel',function(event) {
         event.preventDefault();
         var desde = $("#desde_f").val();
         var hasta = $("#hasta_f").val();
         
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

         window.location="excel_checklistFTTH/"+desde+"/"+hasta;
      });


  /*********TABLA DETALLE******/

     var tabla_detalle = $('#tabla_detalle').DataTable({
       "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
       "iDisplayLength":-1, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       "aaSorting" : [],
       // "scrollY": "60vh",
       // "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
      }); 

      $(document).on('keyup paste', '#buscador_detalle', function() {
        tabla_detalle.search($(this).val().trim()).draw();
      });

      if($(window).width() > 560) {
         tabla_detalle.column(0).visible(true);  
      }else{
         tabla_detalle.column(0).visible(false);  
      }
      
      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var tabla_detalle = $.fn.dataTable.fnTables(true);
        if ( tabla_detalle.length > 0 ) {
            $(tabla_detalle).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var tabla_detalle = $.fn.dataTable.fnTables(true);
        if ( tabla_detalle.length > 0 ) {
            $(tabla_detalle).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var tabla_detalle = $.fn.dataTable.fnTables(true);
        if ( tabla_detalle.length > 0 ) {
            $(tabla_detalle).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

      $(document).on('keyup paste', '#buscador_ftth', function() {
        tabla_detalle.search($(this).val().trim()).draw();
      });

  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">
      <!-- <div class="col-xs-6 col-sm-6 col-md-1 col-lg-1 no-padding">  
         <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
         <button type="button" class="allwidth btn btn-danger btn-sm btn_file_cs" value="" onclick="document.getElementById('userfile').click();">
         <span class="glyphicon glyphicon-folder-open" style="margin-right:5px!important;"></span> CSV</button>
      </div> -->
      <div class="col-6 col-lg-2">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_ftth btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span>Fecha <span></span> 
            </div>
              <input type="text" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">
              <input type="text" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_ftth" class="buscador_ftth form-control form-control-sm">
       </div>
      </div>

      <div class="col-6 col-lg-1">
        <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_ftth btn_xr3">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
      </div>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary btn_excel btn_xr3">
         <i class="fa fa-save"></i>  Excel
         </button>
        </div>
      </div>
      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaChecklistFTTH" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;"></th>    
            <th class="centered">Auditor</th> 
            <th class="centered">Auditor cargo</th>   
            <th class="centered">Fecha</th>   
            <th class="centered">Técnico</th>   
            <th class="centered">Técnico zona</th>   
            <th class="centered">Técnico código</th>   
            <th class="centered">Técnico comuna</th>   
            <th class="centered">N° OT</th>   
            <th class="centered">Tipo actividad</th>   
            <th class="centered">Dirección </th>   
            <th class="centered">Última actualización</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_ftth" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formChecklistFTTH",array("id"=>"formChecklistFTTH","class"=>"formChecklistFTTH"))?>
    <input type="hidden" name="hash" id="hash_ftth">

    <div class="modal-dialog modal_ftth modal-dialog-scrollable">
      <div class="modal-content">

       <div class="modal-header">
        <div class="col-xs-12 col-sm-12 col-lg-4 offset-lg-4 mt-0">
          <div class="form-row">
            <div class="col-9 col-lg-6">
                <button type="submit" class="btn-block btn btn-sm btn-success btn_guardar_ftth">
                 <i class="fa fa-save"></i> Guardar
                </button>
            </div>
            <div class="col-3 col-lg-6">
              <button class="btn-block btn btn-sm btn-danger cierra_modal_ftth" data-dismiss="modal" aria-hidden="true">
             <!--   <i class="fa fa-window-close"></i>  -->Cerrar
              </button>
            </div>
          </div>
        </div>

       </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Checklist FTTH </legend>

            <div class="form-row">
              
              <div class="col-lg-2">               
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Auditor</label>
                <div class="form-group">
                 <div class="input-group mb-3">
                    <select id="auditor" name="auditor" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($auditores as $a){
                          ?>
                            <option value="<?php echo $a["id"]; ?>"><?php echo $a["nombre_completo"]; ?></option>
                          <?php
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Auditor Cargo </label>
                <input placeholder="Auditor Cargo" readonly type="text" name="cargo"  id="cargo" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>



              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha </label>
                <input placeholder="Fecha" type="text" name="fecha"  id="fecha" class="fecha_normal form-control form-control-sm" autocomplete="off" value="<?php echo date("d-m-Y") ?>" />
                </div>
              </div>


              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Técnico</label>
                  <select id="tecnico" name="tecnico" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($tecnicos as $t){
                          ?>
                            <option value="<?php echo $t["id"]; ?>"><?php echo $t["nombre_completo"]; ?></option>
                          <?php
                        }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-1">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Téc. Zona</label>
                <input placeholder="Técnico zona" readonly type="text" name="tecnico_zona"  id="tecnico_zona" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-1">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Téc. Código</label>
                <input placeholder="Técnico código" readonly type="text" name="tecnico_codigo"  id="tecnico_codigo" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Téc. Comuna</label>
                <input placeholder="Técnico comuna" readonly type="text" name="tecnico_comuna"  id="tecnico_comuna" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">N° OT </label>
                <input placeholder="N° OT" type="text" name="n_ot"  id="n_ot" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo Actividad </label>
                <input placeholder="Tipo Actividad" type="text" name="tipo_actividad"  id="tipo_actividad" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Dirección </label>
                <input placeholder="Dirección" type="text" name="direccion"  id="direccion" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

              <!-- <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comuna</label>
                <select id="tecnico_comuna" name="tecnico_comuna" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione...</option>
                      <?php 
                      foreach($comunas as $c){
                        ?>
                          <option value="<?php echo $c["id"]; ?>"><?php echo $c["titulo"]; ?></option>
                        <?php
                      }
                    ?>
                </select>
                </div>
              </div> -->


            </div>
          </fieldset> 

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Checklist FTTH </legend>

              <div class="tabla_detalle">

                <div class="col-12 col-lg-4 offset-lg-4">  
                 <div class="form-group">
                  <input type="text" placeholder="Busqueda" id="buscador_detalle" class="buscador_detalle form-control form-control-sm">
                 </div>
                </div>
           
                <table id="tabla_detalle" width="100%" class="dataTable  display responsive datatable_h table table-hover table-bordered table-condensed">
                <thead>
                  <tr style="background-color:#F9F9F9">
                      <th class="table_head desktop tablet">Tipo</th>
                      <th class="table_head all">Descripci&oacute;n</th>
                      <th class="table_head all">Estado</th>
                      <th class="table_head all">Observaci&oacute;n</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 

                    if($checklist!=FALSE){
                      foreach($checklist as $key){
                        
                        ?>
                          <tr>
                          <td><p class="table_text"  style="margin-left:20px!important;text-align: left!important"><?php echo $key["tipo"]; ?></p></td>
                          <input type="hidden" name="check_<?php echo $key["id"] ?>" id="check_<?php echo $key["id"] ?>" >
                          <td><p class="table_text"><?php echo $key["descripcion"] ?></p></td>
                          <td><p class="table_text">
                            <select  name="estado[]" id="estado_<?php echo $key["id"] ?>"  class="estado input-xs">
                              <option selected value="0">Si</option>
                              <option value="1">No</option>
                            </td>
                          <td>
                            <p class="table_text">
                              <input type="text" name="observacion[]" id="observacion_<?php echo $key["id"] ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                         </tr>
                      <?php
                      }
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

