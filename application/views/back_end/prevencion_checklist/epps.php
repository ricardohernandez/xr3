<style type="text/css">
  .rojo{
    color: red;
  }

  .grey{
    background-color: grey;
    color: #fff;
  }

  .btn_archivo{
    display: block;
    text-align: center!important;
    margin:0 auto!important;
    font-size: 14px!important;
  }

  @media(min-width: 768px){
    .btn_eliminar{
      font-size: 14px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    .btn_editar{
      text-align: center!important;
      font-size: 14px!important;
    }
    .modal_epps{
      width: 40%!important;
    }
    .table_head{
      font-size: 12px!important;
    }

  }
  @media(max-width: 768px){
    .btn_eliminar{
      font-size: 14px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }
    .btn_editar{
      display: block;
      text-align: center!important;
      font-size: 14px!important;
    }

    .modal_epps{
      width: 94%!important;
    }
    .table_head{
      font-size: 11px!important;
    }
  }

  .dataTables_paginate .paginate_button {
    margin-top: 20px!important;
    padding: 5px 11px!important;
    line-height: 1.42857143;
    text-decoration: none;
    font-size: 14px;
    color: #ffffff;
    background-color: #32477C!important;
    border: 1px solid transparent;
    margin-left: -1px;
    cursor: pointer;
  }

  div.dataTables_wrapper div.dataTables_info {
    padding-top: 0.1em!important;
    white-space: nowrap;
  }
  

  .select2-container--default .select2-selection--single {
      border: 1px solid #ced4da!important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      font-size:12px!important;
  }


</style>


<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    /*****DATATABLE*****/  
      const base = "<?php echo base_url() ?>";
      const p ="<?php echo $this->session->userdata('id_perfil'); ?>";

      var tabla_condiciones = $('#tabla_condiciones').DataTable({
        /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
        "iDisplayLength":-1, 
        "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
        "bPaginate": false,
        "aaSorting" : [[8,"desc"]],
        "scrollY": "60vh",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "select" : true,
        info:false,
        columnDefs: [
          { orderable: false, targets: 0 }
        ],
        "ajax": {
          "url":"<?php echo base_url();?>getCondicionesList",
          "dataSrc": function (json) {
            $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_liquidacion").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            if(p==4){
              var trabajador = $("#trabajador_t").val();
            }else{
              var trabajador = $("#trabajadores_t").val();
            }
            var jefe = $("#jefe_t").val();
            var periodo = $('#Periodo_f').val();
            param.trabajador = trabajador;
            param.jefe = jefe;
            param.periodo = periodo;
          }
        },    
        "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
              btn = "";
              if(p<=3){
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
              }
              return btn;

            }
          },
          {
            "class":"centered margen-td","data": function(row,type,val,meta){
              btn  =`<a  target="_blank" href="<?php echo base_url() ?>${row.archivo}" title="Archivo" class="btn_archivo"><i class="fas fa-file"></i> </a>`;
              return btn;
            }
          },
          { "data": "firma" ,"class":"margen-td centered"},
          { "data": "responsable" ,"class":"margen-td centered"},
          { "data": "cargo_responsable" ,"class":"margen-td centered"},
          { "data": "fecha_reporte" ,"class":"margen-td centered"},
          { "data": "fecha_inspeccion" ,"class":"margen-td centered"},
          { "data": "tecnico_auditado" ,"class":"margen-td centered"},
          { "data": "rut_tecnico" ,"class":"margen-td centered"},
          { "data": "zona" ,"class":"margen-td centered"},
          { "data": "plaza" ,"class":"margen-td centered"},
          { "data": "proyecto" ,"class":"margen-td centered"},
        ]
    }); 

    $(document).on('keyup paste', '#buscador', function() {
      tabla_condiciones.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tabla_condiciones = $.fn.dataTable.fnTables(true);
      if ( tabla_condiciones.length > 0 ) {
          $(tabla_condiciones).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tabla_condiciones = $.fn.dataTable.fnTables(true);
      if ( tabla_condiciones.length > 0 ) {
          $(tabla_condiciones).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tabla_condiciones = $.fn.dataTable.fnTables(true);
      if ( tabla_condiciones.length > 0 ) {
          $(tabla_condiciones).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 
 
    $(document).off('click', '.btn_filtro_liquidacion').on('click', '.btn_filtro_liquidacion',function(event) {
      event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
      tabla_condiciones.ajax.reload();
    });

    $(document).off('click', '.btn_nueva_liquidacion').on('click', '.btn_nueva_liquidacion', function(event) {
        $('#formCondiciones')[0].reset();
        $("#hash_liqui").val("");
        $('#modal_epps').modal('toggle'); 
        $("#formCondiciones input,#formCondiciones select,#formCondiciones button,#formCondiciones").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
        $("#periodo").val(new Date().getFullYear() + '-' + ((new Date().getMonth()+1) < 10 ? '0' : '') + (new Date().getMonth()+1))
    });

    $(document).off('submit', '#formCondiciones').on('submit', '#formCondiciones',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formCondiciones");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formCondiciones').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_ingreso").attr("disabled", true);
              $(".cierra_modal").attr("disabled", true);
              $("#formCondiciones input,#formCondiciones select,#formCondiciones button,#formCondiciones").prop("disabled", true);
            },
            success: function (data) {

              if(data.res == "sess"){
                window.location="unlogin";

              }else if(data.res=="ok"){

                $('#modal_epps').modal('toggle'); 
                $("#formCondiciones input,#formCondiciones select,#formCondiciones button,#formCondiciones").prop("disabled", false);
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $('#formCondiciones')[0].reset();
                tabla_condiciones.ajax.reload();

              }else if(data.res=="error"){
                    
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);
                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });
                $("#formCondiciones input,#formCondiciones select,#formCondiciones button,#formCondiciones").prop("disabled", false);

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
                    /*  $('#modal_epps').modal("toggle"); */
                }    
                return;
              }
              if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                /* $('#modal_epps').modal("toggle"); */
              }
          },timeout:35000
        }); 
      return false; 
    })

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
      event.preventDefault();
      $("#hash_liqui").val("")
      hash=$(this).data("hash")
      $('#formCondiciones')[0].reset()
      $("#hash_liqui").val(hash)
      $('#modal_epps').modal('toggle')
      $("#formCondiciones input,#formCondiciones select,#formCondiciones button,#formCondiciones").prop("disabled", true)
      $(".btn_ingreso").attr("disabled", true)
      $(".cierra_modal").attr("disabled", true)

      $.ajax({
        url: base+"getDataCondicion"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash:hash},
        dataType:"json",
        beforeSend:function(){
          $(".btn_ingreso").prop("disabled",true); 
          $(".cierra_modal").prop("disabled",true); 
        },
        success: function (data) {

          if(data.res=="ok"){

            for(dato in data.datos){

              $('#responsable_inspeccion').val(data.datos[dato].responsable_inspeccion).trigger('change');
              $('#cargo').val(data.datos[dato].cargo_responsable).trigger('change');
              $("#fecha_inspeccion").val(data.datos[dato].fecha_inspeccion);
              $("#fecha_generacion").val(data.datos[dato].fecha_reporte);
              $('#tecnico_auditado').val(data.datos[dato].tecnico_auditado).trigger('change');
              $("#rut_tecnico_auditado").val(data.datos[dato].rut_tecnico);
              $('#zona').val(data.datos[dato].zona).trigger('change');
              $('#plaza').val(data.datos[dato].plaza).trigger('change');
              $('#proyecto').val(data.datos[dato].proyecto).trigger('change');
              AgregarEPPS(data.datos[dato].epps);
              AgregarRiesgos(data.datos[dato].riesgos);
              AgregarAcciones(data.datos[dato].acciones);
              $('#firma').val(data.datos[dato].firma).trigger('change');
            }
          
            $("#formCondiciones input,#formCondiciones select,#formCondiciones button,#formCondiciones").prop("disabled", false);
            $(".cierra_modal").prop("disabled", false);
            $(".btn_ingreso").prop("disabled", false);

          }else if(data.res == "sess"){
            window.location="../";
          }

          $(".btn_ingreso").prop("disabled",false); 
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
              $('#modal_epps').modal("toggle");
          }
        } , timeout:35000
      }) 
    })
    
    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('<?php echo base_url();?>eliminaCondicion'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            tabla_condiciones.ajax.reload();

          }else{
            $.notify(data.msg, {
              className:'danger',
              globalPosition: 'top right'
            })
          }
        },"json")
      }
    })

    $(document).off('change', '#tecnico_auditado').on('change', '#tecnico_auditado',function(event) {
      const trabajadores = <?php echo json_encode($trabajadores); ?>;
      trabajadores.forEach(trabajador => {
            if (trabajador.id === document.getElementById("tecnico_auditado").value) {
              document.getElementById("rut_tecnico_auditado").value = trabajador.rut_format;
              document.getElementById("zona").value = trabajador.id_area; 
              document.getElementById("plaza").value = trabajador.id_plaza;
              document.getElementById("proyecto").value = trabajador.id_proyecto;
            }
        });
    }) 

    $(document).off('change', '#responsable_inspeccion').on('change', '#responsable_inspeccion',function(event) {
      const trabajadores = <?php echo json_encode($trabajadores); ?>;
      trabajadores.forEach(trabajador => {
            if (trabajador.id === document.getElementById("responsable_inspeccion").value) {
              document.getElementById("cargo").value = trabajador.id_cargo;
            }
        });
    })

    function AgregarEPPS($data = null){
      if($data != null){
        for(var i = 0; i < $data.length; i++){
          if($data[i]['id'] == document.getElementById("herramientas_"+$data[i]['id']).value ){
            document.getElementById("estado_epps_"+$data[i]['id']).value=$data[i]['estado'];
            document.getElementById("uso_epps_"+$data[i]['id']).value=$data[i]['uso'];
            document.getElementById("observacion_epps_"+$data[i]['id']).value=$data[i]['observacion'];
          }
        }
      }
    }

    function AgregarRiesgos($data = null){
      if($data != null){
        for(var i = 0; i < $data.length; i++){
          if($data[i]['id'] == document.getElementById("riesgos_"+$data[i]['id']).value ){
            document.getElementById("resultado_riesgos_"+$data[i]['id']).value=$data[i]['resultado'];
            document.getElementById("riesgos_nivel_"+$data[i]['id']).value=$data[i]['riesgo'];
            document.getElementById("observacion_riesgos_"+$data[i]['id']).value=$data[i]['observacion'];
          }
        }
      }
    }

    function AgregarAcciones($data = null){
      if($data != null){
        for(var i = 0; i < $data.length; i++){
          if($data[i]['id'] == document.getElementById("acciones_"+$data[i]['id']).value ){
            document.getElementById("acciones_estado_"+$data[i]['id']).value=$data[i]['accion'];
            document.getElementById("responsable_accion_"+$data[i]['id']).value=$data[i]['responsable'];
            document.getElementById("observacion_accion_"+$data[i]['id']).value=$data[i]['observacion'];
          }
        }
      }
    }

  })
</script>
  
<!--FILTROS-->

  <div class="form-row">

    <div class="col-1 col-lg-1"> 
      <div class="form-group">
        <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nueva_liquidacion btn_xr3">
        <i class="fa fa-plus-circle"></i>  Nuevo 
        </button>
      </div>
    </div>

    <div class="col-2 col-lg-4">  
      <div class="form-group">
      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
      </div>
    </div>

	</div>

  <div class="row">
    <div class="col-12">
      <table id="tabla_condiciones" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Archivo</th> 
              <th class="centered">Firma</th> 
              <th class="centered">Inspector/Prev.</th> 
              <th class="centered">Cargo</th>    
              <th class="centered">Fecha registro</th>  
              <th class="centered">Fecha inspeccion</th>  
              <th class="centered">Nombre técnico</th>
              <th class="centered">Rut técnico</th>    
              <th class="centered">Zona</th>
              <th class="centered">Plaza</th>  
              <th class="centered">Proyecto</th>  
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->

  <div id="modal_epps"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_epps">
      <div class="modal-content">
        <?php echo form_open_multipart("formCondiciones",array("id"=>"formCondiciones","class"=>"formCondiciones"))?>
          <input type="hidden" name="hash_liqui" id="hash_liqui">
          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>

          <fieldset class="form-ing-cont"> 
          <legend class="form-ing-border">Datos de verificación</legend>
            <div class="form-row">

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Prevencionista responsable</label>
                  <select id="responsable_inspeccion" class="form-control form-control-sm" name="responsable_inspeccion" style="width:100%!important;">
                      <option selected value="">Seleccione prevencionista</option>
                      <?php 
                      foreach($trabajadores as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["nombre_completo"] ?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cargo</label>
                  <select id="cargo" name="cargo" class="form-control form-control-sm" style="width:100%!important;">
                      <option selected value="">Seleccione cargo</option>
                      <?php 
                      foreach($cargos as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["cargo"] ?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha de inspección</label>
                <input type="date" class="form-control form-control-sm"  name="fecha_inspeccion" id="fecha_inspeccion">
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha de generación del registro</label>
                <input readonly type="date" class="form-control form-control-sm"  name="fecha_generacion" id="fecha_generacion" value="<?=date('Y-m-d');?>">
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre técnico auditado</label>
                  <select id="tecnico_auditado" class="form-control form-control-sm" name="tecnico_auditado" style="width:100%!important;">
                      <option value="">Seleccione tecnico auditado</option>
                      <?php 
                      foreach($trabajadores as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["nombre_completo"] ?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Rut del técnico auditado</label>
                  <input readonly type="text"  placeholder="ingrese rut del técnico" class="form-control form-control-sm"  id="rut_tecnico_auditado" name="rut_tecnico_auditado">
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Zona</label>
                  <select id="zona" class="form-control form-control-sm" name="zona" style="width:100%!important;">
                      <option value="">Seleccione zona</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Plaza</label>
                  <select id="plaza" class="form-control form-control-sm" name="plaza" style="width:100%!important;">
                      <option value="">Seleccione plaza</option>
                      <?php 
                      foreach($plazas as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["plaza"] ?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto</label>
                  <select id="proyecto" class="form-control form-control-sm" name="proyecto" style="width:100%!important;">
                      <option value="">Seleccione proyecto</option>
                      <?php 
                      foreach($proyectos as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["proyecto"] ?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
              </div>


            </div>
          </fieldset><br>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Checklist EPPS</legend>

                <!-- 
                <div class="col-12 col-lg-4 offset-lg-4">  
                 <div class="form-group">
                  <input type="text" placeholder="Busqueda" id="buscador_detalle" class="buscador_detalle form-control form-control-sm">
                 </div>
                </div> -->
                <table id="tabla_condiciones" width="100%" class="dataTable table-striped  datatable_h table table-hover table-bordered table-condensed">
                <thead>
                  <tr style="background-color:#F9F9F9">
                      <th class="table_head desktop tablet">Tipo</th>
                      <th class="table_head all">Descripci&oacute;n</th>
                      <th class="table_head all">Resultado</th>
                      <th class="table_head all">Uso</th>
                      <th class="table_head all">Observaci&oacute;n</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 

                    if($checklist!=FALSE){
                      foreach($checklist as $key){
                        ?>
                        <tr>
                          <td><?php echo $key["tipo"]; ?></td>

                          <input type="hidden" name="herramientas[]" value="<?php echo $key["id"] ?>" id="herramientas_<?php echo $key["id"] ?>" >

                          <td><p class="table_text"><?php echo $key["descripcion"] ?></p></td>

                          <td><p class="table_text">
                            <select  name="estado_epps[]" id="estado_epps_<?php echo $key["id"] ?>"  class="estado input-xs">
                              <option selected value="No">No</option>
                              <option value="Si">Si</option>
                              <option value="No aplica">No aplica</option>
                            </select>
                          </td>
                          
                          <td><p class="table_text">
                            <select  name="uso_epps[]" id="uso_epps_<?php echo $key["id"] ?>"  class="uso input-xs">
                              <option selected value="Uso inadecuado">Uso inadecuado</option>
                              <option value="Uso adecuado">Uso adecuado</option>
                              <option value="No aplica">No aplica</option>
                            </select>
                          </td>
                          
                          <td>
                            <p class="table_text">
                              <input type="text" name="observacion_epps[]" id="observacion_epps_<?php echo $key["id"] ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                        </tr>
                      <?php
                      }
                    }
                  ?>
                </tbody>
              </table>
          </fieldset>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Checklist riesgo de entorno</legend>

                <!-- 
                <div class="col-12 col-lg-4 offset-lg-4">  
                 <div class="form-group">
                  <input type="text" placeholder="Busqueda" id="buscador_detalle" class="buscador_detalle form-control form-control-sm">
                 </div>
                </div> -->
                <table id="tabla_riesgo" width="100%" class="dataTable table-striped  datatable_h table table-hover table-bordered table-condensed">
                <thead>
                  <tr style="background-color:#F9F9F9">
                      <th class="table_head desktop tablet">Tipo</th>
                      <th class="table_head all">Descripci&oacute;n</th>
                      <th class="table_head all">Resultado</th>
                      <th class="table_head all">Riesgo</th>
                      <th class="table_head all">Observaci&oacute;n</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 

                    if($riesgos!=FALSE){
                      foreach($riesgos as $key){
                        ?>
                        <tr>
                          <td>Condiciones</td>

                          <input type="hidden" name="riesgos[]" value="<?php echo $key["id"] ?>" id="riesgos_<?php echo $key["id"] ?>" >

                          <td><p class="table_text"><?php echo $key["riesgo"] ?></p></td>

                          <td><p class="table_text">
                            <select  name="resultado_riesgos[]" id="resultado_riesgos_<?php echo $key["id"]?>"  class="estado input-xs">
                              <option selected value="0">Sin Observaci&oacute;n</option>
                              <option value="1">Con Observaci&oacute;n</option>
                            </select>
                          </td>
                          
                          <td><p class="table_text">
                            <select  name="riesgos_nivel[]" id="riesgos_nivel_<?php echo $key["id"]?>"  class="uso input-xs">
                              <option selected value="0">Bajo</option>
                              <option value="1">Medio</option>
                              <option value="2">Alto</option>
                            </select>
                          </td>
                          
                          <td>
                            <p class="table_text">
                              <input type="text" name="observacion_riesgos[]" id="observacion_riesgos_<?php echo $key["id"]?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                        </tr>
                      <?php
                      }
                    }
                  ?>
                </tbody>
              </table>
          </fieldset>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Acciones preventivas/correctivas</legend>

                <!-- 
                <div class="col-12 col-lg-4 offset-lg-4">  
                 <div class="form-group">
                  <input type="text" placeholder="Busqueda" id="buscador_detalle" class="buscador_detalle form-control form-control-sm">
                 </div>
                </div> -->
                <table id="tabla_acciones" width="100%" class="dataTable table-striped  datatable_h table table-hover table-bordered table-condensed">
                <thead>
                  <tr style="background-color:#F9F9F9">
                      <th class="table_head desktop tablet">Tipo</th>
                      <th class="table_head all">Descripci&oacute;n</th>
                      <th class="table_head all">Resultado</th>
                      <th class="table_head all">Responsable</th>
                      <th class="table_head all">Observaci&oacute;n</th>
                  </tr>
                </thead>

                <tbody>
                  <?php 

                    if($acciones!=FALSE){
                      foreach($acciones as $key){
                        ?>
                        <tr>
                          <td>Acciones</td>

                          <input type="hidden" name="acciones[]" value="<?php echo $key["id"] ?>" id="acciones_<?php echo $key["id"] ?>" >

                          <td><p class="table_text"><?php echo $key["accion"] ?></p></td>

                          <td><p class="table_text">
                            <select  name="acciones_estado[]" id="acciones_estado_<?php echo $key["id"] ?>"  class="estado input-xs">
                              <option selected value="0">No</option>
                              <option value="1">Si</option>
                            </select>
                          </td>
                          
                          <td><p class="table_text">
                            <p class="table_text">
                              <input type="text" name="responsable_accion[]" id="responsable_accion_<?php echo $key["id"] ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                          
                          <td>
                            <p class="table_text">
                              <input type="text" name="observacion_accion[]" id="observacion_accion_<?php echo $key["id"] ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                        </tr>
                      <?php
                      }
                    }
                  ?>
                  <?php 
                    for ($i = 1; $i <= 4; $i++):
                    ?>
                    <tr>
                      <td>Acciones</td>
                      <input type="hidden" name="acciones[]" value="<?php echo $i ?>" id="acciones_<?php echo $i ?>" >
                        <td><p class="table_text">Acción adicional recomendada <?php echo $i ?> (indicar fecha de revisión) </p></td>

                          <td><p class="table_text">
                            <select  name="acciones_estado[]" id="acciones_estado_<?php echo $i ?>"  class="estado input-xs">
                              <option selected value="0">No</option>
                              <option value="1">Si</option>
                            </select>
                          </td>
                          
                          <td><p class="table_text">
                            <p class="table_text">
                              <input type="text" name="responsable_accion[]" id="responsable_accion_<?php echo $i ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                          
                          <td>
                            <p class="table_text">
                              <input type="text" name="observacion_accion[]" id="observacion_accion_<?php echo $i ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                        </tr>
                    <?php
                    endfor;
                  ?>


                </tbody>
              </table>
          </fieldset>


          <fieldset class="form-ing-cont"> 
          <legend class="form-ing-border">Información de firma</legend>

            <div class="col-lg-6">  
              <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">¿El documento fue firmado?</label>
                <select placeholder="Seleccione una opción" id="firma" name="firma" style="width:100%!important;" class="form-control form-control-sm">
                    <option value="si">Si</option>
                    <option selected value="no">No</option>
                </select>
              </div>
            </div>
            
            <div class="col-lg-6"> 
              <div class="form-group"> 
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Adjunto</label>
                <input type="file" id="userfile" name="userfile" >
              </div>
            </div>

          </fieldset><br>

          <div class="col-lg-12">
            <div class="form-row">

              <div class="col-6 col-lg-6">
                <div class="form-group">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso">
                    <i class="fa fa-save"></i> Guardar
                  </button>
                </div>
              </div>

              <div class="col-6 col-lg-6">
                <button class="btn-block btn btn-sm btn-dark cierra_modal" data-dismiss="modal" aria-hidden="true">
                  <i class="fa fa-window-close"></i> Cerrar
                </button>
              </div>
            </div>
          </div>

          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
