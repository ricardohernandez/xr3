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
    .modal_reuniones{
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

    .modal_reuniones{
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
    const base = "<?php echo base_url() ?>";
    const p ="<?php echo $this->session->userdata('id_perfil'); ?>";
    const fecha_hoy="<?php echo $fecha_hoy; ?>";

    /*****DATATABLE*****/  

      var tabla_reuniones = $('#tabla_reuniones').DataTable({
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
          "url":"<?php echo base_url();?>getReunionesList",
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
          { "data": "fecha_generacion" ,"class":"margen-td centered"},
          { "data": "area" ,"class":"margen-td centered"},
          { "data": "fecha_reunion" ,"class":"margen-td centered"},
          { "data": "inicio" ,"class":"margen-td centered"},
          { "data": "termino" ,"class":"margen-td centered"},
          { "data": "objetivo" ,"class":"margen-td centered"},
          { "data": "firma" ,"class":"margen-td centered"}, //ASISTENTES, REVISAR COMO MOSTRAR
          { "data": "tema_1" ,"class":"margen-td centered"},
          { "data": "tema_2" ,"class":"margen-td centered"},
          { "data": "tema_3" ,"class":"margen-td centered"},
          { "data": "tema_4" ,"class":"margen-td centered"},
          { "data": "tema_5" ,"class":"margen-td centered"},
          { "data": "observacion" ,"class":"margen-td centered"},
          { "data": "responsable_inspeccion" ,"class":"margen-td centered"},
          { "data": "cargo_prevencionista" ,"class":"margen-td centered"},
        ]
    }); 

    $(document).on('keyup paste', '#buscador', function() {
      tabla_reuniones.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tabla_reuniones = $.fn.dataTable.fnTables(true);
      if ( tabla_reuniones.length > 0 ) {
          $(tabla_reuniones).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tabla_reuniones = $.fn.dataTable.fnTables(true);
      if ( tabla_reuniones.length > 0 ) {
          $(tabla_reuniones).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tabla_reuniones = $.fn.dataTable.fnTables(true);
      if ( tabla_reuniones.length > 0 ) {
          $(tabla_reuniones).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 
 
    $(document).off('click', '.btn_filtro_liquidacion').on('click', '.btn_filtro_liquidacion',function(event) {
      event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
      tabla_reuniones.ajax.reload();
    });

    $(document).off('click', '.btn_nueva_liquidacion').on('click', '.btn_nueva_liquidacion', function(event) {
        $('#formReuniones')[0].reset();
        $(".form_detalle_asistentes").html("");
        $("#fecha_generacion").val(fecha_hoy);
        $("#hash_liqui").val("");
        $('#modal_reuniones').modal('toggle'); 
        $("#formReuniones input,#formReuniones select,#formReuniones button,#formReuniones").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
        $("#periodo").val(new Date().getFullYear() + '-' + ((new Date().getMonth()+1) < 10 ? '0' : '') + (new Date().getMonth()+1))
       
        $.getJSON("listaTrabajadores",  { 'jefe' : $("#jefe_t").val() } ,function(data) {
          response = data;
        }).done(function() {
            $("#trabajadores").select2({
            placeholder: 'Seleccione Trabajador | Todos',
            data: response,
            width: 'resolve',
            allowClear:true,
            })
        })
    });

    $(document).off('submit', '#formReuniones').on('submit', '#formReuniones',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formReuniones");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formReuniones').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_ingreso").attr("disabled", true);
              $(".cierra_modal").attr("disabled", true);
              $("#formReuniones input,#formReuniones select,#formReuniones button,#formReuniones").prop("disabled", true);
            },
            success: function (data) {

              if(data.res == "sess"){
                window.location="unlogin";

              }else if(data.res=="ok"){

                $('#modal_reuniones').modal('toggle'); 
                $("#formReuniones input,#formReuniones select,#formReuniones button,#formReuniones").prop("disabled", false);
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $('#formReuniones')[0].reset();
                tabla_reuniones.ajax.reload();

              }else if(data.res=="error"){
                    
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);
                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });
                $("#formReuniones input,#formReuniones select,#formReuniones button,#formReuniones").prop("disabled", false);

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
                    /*  $('#modal_reuniones').modal("toggle"); */
                }    
                return;
              }
              if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                /* $('#modal_reuniones').modal("toggle"); */
              }
          },timeout:35000
        }); 
      return false; 
    })

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
      event.preventDefault();
      $("#hash_liqui").val("")
      hash=$(this).data("hash")
      $('#formReuniones')[0].reset()
      $(".form_detalle_asistentes").html("");
      $("#hash_liqui").val(hash)
      $('#modal_reuniones').modal('toggle')
      $("#formReuniones input,#formReuniones select,#formReuniones button,#formReuniones").prop("disabled", true)
      $(".btn_ingreso").attr("disabled", true)
      $(".cierra_modal").attr("disabled", true)

      $.ajax({
        url: base+"getDataReunion"+"?"+$.now(),  
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
              $("#hash_liqui").val(data.datos[dato].hash);
              $("#fecha").val(data.datos[dato].fecha_reunion);
              $('#area').val(data.datos[dato].area).trigger('change');
              $("#inicio").val(data.datos[dato].inicio);
              $("#termino").val(data.datos[dato].termino);
              $("#objetivo").val(data.datos[dato].objetivo);
              AgregarAsistente(data.datos[dato].asistentes);
              $("#tema_1").val(data.datos[dato].tema_1);
              $("#tema_2").val(data.datos[dato].tema_2);
              $("#tema_3").val(data.datos[dato].tema_3);
              $("#tema_4").val(data.datos[dato].tema_4);
              $("#tema_5").val(data.datos[dato].tema_5);
              $("#observacion").val(data.datos[dato].observacion);
              $('#responsable_inspeccion').val(data.datos[dato].responsable_inspeccion).trigger('change');
              $('#cargo_prevencionista').val(data.datos[dato].cargo_prevencionista).trigger('change');
              $("#fecha_generacion").val(data.datos[dato].fecha_generacion);
              $('#firma').val(data.datos[dato].firma).trigger('change');
            }
          
            $("#formReuniones input,#formReuniones select,#formReuniones button,#formReuniones").prop("disabled", false);
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
              $('#modal_reuniones').modal("toggle");
          }
        } , timeout:35000
      }) 
    })
    
    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('<?php echo base_url();?>eliminaReunion',{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            tabla_reuniones.ajax.reload();

          }else{
            $.notify(data.msg, {
              className:'error',
              globalPosition: 'top right'
            })
          }
        },"json")
      }
    })

    $.getJSON(base + "listaTrabajadoresFiltros", { jefe : $("#jefe_t").val() } , function(data) {
      response = data

      }).done(function() {
         $("#trabajadores_t").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })
        $("#trabajador").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })
    })

  
    
    $(document).off('change', '#jefe_t').on('change', '#jefe_t',function(event) {
      $.getJSON(base + "listaTrabajadoresFiltros", { 'jefe' : $(this).val() } , function(data) {
      response = data

      }).done(function() {

        $("#trabajadores_t").empty()
        $("#trabajadores_t").trigger('change')

        $("#trabajadores_t").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })

        $("#trabajador").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })

        tabla_reuniones.ajax.reload()
      })
    }) 

    $(document).off('change', '#trabajadores_t , #Periodo_f').on('change', '#trabajadores_t, #Periodo_f',function(event) {
      tabla_reuniones.ajax.reload()
    }) 

    $(document).off('click', '#agrega_linea').on('click', '#agrega_linea',function(event) {
      event.preventDefault();
      AgregarAsistente();
    });

    function AgregarAsistente($data = null){
      var listacargos = <?php echo json_encode($cargos); ?>;
      var option_cargos ='';
      for (var i = 0; i < listacargos.length; i++) {
        var dato = (listacargos[i]);
        option_cargos += '<option value="'+dato.id+'">'+dato.cargo+'</option>';
      }  
      var listatrabajadores = <?php echo json_encode($trabajadores); ?>;
      var option_trabajadores ='';
      for (var i = 0; i < listatrabajadores.length; i++) {
        var dato = (listatrabajadores[i]);
        option_trabajadores += '<option value="'+dato.id+'">'+dato.nombre_completo+'</option>';
      }
      if($data != null){
        for(var i = 0; i < $data.length; i++){
          var html ='<tr>'+
                  '<td><p class="table_text corr">Asistente</p></td>'+
                    '<td><p class="table_text">'+
                        '<p class="table_text">'+
                          '<select id="nombre_asistentes_'+i+'" name="nombre_asistentes[]" style="width:100%!important;" class="form-control form-control-sm">'+
                            '<option value="">Seleccione Trabajador</option>'+
                            option_trabajadores +
                          '</select>'+
                      '</p>'+
                    '</td>'+
                    '<td><p class="table_text">'+
                      '<p class="table_text">'+
                        '<select id="cargos_'+i+'" name="cargos[]" style="width:100%!important;" class="form-control form-control-sm">'+
                            '<option value="">Seleccione cargo</option>'+
                            option_cargos +
                        '</select>'+
                      '</p>'+
                  '</td>'+
                '</tr>';
          $(".form_detalle_asistentes").append(html);
          document.getElementById("nombre_asistentes_"+i).value=$data[i]['nombre'];
          document.getElementById("cargos_"+i).value=$data[i]['cargo'];
          var correlativo = $('.corr').length;
            $(".corr:last").val(correlativo);
            $(".corr").prop("disabled",true);
            $(".agrega_linea_cont_ingresos").show();
        }
      }else{
        var html ='<tr>'+
                  '<td><p class="table_text corr">Asistente</p></td>'+
                    '<td><p class="table_text">'+
                        '<p class="table_text">'+
                          '<select id="nombre_asistentes" name="nombre_asistentes[]" style="width:100%!important;" class="form-control form-control-sm">'+
                            '<option value="">Seleccione Trabajador</option>'+
                            option_trabajadores +
                          '</select>'+
                      '</p>'+
                    '</td>'+
                    '<td><p class="table_text">'+
                      '<p class="table_text">'+
                        '<select id="cargos" name="cargos[]" style="width:100%!important;" class="form-control form-control-sm">'+
                            '<option value="">Seleccione cargo</option>'+
                            option_cargos +
                        '</select>'+
                      '</p>'+
                  '</td>'+
                '</tr>';
        $(".form_detalle_asistentes").append(html);
        var correlativo = $('.corr').length;
            $(".corr:last").val(correlativo);
            $(".corr").prop("disabled",true);
            $(".agrega_linea_cont_ingresos").show();
      }
    }

    $(document).off('change', '#responsable_inspeccion').on('change', '#responsable_inspeccion',function(event) {
      const trabajadores = <?php echo json_encode($trabajadores); ?>;
      trabajadores.forEach(trabajador => {
            if (trabajador.id === document.getElementById("responsable_inspeccion").value) {
              document.getElementById("cargo_prevencionista").value = trabajador.id_cargo;
            }
        });
    })
      
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
      <table id="tabla_reuniones" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Fecha de creación</th>   
              <th class="centered">Área</th> 
              <th class="centered">Fecha de reunión</th>    
              <th class="centered">Hora de inicio</th>  
              <th class="centered">Hora de finalización</th>  
              <th class="centered">Objetivo de la reunión</th>  
              <th class="centered">Asistentes</th>
              <th class="centered">Tema 1</th>  
              <th class="centered">Tema 2</th>   
              <th class="centered">Tema 3</th>   
              <th class="centered">Tema 4</th>   
              <th class="centered">Tema 5</th>     
              <th class="centered">Observaciones</th>   
              <th class="centered">Nombre del prevencionista</th>    
              <th class="centered">Cargo del prevencionista</th>  
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->

  <div id="modal_reuniones"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_reuniones">
      <div class="modal-content">
        <?php echo form_open_multipart("formReuniones",array("id"=>"formReuniones","class"=>"formReuniones"))?>
          <input type="hidden" name="hash_liqui" id="hash_liqui">

          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Datos de reunión</legend>
            <div class="form-row">

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha</label>
              <input type="date"  placeholder="fecha" class="form-control form-control-sm"  name="fecha" id="fecha">
              </div>
            </div>

            <div class="col-lg-6">  
              <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Área</label>
                <select id="area" name="area" class="form-control form-control-sm" style="width:100%!important;" >
                    <option value="">Seleccione área</option>
                    <option value="comercial">Comercial</option>
                    <option value="tecnica">Técnica</option>
                    <option value="staff">Staff</option>
                </select>
              </div>
            </div>

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Hora inicio</label>
              <input type="time"  placeholder="inicio" class="form-control form-control-sm"  name="inicio" id="inicio">
              </div>
            </div>

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Hora término</label>
              <input type="time"  placeholder="termino" class="form-control form-control-sm"  name="termino" id="termino">
              </div>
            </div>

            <div class="col-lg-12">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Objetivo de la reunión</label>
              <input type="text"  placeholder="Describir el propósito o meta principal de la reunión con los supervisores" class="form-control form-control-sm"  name="objetivo" id="objetivo">
              </div>
            </div>

            </div>

          </fieldset><br>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Datos de asistentes</legend>
            <div class="form-row">
                <table id="tabla_asistentes" width="100%" class="dataTable table-striped  datatable_h table table-hover table-bordered table-condensed">
                  <thead>
                    <tr style="background-color:#F9F9F9">
                        <th class="table_head desktop tablet">Asistente</th>
                        <th class="table_head all">Nombre</th>
                        <th class="table_head all">Cargo</th>
                    </tr>
                  </thead>
                  <tbody class="form_detalle_asistentes">
                  </tbody>
                </table>

                <div class="col text-center agrega_linea_cont_entrega">
                <div class="form-group">
                  <button class="btn btn-sm btn-primary" id="agrega_linea"><i class="fa fa-plus-circle"></i> Agregar m&aacute;s asistentes</button>
                </div>
              </div>
            </div>
          </fieldset><br>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Temas revisados</legend>
            <div class="form-row">
                <table id="tabla_asistentes" width="100%" class="dataTable table-striped  datatable_h table table-hover table-bordered table-condensed">
                  <thead>
                    <tr style="background-color:#F9F9F9">
                        <th class="table_head desktop tablet">Temas</th>
                        <th class="table_head all">Descripcion</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      for ($i = 1; $i <= 5; $i++):
                      ?>
                      <tr>
                          <td><p class="table_text">Tema <?php echo $i ?></p></td>

                          <td>
                            <p class="table_text">
                              <input type="text" name="tema_<?php echo $i ?>" id="tema_<?php echo $i ?>" placeholder="" size="50" maxlength="50" class="form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>

                          </tr>
                      <?php
                      endfor;
                    ?>
                  </tbody>
                </table>
            </div>
            <div class="col-lg-12">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Observaciones adicionales</label>         
              <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="observacion" id="observacion">
              </div>
            </div>


          </fieldset><br>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Antecedentes del prevencionista</legend>
            <div class="form-row">

              <div class="col-lg-6">  
                  <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Prevencionista responsable</label>
                    <select id="responsable_inspeccion" name="responsable_inspeccion" style="width:100%!important;" class="form-control form-control-sm">
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
                    <select id="cargo_prevencionista" name="cargo_prevencionista" style="width:100%!important;" class="form-control form-control-sm">
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
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha del registro</label>
                <input readonly type="date" class="form-control form-control-sm"  name="fecha_generacion" id="fecha_generacion">
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">¿El documento fue firmado?</label>
                  <select placeholder="Seleccione una opción" id="firma" name="firma" style="width:100%!important;" class="form-control form-control-sm">
                      <option value="si">Si</option>
                      <option selected value="no">No</option>
                  </select>
                </div>
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
