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

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }
</style>

<script type="text/javascript">
  $(function(){
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_f").val(desde);
    $("#hasta_f").val(hasta);

  /*****DATATABLE*****/   
    var lista_rcdc = $('#lista_rcdc').DataTable({
       "aaSorting" : [[1,"desc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive":false,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>getRcdcList",
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
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
                return btn;
            }
          },
          { "data": "fecha_ingreso" ,"class":"margen-td centered"},
          { "data": "fecha_inspeccion" ,"class":"margen-td centered"},
          { "data": "tramo" ,"class":"margen-td centered"},
          { "data": "zona" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "nombre_tecnico" ,"class":"margen-td centered"},
          { "data": "nombre_coordinador" ,"class":"margen-td centered"},
          { "data": "proyecto" ,"class":"margen-td centered"},
          { "data": "codigo" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
          { "data": "estado" ,"class":"margen-td centered"},
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
          { "data": "costo_instalacion" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador', function() {
        lista_rcdc.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_rcdc.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_rcdc = $.fn.dataTable.fnTables(true);
        if ( lista_rcdc.length > 0 ) {
            $(lista_rcdc).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_rcdc = $.fn.dataTable.fnTables(true);
        if ( lista_rcdc.length > 0 ) {
            $(lista_rcdc).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_rcdc = $.fn.dataTable.fnTables(true);
        if ( lista_rcdc.length > 0 ) {
            $(lista_rcdc).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


     

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_rcdc').on('click', '.btn_nuevo_rcdc',function(event) {
        $('#modal_rcdc').modal('toggle'); 
        $(".btn_guardar_detalle").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_detalle").attr("disabled", false);
        $(".cierra_modal_rcdc").attr("disabled", false);
        $('#formRcdc')[0].reset();
        $("#hash_detalle").val("");
        $("#formRcdc input,#formRcdc select,#formRcdc button,#formRcdc").prop("disabled", false);
    });     

    $(document).off('submit', '#formRcdc').on('submit', '#formRcdc',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formRcdc");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formRcdc').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_detalle").attr("disabled", true);
              $(".cierra_modal_rcdc").attr("disabled", true);
              $("#formRcdc input,#formRcdc select,#formRcdc button,#formRcdc").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_detalle").attr("disabled", false);
                $(".cierra_modal_rcdc").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formRcdc input,#formRcdc select,#formRcdc button,#formRcdc").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_detalle").attr("disabled", false);
                  $(".cierra_modal_rcdc").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_rcdc').modal("toggle");
                  lista_rcdc.ajax.reload();
            }

            $(".btn_guardar_detalle").attr("disabled", false);
            $(".cierra_modal_rcdc").attr("disabled", false);
            $("#formRcdc input,#formRcdc select,#formRcdc button,#formRcdc").prop("disabled", false);
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
                    $('#modal_rcdc').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_rcdc').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
      event.preventDefault();
      $("#hash_detalle").val("")
      hash=$(this).data("hash")
      $('#formRcdc')[0].reset()
      $("#hash_detalle").val(hash)
      $('#modal_rcdc').modal('toggle')
      $("#formRcdc input,#formRcdc select,#formRcdc button,#formRcdc").prop("disabled", true)
      $(".btn_guardar_detalle").attr("disabled", true)
      $(".cierra_modal").attr("disabled", true)

      $.ajax({
        url: base+"getDataRcdc"+"?"+$.now(),  
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
              $("#fecha_inspeccion").val(data.datos[dato].fecha_inspeccion);
              $('#tramo').val(data.datos[dato].tramo).trigger('change');
              $('#zona').val(data.datos[dato].id_zona).trigger('change');
              $('#comuna').val(data.datos[dato].id_comuna).trigger('change');

              $('#id_tecnico').val(data.datos[dato].id_tecnico).trigger('change');
              $('#id_coordinador').val(data.datos[dato].id_coordinador).trigger('change');
              $('#proyecto').val(data.datos[dato].id_proyecto).trigger('change');
              $("#codigo").val(data.datos[dato].codigo);

              $('#tipo').val(data.datos[dato].tipo).trigger('change');
              $('#estado').val(data.datos[dato].estado).trigger('change');
              $('#costo').val(data.datos[dato].costo_instalacion).trigger('change');

              $("#observacion").val(data.datos[dato].observacion);


            }
          
            $("#formRcdc input,#formRcdc select,#formRcdc button,#formRcdc").prop("disabled", false);
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
              $('#modal_rcdc').modal("toggle");
          }
        } , timeout:35000
      }) 
    })

    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('eliminaRcdc'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            lista_rcdc.ajax.reload();

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

  $(document).off('change', '#proyecto').on('change', '#proyecto',function(event) {
        event.preventDefault();
        var proyecto=$(this).val();
        var label = document.getElementById("label_codigo");
        if(proyecto==""){ // NULL
          label.textContent = "Codigo";
          $("#codigo").attr("readonly", true)
        }
        else if(proyecto=="3"){ //DIRECTV
          label.textContent = "IBS";
          $("#codigo").attr("readonly", false)
        }
        else{ //CLARO VTR
          label.textContent = "OT";
          $("#codigo").attr("readonly", false)
        }
  })

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
    
  $(document).off('click', '.excelrcdc').on('click', '.excelrcdc',function(event) {
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

      window.location="excelrcdc/"+desde+"/"+hasta+"/"+coordinador+"/"+comuna+"/"+zona+"/"+empresa;
    });

    $(document).off('change', '#desde_f , #hasta_f ,#f_coordinador , #f_comuna , #f_zona, #f_empresa').on('change', '#desde_f , #hasta_f ,#f_coordinador , #f_comuna , #f_zona, #f_empresa', function(event) {
      lista_rcdc.ajax.reload()
    }); 

  })


</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <div class="col-12 col-lg-1">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_rcdc btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
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
         <button type="button"  class="btn-block btn btn-sm btn-primary excelrcdc btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div>
      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="lista_rcdc" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciomes</th>   
            <th class="centered">Fecha ingreso</th> 
            <th class="centered">Fecha inspección</th> 
            <th class="centered">Tramo</th> 
            <th class="centered">Zona</th> 
            <th class="centered">Comuna</th> 
            <th class="centered">Nombre técnico</th>
            <th class="centered">Nombre coordinador</th> 
            <th class="centered">Proyecto</th>  
            <th class="centered">Código</th> 
            <th class="centered">Tipo</th> 
            <th class="centered">Estado</th> 
            <th class="centered">Observación</th> 
            <th class="centered">Costo de instalación</th> 
            <th class="centered">Última actualización</th> 
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_rcdc" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formRcdc",array("id"=>"formRcdc","class"=>"formRcdc"))?>

    <div class="modal-dialog modal_rcdc modal-dialog-scrollable">
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
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha de inspección </label>
                       <input type="date" placeholder="Fecha inicio" class="form-control form-control-sm"  value="<?php echo date('Y-m-d')?>" name="fecha_inspeccion" id="fecha_inspección">
                    </div>
                  </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tramo </label>
                  <select id="tramo" name="tramo" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="Bloque 1" >Bloque 1 </option>
                    <option value="Bloque 2" >Bloque 2 </option>
                    <option value="Bloque 3" >Bloque 3 </option>
                    <option value="Bloque 4" >Bloque 4 </option>
                  </select>
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

              <div class="col-lg-3 codigo"> 
                <div class="form-group">
                  <label id="label_codigo" for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Código</label>
                  <input readonly type="text" placeholder="Ingrese código" name="codigo"  id="codigo" size="8" maxlength="8" class="form-control form-control-sm" autocomplete="off"/>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo </label>
                  <select id="tipo" name="tipo" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="Anillo">Anillo</option>
                    <option value="Venta Propia">Venta Propia</option>
                    <option value="Venta Cruzada">Venta Cruzada</option>
                    <option value="Instalación">Instalación</option>
                    <option value="Modificación">Modificación</option>
                    <option value="Reparación">Reparación</option>
                    <option value="Retiro">Retiro</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado </label>
                  <select id="estado" name="estado" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="Completada">Completada</option>
                    <option value="Derivada">Derivada</option>
                    <option value="Objetada">Objetada</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Reagendada">Reagendada</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Costo de instalación </label>
                  <select id="costo" name="costo" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <option value="OK">OK</option>
                    <option value="no OK">no OK</option>
                  </select>
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
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_rcdc" data-dismiss="modal">
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