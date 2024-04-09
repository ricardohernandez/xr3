<style type="text/css">
  .ejemplo_planilla{
    display: inline;
    cursor: pointer;
    color: #17A2B8;
    margin-top:7px;
  }

  .ver_obs_desp{
    cursor: pointer;
    display: inline;
    margin-left: 5px;
    font-size: 11px;
    color: #2780E3;
  }

  .modal-ejemplo{
    width:60%!important;
  }
  .centered {
    text-align: left!important ;
  }

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }


</style>

<script type="text/javascript">
  $(function(){
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    var user="<?php echo $this->session->userdata('id'); ?>";
    const base = "<?php echo base_url() ?>";

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_f").val(desde);
    $("#hasta_f").val(hasta);

  /*****DATATABLE*****/   
    var lista_mad = $('#lista_mad').DataTable({
       "aaSorting" : [[1,"desc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive":false,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>getMadList",
          "dataSrc": function (json) {
            $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_detalle").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.desde = $("#desde_f").val();
            param.hasta = $("#hasta_f").val();
            param.coordinador = $("#f_coordinador").val();
            param.comuna = $("#f_comuna").val();
            param.zona = $("#f_zona").val();
            param.empresa = $("#f_empresa").val();
          }
        },    
       "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
                btn = "";
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                if(perfil==1){
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a>';
                }
                btn+='</center>';
                return btn;
            }
          },
          { "data": "fecha_ingreso" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "zona" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "nombre_tecnico" ,"class":"margen-td centered"},
          { "data": "nombre_coordinador" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
          { "data": "proyecto" ,"class":"margen-td centered"},
          { "data": "cam_con" ,"class":"margen-td centered"},
          { "data": "rssi" ,"class":"margen-td centered"},
          { "data": "pareo_inal" ,"class":"margen-td centered"},
          { "data": "tipo_auditoria" ,"class":"margen-td centered"},
          { "data": "id_orden_ot" ,"class":"margen-td centered"},
          { "class":"margen-td centered", "data": function(row,type,val,meta){
            if(row.observacion!="" && row.observacion!=null){
                   if(row.observacion.length > 30) {
                     str = row.observacion.substring(0,30)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+row.observacion.replace(/ /g,"_")+" data-title="+row.observacion.replace(/ /g,"_")+">Ver más</span>";
                   }else{
                     return "<span class='btndesp2' data-title="+row.observacion.replace(/ /g,"_")+">"+row.observacion+"</span>";
                  }
                }else{
                  return "-";
                }
            }
          },
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador', function() {
        lista_mad.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_mad.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_mad = $.fn.dataTable.fnTables(true);
        if ( lista_mad.length > 0 ) {
            $(lista_mad).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_mad = $.fn.dataTable.fnTables(true);
        if ( lista_mad.length > 0 ) {
            $(lista_mad).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_mad = $.fn.dataTable.fnTables(true);
        if ( lista_mad.length > 0 ) {
            $(lista_mad).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


     

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_mad').on('click', '.btn_nuevo_mad',function(event) {
        $('#modal_mad').modal('toggle'); 
        $(".btn_guardar_detalle").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_detalle").attr("disabled", false);
        $(".cierra_modal_mad").attr("disabled", false);
        $('#formMad')[0].reset();
        $("#hash_detalle").val("");
        $("#id_coordinador").val(user);
        $("#formMad input,#formMad select,#formMad button,#formMad").prop("disabled", false);
    });     

    $(document).off('submit', '#formMad').on('submit', '#formMad',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formMad");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formMad').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_detalle").attr("disabled", true);
              $(".cierra_modal_mad").attr("disabled", true);
              $("#formMad input,#formMad select,#formMad button,#formMad").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_detalle").attr("disabled", false);
                $(".cierra_modal_mad").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formMad input,#formMad select,#formMad button,#formMad").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_detalle").attr("disabled", false);
                  $(".cierra_modal_mad").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_mad').modal("toggle");
                  lista_mad.ajax.reload();
            }

            $(".btn_guardar_detalle").attr("disabled", false);
            $(".cierra_modal_mad").attr("disabled", false);
            $("#formMad input,#formMad select,#formMad button,#formMad").prop("disabled", false);
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
                    $('#modal_mad').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_mad').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
      event.preventDefault();
      $("#hash_detalle").val("")
      hash=$(this).data("hash")
      $('#formMad')[0].reset()
      $("#hash_detalle").val(hash)
      $('#modal_mad').modal('toggle')
      $("#formMad input,#formMad select,#formMad button,#formMad").prop("disabled", true)
      $(".btn_guardar_detalle").attr("disabled", true)
      $(".cierra_modal").attr("disabled", true)

      $.ajax({
        url: base+"getDataMad"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash:hash},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_detalle").prop("disabled",true); 
          $(".cierra_modal").prop("disabled",true); 
        },
        success: function (data) {

          if(data.res=="ok"){

            for(dato in data.datos){
              $("#hash_detalle").val(data.datos[dato].hash);
              $("#fecha").val(data.datos[dato].fecha);
              $('#zona').val(data.datos[dato].id_zona).trigger('change');
              $('#comuna').val(data.datos[dato].id_comuna).trigger('change');

              $('#id_tecnico').val(data.datos[dato].id_tecnico).trigger('change');
              $('#id_coordinador').val(data.datos[dato].id_coordinador).trigger('change');
              $('#tipo').val(data.datos[dato].id_tipo).trigger('change');
              $('#proyecto').val(data.datos[dato].id_proyecto).trigger('change');
              $('#cam_con').val(data.datos[dato].cam_con).trigger('change');
              $('#rssi').val(data.datos[dato].rssi).trigger('change');
              $('#pareo_inal').val(data.datos[dato].pareo_inal).trigger('change');
              $('#tipo_auditoria').val(data.datos[dato].tipo_auditoria).trigger('change');
              $('#id_orden_ot').val(data.datos[dato].id_orden_ot).trigger('change');

              $("#observacion").val(data.datos[dato].observacion);


            }
          
            $("#formMad input,#formMad select,#formMad button,#formMad").prop("disabled", false);
            $(".cierra_modal").prop("disabled", false);
            $(".btn_guardar_detalle").prop("disabled", false);

          }else if(data.res == "sess"){
            window.location="../";
          }

          $(".btn_guardar_detalle").prop("disabled",false); 
          $(".cierra_modal").prop("disabled",false); 
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
                  $('#modal_nuevo_usuario').modal("toggle");
              }    
              return;
          }
          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_mad').modal("toggle");
          }
        } , timeout:35000
      }) 
    })

    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('eliminaMad'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            lista_mad.ajax.reload();

          }else{
            $.notify(data.msg, {
              className:'danger',
              globalPosition: 'top right'
            })
          }
        },"json")
      }
    })
  /********OTROS**********/

  $(document).on('click', '.ver_obs_desp', function(event) { //Función de botón "Ver Texto" en tabla
      event.preventDefault();
      val=$(this).attr("data-tit");
      elem=$(this);
      if(elem.text()=="Ver más"){
        elem.html("Ocultar");     
        elem.attr("title","Acortar texto");
        elem.prev(".btndesp2").text(val.replace(/_/g, ' '));
        var table = $.fn.dataTable.fnTables(true);
        if ( table.length > 0 ) {
            $(table).dataTable().fnAdjustColumnSizing();
        }
      }else if(elem.text()=="Ocultar"){
        val = val.substring(0,60)+"...";
        elem.prev(".btndesp2").text(val.replace(/_/g, ' '));     
        elem.html("Ver más");
        elem.attr("title","Ver texto completo");
        var table = $.fn.dataTable.fnTables(true);
        if ( table.length > 0 ) {
            $(table).dataTable().fnAdjustColumnSizing();
        }
      }
    });
    
  $(document).off('click', '.excelmad').on('click', '.excelmad',function(event) {
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

      var coordinador = $("#f_coordinador").val();  
      var comuna = $("#f_comuna").val();  
      var zona = $("#f_zona").val();  
      var empresa = $("#f_empresa").val(); 

      if(coordinador==""){
        coordinador = "-"
      }
      if(comuna==""){
        comuna = "-"
      }
      if(zona==""){
        zona = "-"
      }
      if(empresa==""){
        empresa = "-"
      }

      window.location="excelmad/"+desde+"/"+hasta+"/"+coordinador+"/"+comuna+"/"+zona+"/"+empresa;
    });

    $(document).off('change', '#desde_f , #hasta_f ,#f_coordinador , #f_comuna , #f_zona, #f_empresa').on('change', '#desde_f , #hasta_f ,#f_coordinador , #f_comuna , #f_zona, #f_empresa', function(event) {
      lista_mad.ajax.reload()
    }); 

  })

  $(document).off('keydown', '.numbersOnly').on('keydown', '.numbersOnly',function(e) {
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188]) !== -1 ||
          (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
          (e.keyCode >= 35 && e.keyCode <= 40)) { 
               return;
      }
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
  });

  $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
      var myFormData = new FormData();
      myFormData.append('userfile', $('#userfile').prop('files')[0]);
      $.ajax({
          url: "formCargaMasivaMad"+"?"+$.now(),  
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
            $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base').prop("disabled",false);
            }
        },timeout:120000
      });
    })



</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <div class="col-12 col-lg-1">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_mad btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
        </div>
      </div>
      <div class=" col-xs-6 col-sm-6  col-md-6  col-lg-2 d-none d-sm-block">  
          <div class="form-group">
            <input hidden type="file" id="userfile" name="userfile" class="file_cs" />
            <button type="button"  class="btn-block btn btn-sm btn-primary btn_file_cs btn_xr3" onclick="document.getElementById('userfile').click();">
            <i class="fa fa-file-import"></i> Carga Masiva
            </div>
        </div>

      <div class="col-12 col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left:5px;font-size:13px;">Fecha <span></span> 
            </div>
            <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">
            <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_coordinador" name="f_coordinador" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione coordinador | Todos</option>
              <?php 
                foreach($usuarios as $u){
                  ?>
                    <option value="<?php echo $u["id"]; ?>"><?php echo $u["nombre_completo"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_comuna" name="f_comuna" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione comuna | Todos</option>
              <?php 
                foreach($comunas as $c){
                  ?>
                    <option value="<?php echo $c["id"]; ?>"><?php echo $c["titulo"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_zona" name="f_zona" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione zona | Todos</option>
              <?php 
                foreach($zonas as $z){
                  ?>
                    <option value="<?php echo $z["id"]; ?>"><?php echo $z["area"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-1">  
        <div class="form-group">
          <select id="f_empresa" name="f_empresa" class="custom-select custom-select-sm" style="width:100%!important;">
              <option value="">Seleccione empresa | Todos</option>
              <?php 
                foreach($proyectos as $p){
                  ?>
                    <option value="<?php echo $p["id"]; ?>"><?php echo $p["proyecto"]?></option>
                  <?php
                }
              ?>
          </select>
        </div>
      </div>
      
      <div class="col-6 col-lg-2">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador" class="buscador form-control form-control-sm">
       </div>
      </div>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary excelmad btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div>
      
    </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="lista_mad" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciones</th>   
            <th class="centered">Fecha ingreso</th> 
            <th class="centered">Fecha</th> 
            <th class="centered">Zona</th> 
            <th class="centered">Comuna</th> 
            <th class="centered">Nombre técnico</th>
            <th class="centered">Nombre coordinador</th> 
            <th class="centered">Tipo</th> 
            <th class="centered">Proyecto</th>  
            <th class="centered">Cert. cambio conect.</th>  
            <th class="centered">Cert. RSSI</th>  
            <th class="centered">Paréo inalámbrico</th>  
            <th class="centered">Tipo Auditoría</th>  
            <th class="centered">Código OT</th>  
            <th class="centered">Observación</th> 
            <th class="centered">Última actualización</th> 
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_mad" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formMad",array("id"=>"formMad","class"=>"formMad"))?>

    <div class="modal-dialog modal_mad modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_detalle" id="hash_detalle">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro centro de comando</legend>

            <div class="form-row">

              <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha</label>
                       <input type="date" placeholder="Fecha" class="form-control form-control-sm"  value="<?php echo date('Y-m-d')?>" name="fecha" id="fecha">
                    </div>
                  </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Zona </label>
                  <select id="zona" name="zona" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <?php 
                      foreach($zonas as $z){
                        ?>
                          <option value="<?php echo $z["id"]; ?>"><?php echo $z["area"]?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comuna </label>
                  <select id="comuna" name="comuna" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <?php 
                      foreach($comunas as $c){
                        ?>
                          <option value="<?php echo $c["id"]; ?>"><?php echo $c["titulo"]?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>
            	
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre de técnico </label>
                  <select id="id_tecnico" name="id_tecnico" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <?php 
                      foreach($usuarios as $u){
                        ?>
                          <option value="<?php echo $u["id"]; ?>"><?php echo $u["nombre_completo"]?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre de coordinador </label>
                  <select id="id_coordinador" name="id_coordinador" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <?php 
                      foreach($usuarios as $u){
                        ?>
                          <option value="<?php echo $u["id"]; ?>"><?php echo $u["nombre_completo"]?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto </label>
                  <select id="proyecto" name="proyecto" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <?php 
                      foreach($proyectos as $p){
                        ?>
                          <option value="<?php echo $p["id"]; ?>"><?php echo $p["proyecto"]?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo</label>
                  <select id="tipo" name="tipo" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                      <?php 
                        foreach($tipos as $t){
                          ?>
                            <option value="<?php echo $t["id"]; ?>"><?php echo $t["tipo"]?></option>
                          <?php
                        }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Certificación cambio de conectores</label>
                  <select id="cam_con" name="cam_con" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="SI" >SI </option>
                    <option value="NO" >NO </option>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Certificación niveles RSSI</label>
                  <select id="rssi" name="rssi" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="SI" >SI </option>
                    <option value="NO" >NO </option>
                  </select>
                </div>
              </div>
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Paréo inalámbrico</label>
                  <select id="pareo_inal" name="pareo_inal" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="SI" >SI </option>
                    <option value="NO" >NO </option>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo auditoría</label>
                  <select id="tipo_auditoria" name="tipo_auditoria" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="POST" >Post</option>
                    <option value="En linea" >En linea</option>
                  </select>
                </div>
              </div>
              
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Órden OT</label>         
                <input type="text"  placeholder="Ingrese código OT" class="form-control form-control-sm"  name="id_orden_ot" id="id_orden_ot">
                </div>
              </div>

              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Observaciones</label>         
                <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="observacion" id="observacion">
                </div>
              </div>

            </div>
          </fieldset> 
         
        </div>

        <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">

                <div class="col-4 col-lg-3">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_detalle">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-4 col-lg-3">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_mad" data-dismiss="modal">
                   <i class="fa fa-window-close"></i> Cerrar
                  </button>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
    <?php echo form_close(); ?>
  </div>