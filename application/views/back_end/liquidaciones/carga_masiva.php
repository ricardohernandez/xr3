<style type="text/css">
  .rojo{
    color: red;
  }

  .grey{
    background-color: grey;
    color: #fff;
  }

  .azul{
    color: blue;
  }

  .btn_archivo{
    display: block;
    text-align: center!important;
    margin:0 auto!important;
    font-size: 14px!important;
  }
  .btn_detalle{
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
    .modal_liquidacion{
      width: 40%!important;
    }
    .modal_carga_masiva{
      width: 40%!important;
    }
    .modal_detalle{
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

    .modal_liquidacion{
      width: 94%!important;
    }
    .modal_carga_masiva{
      width: 40%!important;
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

      var tabla_carga_masiva = $('#tabla_carga_masiva').DataTable({
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
          "url":"<?php echo base_url();?>getCargamasivaList",
          "dataSrc": function (json) {
            $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_liquidacion").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            var periodo = $('#Periodo_f').val();
            param.periodo = periodo;
          }
        },    
        "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
              btn = "";
              if(p<=3){
                //btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Información" class="btn_editar" style="font-size:14px!important;"><i class="fas fa-search"></i> </a>`;
                //btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
                btn ='<center><a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
              }
              return btn;

            }
          },
          {
            "class":"centered margen-td","data": function(row,type,val,meta){
              btn  =`<a  target="_blank" href="${row.archivo}" title="Archivo" class="btn_archivo"><i class="fas fa-file"></i> </a>`;
              return btn;
            }
          },
          { "data": "periodo" ,"class":"margen-td centered"},
          { "data": "digitador" ,"class":"margen-td centered"},
          { "data": "datos_total" ,"class":"margen-td centered"},
          { "data": "datos_fallidos" ,"class":"margen-td centered"},
          { "data": "datos_aceptados" ,"class":"margen-td centered"},
          {
            "class":"centered margen-td","data": function(row,type,val,meta){
              btn = "";
              if(row.observaciones!=""){
                btn  =`<a  target="_blank" data-hash="${row.observaciones}" title="Detalle" class="btn_detalle"><i class="fas fa-eye azul"></i> </a>`;
              }
              return btn;
            }
          },
          //{ "data": "observaciones" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
        ]
    }); 

    $(document).on('keyup paste', '#buscador', function() {
      tabla_carga_masiva.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tabla_carga_masiva = $.fn.dataTable.fnTables(true);
      if ( tabla_carga_masiva.length > 0 ) {
          $(tabla_carga_masiva).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tabla_carga_masiva = $.fn.dataTable.fnTables(true);
      if ( tabla_carga_masiva.length > 0 ) {
          $(tabla_carga_masiva).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tabla_carga_masiva = $.fn.dataTable.fnTables(true);
      if ( tabla_carga_masiva.length > 0 ) {
          $(tabla_carga_masiva).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 
 
    $(document).off('click', '.btn_filtro_liquidacion').on('click', '.btn_filtro_liquidacion',function(event) {
      event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
      tabla_carga_masiva.ajax.reload();
    });

    $(document).off('click', '.btn_carga_masiva').on('click', '.btn_carga_masiva', function(event) {
        $('#formMasivo')[0].reset();
        $("#hash_masiva").val("");
        $('#modal_carga_masiva').modal('toggle'); 
        $("#formMasivo input,#formMasivo select,#formMasivo button,#formMasivo").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
        $("#periodo_carga_masiva").val(new Date().getFullYear() + '-' + ((new Date().getMonth()+1) < 10 ? '0' : '') + (new Date().getMonth()+1))
    });

    $(document).off('submit', '#formMasivo').on('submit', '#formMasivo',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formMasivo");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formMasivo').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_ingreso").attr("disabled", true);
              $(".cierra_modal").attr("disabled", true);
              $("#formMasivo input,#formMasivo select,#formMasivo button,#formMasivo").prop("disabled", true);
            },
            success: function (data) {

              if(data.res == "sess"){
                window.location="unlogin";

              }else if(data.res=="ok"){

                $('#modal_carga_masiva').modal('toggle'); 
                $("#formMasivo input,#formMasivo select,#formMasivo button,#formMasivo").prop("disabled", false);
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });
                $('#formMasivo')[0].reset();

              }else if(data.res=="error"){
                    
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);
                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });
                $("#formMasivo input,#formMasivo select,#formMasivo button,#formMasivo").prop("disabled", false);

              }
              tabla_carga_masiva.ajax.reload();
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
                    /*  $('#modal_carga_masiva').modal("toggle"); */
                }    
                return;
              }
              if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                /* $('#modal_carga_masiva').modal("toggle"); */
              }
          },timeout:35000
        }); 
      return false; 
    })

    $(document).off('click', '.btn_detalle').on('click', '.btn_detalle', function(event) {
        $("#hash_detalle").val("");
        hash=$(this).data("hash");
        list = hash.split(',');
        var ulElement = document.getElementById('listaDetalles');
        ulElement.innerHTML = '';
        list.forEach(function(valor) {
          var liElement = document.createElement('li');
          liElement.textContent = valor;
          ulElement.appendChild(liElement);
        });
        $('#modal_detalle').modal('toggle'); 
        $(".cierra_modal").attr("disabled", false);
    });

    

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
      event.preventDefault();
      $("#hash_liqui").val("")
      hash=$(this).data("hash")
      $('#formLiquidaciones')[0].reset()
      $("#hash_liqui").val(hash)
      $('#modal_liquidacion').modal('toggle')
      $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", true)
      $(".btn_ingreso").attr("disabled", true)
      $(".cierra_modal").attr("disabled", true)

      $.ajax({
        url: base+"getDataLiquidaciones"+"?"+$.now(),  
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
              $('#trabajadores').val(data.datos[dato].id_usuario).trigger('change');
              $("#periodo").val(data.datos[dato].periodo);
            }
          
            $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", false);
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
              $('#modal_liquidacion').modal("toggle");
          }
        } , timeout:35000
      }) 
    })
    
    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('eliminaCargamasiva'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            tabla_carga_masiva.ajax.reload();

          }else{
            $.notify(data.msg, {
              className:'danger',
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

        tabla_carga_masiva.ajax.reload()
      })
    }) 

    $(document).off('change', '#trabajadores_t , #Periodo_f').on('change', '#trabajadores_t, #Periodo_f',function(event) {
      tabla_carga_masiva.ajax.reload()
    }) 

  })
</script>
  
<!--FILTROS-->

  <div class="form-row">

    <?php  
     if($this->session->userdata('id_perfil')<=3){
    ?>
      <div class="col-1 col-lg-1"> 
        <div class="form-group">
          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_carga_masiva btn_xr3">
          <i class="fa fa-plus-circle"></i>  Carga Masiva 
          </button>
        </div>
      </div>
    <?php
      }
    ?>

    <div class="col-lg-1">  
      <div class="form-group">
      <input type="month"  placeholder="Periodo" class="form-control form-control-sm"  name="Periodo_f" id="Periodo_f">
      </div>
    </div>

    <div class="col-2 col-lg-4">  
      <div class="form-group">
      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
      </div>
    </div>

    <?php  
     if($this->session->userdata('id_perfil')<=3){
    ?>
      <div class="col-1 col-lg-1 right"> 
        <div class="form-group">
          <a class="btn-block btn btn-sm btn-outline-primary btn_xr3" href="<?php echo base_url('descargarTemplate'); ?>">
          <i class="fa fa-file"></i>  Template
          </a>
        </div>
      </div>
    <?php
      }
    ?>
                              
	</div>

  <!-- TABLA -->
  <div class="row">
    <div class="col-12">
      <table id="tabla_carga_masiva" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th>
              <th class="centered">Archivo</th>  
              <th class="centered">Fecha emision</th> 
              <th class="centered">Digitador</th> 
              <th class="centered">Datos totales</th>    
              <th class="centered">Datos fallidos</th>  
              <th class="centered">Datos aceptados</th>  
              <th class="centered">Detalle / Errores</th>  
              <th class="centered">Última actualización</th> 
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  CARGA MASIVA -->

<div id="modal_carga_masiva"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_carga_masiva">
      <div class="modal-content">
        <?php echo form_open_multipart("formMasivo",array("id"=>"formMasivo","class"=>"formMasivo"))?>
          <input type="hidden" name="hash_masiva" id="hash_masiva">

          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Datos de ingreso</legend>
          
          <div class="form-row">

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha de emision</label>
              <input type="month"  placeholder="Periodo" class="form-control form-control-sm"  name="periodo_carga_masiva" id="periodo_carga_masiva">
              </div>
            </div>

            <div class="col-lg-12"> 
              <div class="form-group"> 
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Excel</label>
                <input type="file" id="excel_file" name="excel_file">
              </div>
            </div>

          </div>

          </fieldset><br>

          <div class="col-lg-12">
            <div class="form-row">
              
              <!-- <div class="col-6 col-lg-4">
                <div class="form-group">  
                  <div class="form-check">
                    <input type="checkbox" checked name="checkcorreo" class="form-check-input" id="checkcorreo">
                    <label class="form-check-label" for="checkcorreo">¿Enviar correo?</label>
                  </div>
                </div>
              </div> -->

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


<!--  DETALLE -->

<div id="modal_detalle"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_detalle">
      <div class="modal-content">
          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Detalles de la carga masiva</legend>
          <div class="form-row" id="listaDetalles">
          </div>
          </fieldset><br>
          <div class="col-lg-12">
            <div class="form-row">
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


